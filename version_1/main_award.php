<?php 
	// require_once($filePath."pk_action/pk_tool.php");
	do{
		if(isSameDate($userData->main_game->awardtime))
		{	
			$returnData->fail = 1;
			$returnData->sync_main_game = new stdClass();
			$returnData->sync_main_game->awardtime = $userData->main_game->awardtime;
			break;
		}
		
		$level = $userData->main_game->level;
		$rate = 1;
		
		require_once($filePath."main_award_fun.php");
		
		
		$userData->main_game->awardtime = time();
		$returnData->sync_main_game = new stdClass();
		$returnData->sync_main_game->awardtime = $userData->main_game->awardtime;
		$userData->setChangeKey('main_game');
		$userData->write2DB();		

		
	}while(false);
?> 