<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");

	$myChoose = $msg->choose;
	$data_key = $msg->data_key;
	$team1Data = changePKData($myChoose,'main_game');
	do{
		if($userData->getEnergy() < 1)//体力不够
		{
			$returnData->fail = 1;
			$returnData->sync_energy = $userData->energy;
			break;
		}
		if(property_exists($team1Data,'fail'))//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		
		$level = $userData->main_game->level + 1;
		require_once($filePath."cache/main_game".ceil($level/100).".php");
		
		$pkUserInfo = new stdClass();
		$pkUserInfo->level = $level;
		
		
		$team2Data = new stdClass();
		$team2Data->list = $main_game[$level]['list'];
		// $team2Data->ring = new stdClass();
		// $team2Data->ring->id = $main_game[$level]['ring'];
		// $team2Data->ring->level = ceil($level/100);
		$team2Data->fight = getMainForce($level);
		foreach($userData->main_game->kill as $key=>$value)
		{
			$team2Data->list[$value] = 0;
		}
		resetTeam2Data();
		require_once($filePath."pk_action/pk.php");
		addMonsterUse($myChoose,$result);
		$returnData->sync_main_game = new stdClass();
		
		//处更玩家数据,奖励
		$award = new stdClass();
		$returnData->award = $award;
		$award->prop = new stdClass();
		$award->exp = ceil(20*(1+$level/50));
		$award->coin = ceil(30*(1+$level/100));
		
		if($result)
		{
			if($userData->exp < 30 && $userData->level == 1)//新手副利
			{
				$award->coin = 200;
				require_once($filePath."get_monster_collect.php");
				$award->collect = addMonsterCollect(5);
			}
			
			if($team2Data->fight >= $team1Data->fight && count($userData->main_game->kill) == 0)//如果战力小于等于对方，会奖励战力
			{
				$awardForce = ceil(($userData->main_game->level + 100 + 1)/200);
				$returnData->main_award = $awardForce;
				$userData->addAwardForce($awardForce);
				require_once($filePath."add_main_pass.php");
				
			}
			
			
			$userData->main_game->level++;
			$userData->main_game->show_pass = false;
			$userData->main_game->time = time();
			$userData->main_game->kill = array();
			$returnData->sync_main_game->kill = array();
			$returnData->sync_main_game->level = $userData->main_game->level;	
			$returnData->sync_main_game->show_pass = false;	
		}
		else
		{
			$award->exp = 10 + floor($level/50);
			$award->coin = 0;
		}

		
		foreach($award->prop as $key=>$value)
		{
			$userData->addProp($key,$value);
		}
		$userData->addCoin($award->coin);
		$userData->addExp($award->exp);
		// $userData->main_game->choose = null;
		$userData->main_game->pkdata = array("team1"=>$team1Data,"team2"=>$team2Data,"isequal"=>$equalPK,"info"=>$pkUserInfo,'version'=>$pk_version,'time'=>time());
		// $returnData->sync_main_game->choose = null;
		
		renewMyCard();
		$userData->addEnergy(-1);
		$userData->addHistory($team1Data->list);
		$userData->setChangeKey('main_game');
		$userData->write2DB();	

		//插入数据
		if($data_key && $changeFightDataValue->cost >80 && $level > 1 && $level < 50)
		{
			$file1  = $dataFilePath.'log/server'.$serverID.'_create_1.txt';
			if(!is_file($file1))//文件没生成,要去插入数据
			{
				$tableName = $sql_table.'server_game';
				$sql = "select id from ".$tableName." where id between 50 and 100 and data_key='".$data_key."' and gameid='".$userData->gameid."' limit 1";
				$result = $conne->getRowsRst($sql);
				if(!$result)
				{
					$sql = "select id from ".$tableName." where id between 50 and 100 and last_time=0 limit 1";
					$result = $conne->getRowsRst($sql);
					if($result)
					{
						unset($team1Data->teamID);
						$saveData = new stdClass();
						$saveData->pkdata = $team1Data;
						$saveData->base = $changeFightDataValue->chooseList;
						$saveData->userinfo = new stdClass();
						$saveData->userinfo->head = $userData->head;
						$saveData->userinfo->nick = base64_encode($userData->nick);
						$saveData->userinfo->level = $userData->level;
						$saveData->userinfo->force = $userData->tec_force + $userData->award_force;
						$saveData->userinfo->win = $userData->server_game->win;
						$saveData->userinfo->total = $userData->server_game->total;
						$saveData->userinfo->exp = $userData->server_game->exp;
						$saveData->userinfo->gameid = $userData->gameid;
						
						$sql = "update ".$tableName." set gameid='".$userData->gameid."',game_data='".json_encode($saveData)."',data_key='".$data_key."',last_time=".time().",choose_num=0 where id=".$result['id'];
						$conne->uidRst($sql);
					}
					else
					{
						file_put_contents($file1,date('Y-m-d H:i:s', time()),LOCK_EX);
					}
				}
			}
			
			if($level > 30)
			{
				$file2  = $dataFilePath.'log/server'.$serverID.'_create_2.txt';
				if(!is_file($file2))//文件没生成,要去插入数据
				{
					$tableName = $sql_table.'server_game_equal';
					$sql = "select id from ".$tableName." where id between 50 and 100 and data_key='".$data_key."' and gameid='".$userData->gameid."' limit 1";
					$result = $conne->getRowsRst($sql);
					if(!$result)
					{
						$sql = "select id from ".$tableName." where id between 50 and 100 and last_time=0 limit 1";
						$result = $conne->getRowsRst($sql);
						if($result)
						{
							unset($team1Data->teamID);
							$saveData = new stdClass();
							$saveData->pkdata = new stdClass();
							$saveData->pkdata->list =$team1Data->list;
							$saveData->base = $changeFightDataValue->chooseList;
							$saveData->userinfo = new stdClass();
							$saveData->userinfo->head = $userData->head;
							$saveData->userinfo->nick = base64_encode($userData->nick);
							$saveData->userinfo->level = $userData->level;
							$saveData->userinfo->force = $userData->tec_force + $userData->award_force;
							$saveData->userinfo->win = $userData->server_game_equal->win;
							$saveData->userinfo->total = $userData->server_game_equal->total;
							$saveData->userinfo->exp = $userData->server_game_equal->exp;
							$saveData->userinfo->gameid = $userData->gameid;
							
							$sql = "update ".$tableName." set gameid='".$userData->gameid."',game_data='".json_encode($saveData)."',data_key='".$data_key."',last_time=".time().",choose_num=0 where id=".$result['id'];
							$conne->uidRst($sql);
						}
						else
						{
							file_put_contents($file2,date('Y-m-d h:m:s', time()),LOCK_EX);
						}
					}
				}
			}
		}
		
				

		
	}while(false);
	
	function getMainForce($level){
		$add = $level;
		$index = 1;
		while($level > 10*$index)
		{
			$add += ($level - 10*$index);
			$index ++;	
		}
		return $add;
	}


?> 