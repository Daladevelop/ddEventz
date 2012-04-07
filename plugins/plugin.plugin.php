<?php

class plugin
{
	public function __construct()
	{
		$this->service = ''; 

	}

	public function setParameters($parameters)
	{
		$this->parameters = $parameters; 
		return false;	

	}

	public function getFeed()
	{
		return "{'id': 0, 'service': 'dummy','content': '<h1> I AM DUMB AND TAGGED WITH ".$this->parameters['tag']." </h1>','timestamp':' ".time()."'}";

	}

}


?>
