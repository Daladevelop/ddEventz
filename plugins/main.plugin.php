<?php

class ddPlugin
{

	public function cache($jsonObj)
	{
		//cachefolder	
		$this->cacheFolder = "cache/";

		//set some per plugin stuff
		$curPlugin =  get_class($this); 
		logger::log(DEBUG, "Cachfunction initialized for ".$curPlugin);

		//get which event this is
		$curEvent = DDeventz::getEventID();


		$filename = $this->cacheFolder.$curEvent.".".$curPlugin.".cache";
			
		if(!file_exists($filename))
		{
			touch($filename);
			logger::log(DEBUG, "No cache file. Trying to create: ".$filename); 
		
		}
			$fp = fopen($filename, 'r+');

	}




}


?>
