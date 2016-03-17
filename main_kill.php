<?php 

	require_once($filePath."pk_action/pk_tool.php");

	$killIndex = $msg->kill;
	do{
		if(!$userData->main_game->choose)//没生成数据
		{	
			$returnData->fail = 1;
			$returnData->sync_main_game = new stdClass();
			$returnData->sync_main_game->choose = null;
			break;
		}
		
		if(in_array($killIndex,$userData->main_game->kill))//已杀死
		{
			$returnData->fail = 2;
			$returnData->sync_main_game = new stdClass();
			$returnData->sync_main_game->kill = $userData->main_game->kill;
			break;
		}
		
		$level = $userData->main_game->level;
		$needCoin = $level*200*(count($userData->main_game->kill)+1);
		
		if($userData->coin < $needCoin)//不够￥
		{
			$returnData->fail = 3;
			$returnData->sync_coin = $userData->coin;
			break;
		}
		
		array_push($userData->main_game->kill,$killIndex);
		$userData->addCoin(-$needCoin);
		$returnData->sync_main_game = new stdClass();
		$returnData->sync_main_game->kill = $userData->main_game->kill;
		$userData->setChangeKey('main_game');
		$userData->write2DB();	

		$returnData->data = "OK";		
	}while(false);
	


?> 