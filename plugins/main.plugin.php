<?php

class ddPlugin
{

	public function cache($jsonObj)
	{
		//cachefolder	
		$this->cacheFolder = "/cache/";

		//set some per plugin stuff
		$curPlugin =  get_class($this); 


		//get which event this is
		$curEvent = DDeventz::getEventID();

		//timestamp for last cache happening
		$content = file($this->cacheFolder.$curEvent.".".$curPlugin.".cache");
	    $timestamp = $content[0]; 

	}




}


?>
