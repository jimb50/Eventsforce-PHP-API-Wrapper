<?php

namespace EventsForce\Resources;
use EventsForce\Exceptions\InvalidArgumentException;

/**
 * Class for handling invoices resource
 * Api docs: http://docs.eventsforce.apiary.io/#reference/invoices
 *
 * @package EventsForce\Resources
 */
class Invoices extends BaseResource
{
    /**
     * Method to get all Invoices
     * Api Docs: http://docs.eventsforce.apiary.io/#reference/invoices/invoicesjsoninvoicenumberafter/get
     *
     * @param int $invoiceNumberAfter - for paging
     *
     * @return \Psr\Http\Message\StreamInterface
     * @throws InvalidArgumentException
     */
    public function getAll($invoiceNumberAfter = 0)
    {
        if (!is_numeric($invoiceNumberAfter) || 0 > $invoiceNumberAfter) {
            throw new InvalidArgumentException('If passing an invoiceNumberAfter value, it must be a positive integer');
        }

        $request = $this->client->request([
            'endpoint' => $this->genEndpoint('invoices.json')
        ]);

        // set query
        if (0 < $invoiceNumberAfter) {
            $request->setQuery([
                'invoiceNumberAfter' => $invoiceNumberAfter
            ]);
        }

        return $request->send();
    }
}