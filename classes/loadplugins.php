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

	static public function plugins()
	{
		return self::$plugins; 

	}


}

?>
