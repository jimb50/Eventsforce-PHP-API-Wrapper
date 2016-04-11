<?php
class BaseResourceTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new EventsForce\Client('client_slug', 'apikey');
    }

    public function testArgsMerge()
    {

        $defaults = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3'
        ];
        $args = [
            'key1' => 'value1new',
            'key4' => 'value4'
        ];
        $merged = $this->client->events->argsMerge($args, $defaults);
        $expected = [
            'key1' => 'value1new',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => 'value4'
        ];
        $this->assertEquals($expected, $merged);
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testGenEndpointFailingOnBoolInput()
    {
        $this->client->events->genEndpoint(true);
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testGenEndpointFailingOnClassInput()
    {
        $this->client->events->genEndpoint(new stdClass());
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testArgsMergeFailingOnEmptyInput()
    {
        $this->client->events->argsMerge();
    }

    /**
     * @dataProvider argsMergeInvalidInputProvider
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testArgsMergeFailingOnInvalidInput($args, $defaults)
    {
        $this->client->events->argsMerge($args, $defaults);
    }

    public function argsMergeInvalidInputProvider()
    {
        return array(
            array('', array()),
            array(new stdClass(), array()),
            array(true, array()),
            array(false, array()),
            array('test', array())
        );
    }
}