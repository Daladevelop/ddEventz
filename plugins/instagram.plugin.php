<?php
require_once('plugin.plugin.php');

class instagram extends plugin {

	private $endpoints = array(
		'tag' => 'https://api.instagram.com/v1/tags/%s/media/recent?client_id=%s',
		'location' => 'https://api.instagram.com/v1/media/search?lat=%s&lng=%s&distance=%s&client_id=%s'
	);

	private $query = '';

	private $config = array();

	public function __construct () {
		$this->service = 'instagram'; 
		return true;
	}

	public function setParameters($paramters) {
		$this->config = $paramters;

		return true;
	}

	public function getFeed() {
		$response = $this->requestData();

		return $response;
	}

	public function setEndpoint($name) {
		$endpointUrl = '';

		// Setup correct endpoint
		switch ($name) {
			case 'tag':
				$endpointUrl = sprintf($this->endpoints[$name], $this->config['tag'], $this->config['client_id']);
				break;

			case 'location':
				$endpointUrl = sprintf($this->endpoints[$name], $this->config['lat'], $this->config['lon'], $this->config['distance'], $this->config['client_id']);
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




