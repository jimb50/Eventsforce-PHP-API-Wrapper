<?php

namespace EventsForce\Resources;
use EventsForce\Exceptions\InvalidArgumentException;

class Payments extends InvoiceBasedResource
{

    /**
     * Post payment defaults
     * Can be set and updated using setPostDefault($key, $value)
     * @var array
     */
    public $post_defaults = [
        'amount' => 0,
        'currencyCode' => '',
        'comment' => '',
        'transactionReference' => ''
    ];

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

    /**
     * Method to get a single payment under an invoice
     * Api docs: http://docs.eventsforce.apiary.io/#reference/payments/invoicesinvoicenumberpaymentspaymentidjson/get
     *
     * @param bool $payment_id
     * @return \Psr\Http\Message\StreamInterface
     * @throws \EventsForce\Exceptions\EmptyResponseException
     * @throws \EventsForce\Exceptions\Exception
     * @throws \EventsForce\Exceptions\ResourceNotFound
     */
    public function get($payment_id = false)
    {
        if (!is_numeric($payment_id) || 0 > $payment_id) {
            throw new InvalidArgumentException('You must pass a positive integer as a payment id');
        }

        $request = $this->client->request([
            'endpoint' => $this->genEndpoint([$this->getInvoiceId(), 'payments', $payment_id . '.json'])
        ]);

        return $request->send();
    }

    /**
     * Method to set a post default
     * This allows you to run multiple post payments where the post data isn't defined each time
     * e.g  ->setPostDefault('currencyCode', 'GBP')
     *      ->setPostDefault('comment', 'Made by My Application')
     *      ->setInvoice(1)
     *      ->post(['amount' => 2738.22]);
     *
     * @param bool $key
     * @param null $value
     * @return $this
     * @throws InvalidArgumentException;
     */
    public function setPostDefault($key = false, $value = null)
    {
        if (
            !is_string($key) ||
            empty($key) ||
            !isset($this->post_defaults[$key])
        ) {
            throw new InvalidArgumentException('You must pass a valid non empty key to set the default post data for');
        }

        if (null === $value) {
            throw new InvalidArgumentException('You must pass a value that is not null to set a post payment default');
        }

        $this->post_defaults[$key] = $value;

        return $this;
    }

    /**
     * Method to post a payment against an invoice
     * Api docs: http://docs.eventsforce.apiary.io/#reference/payments/invoicesinvoicenumberpaymentsjson/post
     * THIS NEEDS TESTING WITH A FULL ACCESS API
     *
     * @param array $data
     * @return \Psr\Http\Message\StreamInterface
     * @throws \EventsForce\Exceptions\EmptyResponseException
     * @throws \EventsForce\Exceptions\InvalidArgumentException
     * @throws \EventsForce\Exceptions\ResourceNotFound
     */
    public function post($data = [])
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('You must pass data as a non empty array to post a payment');
        }

        // merge post defaults with passed in data
        $data = $this->argsMerge($data, $this->post_defaults);

        // ensure an amount is set and is not 0
        if (
            !isset($data['amount']) ||
            empty($data['amount']) ||
            !is_numeric($data['amount'])
        ) {
            throw new InvalidArgumentException('You must pass a numeric amount greater than 0 to post a payment against an invoice');
        }

        // cast to float
        $data['amount'] = floatval($data['amount']);

        $request = $this->client->request([
            'endpoint' => $this->genEndpoint([$this->getInvoiceId(), 'payments.json'])
        ]);

        $request
            ->setMethod('POST')
            ->setJson($data);

        return $request->send();
    }
}