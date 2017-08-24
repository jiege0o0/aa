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
			$tableName = $sql_table.$pkType;
			
			
			$index = $userData->{$pkType}->exp + 50;
			$begin = $index - 10;
			if($begin < 1)
				$begin = 1;
			$end = $index + 10;
			if($end < 10)
				$end = 10;
			
			//到对应表中找

			$sql = "select * from ".$tableName." where id between ".$begin." and ".$end." and last_time>0";
			$result = $conne->getRowsArray($sql);
			if(!$result)//还是没找到PK对象
			{
				$returnData->stopLog = true;
				$returnData->fail = 21;
				break;
			}
			$lastpker = $userData->{$pkType}->lastpker;
			if($lastpker == null)
			{	
				$lastpker = array();
			}
			$arr1 = array();//首选
			$arr2 = array();//备选
			foreach($result as $key=>$value)
			{
				if(!in_array($value['data_key'],$lastpker,true))
				{
					array_push($arr2,$value);
					if($value['gameid'] != $userData->gameid)
						array_push($arr1,$value);
				}
			}
			
			$enemyAddFight = 0;
			if(count($arr1) > 0)
			{
				usort($arr1,randomSortFun);
				$result = $arr1[0];
			}
			else if(count($arr2) > 0)
			{
				usort($arr2,randomSortFun);
				$result = $arr2[0];
				$enemyAddFight = 10;
			}
			else
			{
				$returnData->fail = 21;
				break;
			}
			
			
			
			$id = $result['id'];
			array_push($lastpker,$result['data_key']);
			while(count($lastpker) > 10)
			{
				array_shift($lastpker);
			}
			$userData->{$pkType}->lastpker = $lastpker;
			
			
			
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