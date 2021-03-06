<?php

namespace EventsForce;

use EventsForce\Exceptions\ResourceNotFound;
use EventsForce\Exceptions\Exception;
use EventsForce\Exceptions\EmptyResponseException;
use EventsForce\Exceptions\InvalidArgumentException;
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
     * @throws InvalidArgumentException
     */
    public function setOptions($options = [])
    {
        if (!is_array($options)) {
            throw new InvalidArgumentException('You must pass in a an array of options');
        }

        $this->options = $options;
        return $this;
    }

    /**
     * Method to set request endpoint
     *
     * @param string $endpoint
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setEndpoint($endpoint = '')
    {
        if (!is_string($endpoint)) {
            throw new InvalidArgumentException('You must pass in a string endpoint');
        }

        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Method for setting the request method
     *
     * @param string $method
     * @return $this
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function setMethod($method = '')
    {
        if (!is_string($method)) {
            throw new InvalidArgumentException('You must pass in a string method');
        }
        $method = strtoupper($method);

        if (!in_array($method, self::$allowedMethods)) {
            throw new Exception('Only the following request methods are allowed: ' . implode(', ', self::$allowedMethods));
        }

        $this->method = $method;
        return $this;
    }

    /**
     * Method to set query
     * @param array $query
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setQuery($query = [])
    {
        if (!is_array($query)) {
            throw new InvalidArgumentException('You must pass in an array query');
        }

        $this->options['query'] = $query;
        return $this;
    }

    /**
     * Method for setting the json options param to be sent with the request
     *
     * @param array $data
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setJson($data = [])
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('You must pass in an array to be parsed as json');
        }
        $this->options['json'] = $data;
        return $this;
    }


    /**
     * Method to handle sending a request
     * @return \Psr\Http\Message\StreamInterface
     * @throws EmptyResponseException
     * @throws ResourceNotFound
     */
    public function send()
    {
        $response = $this->client->request($this->method, $this->endpoint, $this->options);

        if (404 === $response->getStatusCode()) {
            throw new ResourceNotFound('404: ' . $this->endpoint . ', Resource not found');
        }

        if (false === $response->hasHeader('Content-Length')) {
            throw new EmptyResponseException('No content in response from API');
        }

        return $response;
    }
}