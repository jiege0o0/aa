<?php 
	header('Access-Control-Allow-Origin:*');
	error_reporting(E_ALL^(E_NOTICE|8192));
	// error_reporting(0);
	ini_set('display_errors', '1');
	$filePath = dirname(__FILE__).'/';
	require_once($filePath."_config.php");
	require_once($filePath."tool/tool.php");
	require_once($filePath."tool/conn.php");
	require_once($filePath."object/game_user.php");
	
	
	
	$returnData = new stdClass();
	$msg = json_decode($_POST['ext']);
	$serverID = $_POST['serverId'];
	$sql_table = 'no'.$serverID.'_';
	$sql = "select * from ".$sql_table."user_data where gameid='".$msg->gameid."'";
	//trace($sql);
	$userData = $conne->getRowsRst($sql);
	if(!$userData)//µÇÂ¼Ê§Ð§
	{
		echo '{"code":1013}'; 
		return;
	}
	$userData = new GameUser($userData);
	$id = $_POST['goodsId'];
	$arr = array(
		'1'=>array('cost'=>12),
		'101'=>array('cost'=>6,'diamond'=>60),
		'102'=>array('cost'=>30,'diamond'=>305),
		'103'=>array('cost'=>150,'diamond'=>1530),
		'104'=>array('cost'=>600,'diamond'=>6200)
	);
	require_once($filePath."tool/tool.php");
	if($id == 1)
	{
		$userData->energy->vip = 1;
		$userData->setChangeKey('energy');
		// $returnData->sync_energy = $userData->energy;
	}
	else
	{
		$userData->addDiamond($arr[$id]['diamond'],true);
	}
	payLog(json_encode($_POST));
	$userData->write2DB();
	echo '{"code":0}'; 
?> 