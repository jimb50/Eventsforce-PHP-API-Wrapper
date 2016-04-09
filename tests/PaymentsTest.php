<?php
class PaymentsTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new EventsForce\Client('client_slug', 'apikey');
    }

    public function testSetInvoiceAssigningCorrectly()
    {
        $this->client->payments->setInvoice(2);
        $this->assertEquals(2, $this->client->payments->getInvoiceId());
    }

    /**
     * @expectedException EventsForce\Exceptions\EventsForceException
     */
    public function testNotAllowingYouToGetInvoiceIdIfNoInvoiceIdSet()
    {
        $this->client->payments->getInvoiceId();
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSetInvoiceFailingOnEmptyInput()
    {
        $this->client->payments->setInvoice();
    }

    /**
     * @dataProvider invalidInputSetEventProvider
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testSetInvoiceFailingOnStringInput($value)
    {
        $this->client->payments->setInvoice($value);
    }

    public function invalidInputSetEventProvider()
    {
        return array(
            array('Test'),
            array(false),
            array(true),
            array(array(1,2,3)),
            array(-1),
            array(array())
        );
    }

    public function testGenEndpointWithString()
    {
        $endpoint = $this->client->payments->genEndpoint('test.json');

        $this->assertEquals('invoices/test.json', $endpoint);
    }

    public function testGenEndpointWithArray()
    {
        $endpoint = $this->client->payments->genEndpoint([2, 'test.json']);

        $this->assertEquals('invoices/2/test.json', $endpoint);
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testGenEndpointFailingOnBoolInput()
    {
        $this->client->payments->genEndpoint(true);
    }
}