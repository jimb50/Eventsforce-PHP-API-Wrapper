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

    public function testPostDefault()
    {
        $post_defaults = $this->client->payments->post_defaults;
        // test default post payments have the right 4 keys
        $this->assertArrayHasKey('amount', $post_defaults);
        $this->assertArrayHasKey('currencyCode', $post_defaults);
        $this->assertArrayHasKey('comment', $post_defaults);
        $this->assertArrayHasKey('transactionReference', $post_defaults);
        // assert post defaults has only 4 keys
        $this->assertEquals(4, count($post_defaults));

        $payments = $this->client->payments->setPostDefault('currencyCode', 'GBP');
        // assert returns payments object
        $this->assertInstanceOf('EventsForce\Resources\Payments', $payments);
        // assert setting post default works
        $this->assertEquals('GBP', $this->client->payments->post_defaults['currencyCode']);
    }

    /**
     * @dataProvider invalidSetPostDefaultInputProvider
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testInvalidSetPostDefaultInputs($key, $value)
    {
        $this->client->payments->setPostDefault($key, $value);
    }

    public function invalidSetPostDefaultInputProvider()
    {
        return array(
            array('', ''),
            array('adsda', ''),
            array('', 'adadas'),
            array(1, 'asdasda'),
            array(true, 'asdsada'),
            array(new stdClass(), 'adsdsad'),
            array('notvalid', 'asdasd'),
            array(array(), 'dasda')
        );
    }
}