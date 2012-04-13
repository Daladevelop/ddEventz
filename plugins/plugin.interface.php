<?php

//interface for the plugins, they NEED to have atleast this
interface pluginInterface
{
	public function __construct();

	public function setParameters(array $parameters); 

	public function getFeed(); 	

}
