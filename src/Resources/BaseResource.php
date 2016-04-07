<?php

namespace EventsForce\Resources;

use EventsForce\Client;
use EventsForce\Exceptions\InvalidArgumentException;

/**
 * Class BaseResource
 * Abstract used by all resources
 *
 * @package EventsForce\Resources
 */
abstract class BaseResource
{
    /**
     * Stores an instance of the client for resources to Access
     * @var Client
     */
    protected $client;

    /**
     * Stores the endpoint prefix prior to all requests
     * @var string
     */
    protected $endpoint_prefix = '';

    /**
     * BaseResource constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Method to generate the endpoint
     * Accepts either string or array
     * e.g string 2/attendees.json
     * or array [2, 'attendees.json']
     * both return events/2/attendees.json when the endpoint_prefix is events
     *
     * @param string | array $suffix
     * @return string
     */
    public function genEndpoint($suffix = '')
    {
        if (!is_array($suffix) && !is_string($suffix)) {
            throw new InvalidArgumentException('You need to pass a valid suffix to generate an endpoint for a resource');
        }

        if (is_array($suffix)) {
            $suffix = implode('/', $suffix);
        }

        return $this->endpoint_prefix . '/' . $suffix;
    }
}