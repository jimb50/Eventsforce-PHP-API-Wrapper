<?php

namespace EventsForce;

use EventsForce\Exceptions\EventsForceException;
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
    private $method = 'get';


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


    public function send()
    {
        $response = $this->client->request($this->method, $this->endpoint);

        if (false === $response->hasHeader('Content-Length')) {
            throw new EventsForceException('No content in response from API');
        }

        if ('OK' !== $response->getReasonPhrase()) {
            throw new EventsForceException('Response reasonphrase was not OK, it was: ' . $response->getReasonPhrase());
        }

        $body = $response->getBody();

        return $body;
    }
}