<?php

namespace EventsForce\Resources;
use EventsForce\Exceptions\EventsForceException;
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
            'endpoint' => $this->genEndpoint([$this->getEventId(), 'attendees.json'])
        ]);

        $request->setQuery($query);

        return $request->send();
    }

    /**
     * Method to get a single attendee for an event
     * Api Docs: http://docs.eventsforce.apiary.io/#reference/attendees/eventseventidattendeespersonidjson/get
     *
     * @param bool $attendee_id
     * @return \Psr\Http\Message\StreamInterface
     * @throws \EventsForce\Exceptions\EmptyResponseException
     */
    public function get($attendee_id = false)
    {
        if (!is_numeric($attendee_id)) {
            throw new InvalidArgumentException('You need to pass a numeric value as an attendee id');
        }

        $request = $this->client->request([
            'endpoint' => $this->genEndpoint([$this->getEventId(), 'attendees', $attendee_id . '.json'])
        ]);

        return $request->send();
    }

    /**
     * Method to update an attendee with a passed in set of data
     * Api docs: http://docs.eventsforce.apiary.io/#reference/attendees/eventseventidattendeespersonidjsonhttpmethodpatch/post
     * NOTE: NEEDS TESTING WITH FULL ACCESS API
     *
     * @param bool $attendee_id
     * @param array $data
     * @return \Psr\Http\Message\StreamInterface
     * @throws EventsForceException
     * @throws \EventsForce\Exceptions\EmptyResponseException
     */
    public function update($attendee_id = false, $data = [])
    {
        if (!is_numeric($attendee_id)) {
            throw new InvalidArgumentException('You need to pass a numeric value as an attendee id');
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('You need to pass an array for data to update the attendee with');
        }

        $request = $this->client->request([
            'endpoint' => $this->genEndpoint([$this->getEventId(), 'attendees', $attendee_id . '.json'])
        ]);

        $request
            ->setQuery([
                '_Http_Method' => 'PATCH'
            ])
            ->setMethod('POST')
            ->setJson($data);

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
     * @return int
     * @throws EventsForceException
     */
    public function getEventId()
    {
        if (!is_numeric($this->event)) {
            throw new EventsForceException('You must set an event ID using ->setEvent({event_id}) prior to using this method');
        }
        return $this->event;
    }
}