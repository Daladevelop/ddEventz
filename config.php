<?php
//CONFIG FILE FOR DDEventz!

ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
error_reporting(E_ALL);

















	//All config settings are done, lets do this!

	//load all requirements
	require_once('classes/ddeventz.php');
	require_once('classes/loadplugins.php');
	


	//Load all social plugins
	pluginLoader::all();

	DDEventz::initApp();

?>
