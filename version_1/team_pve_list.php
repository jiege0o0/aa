<?php 
	
	$hard = $msg->hard;
	
	$time = time();
	$month =  (int)(date('m', $time));
	$day =  (int)(date('d', $time));
	$name = 'pve'.$month."_".$day."_".$hard;
	$file  = $dataFilePath.'dungeon_game/'.$name.'.txt';
	do{
		if(!is_file($file))//�ļ�δ����
		{
			$returnData->fail = 1;
			break;
		}
		
		$content = file_get_contents($file);
		$returnData->content = $content;
		
	}while(false);
?> 