<?php

namespace EventsForce\Resources;

class Payments extends InvoiceBasedResource
{
    /**
     * Method to get all payments for an invoice
     * Api docs: http://docs.eventsforce.apiary.io/#reference/payments/get
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getAll()
    {
        $request = $this->client->request([
            'endpoint' => $this->genEndpoint([$this->getInvoiceId(), 'payments.json'])
        ]);

        return $request->send();
    }
}