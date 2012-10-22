<?php

/*
 *	TODO
 *	Add login / auth functionality
 *	Make new / edit event
 *  List the available plugins
 *  When plugin is clicked it calls the plugins admin() method which will setup the correct settings for that plugin, and which will later be sent to the plugin
 *  Generate an url for the feed. 
 *
 *
 */



//load config.php, it will load all plugins.
require_once('config.php'); 


class adminInterface
{

	public function __construct()
	{
		
		logger::log(DEBUG,"ADMIN Mode started.\r\n"); 

		//Check how far we have come in admin mode
		//


		//if only eventid is set, lets serve some plugins
		if(isset($_GET['eventId']) && !isset($_GET['plugin']))
		{
			echo $this->choosePlugins(); 
		}
		elseif( isset($_GET['eventId']) && isset($_GET['plugin']) )
		{
			if(class_exists($_GET['plugin']))
			{
				logger::log(DEBUG, "Loading admin page for ".$_GET['plugin']);
				$plug = new $_GET['plugin']($_GET['eventId']);
				$plug->admin();

			}
			else
			{
				logger::log(DEBUG, "The choosen plugin does not exist."); 
				return false; 
			}


		}
		else
			$this->eventPicker();

	}

	public function eventPicker()
	{
		echo '<h2>Existing events</h2>
		<ul>';

		$sql = 'SELECT * FROM `events` LIMIT 0 , 30';

		foreach (DB::$dbh->query($sql) as $event) {
			echo '<li class="event"><a href="?eventId=' . $event['id'] . '">' . $event['namn'] .  '</a></li>';
		}

		echo '</ul>';

	}

	public function choosePlugins()
	{
		echo '<ul>';
		foreach(pluginLoader::plugins() as $plugin)
		{
			echo '<li class="plugin"><a href="?eventId=' . $_GET['eventId'] . '&plugin=' . get_class($plugin) . '">'.get_class($plugin).'</a></li>'; 
		}
		echo '</ul>';
	}

}

$admin = new adminInterface(); 

?>
