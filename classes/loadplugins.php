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
			
			$plugin = substr($plugin, 0, -11);
			$plugin = substr($plugin, 8); 

			array_push(self::$plugins,new $plugin);
				


		}


	}

	public function plugins()
	{
		return self::$plugins; 

	}


}

?>
