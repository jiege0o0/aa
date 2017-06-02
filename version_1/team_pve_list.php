<?php 
	
	$hard = $msg->hard;
	
	$time = time();
	$month =  (int)(date('m', $time));
	$day =  (int)(date('d', $time));
	$name = 'pve'.$month."_".$day."_".$hard;
	$file  = $dataFilePath.'dungeon_game/'.$name.'.txt';
	do{
		if(!is_file($file))//文件未生成
		{
			$returnData->fail = 1;
			break;
		}
		
		$content = file_get_contents($file);
		$returnData->content = $content;
		
	}while(false);
?> 