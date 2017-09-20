<?php 
	//评论（顶踩）
	require_once($filePath."tool/conn.php");
	$monster = $msg->monster;
	$id = $msg->id;
	$talk_key = $msg->talk_key;
	$isgood = $msg->isgood;
	do{
		$time = time();
		if($isgood)
			$sql = "update monster_talk_".$monster." set good=good+1,time=(".$time."+(case when good-bad>1440 then 1440 else good-bad end)*60) where talk_key='".$talk_key."' and id=".$id;
		else
			$sql = "update monster_talk_".$monster." set bad=bad+1,time=(".$time."+(case when good-bad>720 then 720 else good-bad end)*60) where talk_key='".$talk_key."' and id=".$id;
		if(!$conne->uidRst($sql))
		{
			$returnData->fail = 2;
		}
		$returnData->data = 'ok';
	}
	while(false);		
?> 