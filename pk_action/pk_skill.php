<?php
	require_once($filePath."pk_action/skill_value_fun.php");
	require_once($filePath."pk_action/skill_action_fun.php");
	//�������� SKILL1(�չ�),SKILL2��С����,SKILL3���󼼣�,SKILL4�����У�,BEATK,DIE,BEHEAL,HEAL,SHEAL(˫�������Ʋ���)HP,BEFORE,AFTER,STAT
	$Skill_SAT = array(	//������д
			"HP"=>'1',
			"SPD"=>'2',
			"ATK"=>'3',
			"MHP"=>'4',
			"MP"=>'5',
			"MV"=>'6'//���ֻ��֪ͨ������
			
			
	);

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
	
	//���ؼ�������
	function pk_requireSkill($monsterID){
		global $filePath;
		require_once($filePath."pk_action/skill/ms".$monsterID.".php");
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
			$user->addMp(-$PKConfig->skillMP);
			$pkData->addSkillMV($user,$user,pk_skillType('MP',-$PKConfig->skillMP));
		}
		else if(!$skillData->type && $user->isPKing && $skillData->cd > 0 && $user->addMP)//ʹ��С��ֻ���Լ�������
		{
			$user->addMP = false;//����ֻ���һ������
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
	
	//���չ�����ֵ�ĵ���
	function pk_atkHP($play1,$play2,$hp){
		$hp = $play1->changeByHurt($hp,$play2);
		$hp = $play2->changeByDef($hp,$play1);
		//����Ӱ��
		return max(1,$hp);
	}
	
	//����������ֵ�ĵ���
	function pk_healHP($play1,$play2,$hp){
		if($play2->healAdd)
			return max(1,round($hp*(1+$play2->healAdd/100)));
		return max(1,$hp);
	}
			
	//��ͨ����
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
	
	//�غϽ���Ѫ��Ӱ��
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