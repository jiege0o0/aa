<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");
	require_once($filePath."get_monster_collect.php");

	$myChoose = $msg->choose;
	$team1Data = changePKData($myChoose,'server_game_equal',true);
	do{
		if(!$userData->server_game_equal->enemy || !$userData->server_game_equal->choose)//没找到PK对象
		{
			$returnData -> fail = 1;
			break;
		}
		if($team1Data->fail)//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		$pkType = 'server_game_equal';
		$pkLevel = getPKTableLevel($userData->server_game_equal->exp,100);
		
		
		$team2Data = $userData->server_game_equal->enemy->pkdata;
		
		$pkUserInfo = new stdClass();
		if($userData->server_game_equal->enemy->userinfo)
		{
			$pkUserInfo->nick = $userData->server_game_equal->enemy->userinfo->nick;
			$pkUserInfo->head = $userData->server_game_equal->enemy->userinfo->head;
			$pkUserInfo->gameid = $userData->server_game_equal->enemy->userinfo->gameid;
		}
		
		$equalPK = true;
		
		// $enemyAdd = $userData->server_game_equal->last;
		// if($enemyAdd >0 && $enemyAdd <3)
			// $enemyAdd = 0;
		// else($enemyAdd >=3)
			// $enemyAdd -= 2;
		// $team1Data->fight -= $enemyAdd;//知道了对方的卡牌，要降低实力才能平衡
		
		require_once($filePath."pk_action/pk.php");
		// $team2Data->fight -= $enemyAdd;//知道了对方的卡牌，要增加对方实力才能平衡
		
		//---------------------更新战斗池---------------
		if($result || $changeFightDataValue->cost > 80)//用了80以上的才正常
		{
			
			$tableName = $sql_table.$pkType."_".$pkLevel;
			unset($team1Data->teamID);
			$saveData = new stdClass();
			$saveData->pkdata = $team1Data;
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
			
	
			//更新战斗表
			$time = time();
			$sql1 = "update ".$tableName." set gameid='".$userData->gameid."',game_data='".json_encode($saveData)."',result=".($result == 0?0:1).",last_time=".$time.",choose_num=0";
			$sql = $sql1." where id=".$userData->server_game_equal->enemy->id." and last_time=".$userData->server_game_equal->enemy->last_time;
			if(!$conne->uidRst($sql)&& lcg_value()>0.9){//没有更新到    
				//先取ID
				$sql = "select id from ".$tableName." where gameid!='".$userData->gameid."' order by ".$winKey." choose_num asc, last_time asc limit 1";
				$sqlResult = $conne->getRowsRst($sql);
				if($sqlResult)
				{
					$sql = "select * from ".$tableName." order by ".$winKey." choose_num asc, last_time asc limit 1";
					$sqlResult = $conne->getRowsRst($sql);
					$sql = $sql1." where id=".$sqlResult['id'];
					$conne->uidRst($sql);
				}
			}
			
			
		}
		
		
		
		
		
		//---------------------更新玩家数据---------------
		
		
		
		
		addMonsterUse($myChoose,$result);
		
		$returnData->sync_server_game_equal = new stdClass();
		
		//处更玩家数据,奖励
		$award = new stdClass();
		$returnData->award = $award;
		
		$award->prop = new stdClass();
		
		$userData->server_game_equal->total++;
		$returnData->sync_server_game_equal->total = $userData->server_game_equal->total;
		// testLevelTask($pkType,$result);
		if($result)
		{
			$userData->server_game_equal->win++;
			$userData->server_game_equal->last++;
			$returnData->sync_server_game_equal->win = $userData->server_game_equal->win;
			$returnData->sync_server_game_equal->last = $userData->server_game_equal->last;


			if($userData->server_game_equal->last > $userData->server_game_equal->max)//连胜最高纪录
			{
				$userData->server_game_equal->max = $userData->server_game_equal->last;
				$returnData->sync_server_game_equal->max = $userData->server_game_equal->max;
			}
				
			
			$award->g_exp = 2 + $userData->server_game_equal->last;
			if($userData->server_game_equal->exp < 0)
				$award->g_exp += floor(-$userData->server_game_equal->exp/100);
				
			tempAddProp(21);
			
			$userData->server_game_equal->choose = null;
			$userData->server_game_equal->enemy = null;
			$userData->server_game_equal->time = time();
			$userData->server_game_equal->pk = 0;
			
			$winTime = min(9,$userData->server_game_equal->last + 1);//9次以上的奖励不会增加
			$award->exp = round(20*(1+$pkLevel/3)*$winTime);
			$award->coin = round(30*(1+$pkLevel/10)*$winTime);
			$collectNum = ceil($winTime/3*$pkLevel) + 1;
			$award->collect = addMonsterCollect($collectNum);//,2

		}
		else
		{
			$userData->server_game_equal->last = 0;
			$returnData->sync_server_game_equal->last = $userData->server_game_equal->last;
			$award->g_exp = -2-2*pkLevel;
			$award->exp = round(20*(1+$pkLevel/3));
			$userData->server_game_equal->pk += 1;
		}


		

		
		foreach($award->prop as $key=>$value)
		{
			$userData->addProp($key,$value);
		}
		$userData->addCoin($award->coin);
		$userData->addExp($award->exp);
		$userData->server_game_equal->exp += $award->g_exp;
		
		
		$userData->server_game_equal->pkdata = array("team1"=>$team1Data,"team2"=>$team2Data,"isequal"=>$equalPK,"info"=>$pkUserInfo,'version'=>$pk_version);
		$returnData->sync_server_game_equal->exp = $userData->server_game_equal->exp;
		
		if($userData->server_game_equal->exp > $userData->server_game_equal->top)
		{
			$userData->server_game_equal->top = $userData->server_game_equal->exp;
			$returnData->sync_server_game_equal->top = $userData->server_game_equal->top;
		}
		
		if(!$userData->sync_server_game_equal->choose)
		{
			$returnData->sync_server_game_equal->choose = $userData->sync_server_game_equal->choose;
			$returnData->sync_server_game_equal->enemy = $userData->sync_server_game_equal->enemy;
		}
		
		$userData->addHistory($team1Data->list);
		$userData->setChangeKey('server_game_equal');
		$userData->write2DB();	
		if($userData->level >= 5)
			monsterUseLog('server_equal_monster_'.min(25,floor($userData->level/5)*5),$changeFightDataValue->chooseList,$myChoose,$result);		
		
	}while(false);
	
	function tempAddProp($id,$num=1){
		global $award;
		if($award->prop->{$id})
			$award->prop->{$id} += $num;
		else 
			$award->prop->{$id} = $num;		
	}

?> 