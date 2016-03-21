<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：清所有负面状态，回100%伤害血
	class sm_305_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1);
			$this->cleanStat($self,$enemy->teamID,999);
		}
	}
	
	//小技：120%，清对方一个正面状态，CD3
	class sm_305_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
			$this->cleanStat($enemy,$enemy->teamID,1);
		}
	}
	
	//特：-30%当前生命(默认最大生命会多一点)
	class sm_305_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.3,false,false,true);
		}
	}
	
	//辅：回50%伤害血，并清一个负面状态，cd3
	class sm_305_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*0.5);
			$this->cleanStat($self,$enemy->teamID,1);
		}
	}


?> 