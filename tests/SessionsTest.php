<?php
class SessionsTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new EventsForce\Client('client_slug', 'apikey');
    }

    public function testSetEventAssigningCorrectly()
    {
        $this->client->sessions->setEvent(2);
        $this->assertEquals(2, $this->client->sessions->getEventId());
    }

    /**
     * @expectedException EventsForce\Exceptions\EventsForceException
     */
    public function testNotAllowingYouToGetEventIdIfNoEventIdSet()
    {
        $this->client->sessions->getEventId();
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSetEventFailingOnEmptyInput()
    {
        $this->client->sessions->setEvent();
    }

    /**
     * @dataProvider invalidInputSetEventProvider
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSetEventFailingOnInvalidInput($value)
    {
        $this->client->sessions->setEvent($value);
    }

    public function invalidInputSetEventProvider()
    {
        return array(
            array('Test'),
            array(false),
            array(true),
            array(array(1,2,3)),
            array(-1),
            array(new stdClass())
        );
    }

    public function testGenEndpointWithString()
    {
        $endpoint = $this->client->sessions->genEndpoint('test.json');

        $this->assertEquals('events/test.json', $endpoint);
    }

    public function testGenEndpointWithArray()
    {
        $endpoint = $this->client->sessions->genEndpoint([2, 'test.json']);

        $this->assertEquals('events/2/test.json', $endpoint);
    }

    /**
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testEmptyQueryGet()
    {
        $this->client->sessions->get();
    }

    /**
     * @dataProvider invalidQueryGetProvider
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testInvalidQueryGet($value)
    {
        $this->client->sessions->get($value);
    }

    public function invalidQueryGetProvider()
    {
        return array(
            array(true),
            array(false),
            array(''),
            array('test'),
            array(-1),
            array(array()),
            array(new stdClass())
        );
    }

    /**
     * @expectedException \EventsForce\Exceptions\EventsForceException
     */
    public function testNotSetEventIdGetAll()
    {
        $this->client->sessions->getAll();
    }
}