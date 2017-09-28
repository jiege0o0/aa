<?php 
	// header('Access-Control-Allow-Origin:*');
	require_once(dirname(__FILE__).'/'."_create_config.php");
	$serverID = $_GET["serverid"];
	if(!$serverID)
		die('no serverID');
	require_once($dataFilePath."_config.php");
	// require_once($filePath."tool/create/createDB.php");
	require_once($dataFilePath."create/createServerDB.php");
?> 