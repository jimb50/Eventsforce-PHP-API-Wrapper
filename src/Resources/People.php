<?php

namespace EventsForce\Resources;

use EventsForce\Exceptions\InvalidArgumentException;

/**
 * Class for handling people resource
 * Api Docs: http://docs.eventsforce.apiary.io/#reference/people
 *
 * @package EventsForce\Resources
 */
class People extends BaseResource
{
    /**
     * Method to get a single person
     * Api Docs: http://docs.eventsforce.apiary.io/#reference/people/get
     *
     * @param bool|int $person_id
     * @return \Psr\Http\Message\StreamInterface
     * @throws InvalidArgumentException
     */
    public function get($person_id = false)
    {
        if (!is_numeric($person_id) || $person_id < 0) {
            throw new InvalidArgumentException('You need to pass a positive numeric value as a person id');
        }

        $request = $this->client->request([
            'endpoint' => $this->genEndpoint(['people', $person_id . '.json'])
        ]);

        return $request->send();
    }
}