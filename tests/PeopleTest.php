<?php
class PeopleTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new EventsForce\Client('client_slug', 'apikey');
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testGenEndpointFailingOnBoolInput()
    {
        $this->client->people->genEndpoint(true);
    }

    /**
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testEmptyQueryGet()
    {
        $this->client->people->get();
    }

    /**
     * @dataProvider invalidQueryGetProvider
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testInvalidQueryGet($value)
    {
        $this->client->people->get($value);
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
}