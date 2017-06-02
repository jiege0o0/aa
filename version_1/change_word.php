<?php 

	$word = $msg->word;

	do{
		$userData->word = $word;
		$userData->setChangeKey('word');
		$userData->write2DB();	

		$returnData->data = "OK";		
	}while(false);
	


?> 