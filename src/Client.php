<?php

namespace EventsForce;

use EventsForce\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client as GuzzleClient;

class Client
{

    /**
     * The first part of the eventsforce api endpoint uri
     * together: $ef_uri . {client_slug} . $ef_api_endpoint;
     *
     * @var string
     */
    private static $ef_uri = 'https://www.eventsforce.net/';


    /**
     * The second part of the eventsforce api endpoint uri
     * together: $ef_uri . {client_slug} . $ef_api_endpoint;
     *
     * @var string
     */
    private static $ef_api_endpoint = '/api/v2';


    /**
     * Store the blanked api key
     *
     * @var string
     */
    private $api_key = '';


    /**
     * Store the client slug
     *
     * @var string
     */
    private $client_slug = '';



    /**
     * Client constructor.
     *
     * @param $client_slug
     * @param string $api_key_unblanked
     * @throws InvalidArgumentException
     */
    public function __construct($client_slug = '', $api_key_unblanked = '')
    {

        if (false === is_string($client_slug) || true === empty($client_slug)) {
            throw new InvalidArgumentException('You must pass an EventsForce client slug (can be found in your EventsForce dashboard url)');
        }

        if (false === is_string($api_key_unblanked) || true === empty($api_key_unblanked)) {
            throw new InvalidArgumentException('You must pass an EventsForce API Key');
        }

        $this->api_key = Client::blankKey($api_key_unblanked);
        $this->client_slug = $client_slug;

        $this->client = new GuzzleClient([
            'base_uri' => Client::$ef_uri . $this->$client_slug . Client::$ef_api_endpoint,
            'headers'    => [
                'Authorization' => 'Basic ' . $this->api_key,
                'Content-Type'  => 'application/json'
            ]
        ]);
    }

    /**
     * A method that blanks a key passed in
     * The events force api expects a blanked key
     *
     * @param $key
     * @return string
     */
    private static function blankKey($key)
    {
        return base64_encode(':' . $key);
    }

//    /**
//     * Method to merge array passed in
//     *
//     * @param $args - passed in arguments
//     * @param $defaults - default argument array to merge with
//     * @return array
//     */
//    private static function argsMerge($args, $defaults)
//    {
//        if (false === is_array($args))
//            $args = array();
//
//        return array_merge($defaults, $args);
//    }
}
