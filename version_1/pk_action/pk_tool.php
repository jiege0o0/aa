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
		global $conne,$sql_table;
		$tableName = $sql_table.$pkType;
		$sql = "SHOW TABLES LIKE '".$tableName."_".$pkLevel."'";
		if(!$conne->getRowsNum($sql))//û�����
		{
			return createPKTable($pkType,$pkLevel);
		}
		return true;
	}
	
	//���ݾ��飬�������ڵȼ�
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
	
	//���ʹ�ù����ʤ��
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
	
	//��ɵȼ�����
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
			// {//�ɴ�ĳ���< Ҫʤ�ĳ���
				// $userData->active->task->doing = false;
			// }
			// $returnData->sync_active_task = $userData->active->task;
			// $returnData->finish_task = true;
			// $userData->setChangeKey('active');
		// }
	// }
	
	//����ѹ������
	function pkEncode($data){
		
	}
	
	//�����ݽ��н��뻹ԭ
	function pkDecode($data){
		
	}
	
	//������ͳ�ƹ���ʹ�����
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
	
	//����Ļ�������
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
	
	
	
	
	
	
	
	
	
	
	
	
	

?>