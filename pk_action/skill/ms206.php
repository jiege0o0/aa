<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	

	//技：200%伤害
	class sm_206_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//小技：损自己30%当前生命，回复120%血，cd3
	class sm_206_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$user->hp*0.3,false,false,true);
			$this->addHp($user,$self,$self->atk*1.2);
		}
	}
	
	//特：双方回血时，攻+5%回血量
	class sm_206_2 extends SkillBase{
		public $type = 'SHEAL';
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,$this->tData*0.5);
		}
	}
	
	
	//辅：30%伤害，cd1
	class sm_206_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.3);
		}
	}

?> 