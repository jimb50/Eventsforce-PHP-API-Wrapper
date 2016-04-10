<?php

namespace EventsForce\Resources;
use EventsForce\Exceptions\InvalidArgumentException;

/**
 * Class for handling the events resource on the Events Force API: http://docs.eventsforce.apiary.io/#reference/events
 *
 * @package EventsForce\Resources
 */
class Events extends BaseResource
{

    /**
     * Method to get all events
     * Api Docs: http://docs.eventsforce.apiary.io/#reference/events/eventsjson/get
     * 
     * @return \Psr\Http\Message\StreamInterface
     * @throws \EventsForce\Exceptions\Exception
     */
    public function getAll()
    {
        $request = $this->client->request([
            'endpoint' => 'events.json'
        ]);

        return $request->send();
    }

    /**
     * Method to get single event
     * http://docs.eventsforce.apiary.io/#reference/events/eventseventidjson/get
     * @param bool $id
     * @return \Psr\Http\Message\StreamInterface
     * @throws \EventsForce\Exceptions\EmptyResponseException
     * @throws InvalidArgumentException
     */
    public function get($id = false)
    {
        if (!is_numeric($id) || 0 > $id) {
            throw new InvalidArgumentException('You need to pass a numeric value for the event id');
        }

        $request = $this->client->request([
            'endpoint' => $this->genEndpoint(['events', $id . '.json'])
        ]);

        return $request->send();
    }
}