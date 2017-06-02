<?php 
	// header('Access-Control-Allow-Origin:*');
	$filePath = dirname(__FILE__).'/';
	$serverID = $_GET["serverid"];
	if(!$serverID)
		die('no serverID');
	require_once($filePath."_config.php");
	// require_once($filePath."tool/create/createDB.php");
	require_once($filePath."create/createServerDB.php");
?> 