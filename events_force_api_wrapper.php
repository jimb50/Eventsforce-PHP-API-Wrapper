<?php
/*
 * Eventsforce PHP curl wrapper class for accessing the events force API and methods
 *  - API documentation here: http://docs.eventsforce.apiary.io/#reference
 *
 *
 * Author: James Barnard
 * Version: 0.6
 * Contact: james@barnardcoates.co.uk
 * Licence: http://opensource.org/licenses/bsd-license.php
 *
 * */

/**
 * The eventsforce api url constants
 * NB: Ensure these are as stated in the Eventsforce API documentation
 */
define('ef_api_uri_first', 'https://www.eventsforce.net/');
define('ef_api_uri_second', '/api/v2');

/**
 * Default api key for events force
 * NB: when instantiating the EFAuth class you can pass in a slug and api_key so you don't have to store in globals
 */
define('ef_api_key', '');

/**
 * Default client for events force
 * NB: when instantiating the EFAuth class you can pass in a slug and api_key so you don't have to store in globals
 */
define('ef_client_slug', '');


/**
 * EFAuth class
 *
 * contains vars and methods to communicate with the events force API
 *
 * @vars
 * 	- key - api key
 * 	- blanked_key - blanked api key
 * 	- eventID - id of event if want to only access one event
 * 	- client - client slug
 *
 *
 * @public
 * 	- get_events - gets all events
 *  - get_event - gets a single event by id
 * 	- get_attendee - gets a single attendee of an event
 * 	- get_attendees - gets all attendees for an event
 * 	- update_attendee - posts or updates custom data to an attendee
 *  - authenticate_attendee - authenticates an attendee to ensure their details are valid
 *  - get_sessions - gets the sessions linked to a specific event
 *  - get_session - gets a specific session linked to a specific event
 *  - get_person - gets a person based on their generic eventsforce ID (not specifically linked to an event)
 *  - get_invoices - gets invoices for an event starting from a specific number (limited to 1000 per response)
 *  - get_invoice - gets a specific invoice
 *  - set_invoice_ext_ref - sets an external reference value for an invoice
 *  - get_payments - gets payments for an invoice
 *  - get_payment - gets a specific payment for an invoice
 *  - post_payment - posts a payment entry for an invoice
 *
 *
 * @private
 *  - blank_key - blanks an api key
 *  - ef_curl - base function to either get or post data to the Eventsforce API using curl
 *  - args_mrg - merges two arrays (used with array based parameters and default values)
 *
 */
class EFAuth {
	
	// unblanked key
	private $key;
	// blanked api key
	private $blanked_key;
	// event id
	public $eventID;
	//client slug
	private $client;


	/**
	 * CONSTRUCTOR
	 *
	 * @param $args - optional array with properties:
	 * 	- key - api key
	 * 	- client - client slug
	 *  - eventid - id of event as default
	 *
	 *
	 */
	function __construct($args = null) {
		//defaults
		$defaults = array(
			'key' => ef_api_key,
			'client' => ef_client_slug,
			'eventid' => ''
		);
		$r = $this->args_mrg($args, $defaults);

		$this->key = $r['key']; // store key
		$this->blanked_key = $this->blankKey($this->key); // store blanked key
		$this->client = $r['client']; // store client

		$this->eventID = $r['eventid']; // store eventID
	}


	/************************
	 *
	 * EVENTS
	 *
	 ************************/

	/**
	 * public function to get all events for the client
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/events.json
	 *
	 * @return JSON api response
	 */
	public function get_events() {
		$uri = "/events.json";
		return $this->ef_curl($uri);
	}

	/**
	 * public function to get single event
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/events/eventID.json
	 *
	 * @return JSON api response
	 */
	public function get_event($eventID = '') {
		if (!is_int($eventID))
			if (is_int($this->eventID)) : $eventID = $this->eventID; else: return false; endif;

		$uri = "/events/" . $eventID . ".json";
		return $this->ef_curl($uri);
	}


	/************************
	 *
	 * ATTENDEES
	 *
	 ************************/

	/**
	 * public function to get an attendee
	 *
	 * @param $eventID - id of event, defaults to saved one if not given
	 * @param $attendee_id - id of Attendee to get
	 *
	 * @return JSON api response
	 */
	public function get_attendee($attendee_id = '', $eventID = '') {
		if (!is_int($eventID))
			if (is_int($this->eventID)) : $eventID = $this->eventID; else: return false; endif;

		if (!is_int($attendee_id))
			return false;

		$uri = "/events/" . $eventID . "/attendees/" . $attendee_id . ".json";
		return $this->ef_curl($uri);
	}

	/**
	 * public function to get all attendees for the event
	 *
	 * @param $args - optional array with properties
	 * 	- eventID - id of event, defaults to saved one if not given
	 * 	- last_modified - If specified, returns only attendee records that were modified after the given timestamp Example: 2014-01-09T21:27:35Z.
	 *  - payment_status - If specified, returns only attendee records that have the given payment status Example: paid.
	 * 	- category - If specified, returns only attendee records that have the given attendee category Example: Attendee.
	 * 	- registration_status - If specified, returns only attendee records that have the given registration status. If ommitted, only complete registrations are returned. Example: complete
	 *
	 * @return JSON api response
	 *
	 */
	public function get_attendees($args = null) {
		//defaults
		$defaults = array(
			'eventid' => $this->eventID,
			'last_modified' => '',
			'payment_status' => '',
			'category' => '',
			'registration_status' => ''
		);
		$r = $this->args_mrg($args, $defaults);

		if (!is_int($r['eventid']))
			return false;

		$uri = "/events/" . $r['eventid'] . "/attendees.json?lastModifiedAfter=" . $r['last_modified'] . "&paymentStatus=" . $r['payment_status'] . "&category=" . $r['category'] . "&registrationStatus=" . $r['registration_status'];
		return $this->ef_curl($uri);
	}

	/**
	 * public function to update a specific attendee via POST
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/events/eventID/attendees/personID.json?_HttpMethod=PATCH
	 *
	 * @param $args - required array with properties
	 *  - eventid - id of event attendee is linked to
	 *  - userid - id of attendee to update
	 *  - data - multi dimensional array of data to update user with
	 *
	 * @return JSON api response
	 *
	 */
	public function update_attendee($args = null) {
		if ($args == null)
			return false;

		//defaults
		$defaults = array(
			'eventid' => !empty($this->eventID) ? $this->eventID : '',
			'userid' => '',
			'data' => array()
		);
		$r = $this->args_mrg($args, $defaults);

		if (empty($r['data']) || empty($r['userid']))
			return false;

		$uri = '/events/' . $r['eventid'] . '/attendees/' . $r['userid'] .  '.json?_HttpMethod=PATCH'; // set url

		return $this->ef_curl($uri, 'set', $r['data']);
	}

	/**
	 * public function to authenticate an attendee via POST
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/events/eventID/attendees/authenticate.json
	 *
	 * @param int eventID - defaults to global
	 * @param string userID - email of user
	 * @param string password - password of user
	 *
	 * @return JSON API response
	 *
	 */
	public function authenticate_attendee($userID = '', $password = '', $eventID = '') {
		if (!is_int($eventID))
			if (is_int($this->eventID)) : $eventID = $this->eventID; else: return false; endif;

		if (empty($userID) || empty($password))
			return false;

		$uri = '/events/' . $eventID . '/attendees/authenticate.json';
		return $this->ef_curl($uri, 'set', array('userID' => $userID, 'password' => $password));
	}



	/************************
	 *
	 * SESSIONS
	 *
	 ************************/

	/**
	 * public function to get event sessions
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/events/eventID/sessions.json
	 *
	 * @param $eventID - id of event to get sessions for
	 *
	 * @return JSON API response
	 *
	 */
	public function get_sessions($eventID = '') {
		if (!is_int($eventID))
			if (is_int($this->eventID)) : $eventID = $this->eventID; else: return false; endif;

		$uri = "/events/" . $eventID . "/sessions.json";
		return $this->ef_curl($uri);
	}

	/**
	 * public function to get an event session
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/events/eventID/sessions/sessionID.json
	 *
	 * @param $eventID - id of event which includes the session
	 * @param $sessionID - id of session within the event
	 *
	 * @return JSON API response
	 *
	 */
	public function get_session($sessionID = '', $eventID = '') {
		if (!is_int($sessionID))
			return false;

		if (!is_int($eventID))
			if (is_int($this->eventID)) : $eventID = $this->eventID; else: return false; endif;

		$uri = "/events/" . $eventID . "/sessions/" . $sessionID . ".json";
		return $this->ef_curl($uri);
	}



	/************************
	 *
	 * PEOPLE
	 *
	 ************************/

	/**
	 * public function to get a person
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/people/personID.json
	 *
	 * @param $personID - id of person to get
	 *
	 * @return JSON API response
	 *
	 */
	public function get_person($personID = '') {
		if (!is_int($personID))
			return false;

		$uri = "/people/" . $personID . ".json";
		return $this->ef_curl($uri);
	}



	/************************
	 *
	 * INVOICES
	 *
	 ************************/

	/**
	 * public function to get invoices for a client
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/invoices.json?invoiceNumberAfter=invoiceNumber
	 * - limited to 1000 records by Eventsforce
	 *
	 * @param $invoiceNumber - optional int, if present will return all invoices above this number (can be used to get more than 1000 records
	 *
	 * @return JSON API response
	 *
	 */
	public function get_invoices($invoiceNumber = 0) {
		if (!is_int($invoiceNumber))
			return false;

		$uri = "/invoices.json?invoiceNumberAfter=" . $invoiceNumber;
		return $this->ef_curl($uri);
	}

	/**
	 * public function to get an invoice by number for a client
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/invoices/invoiceNumber.json
	 *
	 * @param $invoiceNumber - required int, if present will return all invoices above this number (can be used to get more than 1000 records
	 *
	 * @return JSON API response
	 *
	 */
	public function get_invoice($invoiceNumber = '') {
		if (!is_int($invoiceNumber))
			return false;

		$uri = "/invoices/" . $invoiceNumber . ".json";
		return $this->ef_curl($uri);
	}

	/**
	 * public function to set an external reference for an invoice
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/invoices/invoiceNumber.json?_HttpMethod=PATCH
	 *
	 * @param int $invoiceNumber - required, the invoice number to update
	 * @param string $externalRef - required, the external reference to update an invoice to
	 *
	 * @return JSON API response
	 *
	 */
	public function set_invoice_ext_ref($invoiceNumber = '', $externalRef = '') {
		if (!is_string($externalRef) || !is_int($invoiceNumber))
			return false;

		$uri = "/invoices/" . $invoiceNumber . ".json?_HttpMethod=PATCH";
		return $this->ef_curl($uri, 'set', array('externalInvoiceReference' => $externalRef));
	}


	/************************
	 *
	 * PAYMENTS
	 *
	 ************************/

	/**
	 * public function to get payments linked to an invoice
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/invoices/invoiceNumber/payments.json
	 *
	 * @param $invoiceNumber - number of invoice to get payments for
	 *
	 * @return JSON API response
	 *
	 */
	public function get_payments($invoiceNumber = '') {
		if (!is_int($invoiceNumber))
			return false;

		$uri = "/invoices/" . $invoiceNumber . "/payments.json";
		return $this->ef_curl($uri);
	}

	/**
	 * public function to get a specific payment linked to an invoice
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/invoices/invoiceNumber/payments/paymentID.json
	 *
	 * @param $invoiceNumber - number of invoice to which payment is connected to
	 * @param $paymentID - id of payment to get
	 *
	 * @return JSON API response
	 *
	 */
	public function get_payment($invoiceNumber = '', $paymentID = '') {
		if (!is_int($invoiceNumber) || !is_int($paymentID))
			return false;

		$uri = "/invoices/" . $invoiceNumber . "/payments/" . $paymentID . ".json";
		return $this->ef_curl($uri);
	}

	/**
	 * public function to post a payment to an invoice
	 * - URL query is: https://www.eventsforce.net/apiexample/api/v2/invoices/invoiceNumber/payments.json
	 *
	 * @param $invoiceNumber - number of invoice to get payments for
	 * @param $args - array of data to pass to eventsforce
	 *  - amount - auto converts int to string
	 *  - currencyCode
	 *  - comment
	 *  - transactionReference
	 *
	 * @return JSON API response
	 *
	 */
	public function post_payment($invoiceNumber = '', $args = null) {
		if (!is_int($invoiceNumber))
			return false;

		//defaults
		$defaults = array(
			'amount' => '0',
			'currencyCode' => '',
			'comment' => '',
			'transactionReference' => ''
		);
		$r = $this->args_mrg($args, $defaults); // merge the arguments
		$r['amount'] = (string)$r['amount']; //cast amount to a string

		$uri = '/invoices/' . $invoiceNumber . '/payments.json'; // set url

		return $this->ef_curl($uri, 'set', $r);
	}


	/************************
	 *
	 * CLASS GENERAL METHODS
	 *
	 ************************/

	/**
	 * private function to blank EF API key
	 *
	 * @param $key - key to blank ready for CURL use
	 * 
	 * @return blanked key (string)
	 */
	private function blankKey($key) {
		return base64_encode(':' . $key);
	}

	/**
	 * private function to set or get data from EF API using CURL
	 *
	 * @param string $type (either set or get) - required
	 * @param string $uri - required, URI to go to
	 * @param array $data - required if $type is set - data to be posted to URI
	 * 
	 * @return JSON API response
	 */
	private function ef_curl($uri = '', $type = 'get', $data = '') {
		if (empty($type) || empty($uri))
			return false; // no type or uri set so return

		if ($type == 'set' && !is_array($data))
			return false; // it is set but it doesn't have any data so return

		$httpheaders = array();
		if ($type == 'set' && is_array($data)) {
			array_push($httpheaders, "Content-Type: application/json; charset=utf-8");
		}

		//auth to headers
		array_push($httpheaders,"Authorization: Basic " . $this->blanked_key);

		try {
			$ch = curl_init();
			
			if (FALSE === $ch)
		        throw new Exception('failed to initialize');
		    
		    
			curl_setopt($ch, CURLOPT_URL, ef_api_uri_first . $this->client . ef_api_uri_second . $uri);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);

			//if setting data, add post fields to curl
			if ($type == 'set') {
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			}

			curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheaders);

			// check certificate matches
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_CAINFO, __DIR__  . DIRECTORY_SEPARATOR . 'cert' . DIRECTORY_SEPARATOR . 'cacert.pem');
			// curl_setopt($ch, CURLOPT_CAPATH, __DIR__);
			
			$response = curl_exec($ch);
			
			if (FALSE === $response)
	       		throw new Exception(curl_error($ch), curl_errno($ch));
			
			curl_close($ch);
		} catch(Exception $e) {
			// return false;
			trigger_error(sprintf(
		 	    'Curl failed with error #%d: %s',
		 	    $e->getCode(), $e->getMessage()),
		 	    E_USER_ERROR);
		}
		return $response;
	}

	/**
	 * private function to merge array of arguments with defaults
	 *
	 * Seperate to error check for null
	 *
	 * @param $args - passed in arguments
	 * @param $defaults - default argument array to merge with
	 *
	 * @return merged array
	 */
	private function args_mrg($args, $defaults) {
		if ($args == null)
			$args = array();

		return array_merge($defaults, $args);
	}
}


?>