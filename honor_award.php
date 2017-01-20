<?php 
	$step = $msg->step;
	$type = $msg->type;
	$id = $msg->id;

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
		$need = $honorAwardBase[$step]['num'];
		$award = $honorAwardBase[$step]['diamond'];
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