<?php
class InvoicesTest extends PHPUnit_Framework_TestCase
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
        $this->client->invoices->genEndpoint(true);
    }

    /**
     * @dataProvider invalidQueryGetProvider
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testInvalidQueryGet($after)
    {
        $this->client->invoices->getAll($after);
    }

    public function invalidQueryGetProvider()
    {
        return array(
            array(true),
            array(false),
            array(''),
            array('test'),
            array(-1),
            array(array())
        );
    }
}