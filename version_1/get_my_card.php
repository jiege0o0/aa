<?php 
	//更换卡牌
	$type='main_game';
	$card = $userData->pk_common->my_card;
	$force = $msg->force;
	
	if(!$card || $card->num == 0 || $force)//没有拿过牌
	{
		do{
			$freeGet = false;
			if($card && $card[0]->task && $card[0]->task->current  >= $card[0]->task->num)
			{
				$freeGet = true;
			}
			if(!$freeGet && $force && $card != null)//用钻石拿
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
				$obj->num = 1;
			}
			else
			{
				require_once($filePath."pk_action/get_pk_card.php");
				$obj = getPKCard($userData->level);
				$obj->num = 10;
				
				
				$obj->task = new stdClass();
				$obj->task->current = 0;
				$obj->task->mid = $obj->list[rand(0,count($obj->list) - 1)];
				$mvo = $monster_base[$obj->task->mid];
				
				$obj->task->type = rand(1,6);
				if($mvo['cost'] <10)
				{
					if($obj->task->type == 2 || $obj->task->type == 3)
						$obj->task->type = 6;
					if($obj->task->type == 1)
					{
						$from = 1;
						$end = 4;
					}
					else if($obj->task->type == 4)
					{
						$from = 1;
						$end = 4;
					}
					else if($obj->task->type == 5)
					{
						$from = 3;
						$end = 10;
					}
				}
				else if($mvo['cost'] <20)
				{

					if($obj->task->type == 1)
					{
						$from = 3;
						$end = 7;
					}
					else if($obj->task->type == 2)
					{
						$from = 2;
						$end = 8;
					}
					else if($obj->task->type == 3)
					{
						$from = 1;
						$end = 7;
					}
					else if($obj->task->type == 4)
					{
						$from = 3;
						$end = 7;
					}
					else if($obj->task->type == 5)
					{
						$from = 8;
						$end = 30;
					}
				}
				else
				{

					if($obj->task->type == 1)
					{
						$from = 3;
						$end = 9;
					}
					else if($obj->task->type == 2)
					{
						$from = 3;
						$end = 9;
					}
					else if($obj->task->type == 3)
					{
						$from = 2;
						$end = 8;
					}
					else if($obj->task->type == 4)
					{
						$from = 3;
						$end = 8;
					}
					else if($obj->task->type == 5)
					{
						$from = 8;
						$end = 30;
					}
				}
				if($obj->task->type == 6)
				{
						$from = 3;
						$end = 10;
				}
				$obj->task->num = rand($from,$end);
				if($end - $obj->task->num < 2 && lcg_value()>0.5)
				{
					if(lcg_value()>0.4)
					{
						$obj->task->award_type = 'diamond';
						$obj->task->award_value = rand(2,4)-($end - $obj->task->num);
					}
					else
					{
						$obj->task->award_type = 'ticket';
						$obj->task->award_value = 1;
					}
				}
				else
				{
					$arr = array('coin','card','energy');
					usort($arr,randomSortFun);
					$obj->task->award_type = $arr[0];
					$rate = ($obj->task->num - $from + 1)/($end - $from + 1);
					if($obj->task->award_type == 'coin')
					{
						$obj->task->award_value = round($rate * $userData->level*50);
					}
					else if($obj->task->award_type == 'card')
					{
						$obj->task->award_value = ceil($rate * $userData->level);
					}
					else
					{
						$obj->task->award_value = ceil($rate * 6);
					}
				}			
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