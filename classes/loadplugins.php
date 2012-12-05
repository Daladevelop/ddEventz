<?php

class pluginLoader
{
	private static $plugins = array(); 

	static public function all()
	{
		//Load all classes
		foreach( glob("plugins/*.plugin.php") as $plugin)
		{
			//require them
			require_once($plugin);


			//get the plugin name from the plugin path
			$plugin = substr($plugin, 0, -11);
			$plugin = substr($plugin, 8); 
			
			//if its the main plugin jump on to next plug! 
			if($plugin === 'main')
				continue; 
			
			//create new object from plugin class
			$temp = new $plugin($_REQUEST['eventId']);
			
			//check that current plugin is infact using our pluginInterface! 
			if($temp instanceof pluginInterface)
			{
                array_push(self::$plugins,$temp);
                logger::log(DEBUG,"Loaded plugin $plugin"); 
            }
            else
                logger::log(DEBUG,"Could not load $plugin as its not correctly written. Check the plugin Interface for guidelines"); 
			


		}


	}
	
	static public function getPlugin($name)
	{
		foreach(self::$plugins as $plug)
		{
			if($plug->curPlugin === $name)
				return $plug;
		}


	}

	static public function plugins()
	{
		return self::$plugins; 

	}


}

?>
