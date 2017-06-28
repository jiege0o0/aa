<?php
	header('Access-Control-Allow-Origin:*');

	$serverID = 1;
	require_once(dirname(__FILE__)."/game_version_path.php");
	require_once($filePath."index.php");
?>