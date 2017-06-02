<?php 
	ini_set('date.timezone','Asia/Shanghai');
	$serverID = $_GET["serverid"];
	$filePath = dirname(__FILE__).'/';
	require_once($filePath."_config.php");
	require_once($filePath."create/conn.php");
	$time = time() - 3600*24*3;//3天前
	
	$sql = "DELETE FROM ".$sql_table."friend_log where time<".$time;
	$result = $conne->uidRst($sql);
	echo $result.'-'.time();
	
	//向前删7天前的任务表
	$time = time() - 3600*24*7;
	$month =  (int)(date('m', $time));
	$day =  (int)(date('d', $time));
	while(true)
	{
		for($i=0;$i<10;$i++)
		{
			$sql = "DROP TABLE IF EXISTS ".$sql_table."team_pve_".$month.'_'.$day;
			$result = $conne->uidRst($sql);
			$day --;
			if($day == 0)
			{
				$month --;
				$day = 31;
				if($month == 0)
					$month = 12;
			}
		}
		$sql = "show tables like '".$sql_table."team_pve_".$month.'_'.$day."'";
		if(!$conne->getRowsRst($sql))
			break;
	}
	
	
	
		
?> 