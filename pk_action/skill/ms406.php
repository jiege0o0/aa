<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：造成220%伤害
	class sm_406_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	//小技：CD3，200%伤害，+10能量
	class sm_406_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			$this->addMp($user,$self,10);
		}
	}
	
	//特：HP<50%时，攻击+30%
	class sm_406_1 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function action($user,$self,$enemy){
			$this->decAtk($user,$self,$self->base_atk*0.3);
		}
	}
	
	
	//辅：+10能量，CD3
	class sm_406_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}

?> 