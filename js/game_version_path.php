<?php
	if($_POST['debug_server'])//DEBUG客户端
		$filePath = dirname(__FILE__).'/game/version_1/';
	else if($_POST['new_version'])//新的客户端
		$filePath = dirname(__FILE__).'/game/version_1/';
	else//老的客户端,兼容一段时间,会提示版本更新
		$filePath = dirname(__FILE__).'/game/';
		
	$dataFilePath = dirname(__FILE__).'/game/';	
	require_once($dataFilePath."_config.php");
?>