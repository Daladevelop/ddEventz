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
		


		//if only eventid is set, lets serve some plugins
		if(isset($_GET['eventId']) && !isset($_GET['plugin'])){

			echo $this->pluginAdmin(); 
		}
		else
			$this->eventPicker();

	}

	public function header()
	{
		$str = '<html>
				<head>
					<title>Admin</title>
					<link rel="stylesheet" href="/admininterface/style.css" type="text/css" />
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
					<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />
					<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
				</head>
				<body>';
	
		return $str; 
	}

	public function footer()
	{
		$str = '<script src="/admininterface/admin.js" type="text/javascript"></script>
				</div>
				</body>
				</html>';

		return $str; 

	}

	public function getActivePlugins()
	{
		//This function gets what plugins are active for this event
		//later i dont know if this functionality should be here or some where else. 
		$eventId = $_GET['eventId'];
		
		$sql = "select plugin,parm,value,instance from events_plugins where eventid = ".$eventId; 
		$activePlugins = array(); 

		foreach( DB::$dbh->query($sql) as $plugin)
		{
			if( !isset($activePlugins[$plugin['plugin']]) )
			{
				$activePlugins[$plugin['plugin']] = array();

				if( !isset( $activePlugins[$plugin['plugin']][$plugin['instance']] )) // <-- error på den här raden
				{
					$activePlugins[$plugin['plugin']][$plugin['instance']] = array();

				}
				
				$activePlugins[$plugin['plugin']][$plugin['instance']][$plugin['parm']] = $plugin['value'];
 			}
 		}

		foreach($activePlugins as $name => $plugin)
		{
			foreach($plugin as $pluginInstance)
			{
				echo pluginLoader::getPlugin($name)->adminInterface($pluginInstance);

			}
		}

 

	}

	public function pluginAdmin()
	{
		$this->plugins = pluginLoader::plugins(); 
		echo $this->header(); 

		echo '<div id="main">';
		echo '		<section id="activeplugins">';

		echo $this->getActivePlugins(); 
		
		echo '	</section>';
		echo '<div class="pluginbuttons">Save / Clear</div>';
		echo '</div>';

		echo '<aside id="pluginlist">';
		
		foreach($this->plugins as $plugin)
			echo $plugin->adminInterface(); 	

		echo '</aside>';

		echo $this->footer(); 

	}

	public function eventPicker()
	{
		echo $this->header(); 
		echo '<h2>Existing events</h2>
		<ul>';

		$sql = 'SELECT * FROM `events` LIMIT 0 , 30';

		foreach (DB::$dbh->query($sql) as $event) {
			echo '<li class="event"><a href="?eventId=' . $event['id'] . '">' . $event['namn'] .  '</a></li>';
		}

		echo '</ul>';
		echo $this->footer(); 
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
