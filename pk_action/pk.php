<?php 
	//进入前已验证完合法性
	require_once($filePath."cache/monster.php");
	require_once($filePath."tool/tool.php");
	
	require_once($filePath."pk_action/pk_player.php");
	require_once($filePath."pk_action/pk_team.php");
	require_once($filePath."pk_action/pk_fun.php");
	require_once($filePath."pk_action/pk_skill.php");
	require_once($filePath."pk_action/pk_data.php");
	
	
	// $team2Data = array('list'=>array(107,107,107,107,107,107,107,107,107,106),'ring'=>array('id'=>1,'level'=>10),'tec'=>array(107,107,107,107,107,107,107,107,107,107));
	// $team1Data = array('list'=>array(107,107,107,107,107,107,107,107,107,107),'ring'=>array('id'=>1,'level'=>10),'tec'=>array(107,107,107,107,107,107,107,107,107,107));
	$team1Data->teamID = 1;
	$team2Data->teamID = 2;
	

	
	$pkData = new PKData($team1Data,$team2Data);
	$playArr1 = $pkData->team1->getFightArr();
	$playArr2 = $pkData->team2->getFightArr();
	
	unset($team1Data->teamID);
	unset($team2Data->teamID);
	
	// $pkData->outDetail=true;

	while(true)
	{
		//循环PK逻辑
		pkOneRound($playArr1,$playArr2);
		if($playArr1[0]->hp == 0)
			$playArr1 = $pkData->team1->getFightArr();
		if($playArr2[0]->hp == 0)
			$playArr2 = $pkData->team2->getFightArr();
		if(count($playArr1) == 0 && count($playArr2) == 0)
		{
			//平局(平局算进攻方输)
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
			$playArr1 = $pkData->team1->getFightArr();
		if($playArr2[0]->pkRound >= 3)
			$playArr2 = $pkData->team2->getFightArr();
			
		if(count($playArr1) == 0 && count($playArr2) == 0)
		{
			//平局(平局算进攻方输)
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
	if($pkData->outDetail)
	{
		$returnData->detail = $pkData->roundResultCollect;
	}
	
	// echo join('##',$pkData->resultArray);//.'<br/>'.$result.$playArr2[0]->hp;
	// debug($result);
	// debug('trace');
	
	
?> 