<?php 
	

	//技：自然之甲（技）：为自己抵挡3次伤害，回10%血
	class sm_50_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.3);
			$self->missTimes += 3;
		}
	}
	
	//自然之力：行动结束后回复自己5%血
	class sm_50_1 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.15);
		}
	}
	
	//魔免
	class sm_50_2 extends SkillBase{
		public $cd = 0;
		public $order = 20;
		function action($user,$self,$enemy){
			$this->setStat31($user);
		}
	}
	
	
	//辅：--回复2% + atk*0.5血
	class sm_50_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.05 + $user->atk*0.8);
		}
	}	
	//辅：--自然之甲：抵挡2次伤害，cd5
	class sm_50_f2 extends SkillBase{
		public $cd = 4;
		public $order = 1;
		function action($user,$self,$enemy){
			$self->missTimes += 2;
		}
	}

?> 