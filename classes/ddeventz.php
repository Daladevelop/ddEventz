<?php



class DDeventz
{
	public static function initApp()
	{
		self::generateFeed(); 
		

	}

	public static function generateFeed()
	{
		global $parameters; 
		foreach(pluginLoader::plugins() as $plugin)
		{
			$plugin->setParameters($parameters); 
			echo $plugin->getFeed(); 

		}

	}




}



?>
