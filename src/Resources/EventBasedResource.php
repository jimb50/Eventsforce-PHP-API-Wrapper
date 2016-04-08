<?php

namespace EventsForce\Resources;
use EventsForce\Exceptions\EventsForceException;
use EventsForce\Exceptions\InvalidArgumentException;

/**
 * Class BaseResource
 * Abstract used by resources that depend upon an event
 * e.g Attendees / Sessions
 *
 * @package EventsForce\Resources
 */
class EventBasedResource extends BaseResource
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
     * Method to set the current event we're looking up in for attendees
     *
     * @param bool $event_id
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setEvent($event_id = false)
    {
        if (!is_numeric($event_id) || $event_id < 0) {
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