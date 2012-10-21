<?php
	require_once("classes/logger.class.php");
	
	logger::init(); 

	logger::log(DEBUG,"STARTING");


	logger::log(DEBUG,print_r($_GET,true));

	logger::shutDown(); 	

?>
