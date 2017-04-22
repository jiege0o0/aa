<?php 
	//领取可以出的牌
	$isagain = $msg->isagain;
	$pkType='server_game_equal';
	$choose = $userData->{$pkType}->choose;
	$propCost = $userData->{$pkType}->open?0:1;
	do{
		if($userData->getPropNum(21) < $propCost)
		{
			$returnData->fail = 1;
			if(!$returnData->sync_prop)
			{
				$returnData->sync_prop = new stdClass();
			}
			$returnData->sync_prop->{'21'} = $userData->prop->{'21'};
			break;
		}
		if($isagain)
		{
			if(!$choose)//没数据
			{
				$returnData->fail = 5;
				break;
			}
			else if($userData->{$pkType}->pk == 0)//没打过
			{
				$returnData->fail = 6;
				break;
			}
			
			$returnData->choose = true;
			$returnData->enemy = $userData->{$pkType}->enemy;
			$returnData->enemyinfo = $userData->{$pkType}->enemy->userinfo;
			
			$userData->{$pkType}->pk = 0;
			$userData->{$pkType}->pktime ++;
			$userData->setChangeKey($pkType);
			$userData->{$pkType}->open = true;
			$userData->addProp(21,-$propCost);
			$userData->write2DB();
		}
		else if($userData->{$pkType}->pk > 0 || (!$choose))//没有拿过牌
		{
			require_once($filePath."pk_action/get_pk_card.php");
			//取卡---------------------------------
			// $choose = array(getPKCard($userData->level),getPKCard($userData->level));
			
			//取对手---------------------------------
			require_once($filePath."pk_action/pk_tool.php");
			//创键对应表（如果不存在）
			$pkLevel = getPKTableLevel($userData->server_game_equal->exp,100);
			if(!testPKTable($pkType,$pkLevel))
			{
				$returnData->fail = 20;
				break;
			}
			$tableName = $sql_table.$pkType."_".$pkLevel;
			
			//到对应表中找
			$winKey = "";
			// if($userData->server_game_equal->last >= 1)
				// $winKey = 'result desc,';
			// else
				// $winKey = 'result asc,';
			$sql = "select * from ".$tableName." where gameid!='".$userData->gameid."' order by ".$winKey." choose_num asc, last_time asc limit 1";
			$result = $conne->getRowsRst($sql);
			if(!$result)//没找到PK对象
			{
				$sql = "select * from ".$tableName." order by ".$winKey." choose_num asc, last_time asc limit 1";
				$result = $conne->getRowsRst($sql);
			}
			if(!$result)//还是没找到PK对象
			{
				$returnData->fail = 21;
				break;
			}
			$sql = "update ".$tableName." set choose_num=choose_num+1 where id=".$result['id'];
			$conne->uidRst($sql);
			$id = $result['id'];
			$team2Data = json_decode($result['game_data']);
			$team2Data->id = $result['id'];
			$team2Data->last_time = $result['last_time'];
			
			
			
			
			$userData->{$pkType}->choose = true;
			$userData->{$pkType}->enemy = $team2Data;
			$userData->{$pkType}->pk = 0;
			$userData->{$pkType}->pktime = 0;
			$userData->setChangeKey($pkType);
			$userData->{$pkType}->open = true;
			$userData->addProp(21,-$propCost);
			$userData->write2DB();
			
			$returnData->choose = true;
			$returnData->enemy = $team2Data;
			$returnData->enemyinfo = $team2Data->userinfo;
		
		}
		else
		{
			$returnData->fail = 3;
			$returnData->choose = $choose;
			$returnData->enemy = $userData->{$pkType}->enemy;
			$returnData->enemyinfo = $userData->{$pkType}->enemy->userinfo;
		}
		if(!$userData->{$pkType}->pktime)
			unset($returnData->enemy->pkdata);
	}while(false);
		
?> 