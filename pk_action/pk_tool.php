<?php
	//���ɶ�Ӧ��
	function createPKTable($pkType,$pkLevel){
		if($pkLevel == 1)
			throw new Exception("not base table in ".$pkType);
		global $conne,$sql_table;
		$tableName = $sql_table.$pkType;
		$sql = "SHOW TABLES LIKE '".$tableName."_".($pkLevel-1)."'";
		if(!$conne->getRowsNum($sql))//û�����
		{
			createPKTable($pkType,$pkLevel-1);
		}
		$sql = "CREATE TABLE ".$tableName."_".$pkLevel." AS SELECT * FROM ".$tableName."_".($pkLevel-1)."";
		return $conne->uidRst($sql);
	}
	
	//ȡPK����Ӧ�ı����û�У�������
	function testPKTable($pkType,$pkLevel){
		global $conne;
		$tableName = $pkType;
		$sql = "SHOW TABLES LIKE '".$tableName."_".$pkLevel."'";
		if(!$conne->getRowsNum($sql))//û�����
		{
			return createPKTable($pkType,$pkLevel);
		}
		return true;
	}
	
	//���ݾ��飬�������ڵȼ�
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
	
	//���ʹ�ù����ʤ��
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
	
	//��ɵȼ�����
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
			{//�ɴ�ĳ���< Ҫʤ�ĳ���
				$userData->honor->task->doing = false;
			}
			$returnData->sync_honor_task = $userData->honor->task;
			$userData->setChangeKey('honor');
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

?>