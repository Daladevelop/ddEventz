<?php



class DDeventz
{
	public function __construct()
	{
		$this->initApp();
	}


	public function initApp()
	{
		$this->generateFeed(); 
		

	}

	public function generateFeed()
	{
		global $parameters; 
		$this->feed = array();
		$this->mediaHook = array();

		foreach(pluginLoader::plugins() as $plugin)
		{
			if(method_exists($plugin,'getHooks'))
				array_push($this->mediaHook,$plugin->getHooks());

			
			$plugin->setParameters($parameters); 
			
			array_push($this->feed, $plugin->getFeed() );

		}
		echo "<pre>".print_r($this->feed,true)."</pre>"; 
	}




}



?>
