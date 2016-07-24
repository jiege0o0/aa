<?php
	require_once($filePath."pk_action/skill_value_fun.php");
	require_once($filePath."pk_action/skill_action_fun.php");
	require_once($filePath."pk_action/skill/common_skill.php");
	//特性类型 SKILL1(普攻),SKILL2（小技）,SKILL3（大技）,SKILL4（所有）,DIE,BEHEAL,HEAL,SHEAL(双方有治疗产生)HP,BEFORE,AFTER,STAT,
	//ATK(对对方造成伤害)，BEATK(被对方造成伤害),
	$Skill_SAT = array(	//技能缩写
			"HP"=>'1',
			"SPD"=>'2',
			"ATK"=>'3',
			"MHP"=>'4',
			"MP"=>'5',
			"MV"=>'6',//这个只是通知播动画
			"MISS"=>'7',//目标闪开
			"NOHURT"=>'8'//目标免伤
			
			
	);
	$skillPool = array();

	function pk_skillType($type,$v){
		global $Skill_SAT;
		if($Skill_SAT[$type])
			return $Skill_SAT[$type].$v;
		return false;
	}
	
	//怪物的技能对象
	function pk_monsterSkill($monsterID,$index){
		$skillName = 'sm_'.$monsterID.'_'.$index;
		return pk_decodeSkill($skillName);
	}
	
	//通过技能ID得到怪物ID
	function pk_skillMonster($skillID){
		$arr = split('_',$skillID);
		if($arr[0] == 'sm')
		{
			return $arr[1];
		}
		return null;
	}
	
	//根据技能ID实例化技能
	function pk_decodeSkill($skillClass){
		// trace($skillClass);
		$arr = split('#',$skillClass);
		if(!class_exists($arr[0]))
			return null;
		global $skillPool; 
		if($skillPool[$skillClass] && count($skillPool[$skillClass])>0)
		{
			$vo = array_pop($skillPool[$skillClass]);
			$vo->reInit();
			// trace('use--'.$skillClass);
		}
		else
		{
			$refl = new ReflectionClass($arr[0]);
			$vo = $refl->newInstance();
			// trace('new--'.$skillClass);
		}
		
		if($arr[1])
		{
			$vo->tData = (int)$arr[1];
		}
		return $vo;
	}
	
	//加载技能数据
	function pk_requireSkill($monsterID){
		global $filePath;
		require_once($filePath."pk_action/skill/ms".$monsterID.".php");
	}
	
	//把技能放回去，已备重用
	function pk_freeSkill($skill){
		if(!$skill)
			return;
		$name = $skill->name;
		if(!$name)
			return;
		global $skillPool; 
		if(!$skillPool[$name])
			$skillPool[$name] = array();
		// trace($name.'--'.count($skillPool[$name]));
		if(in_array($skill, $skillPool[$name],true))
		{
			throw new Exception("xxxxxx!".$skill->owner->id);
			return;
		}
			
		array_push($skillPool[$name],$skill);
	}
	
	
	//执行技能使用
	function pk_action_skill($skillData,$user,$self,$enemy){
		global $pkData,$skillActionFun,$PKConfig;
		
		if($skillData->disabled)
			return false;
			
		if(!$skillData->canUse($user,$self,$enemy))
			return false;
			
		$pkData->startSkillMV($user);
		
		if($skillData->isMain)//使用大绝扣能量
		{
			$mp = $user->mp;
			$user->addMp(-$mp);
			$pkData->addSkillMV($user,$user,pk_skillType('MP',-$mp));
		}
		else if(!$skillData->type && $user->isPKing && $skillData->cd > 0 && $user->addMP)//使用小技只是自己加能量
		{
			$user->addMP = false;//本轮只会加一次能量
			$user->addMp($PKConfig->atkMP);
			$pkData->addSkillMV($user,$user,pk_skillType('MP',$PKConfig->atkMP));
		}	
		
		$pkData->startSkillEffect();
		$b = $skillData->actionSkill($user,$self,$enemy);	
		

		$pkData->endSkillMV($skillData->index);		
			
		if(!$skillData->type)
			$user->setSkillUse($skillData->index);
			
		if($skillData->once)
			$skillData->disabled = true;
			
		return true;	
	}

	
	//秒杀技
	function pk_kill($play1,$play2)
	{
		global $pkData;
		$hp = -$play2->hp;
		$play2->hp += $hp;
		
		$pkData->startSkillMV($play1);
		$pkData->addSkillMV($play1,$play2,pk_skillType('HP',$hp));	
		$pkData->endSkillMV(51);	
				
		$play1->setRoundEffect();
		$play2->setRoundEffect();
	}
?> 