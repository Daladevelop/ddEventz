<?php

class twitter extends plugin {

	private $geo = array();
	private $tag, $query;

	public function __construct() {
		$this->service = 'twitter';	
	}

	public function setParameters($parameters) {
		/* Set the parameters for the query to twitter */
		if (isset($parameters['tag'])) {
			$this->tag = $tag;	
		}

		if ((isset($parameters['lat'])) && (isset($parameters['lon']))) {
			$this->geo('lat' => $parameters['lat'], 'lon' => $parameters['lon']);
		
			/* Check if a distance is set. If not, fall back to a default */
			if (isset($parameters['distance'])) {
				$this->geo['distance'] = $parameters['distance'];	
			} else {
				$this->geo['distance'] = '5000'; // 5 km
			}
		}

	}

	public function getFeed() {
		$this->makeTwitterQuery();
		
		$response = $this->requestData();
	}
	

	/* twitter class specific methods is below here */

	private function makeTwitterQuery() {
		/* The basic URL for the twitter search API */
		$base_query = 'http://search.twitter.com/search.json?q=';

		if (isset($this->tag)) { // Make a search query by tag
			$this->query = $base_query . urlencode($this->tag);
		}
	}

	/**
	 * Request the tweets from the twitter api
	 */
	private function requestData() {
		/* Set up cURL */
		$curl = curl_init($this->query); 
		curl_setopt($curl, CURLOPT_POST, false); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
			
		return $response;
	}

}

?>
