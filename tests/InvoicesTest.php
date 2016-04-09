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
     * @dataProvider invalidQueryGetAllProvider
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testInvalidQueryGetAll($after)
    {
        $this->client->invoices->getAll($after);
    }

    public function invalidQueryGetAllProvider()
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
     * @dataProvider invalidParameterGetProvider
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testInvalidParameterGet($value)
    {
        $this->client->invoices->get($value);
    }

    public function invalidParameterGetProvider()
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