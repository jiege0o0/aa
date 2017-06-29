<?php 
	

	//技：死亡一指：600%伤害
	class sm_64_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*5);
		}
	}
	
	//蓄力：+10MP
	class sm_64_1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}
	
	
	
	//辅：--回蓝：+8MP
	class sm_64_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}	
	//辅：--进场时加20点MP
	class sm_64_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}

?> 