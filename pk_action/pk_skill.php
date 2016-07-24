<?php
	require_once($filePath."pk_action/skill_value_fun.php");
	require_once($filePath."pk_action/skill_action_fun.php");
	require_once($filePath."pk_action/skill/common_skill.php");
	//�������� SKILL1(�չ�),SKILL2��С����,SKILL3���󼼣�,SKILL4�����У�,DIE,BEHEAL,HEAL,SHEAL(˫�������Ʋ���)HP,BEFORE,AFTER,STAT,
	//ATK(�ԶԷ�����˺�)��BEATK(���Է�����˺�),
	$Skill_SAT = array(	//������д
			"HP"=>'1',
			"SPD"=>'2',
			"ATK"=>'3',
			"MHP"=>'4',
			"MP"=>'5',
			"MV"=>'6',//���ֻ��֪ͨ������
			"MISS"=>'7',//Ŀ������
			"NOHURT"=>'8'//Ŀ������
			
			
	);
	$skillPool = array();

	function pk_skillType($type,$v){
		global $Skill_SAT;
		if($Skill_SAT[$type])
			return $Skill_SAT[$type].$v;
		return false;
	}
	
	//����ļ��ܶ���
	function pk_monsterSkill($monsterID,$index){
		$skillName = 'sm_'.$monsterID.'_'.$index;
		return pk_decodeSkill($skillName);
	}
	
	//ͨ������ID�õ�����ID
	function pk_skillMonster($skillID){
		$arr = split('_',$skillID);
		if($arr[0] == 'sm')
		{
			return $arr[1];
		}
		return null;
	}
	
	//���ݼ���IDʵ��������
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
	
	//���ؼ�������
	function pk_requireSkill($monsterID){
		global $filePath;
		require_once($filePath."pk_action/skill/ms".$monsterID.".php");
	}
	
	//�Ѽ��ܷŻ�ȥ���ѱ�����
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
			throw new Exception($name.$skill->owner->monsterID);
			return;
		}
			
		array_push($skillPool[$name],$skill);
	}
	
	
	//ִ�м���ʹ��
	function pk_action_skill($skillData,$user,$self,$enemy){
		global $pkData,$skillActionFun,$PKConfig;
		
		if($skillData->disabled)
			return false;
			
		if(!$skillData->canUse($user,$self,$enemy))
			return false;
			
		$pkData->startSkillMV($user);
		
		if($skillData->isMain)//ʹ�ô��������
		{
			$mp = $user->mp;
			$user->addMp(-$mp);
			$pkData->addSkillMV($user,$user,pk_skillType('MP',-$mp));
		}
		else if(!$skillData->type && $user->isPKing && $skillData->cd > 0 && $user->addMP)//ʹ��С��ֻ���Լ�������
		{
			$user->addMP = false;//����ֻ���һ������
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

	
	//��ɱ��
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