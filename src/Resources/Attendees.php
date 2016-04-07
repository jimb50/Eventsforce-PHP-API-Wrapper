<?php

namespace EventsForce\Resources;
use EventsForce\Exceptions\InvalidArgumentException;

/**
 * Class for handling the attendees resource on the Events Force API: http://docs.eventsforce.apiary.io/#reference/attendees
 *
 * @package EventsForce\Resources
 */
class Attendees extends Base
{
    /**
     * Event id which we'll be looking in
     *
     * @var int
     */
    protected $event;


    /**
     * Method to set the current event we're looking up in for attendees
     *
     * @param bool $event_id
     */
    public function setEvent($event_id = false)
    {
        if (!is_numeric($event_id)) {
            throw new InvalidArgumentException('You need to pass a int event id');
        }
        $this->event = $event_id;
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