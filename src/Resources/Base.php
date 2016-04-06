<?php

namespace EventsForce\Resources;

use EventsForce\Client;

abstract class Base
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}