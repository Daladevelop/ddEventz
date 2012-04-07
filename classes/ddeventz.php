<?php



class DDeventz
{
	public static function initApp()
	{
		echo self::generateFeed(); 
		

	}

	public static function generateFeed()
	{
		global $parameters; 
		foreach(pluginLoader::plugins() as $plugin)
		{
			return $plugin->getFeed($parameters); 

		}

	}




}



?>
