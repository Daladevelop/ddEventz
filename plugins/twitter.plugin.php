<?php

require_once('plugin.interface.php');


class twitter extends ddPlugin implements pluginInterface {

	private $geo = array();
	private $tag, $query;

	public function __construct($eventId) {
		$this->service = 'twitter';	
		parent::__construct($eventId); 

		$params = array('tag', 'lat', 'lon', 'distance');
		$this->adminparameters = $params;
	}

	public function admin()
	{
		echo "ADMINPAGE FOR TWITTERPLUGINS! Japp Japp!"; 

		$parameters = $this->adminInterface();
		echo 'Parameters: ';
		foreach ($parameters as $param) {
			echo $param . ' ';	
		}
	}
	public function setParameters(array $parameters) {
		$this->tag = null; 
		$this->geo = array(); 
		logger::log(DEBUG,"TWITTER - ".print_r($parameters,true)); 
		// Set the parameters for the query to twitter
		if (isset($parameters['tag'])) {
			$this->tag = $parameters['tag'];
		}

		if ((isset($parameters['lat'])) && (isset($parameters['lon']))) {
			$this->geo['lat'] = $parameters['lat'];
			$this->geo['lon'] = $parameters['lon'];

			// Check if a distance is set. If not, fall back to a default
			if (isset($parameters['distance'])) {
				$this->geo['distance'] = $parameters['distance'];	
			} else {
				$this->geo['distance'] = '5000'; // 5 km
			}
			logger::log(DEBUG, "TWITTER - Making geo request"); 
		}
		$this->makeTwitterQuery();

	}

	

	// twitter class specific methods is below here

	private function makeTwitterQuery() {
		// The basic URL for the twitter search API
		$base_query = 'http://search.twitter.com/search.json?q=';

		if (isset($this->tag)) { // Make a search query by tag
			$this->query = $base_query . urlencode('#' . $this->tag);
		} elseif ((isset($this->geo)) && (count($this->geo) >0 )) {
			$this->query = $base_query . '&' . 'geocode=' . $this->geo['lat'] . urlencode(',') . $this->geo['lon'] . urlencode (',') . $this->geo['distance'] / 1000 . 'km';
		}

		logger::log(DEBUG, "TWITTER - Query: ".$this->query );
	}

	/**
	 * Request the tweets from the twitter api
	 */
	public function requestData() {
		// Set up cURL 
		$curl = curl_init($this->query); 
		curl_setopt($curl, CURLOPT_POST, false); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
			
		return $this->parseAPIResponse($response);
	}

	// Parse the result from twitters API
	private function parseAPIResponse($r) {
		$r = json_decode($r);
		//$r = $r->results;
		$posts = array();
		//print_r($r->results);
		//logger::log(DEBUG, 'Twitter response:' . json_encode($r));
		
		// This is not an array. It's an object!
		if(!is_array($r->results))
			return false; 

		foreach ($r->results as $tweet) {
			$post = array (
				'id' => $tweet->id_str,
				'metadata' => array(
					'service' => 'twitter',
					'handle' => $tweet->from_user,
					'profile_url' => 'http://twitter.com/' . $tweet->from_user
				),
				'content' => array(
					'text' => $tweet->text
				),
				'time' => strtotime($tweet->created_at)
			);
			array_push($posts, $post);
		}

		return json_encode($posts);
	}

}

?>
