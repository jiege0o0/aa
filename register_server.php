<?php 
require_once($filePath."tool/conn.php");
$nick=$msg->nick;
$head=$msg->head;
$gameid = $serverID.'_'.$msg->id;

do{
	//这个号在这个服有没有注册过
	$sql = "select nick from ".$sql_table."user_data where gameid='".$gameid."'";
	$result = $conne->getRowsRst($sql);
	if($result)
	{
		$returnData -> fail = 2;
		$returnData ->nick = $result['nick'];
		// addToUser($msg->id,$serverID);
		break;
	}

	//有没有重名
	$sql = "select * from user_data where nick='".$nick."'";
	if($conne->getRowsNum($sql))
	{
		$returnData -> fail = 3;
		break;
	}

	//可以注册
	$time = time();
	$sql = "insert into ".$sql_table."user_data(gameid,nick,head,last_land,land_key) values('".$gameid."','".$nick."','".$head."',".$time.",'".$time."')";
	$num = $conne->uidRst($sql);
	if($num == 1){//注册成功
		$returnData->data = 'success';
		// addToUser($msg->id,$serverID);
	}
	else
	{
		$returnData -> fail = 4;
		errorLog('register_server:'.json_encode($msg));
	}

}while(false)


?> 