<?php

namespace EventsForce;

use EventsForce\Exceptions\EventsForceException;
use EventsForce\Exceptions\EmptyResponseException;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Request
 * To be built up during every request
 *
 * @package EventsForce
 */
class Request
{
    /**
     * The Guzzle client for handling our actual requests
     *
     * @var GuzzleClient
     */
    private $client;

    /**
     * Endpoint for the request
     *
     * @var string
     */
    private $endpoint = '';

    /**
     * Options for the request
     *
     * @var array
     */
    private $options = [];

    /**
     * Store the allowed methods for the Request
     * @var array
     */
    public static $allowedMethods = [
        "GET",
        "POST",
        "PUT",
        "DELETE"
    ];

    /**
     * Request method
     *
     * @var string
     */
    private $method = 'GET';


    /**
     * Request constructor.
     *
     * @param GuzzleClient $client
     * @param array $properties
     */
    public function __construct(GuzzleClient $client, $properties = [])
    {
        $this->client = $client;

        if (is_array($properties) && !empty($properties)) {
            $this->setParameters($properties);
        }
    }

    /**
     * Method that takes an array of properties and assigns them to their relevant object properties
     *
     * @param array $properties
     * @return $this
     */
    public function setParameters($properties = [])
    {
        // loop over properties and then find if the relevant setter method exists (so we can massage and validate)
        foreach($properties as $property => $value) {
            $methodName = 'set' . ucfirst($property);
            if ('setParameters' !== $methodName && method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }
        return $this;
    }

    /**
     * Method for setting request options
     * @param array $options
     * @return $this
     */
    public function setOptions($options = [])
    {
        if (!is_array($options)) {
            $options = (array) $options;
        }

        $this->options = $options;
        return $this;
    }

    /**
     * Method to set request endpoint
     *
     * @param string $endpoint
     * @return $this
     */
    public function setEndpoint($endpoint = '')
    {
        if (!is_string($endpoint)) {
            $endpoint = (string) $endpoint;
        }

        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Method for setting the request method
     *
     * @param string $method
     * @return $this
     * @throws EventsForceException
     */
    public function setMethod($method = '')
    {
        if (!is_string($method)) {
            $method = (string) $method;
        }
        $method = strtoupper($method);

        if (!in_array($method, self::$allowedMethods)) {
            throw new EventsForceException('Only the following request methods are allowed: ' . implode(', ', self::$allowedMethods));
        }

        $this->method = $method;
        return $this;
    }


    /**
     * Method to handle sending a request
     *
     * @return \Psr\Http\Message\StreamInterface
     * @throws EmptyResponseException
     */
    public function send()
    {
        // ensure that for get requests we put options inside the query key
        if ('GET' === $this->method) {
            $options = [
                'query' => $this->options
            ];
        }

        $response = $this->client->request($this->method, $this->endpoint, $options);

        if (false === $response->hasHeader('Content-Length')) {
            throw new EmptyResponseException('No content in response from API');
        }

        $body = $response->getBody();

        return $body;
    }
}