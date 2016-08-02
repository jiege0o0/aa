<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//������ն��-50%�˺��������˺���2round        5%����
	class sm_29_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			
			$buff = new HPBuff(-$enemy->maxHp*0.05,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//������+40%�˺�
	class sm_29_1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.4);
		}
	}
	
	//���޳����Է��ж���-2%����
	class sm_29_2 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.02);
		}
	}
	
	//����--50%��
	class sm_29_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//����--���ף��˺���������Ѫ��round2,cd4    3%����
	class sm_29_f2 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$enemy->maxHp*0.03,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 