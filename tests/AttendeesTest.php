<?php
class AttendeesTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new EventsForce\Client('client_slug', 'apikey');
    }

    public function testSetEventAssigningCorrectly()
    {
        $this->client->attendees->setEvent(2);
        $this->assertEquals(2, $this->client->attendees->getEventId());
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSetEventFailingOnEmptyInput()
    {
        $this->client->attendees->setEvent();
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSetEventFailingOnStringInput()
    {
        $this->client->attendees->setEvent('Test');
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSetEventFailingOnBoolInput()
    {
        $this->client->attendees->setEvent(true);
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSetEventFailingOnArrayInput()
    {
        $this->client->attendees->setEvent(array(1,2,3));
    }

    public function testGetEventNotSetReturnsNull()
    {
        $result = $this->client->attendees->getEventId();

        $this->assertEquals(null, $result);
    }

    public function testGenEndpointWithString()
    {
        $endpoint = $this->client->attendees->genEndpoint('test.json');

        $this->assertEquals('events/test.json', $endpoint);
    }

    public function testGenEndpointWithArray()
    {
        $endpoint = $this->client->attendees->genEndpoint([2, 'test.json']);

        $this->assertEquals('events/2/test.json', $endpoint);
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testGenEndpointFailingOnBoolInput()
    {
        $this->client->attendees->genEndpoint(true);
    }
}