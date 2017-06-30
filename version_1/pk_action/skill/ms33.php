<?php 
	

	//技：连击(技)：220%伤害
	class sm_33_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
			
			$buff = new ValueBuff('def',-3,3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//反击：被攻击时对对方造成60%伤害
	class sm_33_1 extends SkillBase{
		public $type = 'BEATK';
		function canUse($user,$self=null,$enemy=null){
			return $this->tData[0]->isPKing;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			
			$buff = new ValueBuff('def',-3,3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//腐蚀：每次攻击-3%防，round3,可叠加
	class sm_33_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			
			$buff = new ValueBuff('def',-3,3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//辅：--腐蚀：50%伤害，每次攻击-3%防，round3,可叠加
	class sm_33_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
			
			$buff = new ValueBuff('def',-3,3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}	

?> 