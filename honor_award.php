<?php 
	$step = $msg->step;
	$type = $msg->type;
	$id = $msg->id;
	$awardBase = array('1'=>array('num'=>10,'diamond'=>10),'2'=>array('num'=>100,'diamond'=>50),'3'=>array('num'=>1000,'diamond'=>200),'4'=>array('num'=>5000,'diamond'=>400),'5'=>array('num'=>10000,'diamond'=>500));
	do{
		$returnData->{'sync_honor_'.$type} = new stdClass();
		if(!$userData->honor->{$type}->{$id}){
			$returnData->fail = 1;//找不到成就数据
			$returnData->{'sync_honor_'.$type}->{$id} = null;
			break;
		}
		if(((int)$userData->honor->{$type}->{$id}->a) != ($step-1)){
			$returnData->fail = 2;//已领奖
			$returnData->{'sync_honor_'.$type}->{$id} = $userData->honor->{$type}->{$id};
			break;
		}
		$need = $awardBase[$step]['num'];
		$award = $awardBase[$step]['diamond'];
		if($userData->honor->{$type}->{$id}->w < $need){
			$returnData->fail = 3;//未达标
			$returnData->{'sync_honor_'.$type}->{$id} = $userData->honor->{$type}->{$id};
			break;
		}
		
		$returnData->diamond = $award;
		$userData->addDiamond($award);
		$userData->honor->{$type}->{$id}->a = $step;
		$returnData->{'sync_honor_'.$type}->{$id} = $userData->honor->{$type}->{$id};
		$userData->setChangeKey('honor');
		$userData->write2DB();
	}while(false);
	
?> 