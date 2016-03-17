<?php 
	//取列表
	$lastTime = $msg->lasttime;
	do{
		$sql = "select * from ".$sql_table."friend_log where (to_gameid='".$userData->gameid."' or (from_gameid='".$userData->gameid."' and type!=1)) and time>".$lastTime;
		$result = $conne->getRowsArray($sql);
		if(!$result)	
		{
			$returnData->list = array();
			break;
		}
		$list = array();
		foreach($result as $key=>$value)
		{
			$value['content'] = json_decode($value['content']);
			// trace($value['content']['answer_choose']);
			if($value['type'] == 2 && $value['from_gameid'] != $userData->gameid && !$value['content']->{'answer_choose'})//如果未打，要过滤对方的出牌数据
			{
				unset($value['content']->{'ask_choose'});
			}
			$list[] = $value;
		}
		$returnData->list = $list;
	}
	while(false);	
?> 