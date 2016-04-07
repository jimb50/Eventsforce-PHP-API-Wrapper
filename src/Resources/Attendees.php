<?php

namespace EventsForce\Resources;
use EventsForce\Exceptions\InvalidArgumentException;

/**
 * Class for handling the attendees resource on the Events Force API: http://docs.eventsforce.apiary.io/#reference/attendees
 *
 * @package EventsForce\Resources
 */
class Attendees extends BaseResource
{
    /**
     * Event id which we'll be looking in
     *
     * @var int
     */
    protected $event;

    /**
     * Endpoint prefix
     *
     * @var string
     */
    protected $endpoint_prefix = 'events';

    /**
     * Method to get all events
     * Api Docs: http://docs.eventsforce.apiary.io/#reference/attendees/eventseventidattendeesjsonlastmodifiedafterpaymentstatuscategoryregistrationstatus/get
     *
     * @param array $query
     *
     * @return \Psr\Http\Message\StreamInterface
     * @throws InvalidArgumentException
     */
    public function getAll($query = [])
    {
        if (!is_array($query)) {
            throw new InvalidArgumentException('If passing $query, it must be an array');
        }

        $request = $this->client->request([
            'endpoint' => $this->genEndpoint([$this->event, 'attendees.json'])
        ]);

        $request->setQuery($query);

        return $request->send();
    }

    /**
     * Method to set the current event we're looking up in for attendees
     *
     * @param bool $event_id
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setEvent($event_id = false)
    {
        if (!is_numeric($event_id)) {
            throw new InvalidArgumentException('You need to pass an integer event id');
        }
        $this->event = $event_id;

        return $this;
    }

    /**
     * Getter for event id
     *
     * @return int
     */
    public function getEventId()
    {
        return $this->event;
    }
}