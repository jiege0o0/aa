<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//¼¼£ºÁúÕ¶£º-50%ÉËº¦£¬³ÖÐøÉËº¦£¬2round        5%ÉúÃü
	class sm_29_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			
			$buff = new HPBuff(-$enemy->maxHp*0.05,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//±©»÷£º+40%ÉËº¦
	class sm_29_1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.4);
		}
	}
	
	//ÐÞÂÞ³¡£º¶Ô·½ÐÐ¶¯ºó£¬-2%ÉúÃü
	class sm_29_2 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.02);
		}
	}
	
	//¸¨£º--50%ÉË
	class sm_29_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//¸¨£º--ÁúÑ×£¬ÉËº¦²¢³ÖÐø¿ÛÑª£¬round2,cd4    3%ÉúÃü
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