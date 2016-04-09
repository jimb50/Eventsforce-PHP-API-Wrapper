<?php
class InvoicesTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new EventsForce\Client('client_slug', 'apikey');
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

    /**
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testEmptyInputUpdate()
    {
        $this->client->invoices->update();
    }

    /**
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testEmptyDataInputUpdate()
    {
        $this->client->invoices->update(1);
    }

    /**
     * @dataProvider invalidInputUpdateProvider
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testInvalidInputUpdate($id, $data)
    {
        $this->client->invoices->update($id, $data);
    }

    public function invalidInputUpdateProvider()
    {
        return array(
            array('asdasd', array('data')),
            array(true, array('data')),
            array(false, array('data')),
            array(-1, array('data')),
            array(array(), array('data')),
            array(new stdClass(), array('data')),
            array(1, true),
            array(1, false),
            array(1, new stdClass()),
            array(1, 1),
            array(1, 'adad')
        );
    }


    /**
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testEmptyRefInputUpdateExternalRef()
    {
        $this->client->invoices->updateExternalRef(1);
    }

    /**
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testEmptyInputUpdateExternalRef()
    {
        $this->client->invoices->updateExternalRef();
    }

    /**
     * @dataProvider invalidInputUpdateExternalRefProvider
     * @expectedException \EventsForce\Exceptions\InvalidArgumentException
     */
    public function testInvalidInputUpdateExternalRef($id, $ref)
    {
        $this->client->invoices->updateExternalRef($id, $ref);
    }

    public function invalidInputUpdateExternalRefProvider()
    {
        return array(
            array('asdasd', 'asdasdad'),
            array(true, 'asdasdad'),
            array(false, 'asdasdad'),
            array(-1, 'asdasdad'),
            array(array(), 'asdasdad'),
            array(new stdClass(), 'asdasdad'),
            array(1, true),
            array(1, false),
            array(1, new stdClass()),
            array(1, 1),
            array(1, array('adasd')),
            array(1, '')
        );
    }
}