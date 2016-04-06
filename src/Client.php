<?php

namespace EventsForce;

use EventsForce\Exceptions\InvalidArgumentException;
use EventsForce\Resources\Events;
use GuzzleHttp\Client as GuzzleClient;

class Client
{

    /**
     * The first part of the eventsforce api endpoint uri
     * together: $ef_uri . {client_slug} . $ef_api_endpoint;
     *
     * @var string
     */
    private static $efUri = 'https://www.eventsforce.net/';


    /**
     * The second part of the eventsforce api endpoint uri
     * together: $ef_uri . {client_slug} . $ef_api_endpoint;
     *
     * @var string
     */
    private static $efApiEndpoint = '/api/v2';


    /**
     * Store the blanked api key
     *
     * @var string
     */
    private $apiKey = '';


    /**
     * Store the client slug
     *
     * @var string
     */
    private $clientSlug = '';

    /**
     * Instance of our Guzzle Client
     *
     * @var object
     */
    private $client;


    /**
     * Instance of Events resource class \EventsForce\Resources\Events
     *
     * @var object
     */
    public $events;


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

        $this->apiKey = Client::blankKey($api_key_unblanked);
        $this->clientSlug = $client_slug;

        $this->client = new GuzzleClient([
            'base_uri' => Client::$efUri . $this->clientSlug . Client::$efApiEndpoint . '/',
            'headers'    => [
                'Authorization' => 'Basic ' . $this->apiKey,
                'Content-Type'  => 'application/json'
            ]
        ]);

        $this->bootstrapResources();
    }

    /**
     * Method that bootstraps our resources
     */
    private function bootstrapResources()
    {
        $this->events = new Events($this);
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

    /**
     * Method that creates a Request instance (EventsForce\Request)
     *
     * @param $parameters
     * @return Request
     */
    public function request($parameters = [])
    {
        return new Request($this->client, $parameters);
    }
}
