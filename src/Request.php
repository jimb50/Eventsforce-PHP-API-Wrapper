<?php

namespace EventsForce;

/**
 * Class Request
 * To be built up during every request
 *
 * @package EventsForce
 */
class Request
{
    /**
     * Endpoint for the request
     *
     * @var string
     */
    private $endpoint = '';

    /**
     * Arguments for the request
     *
     * @var array
     */
    private $arguments = [];


    /**
     * Request constructor.
     *
     * Returns instance so can method chain
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * Method to set request endpoint
     *
     * @param $endpoint
     * @return $this
     */
    public function setEndpoint($endpoint = '')
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Method to set the request arguments
     *
     * @param array $arguments
     * @return $this
     */
    public function setArguments($arguments = [])
    {
        $this->arguments = $arguments;
        return $this;
    }
}