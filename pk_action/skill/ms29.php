<?php 
	

	//������ն��-150%�˺��������˺���2round        6%����
	class sm_29_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			
			$buff = new HPBuff(-$enemy->maxHp*0.15,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//������+70%�˺�,cd3
	class sm_29_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1 + $enemy->maxHp*0.08);
		}
	}
	
	//���޳����Է��ж���-3%����
	class sm_29_2 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.08);
		}
	}
	
	//����--70%��
	class sm_29_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//����--���ף�50%�˺���������Ѫ��round2,cd5    3%����
	class sm_29_f2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$enemy->maxHp*0.08,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 