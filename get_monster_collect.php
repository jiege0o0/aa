<?php 

	//$num奖励数量，$maxTypeNum种类最大数量
	function addMonsterCollect($callNum,$maxTypeNum){
		global $monster_base,$userData;
		$userLevel = $userData->level+1;
		$award = array();
		$awardList = array();
		$awardList2 = array();
		foreach($monster_base as $key=>$value)
		{
			if($userLevel >= $value['level'])
			{	
				$num = $userData->getCollectNum($key);
				array_push($awardList,array('id'=>$key,'num'=>$num));
			}
		}
		
		usort($awardList,monsterSplitSort);
		array_push($awardList2,array_shift($awardList));
		array_push($awardList2,array_shift($awardList));
		usort($awardList,randomSortFun);
		array_push($awardList2,array_shift($awardList));
		array_push($awardList2,array_shift($awardList));
		array_push($awardList2,array_shift($awardList));
		if(!$maxTypeNum)
		{
			$maxTypeNum -= 5;
			while($maxTypeNum > 0)
			{
				array_push($awardList2,array_shift($awardList));
				$maxTypeNum --;
			}
			if($maxTypeNum < 0)
			{
				usort($awardList2,randomSortFun);
				while($maxTypeNum < 0)
				{
					array_shift($awardList2);
					$maxTypeNum ++;
				}
			}
		}
		
		$len = count($awardList2)-1;
		while($callNum--)
		{
			$id = $awardList2[rand(0,$len)]['id'];
			if(!$award[$id])
				$award[$id] = 0;
			$award[$id]++;
		}
		
		foreach($award as $key=>$value)
		{
			$userData->addCollect($key,$value);
		}
		
		return $award;
	}
	
	function monsterSplitSort($a,$b){
		if($a['num'] < $b['num'])
			return -1;
		return 1;
	}
	
	function randomSortFun($a,$b){
		return lcg_value()>0.5?1:-1;
	}
	

?> 