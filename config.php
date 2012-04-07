<?php
//CONFIG FILE FOR DDEventz!

//enable error reporting
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
error_reporting(E_ALL);









//First time settings fo loading feed:
global $parameters = array('tag' => "#daladevelop",'lag' => 60.6054, 'long' =>  15.6535, 'distance' => 5000);







//All config settings are done, lets do this!

//load all requirements
require_once('classes/ddeventz.php');
require_once('classes/loadplugins.php');
	


//Load all social plugins
pluginLoader::all();

//start things up
DDEventz::initApp();

?>
