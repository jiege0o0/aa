<?php 
	require_once($filePath."pk_action/change_fight_data.php");
	require_once($filePath."pk_action/pk_tool.php");
	require_once($filePath."get_monster_collect.php");

	$data_key = $msg->data_key;
	$myChoose = $msg->choose;
	$team1Data = changePKData($myChoose,'server_game');
	do{
		if($userData->getEnergy() < 1)//体力不够
		{
			$returnData->fail = 1;
			$returnData->sync_energy = $userData->energy;
			break;
		}
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
		$pkLevel = getPKTableLevel($userData->server_game->exp,100);
		
		
		$team2Data = $userData->server_game->enemy->pkdata;
		
		$pkUserInfo = new stdClass();
		if($userData->server_game->enemy->userinfo)
		{
			$pkUserInfo->nick = $userData->server_game->enemy->userinfo->nick;
			$pkUserInfo->head = $userData->server_game->enemy->userinfo->head;
			$pkUserInfo->gameid = $userData->server_game->enemy->userinfo->gameid;
		}
		else//系统机器人
		{
			$team2Data->fight = max($team1Data->fight-10,10);
		}
		
		// $enemyAdd = $userData->server_game->last;
		// $team2Data->fight += $enemyAdd;//知道了对方的卡牌，要增加对方实力才能平衡
		require_once($filePath."pk_action/pk.php");
		// $team2Data->fight -= $enemyAdd;//知道了对方的卡牌，要增加对方实力才能平衡
		
		//---------------------更新战斗池---------------
		if($result || $changeFightDataValue->cost >80)//cost最大是88  && ($userData->tec_force + $userData->award_force)
		{
			$id = $userData->server_game->exp + 50;
			if($id < 1)
				$id = 1;
			$tableName = $sql_table.$pkType;
			
		
			//更新战斗表
			$sql = "select id from ".$tableName." where id between ".($id-10)." and ".($id+10)." and data_key='".$data_key."' and gameid='".$userData->gameid."' limit 1";
			$result2 = $conne->getRowsRst($sql);
			if(!$result2)
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
			
				$sql = "select id from ".$tableName." where id between ".($id-3)." and ".($id+3)." order by last_time asc limit 1";
				$result2 = $conne->getRowsRst($sql);
				$sql = "update ".$tableName." set gameid='".$userData->gameid."',game_data='".json_encode($saveData)."',data_key='".$data_key."',last_time=".time().",choose_num=0 where id=".$result2['id'];
				$conne->uidRst($sql);
			}
			
			
		}
		
		
		
		
		
		//---------------------更新玩家数据---------------
		addMonsterUse($myChoose,$result);
		$returnData->sync_server_game = new stdClass();
		
		//处更玩家数据,奖励
		$award = new stdClass();
		$returnData->award = $award;
		$award->exp = round(20*(1+$pkLevel/3));
		$award->coin = round(30*(1+$pkLevel/10));//*$userData->server_game->last
		$award->prop = new stdClass();
		
		// testLevelTask($pkType,$result);
		$userData->server_game->total++;
		$returnData->sync_server_game->total = $userData->server_game->total;
		if($result)
		{
			$userData->server_game->win++;
			$userData->server_game->last++;
			$returnData->sync_server_game->win = $userData->server_game->win;
			$returnData->sync_server_game->last = $userData->server_game->last;
			if($userData->server_game->win%6 == 0)//每胜6场，可得一个无科技场门券
			{
				$award->prop->{"21"} = 1;
			}
			
			$collectNum = 0;
			if($userData->server_game->win%5 == 0)//每胜5场
			{
				$collectNum ++;
			}
			if($userData->server_game->win%10 == 0)//每胜10场
			{
				$collectNum ++;
			}
			if($userData->server_game->win%30 == 0)//每胜30场
			{
				$collectNum ++;
			}
			$collectNum = ceil($pkLevel/2)*($collectNum + 1);
			$award->collect = addMonsterCollect($collectNum);//,2
			$award->g_exp = 4;
			if($userData->server_game->exp < 0)//少于0的加速回归
				$award->g_exp += floor(-$userData->server_game->exp/100);
				
			$enemyForce = $userData->server_game->enemy->userinfo->force;
			$userForce = $userData->tec_force + $userData->award_force;
			if($enemyForce && $userForce / $enemyForce > 1.2)//高于阶段战力的加速上升
			{
				$award->g_exp += floor(min($userForce / $enemyForce,2)*$pkLevel);
			}
				
			$userData->server_game->choose = false;
			$userData->server_game->enemy = null;
			$userData->server_game->time = time();
			$userData->server_game->pk = 0;
		}
		else
		{
			$userData->server_game->last = 0;
			$returnData->sync_server_game->last = $userData->server_game->last;
			$award->exp = round($award->exp/2);
			$award->coin = round($award->coin/3);
			$award->g_exp = -3 - ($pkLevel-1)*2;
			$userData->server_game->pk += 1;
		}	
		
		foreach($award->prop as $key=>$value)
		{
			$userData->addProp($key,$value);
		}
		$userData->addCoin($award->coin);
		$userData->addExp($award->exp);
		$userData->server_game->exp += $award->g_exp;
		
		
		
		$userData->server_game->pkdata = array("team1"=>$team1Data,"team2"=>$team2Data,"isequal"=>$equalPK,"info"=>$pkUserInfo,'version'=>$pk_version,'time'=>time());
		
		if($userData->server_game->exp > $userData->server_game->top)
		{
			$userData->server_game->top = $userData->server_game->exp;
			$returnData->sync_server_game->top = $userData->server_game->top;
		}
		
		$returnData->sync_server_game->exp = $userData->server_game->exp;
		$returnData->sync_server_game->pk = $userData->server_game->pk;
		if(!$userData->server_game->choose)
		{
			$returnData->sync_server_game->choose = $userData->server_game->choose;
			$returnData->sync_server_game->enemy = $userData->server_game->enemy;
		}
		
		
		renewMyCard();
		$userData->addEnergy(-1);
		
		
		$userData->addHistory($team1Data->list);
		$userData->setChangeKey('server_game');
		$userData->write2DB();
		
		if($userData->level >= 5)
			monsterUseLog('server_monster_'.min(25,floor($userData->level/5)*5),$changeFightDataValue->chooseList,$myChoose,$result);

		
	}while(false);
	
	function tempAddProp($id,$num=1){
		global $award;
		if($award->prop->{$id})
			$award->prop->{$id} += $num;
		else 
			$award->prop->{$id} = $num;		
	}

?> 