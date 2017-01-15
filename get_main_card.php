<?php 
	//领取可以出的牌
	$type='main_game';
	$choose = $userData->{$type}->choose;
	if(!$choose || count($choose) == 0)//没有拿过牌
	{
		do{
			if($userData->getEnergy() < 1)//体力不够
			{
				$returnData->fail = 4;
				$returnData->sync_energy = $userData->energy;
				break;
			}
			if($userData->exp == 0)
			{
				$obj = new stdClass();
				$obj->list = array(16,53,29,59,20,43,1,5);
				$choose = array($obj);
			}
			else
			{
				require_once($filePath."pk_action/get_pk_card.php");
				$choose = array(getPKCard($userData->level));
			}
			
			$userData->{$type}->choose = $choose;
			$userData->setChangeKey($type);
			$userData->addEnergy(-1);
				
			$userData->write2DB();
			
			$returnData->choose = $choose;
		}while(false);
	}
	else
	{
		$returnData->fail = 3;
		$returnData->choose = $choose;
	}
		
?> 