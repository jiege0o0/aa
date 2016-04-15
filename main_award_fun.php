<?php 

	$award = new stdClass();
	$returnData->award = $award;
	
	$award->prop = new stdClass();
	$award->coin = $level*100;
	
	//每过30小关奖一个普通道具
	$propNum = floor($level/30);
	while($propNum--)
	{
		if(lcg_value()<0.4)
			tempAddProp(2);
		else if(lcg_value()>0.5)
			tempAddProp(1);
		else
			tempAddProp(3);
	}
	
	//每过100小关奖一个高级道具
	$propNum = floor($level/100);
	while($propNum--)
	{
		if(lcg_value()<0.4)
			tempAddProp(12);
		else if(lcg_value()>0.5)
			tempAddProp(11);
		else
			tempAddProp(13);
	}

	
	foreach($award->prop as $key=>$value)
	{
		$userData->addProp($key,$value);
	}
	$userData->addCoin($award->coin);
	$userData->addExp($award->exp);
		

	
	function tempAddProp($id,$num=1){
		global $award;
		if($award->prop->{$id})
			$award->prop->{$id} += $num;
		else 
			$award->prop->{$id} = $num;		
	}

?> 