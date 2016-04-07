<?php

class EventsForceClientTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new EventsForce\Client('client_slug', 'apikey');
    }

    public function testClientObject()
    {
        $this->assertInstanceOf('EventsForce\Client', $this->client);
    }

    public function testResourcesExtendBase() {
        $this->assertInstanceOf('EventsForce\Resources\Base', $this->client->events);
    }

    public function testResourcesInstances()
    {
        $this->assertInstanceOf('EventsForce\Resources\Events', $this->client->events);
    }
}