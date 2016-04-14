<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");

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
		$pkLevel = getPKTableLevel($userData->server_game_equal->exp);
		
		
		$team2Data = $userData->server_game_equal->enemy->pkdata;
		
		$equalPK = true;
		require_once($filePath."pk_action/pk.php");
		
		//---------------------更新战斗池---------------
		if($userData->server_game_equal->pk == 0 && ($result || $changeFightDataValue->cost < 20))//用了80的才正常
		{
			$enemyAdd = 3;
			$team1Data->fight += $enemyAdd;//知道了对方的卡牌，要增加对方实力才能平衡
			$tableName = $sql_table.$pkType."_".$pkLevel;
			unset($team1Data->teamID);
			$saveData = new stdClass();
			$saveData->pkdata = $team1Data;
			$saveData->base = $changeFightDataValue->chooseList;
			$saveData->userinfo = new stdClass();
			$saveData->userinfo->head = $userData->head;
			$saveData->userinfo->nick = $userData->nick;
			$saveData->userinfo->level = $userData->level;
			$saveData->userinfo->force = $userData->tec_force + $userData->award_force;
			$saveData->userinfo->win = $userData->server_game_equal->win;
			$saveData->userinfo->total = $userData->server_game_equal->total;
			$saveData->userinfo->exp = $userData->server_game_equal->exp;
			$saveData->userinfo->gameid = $userData->gameid;
			
	
			//更新战斗表
			$sql = "update ".$tableName." set gameid='".$userData->gameid."',game_data='".json_encode($team1Data)."',result=".($result == 0?0:1)." where id=".$id;
			$conne->uidRst($sql);
			
			//更新战斗表
			$time = time();
			$sql1 = "update ".$tableName." set gameid='".$userData->gameid."',game_data='".json_encode($saveData)."',result=".($result == 0?0:1).",last_time=".$time.",choose_num=0";
			$sql = $sql1." where id=".$userData->server_game_equal->enemy->id." and last_time=".$userData->server_game_equal->enemy->last_time;
			if(!$conne->uidRst($sql)){//没有更新到
				//先取ID
				$sql = "select id from ".$tableName." where gameid!='".$userData->gameid."' order by ".$winKey." choose_num asc, last_time asc limit 1";
				$result = $conne->getRowsRst($sql);
				if($result)
				{
					$sql = "select * from ".$tableName." order by ".$winKey." choose_num asc, last_time asc limit 1";
					$result = $conne->getRowsRst($sql);
					$sql = $sql1." where id=".$result['id'];
					$conne->uidRst($sql);
				}
			}
			
			$team1Data->fight -= $enemyAdd;//知道了对方的卡牌，要增加对方实力才能平衡
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
		testLevelTask($pkType,$result);
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

		}
		else
		{
			$userData->server_game_equal->last = 0;
			$returnData->sync_server_game_equal->last = $userData->server_game_equal->last;
			$award->g_exp = -2;
		}

		$winTime = min(10,$userData->server_game_equal->last + 1);//10次以上的奖励不会增加
		$award->exp = round(30*(1+$pkLevel/10)*$winTime);
		$award->coin = round(100*(1+$pkLevel/10)*$winTime);
		if($winTime > 5)//赢5场以上，每次可得一个无科技场门券，每次可增加一个高级道具
		{
			// tempAddProp(21);
			
			$propNum = ceil(($winTime - 5)/2);
			while($propNum > 0)
			{
				if(lcg_value()>0.5)
					tempAddProp(11);
				else if(lcg_value()>0.5)
					tempAddProp(12);
				else
					tempAddProp(13);
				$propNum --;
			}
		}
		if($winTime > 2)//赢2场以上，每次可增加一个普通道具
		{
			$propNum = ceil(($winTime - 2)/2);
			while($propNum > 0)
			{
				if(lcg_value()>0.5)
					tempAddProp(1);
				else if(lcg_value()>0.5)
					tempAddProp(2);
				else
					tempAddProp(3);
				$propNum--;
			}
		}

		
		foreach($award->prop as $key=>$value)
		{
			$userData->addProp($key,$value);
		}
		$userData->addCoin($award->coin);
		$userData->addExp($award->exp);
		$userData->server_game_equal->exp += $award->g_exp;
		$userData->server_game_equal->pk += 1;
		
		$userData->server_game_equal->pkdata = array("team1"=>$team1Data,"team2"=>$team2Data,"isequal"=>$equalPK);
		$returnData->sync_server_game_equal->exp = $userData->server_game_equal->exp;
		
		if(!$userData->sync_server_game_equal->choose)
		{
			$returnData->sync_server_game_equal->choose = $userData->sync_server_game_equal->choose;
			$returnData->sync_server_game_equal->enemy = $userData->sync_server_game_equal->enemy;
		}
		
		
		$userData->setChangeKey('server_game_equal');
		$userData->write2DB();				
		
	}while(false);
	
	function tempAddProp($id,$num=1){
		global $award;
		if($award->prop->{$id})
			$award->prop->{$id} += $num;
		else 
			$award->prop->{$id} = $num;		
	}

?> 