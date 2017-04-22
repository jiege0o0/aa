<?php 
	//更换卡牌
	$type='main_game';
	$card = $userData->pk_common->my_card;
	$force = $msg->force;
	
	if(!$card || $card->num == 0 || $force)//没有拿过牌
	{
		do{
			if($force && $card != null)//用钻石拿
			{
				$needDiamond = max(0,$card[0]->num);
				if($userData->getDiamond() < $needDiamond)
				{
					$returnData->fail = 201;
					$returnData->sync_diamond = $userData->diamond;
					break;
				}
				$userData->addDiamond(-$needDiamond);
			}
			if($userData->exp == 0)
			{
				$obj = new stdClass();
				$obj->list = array(16,53,29,59,20,43,1,5);
				$obj->num = 10;
			}
			else
			{
				require_once($filePath."pk_action/get_pk_card.php");
				$obj = getPKCard($userData->level);
				$obj->num = 10;
			}
			$choose = array($obj);
			$userData->pk_common->my_card = $choose;
			$returnData->sync_my_card = $choose;
			$userData->setChangeKey('pk_common');
			if(!$stopWriteDB)
				$userData->write2DB();
			// $returnData->my_card = $choose;
		}while(false);
	}
	else
	{
		// $returnData->fail = 3;
		// $returnData->my_card = $userData->pk_common->my_card;
		$returnData->sync_my_card = $choose;
	}
		
?> 