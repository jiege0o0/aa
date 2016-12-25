<?php 
	
	
	//技：舍命一击：生命降到1，对对方造成损失血量*2的伤害
	class sm_35_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,($user->hp - 1)*2.5);
			$this->decHp($user,$user,$user->hp-1,false,true);
		}
	}
	
	//荼毒：使对方中毒，减血，round1,cd1ATK*0.5
	class sm_35_1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){	
			$this->decHp($user,$enemy,$user->atk*1);		
			$buff = new HPBuff(-$user->atk*0.8,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//暴击：+30%伤害,cd2
	class sm_35_2 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			
			$buff = new HPBuff(-$user->atk*0.8,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//疗伤：进入时回50%血
	class sm_35_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.5);
		}
	}
	
	//辅：--荼毒：60%伤害,使对方中毒，减血，round1,cd1  ATK*0.4
	class sm_35_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
			
			$buff = new HPBuff(-$user->atk*0.4,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}	


?> 