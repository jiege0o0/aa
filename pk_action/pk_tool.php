<?php
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
		global $conne;
		$tableName = $pkType;
		$sql = "SHOW TABLES LIKE '".$tableName."_".$pkLevel."'";
		if(!$conne->getRowsNum($sql))//没这个表
		{
			return createPKTable($pkType,$pkLevel);
		}
		return true;
	}
	
	//根据经验，返回所在等级
	function getPKTableLevel($exp){
		$level = 1;
		for($i=0;$i<=20;$i++)
		{
			if($exp >= pow(2,$i)*100)
				$level ++;
			else
				break;
		}
		return $level;
	}
	
	//玩家使用怪物的胜率
	function addMonsterUse($pkData,$result){
		global $userData,$returnData;
		if(!$returnData->sync_honor_ring)
		{
			$returnData->sync_honor_ring = new stdClass();
		}
		if(!$userData->honor->ring->{$pkData->ring})
		{
			$userData->honor->ring->{$pkData->ring} = new stdClass();
			$userData->honor->ring->{$pkData->ring}->t = 0;
			$userData->honor->ring->{$pkData->ring}->w = 0;
			// $userData->honor->ring->{$pkData->ring}->a = 0;
		}

		$userData->honor->ring->{$pkData->ring}->t ++;
		if($result)
			$userData->honor->ring->{$pkData->ring}->w ++;
		$returnData->sync_honor_ring->{$pkData->ring} = $userData->honor->ring->{$pkData->ring};
			
			
		if(!$returnData->sync_honor_monster)
		{
			$returnData->sync_honor_monster = new stdClass();
		}
		$list = $pkData->list;
		foreach($list as $key=>$value)
		{
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
	function testLevelTask($type,$result){
		global $userData,$returnData;
		if($userData->honor->task->doing  && $userData->honor->task->type = $type)
		{
			if($result)
				$userData->honor->task->win ++;
			$userData->honor->task->total ++;
			if($userData->honor->task->win == $userData->honor->task->targetwin)
			{
				$returnData->honor_task_award = $userData->honor->task->award;
				$userData->honor->task->doing = false;
				$userData->addAwardForce($userData->honor->task->award);
			}
			else if($userData->honor->task->targettotal - $userData->honor->task->total < $userData->honor->task->targetwin - $userData->honor->task->win)
			{//可打的场数< 要胜的场数
				$userData->honor->task->doing = false;
			}
			$returnData->sync_honor_task = $userData->honor->task;
			$userData->setChangeKey('honor');
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

?>