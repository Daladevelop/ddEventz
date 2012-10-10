<?php
//CONFIG FILE FOR DDEventz!

//enable error reporting
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_PARSE);

//First time settings fo loading feed:
$GLOBALS['parameters'] = array(
	'tag' => "daladevelop",
	'lat' => 60.6054, 
	'lon' =>  15.6535, 
	'distance' => 5000);

//All config settings are done, lets do this!

//load all requirements
require_once('classes/logger.class.php'); 
logger::init(); 

// load the settings
require_once('settings.php');

//load the databasehandler and our db class
require_once('classes/db.class.php');


require_once('classes/ddeventz.php');
require_once('classes/loadplugins.php');


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

//shut things down
logger::shutDown(); 
?>
