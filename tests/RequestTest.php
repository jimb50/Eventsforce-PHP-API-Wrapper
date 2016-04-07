<?php
class RequestTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new EventsForce\Client('client_slug', 'apikey');
    }

    public function testRequestInstance()
    {
        $request = $this->client->request();
        $this->assertInstanceOf('EventsForce\Request', $request);
    }

    public function testCheckMethodsReturnInstance()
    {
        $request = $this->client->request();

        $this->assertInstanceOf('EventsForce\Request', $request->setOptions());
        $this->assertInstanceOf('EventsForce\Request', $request->setMethod('GET'));
        $this->assertInstanceOf('EventsForce\Request', $request->setParameters());
        $this->assertInstanceOf('EventsForce\Request', $request->setEndpoint());
        $this->assertInstanceOf('EventsForce\Request', $request->setQuery());
    }

    /**
     * @expectedException EventsForce\Exceptions\EventsForceException
     */
    public function testSettingMethodFailsWhenSettingNoMethod()
    {
        $request = $this->client->request();
        $request->setMethod();
    }

    /**
     * @expectedException EventsForce\Exceptions\EventsForceException
     */
    public function testSettingMethodFailsWhenSettingNotAllowedMethod()
    {
        $request = $this->client->request();
        $request->setMethod('NotAllowed');
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSettingMethodFailsWhenSettingBoolMethod()
    {
        $request = $this->client->request();
        $request->setMethod(true);
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSettingMethodFailsWhenSettingArrayMethod()
    {
        $request = $this->client->request();
        $request->setMethod(array('GET'));
    }
}