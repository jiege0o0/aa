<?php
	if($_POST['debug_server'])//DEBUG�ͻ���
		$filePath = dirname(__FILE__).'/game/version_1/';
	else if($_POST['new_version'])//�µĿͻ���
		$filePath = dirname(__FILE__).'/game/version_1/';
	else//�ϵĿͻ���,����һ��ʱ��,����ʾ�汾����
		$filePath = dirname(__FILE__).'/game/';
		
	$dataFilePath = dirname(__FILE__).'/game/';	
	require_once($dataFilePath."_config.php");
?>