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
        $this->assertInstanceOf('EventsForce\Resources\BaseResource', $this->client->events);
        $this->assertInstanceOf('EventsForce\Resources\EventBasedResource', $this->client->attendees);
        $this->assertInstanceOf('EventsForce\Resources\EventBasedResource', $this->client->sessions);
        $this->assertInstanceOf('EventsForce\Resources\BaseResource', $this->client->people);
        $this->assertInstanceOf('EventsForce\Resources\BaseResource', $this->client->invoices);
    }

    public function testResourcesInstances()
    {
        $this->assertInstanceOf('EventsForce\Resources\Events', $this->client->events);
        $this->assertInstanceOf('EventsForce\Resources\Attendees', $this->client->attendees);
        $this->assertInstanceOf('EventsForce\Resources\Sessions', $this->client->sessions);
        $this->assertInstanceOf('EventsForce\Resources\People', $this->client->people);
        $this->assertInstanceOf('EventsForce\Resources\Invoices', $this->client->invoices);
    }
}