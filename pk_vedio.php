<?php 
	//进入前已验证完合法性
	require_once($filePath."cache/monster.php");
	require_once($filePath."tool/tool.php");
	
	require_once($filePath."pk_action/pk_player.php");
	require_once($filePath."pk_action/pk_team.php");
	require_once($filePath."pk_action/pk_fun.php");
	require_once($filePath."pk_action/pk_skill.php");
	require_once($filePath."pk_action/pk_data.php");
	
	//$dataIn = json_decode('{"team1":{"ac":[],"rl":10,"r":1,"jr":1},"team2":{"ac":[],"rl":10,"r":1,"jr":1},"player1":[{"hp":40000000,"mhp":4000000,"spd":5700,"atk":10000,"mid":107,"id":10},{"hp":4000000,"mhp":4000000,"spd":5700,"atk":10000,"mid":107,"id":11},{"hp":4000000,"mhp":4000000,"spd":5700,"atk":10000,"mid":107,"id":12}],"player2":[{"hp":40000000,"mhp":4000000,"spd":5700,"atk":10000,"mid":107,"id":20},{"hp":4000000,"mhp":4000000,"spd":5700,"atk":10000,"mid":107,"id":21},{"hp":4000000,"mhp":4000000,"spd":5700,"atk":10000,"mid":107,"id":22}],"result":{"w":1,"hp":262000}}');
	
	
	//重新填充数据
	$pkData = new PKData();
	$pkData->outDetail = true;
	$pkData->outResult = false;
	$pkData->isVedio = true;
	
	$pkData->team1 = new Team();
	$pkData->team2 = new Team();
	$pkData->team1->fromReplayNeed(1,$msg->team1,$msg->team1base,$msg->player1);
	$pkData->team2->fromReplayNeed(2,$msg->team2,$msg->team2base,$msg->player2);
	$pkData->team1->enemy = $pkData->team2;
	$pkData->team2->enemy = $pkData->team1;
	
	// $pkData->outDetail = false;
	
	
	$playArr1 = $pkData->team1->monsterList;
	$playArr2 = $pkData->team2->monsterList;
	
	pkOneRound($playArr1,$playArr2);
	
	$returnData->pkdata = $pkData->roundResultCollect->{1};

	
	
?> 