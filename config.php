<?php
//CONFIG FILE FOR DDEventz!


	

















	//All config settings are done, lets do this!

	//load all requirements
	require_once('classes/ddeventz.php');
	require_once('classes/loadplugins.php');
	


	//Load all social plugins
	pluginLoader::all();

	DDEventz::initApp();

?>
