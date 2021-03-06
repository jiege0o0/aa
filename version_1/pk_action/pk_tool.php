<?php
	$HardBase = array(
		"force"=>array(0,100,1000,3000,6000,10000,15000),
		"level"=>array(0,4,10,15,20,25,30),
		"leader"=>array(0,0,5,15,20,25,30)
	);
	//生成对应表
	function createPKTable($pkType,$pkLevel){
		if($pkLevel == 1)
			throw new Exception("not base table in ".$pkType);
		global $conne,$sql_table;
		$tableName = $sql_table.$pkType;
		$sql = "SHOW TABLES LIKE '".$tableName."_".($pkLevel-1)."'";
		if(!$conne->getRowsNum($sql))//没这个表
		{
			createPKTable($pkType,$pkLevel-1);
		}
		$sql = "CREATE TABLE ".$tableName."_".$pkLevel." AS SELECT * FROM ".$tableName."_".($pkLevel-1)."";
		return $conne->uidRst($sql);
	}
	
	//取PK所对应的表，如果没有，则生成
	function testPKTable($pkType,$pkLevel){
		global $conne,$sql_table;
		$tableName = $sql_table.$pkType;
		$sql = "SHOW TABLES LIKE '".$tableName."_".$pkLevel."'";
		if(!$conne->getRowsNum($sql))//没这个表
		{
			return createPKTable($pkType,$pkLevel);
		}
		return true;
	}
	
	//根据经验，返回所在等级
	function getPKTableLevel($exp,$step){
		$level = 1;
		for($i=1;$i<=100;$i++)
		{
			if($exp >= pow(1.2,$i)*$step-$step)
				$level ++;
			else
				break;
		}
		return $level;
	}
	
	//玩家使用怪物的胜率
	function addMonsterUse($pkData,$result){
		global $userData,$returnData;
		// if(!$returnData->sync_honor_ring)
		// {
			// $returnData->sync_honor_ring = new stdClass();
		// }
		// if(!$userData->honor->ring->{$pkData->ring})
		// {
			// $userData->honor->ring->{$pkData->ring} = new stdClass();
			// $userData->honor->ring->{$pkData->ring}->t = 0;
			// $userData->honor->ring->{$pkData->ring}->w = 0;
		// }

		// $userData->honor->ring->{$pkData->ring}->t ++;
		// if($result)
			// $userData->honor->ring->{$pkData->ring}->w ++;
		// $returnData->sync_honor_ring->{$pkData->ring} = $userData->honor->ring->{$pkData->ring};
			
			
		if(!$returnData->sync_honor_monster)
		{
			$returnData->sync_honor_monster = new stdClass();
		}
		$list = $pkData->list;
		$addData = array();
		foreach($list as $key=>$value)
		{
			if($addData[$value])
				continue;
			$addData[$value] = true; 
			if(!$userData->honor->monster->{$value})
			{
				$userData->honor->monster->{$value} = new stdClass();
				$userData->honor->monster->{$value}->t = 0;
				$userData->honor->monster->{$value}->w = 0;
				// $userData->honor->monster->{$value}->a = 0;
			}
			$userData->honor->monster->{$value}->t ++;
			if($result)
				$userData->honor->monster->{$value}->w ++;
			$returnData->sync_honor_monster->{$value} = $userData->honor->monster->{$value};
		}
		$userData->setChangeKey('honor');
	}
	
	//完成等级任务
	// function testLevelTask($type,$result){
		// global $userData,$returnData;
		// if($userData->active->task->doing  && $userData->active->task->type = $type)
		// {
			// if($result)
				// $userData->active->task->win ++;
			// $userData->active->task->total ++;
			// if($userData->active->task->win == $userData->active->task->targetwin)
			// {
				// $returnData->honor_task_award = $userData->active->task->award;
				// $userData->active->task->doing = false;
				// $userData->addAwardForce($userData->active->task->award);
			// }
			// else if($userData->active->task->targettotal - $userData->active->task->total < $userData->active->task->targetwin - $userData->active->task->win)
			// {//可打的场数< 要胜的场数
				// $userData->active->task->doing = false;
			// }
			// $returnData->sync_active_task = $userData->active->task;
			// $returnData->finish_task = true;
			// $userData->setChangeKey('active');
		// }
	// }
	
	//编码压缩数据
	function pkEncode($data){
		
	}
	
	//对数据进行解码还原
	function pkDecode($data){
		
	}
	
	//服务器统计怪物使用情况
	function monsterUseLog($table,$list,$use,$isWin){
		$arr = array();
		$list = $list->list;
		$use = $use->list;
		foreach($list as $key=>$value)
		{
			$arr[$value] = 0;
		}
		foreach($use as $key=>$value)
		{
			$arr[$value] ++;
		}
		foreach($arr as $key=>$value)
		{
			monsterUseLogOne($table,$key,$value,$isWin);
		}
	}
	
	function monsterUseLogOne($table,$id,$useNum,$isWin){
		global $conne;
		$arr = array();
		array_push($arr,addMonsterLogKey('display',1));
		if($useNum)
		{
			array_push($arr,addMonsterLogKey('use_time',1));
			array_push($arr,addMonsterLogKey('use_num',$useNum));
			if($isWin)
				array_push($arr,addMonsterLogKey('win',1));
		}
		$sql = "update ".$table." set ".join(",",$arr)." where id='".$id."'";
		$conne->uidRst($sql);
	}
	
	function addMonsterLogKey($key,$value){
		return $key."=".$key.'+'.$value;
	}
	
	//怪物的基础属性
    function getMonsterValue($monsterID){
		global $userData,$monster_base;
		$mvo = $monster_base[$monsterID];
        $force = ($userData->award_force + $userData->tec_force);
		$mLevel = $userData->tec->monster->{$monsterID};
		if(!$mLevel)
			$mLevel = 0;
		for($i=1;$i<=$mLevel;$i ++)
		{
			$force += $i;
		}
		
		return array(
			'atk'=>round($mvo['atk'] * (1+$force/100)),
			'hp'=>round($mvo['hp'] * (1+$force/100)),
			'speed'=>$mvo['speed']
		);
    }
	
	//这个战力下对应的怪物技能
	function getForceSkill($force){
		if($force < 2000)
			return 0;
		return rand(1,min(50,ceil(pow(($force - 1500)/200,0.85)/2)));
	}
	
	//这个战力下对应的怪物等级
	function getForceLevel($force){
		$force = pow($force,0.62)-6;
		$level = 0;
		$levelForce = 0;
		while($levelForce + $level < $force)
		{
			$levelForce += $level;
			$level ++;
		}
		return max(0,$level-1);
	}
	//这个战力下对应的怪物统帅
	function getForceLeader($force){
		if($force < 450)
            return 0;
        return floor(pow(($force-450)/10,0.42));
		// $force = pow(max(0,$force- 450),0.64);
		// $level = 0;
		// $levelForce = 0;
		// while($levelForce + $level < $force)
		// {
			// $levelForce += $level;
			// $level ++;
		// }
		// return max(0,$level-1);
	}
	//怪物对应战力
	function getMonsetrForce($level){
		$count = 0;
		for($i=1;$i<=$level;$i ++)
		{
			$count += $i;
		}
		return $count;
	}
	
	function resetTeam2Data($hard=0){
		global $team2Data,$HardBase;
		$mLevel = getForceLevel($team2Data->fight);
		$mLeader = getForceLeader($team2Data->fight);
		if($hard)
		{
			$mLevel = min($mLevel,$HardBase['level'][$hard]);
			$mLeader = min($mLeader,$HardBase['leader'][$hard]);
		}
		$team2Data->tec = new stdClass();
		if($mLevel)
		{
			$mForce = getMonsetrForce($mLevel);
			$team2Data->mlevel = new stdClass();
			foreach($team2Data->list as $key=>$monsterID)
			{	
				if($monsterID && !isset($team2Data->tec->{$monsterID}))
				{
					$team2Data->mlevel->{$monsterID} = $mLevel;
					$team2Data->tec->{$monsterID} = $mForce;
				}
			}
		}

		if($mLeader)
		{
			$team2Data->leader = new stdClass();
			$team2Data->leader->{'1'} = $mLeader;
			$team2Data->leader->{'2'} = $mLeader;
			$team2Data->leader->{'3'} = $mLeader;
		}			
		
		
	}
	
	
	
	
	
	
	
	
	
	

?>