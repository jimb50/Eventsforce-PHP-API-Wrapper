PHP API wrapper for the Events Force API
======

An API client package for the Events Force API.
For reference to what kind of data you can pass to the API methods refer to the general [API documentation](http://docs.eventsforce.apiary.io/#reference).
To get a client slug / client account string see [here](http://docs.eventsforce.apiary.io/#introduction/url)

Contents
------

1. [Requirements](#requirements)
2. [Installation](#install)
3. [Initializing](#init)
4. [Full usage](#usage)
    - [Events](#events-usage)
    - [Attendees](#attendees-usage)
    - [Sessions](#sessions-usage)
    - [People](#people-usage)
    - [Invoices](#invoices-usage)
    - [Payments](#payments-usage)
5. [Responses](#response)
6. [Contributing](#contrib)
7. [Licence](#licence)


<a name=requirements>Requirements</a>
------

* PHP >= 5.5.0
* [Guzzle](https://github.com/guzzle/guzzle)

<a name=install>Installation</a>
------

```
composer require jbarnard/efapiphp
```

<a name=init>Initializing and basic usage of the client</a>
------

Note: The follow examples all use the apiexample credentials as shown [here](http://docs.eventsforce.apiary.io/#introduction/example-data)

```php
// Define a new client, passing in the client slug and api key
$client = new \EventsForce\Client('apiexample', '035E0A65508A4D8FAB63A983F36ACCAC');
```

Now you are ready to use specific resources and methods
The resources are split as shown on the [api docs](http://docs.eventsforce.apiary.io/#introduction/url)
E.g to access [events get all](http://docs.eventsforce.apiary.io/#reference/events/eventsjson/get) you would do the following:

```php
$stream = $client->events->getAll();
```

The response is a \Psr\Http\Message\StreamInterface

<a name=usage>Usage - Full method map</a>
------

#### <a name=events-usage>Events</a> - http://docs.eventsforce.apiary.io/#reference/events ####
------

#####[Get all - /events.json](http://docs.eventsforce.apiary.io/#reference/events/eventsjson/get)#####
```php
$client->events->getAll();
```

#####[Get single - /events/{event_id}.json](http://docs.eventsforce.apiary.io/#reference/events/eventseventidjson/get)
```php
$client->events->get(2); // where 2 is the event id
```

#### <a name=attendees-usage>Attendees</a> - http://docs.eventsforce.apiary.io/#reference/attendees ####
------

#####[Get all attendees for an event - /events/{event_id}/attendees.json](http://docs.eventsforce.apiary.io/#reference/attendees/eventseventidattendeesjsonlastmodifiedafterpaymentstatuscategoryregistrationstatus/get)#####
Available parameters:
* lastModifiedAfter
* paymentStatus
* category
* registrationStatus

```php
$client->attendees
    ->setEvent(2)
    ->getAll();

// Or you can also pass arguments where it is a key value array as below
$arguments = [
    'lastModifiedAfter' => '2016-04-07 19:43:26', // date('Y-m-d H:i:s');
    'paymentStatus' => 'paid',
    'category' => 'Attendee',
    'registrationStatus' => 'complete'
];

$client->attendees
    ->getAll($arguments);

// this will return a stream for only attendees who have paid, are an Attendee, they have completed their registration and they were last modified after 2016-04-07 19:43:26
// also notice we didn't run 'setEvent' again, this is because until you set an event again it will use the previously set one
// nb: attempting to get a resource which depends on an id prior to setting it will throw an exception
```

#####[Get a single attendee for an event by their person ID - /events/{event_id}/attendees/{attendee_id}.json](http://docs.eventsforce.apiary.io/#reference/attendees/eventseventidattendeespersonidjson/get)#####
```php
$client->attendees
    ->setEvent(1)
    ->get(103);
```

#####[Update an attendee - /events/{event_id}/attendees/{attendee_id}.json?_HttpMethod=PATCH](http://docs.eventsforce.apiary.io/#reference/attendees/eventseventidattendeespersonidjsonhttpmethodpatch/post)#####
Needs testing with a full access api, not just the example
```php
$client->attendees
    ->setEvent(1)
    ->update(103, [
        'customData' => [
            [
                'key' => 'Receive Newsletter',
                'value' => 'false',
            ],
            [
                'key' => 'CRM ID',
                'value' => '123456'
            ]
        ]
    ]);
```

#####[Authenticate an attendee - /events/{event_id}/attendees/authenticate.json](http://docs.eventsforce.apiary.io/#reference/attendees/eventseventidattendeesauthenticatejson/post)#####
The value required for userID depends on the attendeeIDMode set for the event.
```php
$client->attendees
    ->setEvent(1)
    ->auth('aliquet@mauris.co.uk', 'DWS7C6Z');
```

#### <a name=sessions-usage>Sessions</a> - http://docs.eventsforce.apiary.io/#reference/sessions ####
------

#####[Get all sessions for an event - /events/{event_id}/sessions.json](http://docs.eventsforce.apiary.io/#reference/sessions/eventseventidsessionsjson/get)#####
```php
$client->sessions
    ->setEvent(3)
    ->getAll();
```

#####[Get a single session for an event - /events/{event_id}/sessions/{session_id}.json](http://docs.eventsforce.apiary.io/#reference/sessions/eventseventidsessionssessionidjson/get)#####
```php
$client->sessions
    ->setEvent(3)
    ->get(17);
```

#### <a name=people-usage>People</a> - http://docs.eventsforce.apiary.io/#reference/people ####
------

#####[Get a single person - /people/{person_id}.json](http://docs.eventsforce.apiary.io/#reference/people/get)#####
```php
$client->people
    ->get(99);
```

#### <a name=invoices-usage>Invoices</a> - http://docs.eventsforce.apiary.io/#reference/invoices ####
------


#####[Get all invoices - /invoices.json](http://docs.eventsforce.apiary.io/#reference/invoices/invoicesjsoninvoicenumberafter/get)#####
Can have an optional invoiceNumberAfter parameter which will return the items with id's from that point, defaults to 0
```php
$client->invoices
    ->getAll();

// or with parameter
$client->invoices
    ->getAll(1);
```

#####[Get a single invoice - /invoices/{invoice_number}.json](http://docs.eventsforce.apiary.io/#reference/invoices/invoicesinvoicenumberjson/get)#####
```php
$client->invoices
    ->get(1);
```

#####[Update an invoice - /invoices/{invoice_number}.json?_HttpMethod=PATCH](http://docs.eventsforce.apiary.io/#reference/invoices/invoicesinvoicenumberjson/post)#####
Needs testing with a full access api, not just the example
```php
$client->invoices
    ->update(1, [
        'externalInvoiceReference' => 'EF123456'
    ]);
// Because EventsForce have only opened one field to be updated on an invoice this method has a helper as below:
$client->invoices
    ->updateExternalRef(1, 'EF123456');
```

#### <a name=payments-usage>Payments</a> - http://docs.eventsforce.apiary.io/#reference/payments ####
------

#####[Get all payments for an invoice - /invoices/{invoice_number}/payments.json](http://docs.eventsforce.apiary.io/#reference/payments/invoicesinvoicenumberpaymentsjson/get)#####
```php
$client->payments
    ->setInvoice(2)
    ->getAll();
```

#####[Post a payment against an invoice /invoices/{invoice_number}/payments.json](http://docs.eventsforce.apiary.io/#reference/payments/invoicesinvoicenumberpaymentsjson/post)#####
Needs testing with a full access api, not just the example
```php
$client->payments
    ->setInvoice(2)
    ->setPostDefault('currencyCode', 'GBP') // you can use setPostDefault to set a default payment parameter, this allows you to set default then post multiple payments using similar details
    ->setPostDefault('comment', 'Made by My Application') // set a default comment for all future payments
    ->post(['amount' => 29.99]); // post one payment

$client->payments
    ->post(['amount' => 27.79]); // post another
```

#####[Get a single payment for an invoice - /invoices/{invoice_number}/payments/{payment_id}.json](http://docs.eventsforce.apiary.io/#reference/payments/invoicesinvoicenumberpaymentspaymentidjson/get)#####
```php
$client->payments
    ->setInvoice(2)
    ->get(2);
```


<a name=responses>Return values</a>
------

The methods return a response object - http://guzzle3.readthedocs.org/http-client/response.html
You can get the body of the response by calling:
```php
$body = $response->getBody();
```

You can also get the status code:
```php
$status_code = $response->getStatusCode();
```

There are other helpers that can be seen here: http://guzzle3.readthedocs.org/http-client/response.html


Example JSON output:
``` JSON
{
  "responseCode": 200,
  "systemErrorCode": "",
  "systemErrorMessage": "",
  "userErrorMessage": "",
  "itemCount": 2,
  "data": [
    {
      "detailsURL": "https://www.eventsforce.net/apiexample/api/v2/events/1.json",
      "eventID": 1,
      "eventName": "Test API event",
      "eventStatus": "notlive",
      "eventStartDateTime": "2014-01-23T09:00:00Z",
      "eventEndDateTime": "2014-01-23T17:00:00Z",
      "venueName": ""
    },
    {
      "detailsURL": "https://www.eventsforce.net/apiexample/api/v2/events/2.json",
      "eventID": 2,
      "eventName": "Test API event with payment",
      "eventStatus": "notlive",
      "eventStartDateTime": "2014-01-23T09:00:00Z",
      "eventEndDateTime": "2014-01-23T17:00:00Z",
      "venueName": ""
    }
  ]
}
```

<a name=contrib>Contributing</a>
------

Found in CONTRIBUTING.md


<a name=licence>Licence</a>
------

Found in LICENSE file