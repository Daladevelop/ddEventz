<?php

class plugin
{
	public function __construct()
	{
		$this->service = ''; 

	}

	public function setParameters($paramters)
	{
		return false;	

	}

	public function getFeed()
	{
		return "{'id': 0, 'service': 'dummy','content': '<h1> I AM DUMB! </h1>','timestamp':' ".time()."'}";

	}

}


?>