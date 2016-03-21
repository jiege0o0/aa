<?php
	require_once($filePath."pk_action/skill_value_fun.php");
	require_once($filePath."pk_action/skill_action_fun.php");
	//特性类型 SKILL1(普攻),SKILL2（小技）,SKILL3（大技）,SKILL4（所有）,BEATK,DIE,BEHEAL,HEAL,SHEAL(双方有治疗产生)HP,BEFORE,AFTER,STAT
	$Skill_SAT = array(	//技能缩写
			"HP"=>'1',
			"SPD"=>'2',
			"ATK"=>'3',
			"MHP"=>'4',
			"MP"=>'5',
			"MV"=>'6'//这个只是通知播动画
			
			
	);

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
		$arr = split('#',$skillClass);
		if(!class_exists($arr[0]))
			return null;
		$refl = new ReflectionClass($arr[0]);
		$vo = $refl->newInstance();
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
			$user->addMp(-$PKConfig->skillMP);
			$pkData->addSkillMV($user,$user,pk_skillType('MP',-$PKConfig->skillMP));
		}
		else if(!$skillData->type && $user->isPKing && $skillData->cd > 0 && $user->addMP)//使用小技只是自己加能量
		{
			$user->addMP = false;//本轮只会加一次能量
			$user->addMp($PKConfig->atkMP);
			$pkData->addSkillMV($user,$user,pk_skillType('MP',$PKConfig->skillMP));
		}	
		
		$b = $skillData->action($user,$self,$enemy);	
		

		$pkData->endSkillMV($skillData->index);		
			
		if(!$skillData->type)
			$user->setSkillUse($skillData->index);
			
		if($skillData->once)
			$skillData->disabled = true;
			
		return true;	
	}
	
	//最终攻击数值的调整
	function pk_atkHP($play1,$play2,$hp){
		$hp = $play1->changeByHurt($hp,$play2);
		$hp = $play2->changeByDef($hp,$play1);
		//特性影响
		return max(1,$hp);
	}
	
	//最终治疗数值的调整
	function pk_healHP($play1,$play2,$hp){
		if($play2->healAdd)
			return max(1,round($hp*(1+$play2->healAdd/100)));
		return max(1,$hp);
	}
			
	//普通攻击
	function pk_atk($play1,$play2)
	{
		global $pkData,$PKConfig;
		$hp = $play1->atk;
		$hp = -pk_atkHP($play1,$play2,$hp);
		$play2->addHp($hp);
		$play1->addMp($PKConfig->atkMP);
		$play2->addMp($PKConfig->defMP);
		
		$pkData->startSkillMV($play1);
		$pkData->addSkillMV($play1,$play2,pk_skillType('HP',$hp));	
		$pkData->addSkillMV(null,$play2,pk_skillType('MP',$PKConfig->defMP));	
		$pkData->addSkillMV(null,$play1,pk_skillType('MP',$PKConfig->atkMP));	
		$pkData->endSkillMV(50);		
		
		
		if($play2->hp > 0)
			$play2->testTSkill('BEATK',$hp);
			
		$play1->testStat2(-$hp);
		
		$play1->setRoundEffect();
		$play2->setRoundEffect();
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
	
	//回合结束血的影响
	function pk_cdhp($play1)
	{
		global $pkData;
		$hp = $play1->cdhp;
		$play1->hp += $hp;
		
		$pkData->startSkillMV(null);
		$pkData->addSkillMV(null,$play1,pk_skillType('HP',$hp));	
		$pkData->endSkillMV(52);	
		
		$play1->setRoundEffect();
	}
?> 