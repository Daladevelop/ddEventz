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


	public function getFeed() {
		$response = $this->getCache();

		return $response; 


	}

	public function cache($jsonObj)
	{


			
		if(!file_exists($this->filename))
		{
			touch($this->filename);
			logger::log(DEBUG, "No cache file. Trying to create: ".$this->filename); 
		
		}
		
		$fp = fopen($this->filename, 'w');
		if($fp)
		{
			fwrite($fp, time()."\n");
			fwrite($fp,$jsonObj);

			fclose($fp); 
		}
		else
			logger::log(DEBUG,'CACHE - could not open file:'. $this->filename);

	}

	public function getCache()
	{

		if(file_exists($this->filename))
		{
			$fp = fopen($this->filename,'r');
			$timestamp = fgets($fp);
			if( (time() - $timestamp) > 600) // tio minuter
			{
				logger::log(DEBUG, "CACHE - Cache not fresh enough. Requesting new data. ".$this->curPlugin);
				$this->cache($this->requestData()); 
				return false;
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
			$this->cache($content);

			return $content; 

		}

	}	


}


?>
