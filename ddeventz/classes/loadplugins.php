<?php

class pluginLoader
{
	public function all()
	{
		//Load all classes
		foreach( glob("plugins/*.plugin.php") as $plugin)
		{
			echo $plugin; 


		}


	}


}

?>
