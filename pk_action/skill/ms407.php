<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//减对方10%攻击，增加自己相应攻击
	class sm_407_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->addAtk($user,$enemy,-$enemy->atk*0.1);
			$this->addAtk($user,$user,$v);
		}
	}
	
	//小技：20%伤害，CD3
	class sm_407_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//特：进场-20%生命，增加50点能量
	class sm_407_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,-$self->hp*0.2,false,false,true);
			$this->addMp($user,$self,50);
		}
	}
	
	//辅：减对方10%攻击，CD5
	class sm_407_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$this->addAtk($user,$enemy,-$enemy->atk*0.1);
		}
	}
	
	//辅：30%伤害，CD2
	class sm_407_f2 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.3);
		}
	}


?> 