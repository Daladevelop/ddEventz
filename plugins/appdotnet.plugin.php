<?php

require_once('plugin.interface.php');


class appdotnet extends ddPlugin implements pluginInterface {

	//private $geo = array();
	private $tag, $query;

	public function __construct($eventId) {
		$this->service = 'app.net';
		parent::__construct($eventId);

	}

	public function admin()
	{
		echo "ADMINPAGE FOR APP.NET-PLUGINS! Japp Japp!"; 
	}
	public function setParameters(array $parameters) {
		// Set the parameters for the query to twitter
		if (isset($parameters['tag'])) {
			$this->tag = $parameters['tag'];
		}
	}

	/*public function getFeed() {	
		$response = $this->requestData();
		return $this->parseAPIResponse($response);
	}*/
	

	// twitter class specific methods is below here

	private function makeAppNetQuery() {
		// The basic URL for the twitter search API
		$base_query = 'https://alpha-api.app.net/stream/0/posts';

		if (isset($this->tag)) { // Make a search query by tag
			$this->query = $base_query . '/tag/' . urlencode($this->tag);
		}
	}

	/**
	 * Request the tweets from the twitter api
	 */
	public function requestData() {
		$this->makeAppNetQuery();

		// Check to see if cURL is installed
		if (function_exists('curl_init')) {
			// Set up cURL 
			$curl = curl_init($this->query); 
			curl_setopt($curl, CURLOPT_POST, false); 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, false);
			
			// Necessary in order for cURL to work with https(?)
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($curl);
			curl_close($curl);
			
			//return $response;
			return $this->parseAPIResponse($response);
		} else {
			// Log the fact that cURL isn't installed
			logger::log(ALL, 'Failed to find function \'curl_init\'. This will prevent plugin' . $plugin . 'from working.');
			return false;
		}
	}

	// Parse the result from twitters API
	private function parseAPIResponse($r) {
		$r = json_decode($r);
		$r = $r->data;

		$posts = array();

		if (!is_array($r)) {
			return false;	
		} else {
			foreach ($r as $dot) { // What the hell do we call posts on app.net? Someone suggesten 'dot'. I'm gonna go with that
				$post = array (
					'id' => $dot->id,
					'metadata' => array(
						'service' => 'app.net',
						'handle' => $dot->user->name,
						'profile_url' => 'http://alpha.app.net/' . $dot->user->username
					),
					'content' => array(
						'text' => $dot->text
					),
					'time' => strtotime($dot->created_at)
				);
				array_push($posts, $post);
			}

			return json_encode($posts);
		}

	}

}

?>
