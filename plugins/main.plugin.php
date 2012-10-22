<?php

class ddPlugin
{
	public function __construct($eventId)
	{
	
		//cachefolder	
		$this->cacheFolder = "cache/";

		//set some per plugin stuff
		$this->curPlugin =  get_class($this); 

		logger::log(DEBUG, "Cachfunction initialized for ".$this->curPlugin);

		//get which event this is
		$this->curEvent = $eventId;

		$this->filename = $this->cacheFolder.$this->curEvent.".".$this->curPlugin.".cache";
		logger::log(DEBUG, "CACHE: Setting cache file to: ".$this->filename); 
	}


	public function getFeed($instance) {
		$response = $this->getCache($instance);
		return $response; 


	}

	public function cache($jsonObj,$instance = 0)
	{
		$filename = $this->filename.".".$instance; 

			
		if(!file_exists($filename))
		{
			touch($filename);
			logger::log(DEBUG, "No cache file. Trying to create: ".$filename); 
		
		}
		
		$fp = fopen($filename, 'w');
		if($fp)
		{
			fwrite($fp, time()."\n");
			fwrite($fp,$jsonObj);

			fclose($fp); 
		}
		else
			logger::log(DEBUG,'CACHE - could not open file:'. $filename);

	}

	public function getCache($instance)
	{
		$filename = $this->filename.".".$instance; 

		if(file_exists($filename))
		{
			$fp = fopen($filename,'r');
			$timestamp = fgets($fp);
			if(!defined("CACHETIME"))
				define("CACHETIME",600); //can be set in settings.php
			if( (time() - $timestamp) > CACHETIME) // tio minuter
			{
				logger::log(DEBUG, "CACHE - Cache not fresh enough. Requesting new data. ".$this->curPlugin);
				$content = $this->requestData(); 

				$this->cache($content, $instance); 
				return $content;
			}
			else
			{
				$content = fgets($fp);
				fclose($fp);
				return $content; 
			}

		}
		else
		{
			$content = $this->requestData();
			$this->cache($content,$instance);

			return $content; 

		}

	}	


}


?>
