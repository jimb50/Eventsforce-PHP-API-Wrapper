<?php

namespace EventsForce;

use EventsForce\Exceptions\InvalidArgumentException;

class Client
{
    /**
     * The first part of the eventsforce api endpoint uri
     * together: $ef_uri . {client_slug} . $ef_api_endpoint;
     * @var string
     */
    private static $ef_uri = 'https://www.eventsforce.net/';

    /**
     * The second part of the eventsforce api endpoint uri
     * together: $ef_uri . {client_slug} . $ef_api_endpoint;
     * @var string
     */
    private static $ef_api_endpoint = '/api/v2';

    /**
     * @var string
     */
    private $api_key = '';

    /**
     * @var string
     */
    private $client_slug = '';


    /**
     * Client constructor.
     * @param $client_slug
     * @param string $api_key_unblanked
     * @throws InvalidArgumentException
     */
    public function __construct($client_slug, $api_key_unblanked = '')
    {
        if (false === is_string($client_slug) || true === empty($client_slug)) {
            throw new InvalidArgumentException('You must pass an EventsForce client slug (can be found in your EventsForce dashboard url)');
        }
        if (false === is_string($api_key_unblanked) || true === empty($api_key_unblanked)) {
            throw new InvalidArgumentException('You must pass an EventsForce API Key');
        }

        $this->api_key = Client::blankKey($api_key_unblanked);
        $this->client_slug = $client_slug;
    }

    /**
     * method to blank a string passed in
     * @param $key
     * @return string
     */
    private static function blankKey($key)
    {
        return base64_encode(':' . $key);
    }

    /**
     * method to merge array of arguments with defaults
     * @param $args - passed in arguments
     * @param $defaults - default argument array to merge with
     * @return array
     */
    private static function argsMerge($args, $defaults)
    {
        if (false === is_array($args))
            $args = array();

        return array_merge($defaults, $args);
    }
}
