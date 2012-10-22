<?php
require_once('plugin.interface.php');

class instagram extends ddPlugin implements pluginInterface {

	private $endpoints = array(
		'tag' => 'https://api.instagram.com/v1/tags/%s/media/recent?client_id=%s',
		'location' => 'https://api.instagram.com/v1/media/search?lat=%s&lng=%s&distance=%s&client_id=%s',
		'user' => 'https://api.instagram.com/v1/users/%s/media/recent/?access_token=%s'
	);

	private $query = '';

	private $parameters = array();

	// Instagram Settings
	private $config = array(
			'client_id' => '3b2a4b4860bb4899b0405b7bd3cb4be0'
		);

	public function __construct () {
		$this->service = 'instagram';
		return true;
	}

	public function admin()
	{
		echo "YEP"; 
	}
	public function setParameters(array $paramters) {
		$this->parameters = $paramters;

		// set correct endpoint url and query
		if(isset($this->parameters['tag'])) {
			$this->setEndpoint('tag');
		} elseif(isset($this->parameters['lat']) && isset($this->parameters['lon'])) {
			$this->setEndpoint('location');
		} elseif(isset($this->parameters['user'])) {
			$this->setEndpoint('user');
		}

		

		return true;
	}

	public function getFeed() {
		$response = $this->requestData();
		$this->cache($response); 
		return $response;
	}

	// set correct endpoint url and query
	public function setEndpoint($name) {
		$endpointUrl = '';
		$this->endpoint = $name;
	    logger::log(DEBUG, "Setting INSTAGRAM endpoint to ".$this->endpoint); 	

		// Setup correct endpoint
		switch ($name) {
			case 'tag':
				$endpointUrl = sprintf($this->endpoints[$name], $this->parameters['tag'], $this->config['client_id']);
				break;

			case 'location':
				$endpointUrl = sprintf($this->endpoints[$name], $this->parameters['lat'], $this->parameters['lon'], $this->config['distance'], $this->parameters['client_id']);
				break;
			case 'user':
				$endpointUrl = sprintf($this->endpoints[$name], $this->getUserID($this->endpoints['user']),InstagramAccessToken);
				break; 
		}

		$this->query = $endpointUrl;

		return true;
	}

	private function getUserID($username)
	{
		return 6891333; 

	}
	private function requestData() {
		if($this->query !== '') {
			logger::log(DEBUG, "INSTAGRAM QUERY: ".$this->query); 

			$handle = fopen($this->query, 'r');
			$content = stream_get_contents($handle);
			fclose($handle);

			$content = $this->parseAPIResponse($content);
			return $content;
		} else {
			return false;
		}
	}


	// Parse the result from instagram's API
	private function parseAPIResponse($r) {
		$r = json_decode($r);
		$posts = array();

		if(!is_object($r))
			return false; 


		foreach ($r->data as $result) {
			$post = array (
				'id' => $result->id,
				'metadata' => array(
					'service' => 'instagram',
					'handle' => $result->caption->from->username
				),
				'content' => array(
					'text' => $result->caption->text,
					'media' => array(
						array(
							'type' => 'image',
							'tumbnail_url' => $result->images->thumbnail->url,
							'lowres_url' => $result->images->low_resolution->url,
							'hires_url' => $result->images->standard_resolution->url
						)
					)
				),
				'time' => $result->created_time
			);

			array_push($posts, $post);
		}
		return json_encode($posts);
	}

}
