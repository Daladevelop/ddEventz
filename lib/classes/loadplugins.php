<?php

class pluginLoader
{
	private static $plugins = array(); 

	static public function all()
	{
		//Load all classes
		foreach( glob("lib/plugins/*.plugin.php") as $plugin)
		{
			//require them
			logger::log(DEBUG, "Requiring plugin: ".$plugin); 
			if(is_file($plugin))
			{
				logger::log(DEBUG, "Found file!");
				require_once($plugin);
			}
			else
				logger::log(DEBUG, "Could not require $plugin . File does not exist"); 


			//get the plugin name from the plugin path
			$plugin = substr($plugin, 0, -11);
			$plugin = substr($plugin, 12); 
			
			logger::log(DEBUG, "The plugin to load is: ".$plugin);	
			//if its the main plugin jump on to next plug! 
			if($plugin === 'main')
				continue; 
			
			//create new object from plugin class
			if(isset($_REQUEST['eventId']))
				$temp = new $plugin($_REQUEST['eventId']);
			else
			{
				logger::log(DEBUG,"Inget eventid. Stoppar"); 
				die("Inget Eventid. Stoppar"); 
			}
			
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
			logger::log(DEBUG, "Checking plugin: ".get_class($plug)); 	
			if(get_class($plug) === $name)
				return $plug;
		}


	}

	static public function plugins()
	{
		return self::$plugins; 

	}


}

?>
