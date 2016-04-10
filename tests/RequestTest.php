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
     * @dataProvider notAllowedSetMethodProvider
     * @expectedException EventsForce\Exceptions\Exception
     */
    public function testSettingMethodFailsWhenSettingNotAllowed($value)
    {
        $request = $this->client->request();
        $request->setMethod($value);
    }

    public function notAllowedSetMethodProvider()
    {
        return array(
            array('test'),
            array('NotAllowed')
        );
    }

    /**
     * @dataProvider invalidDataSetMethodProvider
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSettingMethodFailsWithIncorrectInput($value)
    {
        $request = $this->client->request();
        $request->setMethod($value);
    }

    public function invalidDataSetMethodProvider()
    {
        return array(
            array(true),
            array(false),
            array(array('GET')),
            array(array()),
            array(1),
            array(-1),
            array(new stdClass())
        );
    }
}