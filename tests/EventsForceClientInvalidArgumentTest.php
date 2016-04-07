<?php
class EventsForceClientInvalidArgumentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnEmptyApiKey()
    {
        new EventsForce\Client('client_slug', '');
    }
    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnNumericalApiKey()
    {
        new EventsForce\Client('client_slug', 1);
    }
    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnBoolApiKey()
    {
        new EventsForce\Client('client_slug', true);
    }
    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnNoApiKey()
    {
        new EventsForce\Client('client_slug');
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnEmptyClientSlug()
    {
        new EventsForce\Client('', 'api_key');
    }
    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnNumericalClientSlug()
    {
        new EventsForce\Client(1, 'api_key');
    }
    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnBoolClientSlug()
    {
        new EventsForce\Client(true, 'api_key');
    }
    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnNothingPassed()
    {
        new EventsForce\Client();
    }
}