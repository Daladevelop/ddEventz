<?php

//interface for the plugins, they NEED to have atleast this
interface pluginInterface
{
	public function __construct($eventId);

	public function setParameters(array $parameters); 

	public function getFeed(); 
    
    public function admin(); 	

}
