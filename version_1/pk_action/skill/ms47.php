<?php 
	

	//技：砍击：180%伤害
	class sm_47_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//简单包扎：回20%血，cd4
	class sm_47_1 extends SkillBase{
		public $cd = 4;
		public $isSendAtOnce = true;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->maxHp*0.3);
		}
	}
	
	//合作。有人类士兵同时出战，攻+10%，血上限+20%
	class sm_47_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$num = $user->team->monsterBase->{$user->monsterID}->num;	
			$this->addAtk($user,$user,$user->base_atk*0.1*$num);
			$this->addHp($user,$self,$user->base_hp*0.2*$num,true);
		}
	}
	
	
	//辅：--60%伤害
	class sm_47_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.9);
		}
	}	
	//辅：--简单包扎：回atk*0.7血，cd3
	class sm_47_f2 extends SkillBase{
		public $cd = 3;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk);
		}
	}
	
	class sm_47_f3 extends SkillBase{
		public $cd = 0;
		public $order = 20;
		function action($user,$self,$enemy){
			$this->setStat31($user,$user);
		}
	}

?> 