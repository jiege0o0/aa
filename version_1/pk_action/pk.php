<?php 
	//进入前已验证完合法性
	require($filePath."cache/monster.php");

	require($filePath."pk_action/skill/skill_base.php");
	require($filePath."pk_action/pk_player.php");
	require($filePath."pk_action/pk_team.php");
	require($filePath."pk_action/pk_fun.php");
	require($filePath."pk_action/pk_skill.php");
	require($filePath."pk_action/pk_data.php");
	
	
	// $team2Data = array('list'=>array(107,107,107,107,107,107,107,107,107,106),'ring'=>array('id'=>1,'level'=>10),'tec'=>array(107,107,107,107,107,107,107,107,107,107));
	// $team1Data = array('list'=>array(107,107,107,107,107,107,107,107,107,107),'ring'=>array('id'=>1,'level'=>10),'tec'=>array(107,107,107,107,107,107,107,107,107,107));
	$team1Data->teamID = 1;
	$team2Data->teamID = 2;
	
	$pkTaskData = array();

	
	$pkData = new PKData($team1Data,$team2Data);
	$playArr1 = $pkData->team1->getFightArr();
	$playArr2 = $pkData->team2->getFightArr();
	
	unset($team1Data->teamID);
	unset($team2Data->teamID);
	
	$pkData->outDetail=true;
// trace(count($pkData->team2->monsterList));
// trace($team2Data);
	$star1 = 0;
	$star2 = 0;
	while(true)
	{
		//循环PK逻辑
		pkOneRound($playArr1,$playArr2);
		
		if($playArr2[0]->hp == 0)
		{
			$playArr2 = $pkData->team2->getFightArr();
			if($playArr1[0]->hp != 0 || $pkData->lastSkiller->teamID == 1)
			{
				array_push($pkTaskData,$playArr1[0]);
			}
		}
		if($playArr1[0]->hp == 0)
			$playArr1 = $pkData->team1->getFightArr();
		if(count($playArr1) == 0 && count($playArr2) == 0)
		{
			//平局(平局算进攻方输)
			if($star1 > $star2)
				$result = 1;
			else 
				$result = 0;
			break;
		}
		else if(count($playArr1) == 0)
		{
			//2P胜
			$result = 0;
			break;
		}
		else if(count($playArr2) == 0)
		{
			//1P胜
			$result = 1;
			break;
		}
		
		// 战斗了3回合的要下场
		if($playArr1[0]->pkRound >= 3)
		{
			$star1 ++;
			$playArr1 = $pkData->team1->getFightArr();
		}
			
		if($playArr2[0]->pkRound >= 3)
		{
			$playArr2 = $pkData->team2->getFightArr();
			$star2 ++;
		}
			
		if(count($playArr1) == 0 && count($playArr2) == 0)
		{
			//平局(平局算进攻方输)
			if($star1 > $star2)
				$result = 1;
			else 
				$result = 0;
			break;
		}
		else if(count($playArr1) == 0)
		{
			//2P胜
			$result = 0;
			break;
		}
		else if(count($playArr2) == 0)
		{
			//1P胜
			$result = 1;
			break;
		}
	}
	
	
	$returnData->pkdata = $pkData->resultArray;
	$returnData->result = $result;
	$returnData->team1base = $pkData->team1->getTeamBase();
	$returnData->team2base = $pkData->team2->getTeamBase();
	$returnData->info = $pkUserInfo;
	$returnData->isequal = $equalPK;
	$returnData->pk_version = $pk_version;
	if($pkData->outDetail)
	{
		$returnData->detail = $pkData->roundResultCollect;
	}
	
	$returnData->mvp = new stdClass();
	foreach($pkData->team1->allMonsterList as $key=>$value)
	{
		$returnData->mvp->{$value->id} = floor($value->hpCount)."|".
		floor($value->atkCount)."|".floor($value->healCount)."|".floor($value->effectCount);
	}
	
	foreach($pkData->team2->allMonsterList as $key=>$value)
	{
		$returnData->mvp->{$value->id} = floor($value->hpCount)."|".
		floor($value->atkCount)."|".floor($value->healCount)."|".floor($value->effectCount);
	}
	

	
	
	// echo join('##',$pkData->resultArray);//.'<br/>'.$result.$playArr2[0]->hp;
	// debug($result);
	// debug('trace');
	
	
?> 