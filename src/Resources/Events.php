<?php

namespace EventsForce\Resources;

/**
 * Class for handling the events resource on the Events Force API: http://docs.eventsforce.apiary.io/#reference/events
 *
 * @package EventsForce\Resources
 */
class Events extends Base
{

    /**
     * Method to get all events
     *
     * 
     * @return \Psr\Http\Message\StreamInterface
     * @throws \EventsForce\Exceptions\EventsForceException
     */
    public function getAll()
    {
        $request = $this->client->request([
            'endpoint' => 'events.json',
            'method' => 'get'
        ]);

        return $request->send();
    }
}