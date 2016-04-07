<?php
class EventsForceClientInvalidArgumentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidClientParametersProvider
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnEmptyApiKey($slug, $api)
    {
        new EventsForce\Client($slug, $api);
    }

    public function invalidClientParametersProvider()
    {
        return array(
            array('client_slug', ''),
            array('', ''),
            array('client_slug', 1),
            array('client_slug', true),
            array('', 'api_key'),
            array(1, 'api_key'),
            array(true, 'api_key')
        );
    }

    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnNoParams()
    {
        new EventsForce\Client();
    }
    /**
     * @expectedException EventsForce\Exceptions\InvalidArgumentException
     */
    public function testExceptionOnOnlyClientSlugParam()
    {
        new EventsForce\Client('test_client_slug');
    }
}