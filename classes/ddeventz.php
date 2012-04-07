<?php



class DDeventz
{
	public static function initApp()
	{
		


	}

	public static function generateFeed()
	{
		foreach(pluginLoader::plugins() as $plugin)
		{
			echo $plugin->getFeed(); 

		}

	}




}



?>
