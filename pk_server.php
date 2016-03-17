<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");

	$myChoose = $msg->choose;
	$team1Data = changePKData($myChoose,'server_game');
	do{
		if(!$userData->server_game->enemy || !$userData->server_game->choose)//没找到PK对象
		{
			$returnData -> fail = 1;
			break;
		}
		if($team1Data->fail)//玩家牌的数据不对
		{
			$returnData -> fail = $team1Data->fail;
			break;
		}
		$pkType = 'server_game';
		$pkLevel = getPKTableLevel($userData->server_game->exp);
		
		
		$team2Data = $userData->server_game->enemy->pkdata;
		require_once($filePath."pk_action/pk.php");
		
		//---------------------更新战斗池---------------
		if($userData->server_game->pk == 0 && ($result || $changeFightDataValue->cost <20))//用了80的才正常
		{
			$enemyAdd = 5;
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
			$saveData->userinfo->win = $userData->server_game->win;
			$saveData->userinfo->total = $userData->server_game->total;
		
			//更新战斗表
			$time = time();
			$sql1 = "update ".$tableName." set gameid='".$userData->gameid."',game_data='".json_encode($saveData)."',result=".($result == 0?0:1).",last_time=".$time.",choose_num=0";
			$sql = $sql1." where id=".$userData->server_game->enemy->id." and last_time=".$userData->server_game->enemy->last_time;
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
		$returnData->sync_server_game = new stdClass();
		
		//处更玩家数据,奖励
		$award = new stdClass();
		$returnData->award = $award;
		$award->exp = round(30*(1+$pkLevel/10));
		$award->coin = round(90*(1+$pkLevel/10));//*$userData->server_game->last
		$award->prop = new stdClass();
		
		testLevelTask($pkType,$result);
		$userData->server_game->total++;
		$returnData->sync_server_game->total = $userData->server_game->total;
		if($result)
		{
			$userData->server_game->win++;
			$userData->server_game->last++;
			$returnData->sync_server_game->win = $userData->server_game->win;
			$returnData->sync_server_game->last = $userData->server_game->last;
			if($userData->server_game->total%10 == 0)//每打10场，可得一个无科技场门券
			{
				$award->prop->{"21"} = 1;
			}
			if($userData->server_game->win%5 == 0)//每胜5场，可得一个普通道具
			{
				if(lcg_value()>0.5)
					$award->prop->{"3"} = 1;
				else if(lcg_value()>0.5)
					$award->prop->{"1"} = 1;
				else
					$award->prop->{"2"} = 1;
			}
			if($userData->server_game->win%100 == 0)//每胜100场，可得一个高级道具
			{
				if(lcg_value()>0.5)
					$award->prop->{"13"} = 1;
				else if(lcg_value()>0.5)
					$award->prop->{"11"} = 1;
				else
					$award->prop->{"12"} = 1;
			}
			$award->g_exp = 3;
			if($userData->server_game->exp < 0)
				$award->g_exp += floor(-$userData->server_game->exp/100);
				
			$userData->server_game->choose = null;
			$userData->server_game->enemy = null;
		}
		else
		{
			$userData->server_game->last = 0;
			$returnData->sync_server_game->last = $userData->server_game->last;
			$award->exp = round($award->exp/3*2);
			$award->coin = round($award->coin/5);
			$award->g_exp = -2;
		}	
		
		foreach($award->prop as $key=>$value)
		{
			$userData->addProp($key,$value);
		}
		$userData->addCoin($award->coin);
		$userData->addExp($award->exp);
		$userData->server_game->exp += $award->g_exp;
		$userData->server_game->pk += 1;
		
		
		$userData->server_game->pkdata = array("team1"=>$team1Data,"team2"=>$team2Data,"isequal"=>$equalPK);
		
		$returnData->sync_server_game->exp = $userData->server_game->exp;
		$returnData->sync_server_game->pk = $userData->server_game->pk;
		if(!$userData->server_game->choose)
		{
			$returnData->sync_server_game->choose = $userData->server_game->choose;
			$returnData->sync_server_game->enemy = $userData->server_game->enemy;
		}
		
		$userData->setChangeKey('server_game');
		$userData->write2DB();

		
	}while(false);
	
	

?> 