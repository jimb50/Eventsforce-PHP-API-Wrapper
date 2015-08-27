PHP API wrapper for the eventsforce API
======

A basic PHP wrapper for the eventsforce API methods, providing methods to POST and GET results
For reference to what kind of data you can pass to the API methods refer to the general [API documentation](http://docs.eventsforce.apiary.io/#reference).


Usage - General
------

``` php
//Require the class wrapper
require_once 'events_force_api_wrapper.php';

//create standard instance of Events force wrapper class
$ef = new EFAuth(array(
    'key' => '035E0A65508A4D8FAB63A983F36ACCAC', // non blanked key
    'client' => 'apiexample' // client slug
));

//Then you are ready to call methods such as:
$ef->get_events();
$ef->get_event(2);
$ef->authenticate(2, 'test@example.com', 'adhh322h8flan');

//and so on
```


Usage - more specific
------

Another way to use the class is to pass in an event id initially when you instantiate it as below.

``` php
$ef = new EFAuth(array(
    'key' => 'NON BLANKED KEY',
    'client' => 'CLIENT SLUG',
    'eventid' => 2
));

//this allows you to then call methods without passing in an eventID, as you are using a 'default' eventID as standard, you can still pass in an eventID which will override the default for that particular method

```



Available methods
------

### Event Methods
---


#### Get Events
This doesn't take any parameters and will all the events for the client slug passed in when the class was instantiated

``` php
$ef->get_events();
```


#### Get Event
This takes a single optional parameter of an event ID, it will return the details to that event

``` php
$ef->get_event(2);
```


### Attendee Methods
---


#### Get Attendees
This takes an array of key value options, all are optional.
They are:
eventid
last_modified
payment_status
category
registration_status
			

``` php
$ef->get_attendees(array('eventid' => 2));
```


#### Get Attendee
This takes an attendee id parameter that is required and then an optional eventid

``` php
$ef->get_attendee(10, 2);
```

#### Update Attendee
This takes an array of key value options, userid and data are mandatory.
The data option itself is an array of arrays and key value pairs which depend on the way the eventsforce event and attendees are set up and what options are available

``` php
$args = array(
	'eventid' => '2',
	'userid' => '9',
	'data' => array(
		'customData' => array(
			array('key' => 'Receive Newsletter', 'value' => 'false'),
			array('key' => 'CRM ID', 'value' => '123456')
		),
		'attendedSessions' => array(
			array('sessonID' => 987, 'lastUpdate' => '2013-10-07T13:27:00Z')
		),
		'attendedDays' => array(
			array('date' => '2013-08-29', 'lastUpdate' => '2013-10-07T13:27:00Z')
		),
		'customRegistrationData' => array(
			array('key' => 'Company Size', 'value' => '20'),
			array('key' => 'Year Founded', 'value' => '1886')
		)
	)
);
$updatedAttendee = $ef->update_attendee($args);
```


#### Authenticate Attendee
This takes a user email and password (mandatory) and an optional eventid.
It will return true or false dependent on the success of the authentication

``` php
$authenticate = $ef->authenticate_attendee('test@example.com', 'asdad4343h', 2);
```


### Session Methods
---


#### Get Sessions
This takes an optional eventID and returns info on all the related sessions (if they exist)

``` php
$sessions = $ef->get_sessions(2);
```

#### Get Session
This takes a mandatory session ID and an optional eventID and returns info on the specific session (if it exists)

``` php
$session = $ef->get_session(1,2);
```


### Person Methods
---


#### Get Person
This takes a mandatory person ID, this differs to attendees as is not linked to an event and just returns info on a person who is stored in the database

``` php
$person = $ef->get_person(6);
```


### Invoice Methods
---


#### Get Invoices
This returns up to 1000 invoice records (not linked to specific event)
It also takes an optional invoice number parameter which tells the method where to start getting invoice records from

``` php
$invoices = $ef->get_invoices();
```

#### Get Invoice
This gets a single invoice record via a mandatory invoice number parameter

``` php
$invoice = $ef->get_invoice(1);
```

#### Set Invoice External Reference
This will update an invoice external reference by taking an invoice number and new external reference string as parameters

``` php
$extRefUpdate = $ef->set_invoice_ext_ref(1,'');
```


### Payment Methods
---


#### Get Payments
This gets all payments under a specific invoice number (taken via mandatory parameter)

``` php
$payments = $ef->get_payments(1);
```

#### Get Payment
This gets info on a specific payment via mandatory invoicenumber and paymentid (respectively)

``` php
$payment = $ef->get_payment(1,1);
```

#### Post Payment
This method posts a payment against an invoice
It takes a mandatory invoice number and array of payment details:
amount (int)
currencyCode (string)
comment (string)
transactionReference (string)

``` php
$args = array('amount' => 20, 'currencyCode' => 'GBP', 'comment' => 'This is some money', 'transactionReference' => 'test1');
$payment = $ef->post_payment(1, $args);
```



Return values
------

The methods will always return a JSON string similar to the below:

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


Dependencies
------

Built running PHP version 5.6.2


Licence
------

http://opensource.org/licenses/bsd-license.php