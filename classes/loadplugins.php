<?php

class pluginLoader
{
	private static $plugins = array(); 

	public function all()
	{
		//Load all classes
		foreach( glob("plugins/*.plugin.php") as $plugin)
		{
			//require them
			require_once($plugin);

			//get the plugin name from the plugin path
			$plugin = substr($plugin, 0, -11);
			$plugin = substr($plugin, 8); 

			//create new object from plugin class
			$temp = new $plugin;
			
			//check that current plugin is infact using our pluginInterface! 
			if($temp instanceof pluginInterface)
			{
				array_push(self::$plugins,$temp);
			}
			


		}


	}

	public function plugins()
	{
		return self::$plugins; 

	}


}

?>
