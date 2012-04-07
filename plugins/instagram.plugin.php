<?php
require_once('plugin.plugin.php');

class instagram extends plugin {

	private $endpoints = array(
		'tag' => 'https://api.instagram.com/v1/tags/%s/media/recent?client_id=%s',
		'location' => 'https://api.instagram.com/v1/media/search?lat=%s&lng=%s&distance=%s&client_id=%s'
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

	public function setParameters($paramters) {
		$this->parameters = $paramters;

		// set correct endpoint url and query
		if($this->parameters['tag']) {
			$this->setEndpoint('tag');
		} elseif($this->parameters['lat'] && $this->parameters['lon']) {
			$this->setEndpoint('location');
		}

		return true;
	}

	public function getFeed() {
		$response = $this->requestData();

		return $response;
	}

	// set correct endpoint url and query
	public function setEndpoint($name) {
		$endpointUrl = '';

		// Setup correct endpoint
		switch ($name) {
			case 'tag':
				$endpointUrl = sprintf($this->endpoints[$name], $this->parameters['tag'], $this->config['client_id']);
				break;

			case 'location':
				$endpointUrl = sprintf($this->endpoints[$name], $this->parameters['lat'], $this->parameters['lon'], $this->config['distance'], $this->parameters['client_id']);
				break;
		}

		$this->query = $endpointUrl;

		return true;
	}

	private function requestData() {
		if($this->query !== '') {
			$handle = fopen($this->query, 'r');
			$content = stream_get_contents($handle);
			fclose($handle);

			return $content;
		} else {
			return false;
		}
	}

}
