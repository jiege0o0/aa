<?php 
	if($userData->pk_common->map == null)
	{
		$userData->pk_common->map = new stdClass();
		$userData->pk_common->map->value = 0;//总值
		$userData->pk_common->map->level = 1;//当前挂机关卡
		$userData->pk_common->map->max_level = 0;//最高挂机关卡
		$userData->pk_common->map->bag = 0;//背包中的值
		$userData->pk_common->map->pk_value = 0;//可PK次数
		$userData->pk_common->map->step = 0;//当前PK胜利次数
		$userData->pk_common->map->lasttime = 0;//上次结算时间
		
		$userData->pk_common->map->cd=0;
		$userData->pk_common->map->cd_force=0;
	}
	else if(!$userData->pk_common->map->level)
	{
		$userData->pk_common->map->level = 1;
		$userData->pk_common->map->step = 0;
	}
	
	//重置PKCD
	function resetMapCD(){
		global $userData;
		$force = $userData->tec_force + $userData->award_force;
		if($userData->pk_common->map->cd_force == $force)
			return false;
		
		require_once($filePath."cache/monster.php");
		require_once($filePath."pk_action/pk_tool.php");
		global $monster_base;		
		
		$userData->pk_common->map->cd_force = $force;
		$boss = {
             atk:90,
             hp:1000,
             speed:50,
         }
        $force = ceil(pow($userData->pk_common->map->level,1.2));
        $boss->atk = $boss->atk * $force*2;
        $boss->hp = $boss->hp * $force*30;	
		
		$totalHurt = 0;
		$monsterNum = 0;
		
		foreach($monster_base as $key=>$value)
		{
			if($userData->level >= $value['level'])
			{	
				$monsterNum ++;
				
				$mValue = getMonsterValue($key);
				$bossTime = ceil($mValue['hp'] / $boss->atk); //boss攻击我的次数
				$mTime = floor($bossTime*$mValue['speed']/$boss->speed); //我在死之前的攻击次数
				$totalHurt += $mTime * $mValue['atk'];
			}
		}
		
		$totalHurt = $totalHurt/$monsterNum;//平均伤害值
        $userData->pk_common->map->cd = ceil($bossData->hp/$totalHurt) * 5;
		return true;
	 }
	 
	 function resetMapData(){
		global $userData;
		if(!$userData->pk_common->map->lasttime)
			return false;
		resetMapCD();
		$cd = $userData->pk_common->map->cd;
		
        $passcd = time() - $userData->pk_common->map->lasttime;
        $addNum =  floor($passcd/$cd);

        if(addNum) //要结算
        {
			$currentAward = ceil(pow($userData->pk_common->map->level,1.2));
			$maxAward = $currentAward *100;
		
			$userData->pk_common->map->pk_value += addNum;
			$userData->pk_common->map->bag += addNum*$currentAward;
			$userData->pk_common->map->lasttime += addNum * $cd;
			
			if($userData->pk_common->map->bag > $maxAward)
                $userData->pk_common->map->bag > $maxAward;
			return true;
        }
		return false;
	 }
		
?> 