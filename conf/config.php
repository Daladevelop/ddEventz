<?php
//CONFIG FILE FOR DDEventz!

//enable error reporting
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
error_reporting(E_ALL);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_PARSE);

//First time settings fo loading feed:
$GLOBALS['parameters'] = array(
	'tag' => "daladevelop",
	'lat' => 60.6054, 
	'lon' =>  15.6535, 
	'distance' => 5000);

date_default_timezone_set('UTC');

//All config settings are done, lets do this!

//load all requirements
require_once('lib/classes/logger.class.php'); 
logger::init(); 

// load the settings
if(file_exists('settings.php'))
	require_once('settings.php');
elseif(file_exists("../settings.php"))
{
	
	require_once('../settings.php');
}
else
{
	logger::log(DEBUG,'Create settings.php'); 
	die("You need to create a settings.php with atleast some database settings. See settings-example.php"); 
}
//load the databasehandler and our db class
require_once('lib/classes/db.class.php');


require_once('lib/classes/ddeventz.php');
require_once('lib/classes/loadplugins.php');
require_once('lib/plugins/main.plugin.php'); 

//do some databasechecking

//if we dont have databaseconnection - dont connect
if(!db::init())
{
	logger::log(FATAL,"For some reason the databaseconnection is not working. Starting shutdown process. "); 
	logger::shutDown();
	die(); 
}

//Load all social plugins
pluginLoader::all();

?>
