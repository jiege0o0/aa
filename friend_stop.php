<?php 
	//拒绝成为好友
	$stop = $msg->stop;
	do{
		$userData->friends->stop = $stop;
		$userData->setChangeKey('friends');
		$userData->write2DB();
		$returnData->data = 'ok';
	}
	while(false);	
?> 