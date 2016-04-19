<?php 
	require_once($filePath."pk_action/skill/skill_base.php");


	//技：150%伤害，禁技round3
	class sm_301_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			$enemy->action3 ++;
			$enemy->addState($user,array('action3'=>1),3);
		}
	}
	
	//小技：70%伤害，-10能量，CD3
	class sm_301_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
			$this->addMp($user,$enemy,-10);
		}
	}
	
	//特：出场时获得魔免，5回合
	class sm_301_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat1 ++;
			$self->addState($user,array('stat1'=>1),5);
		}
	}
	
	//辅：禁技 round3，CD5
	class sm_301_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$enemy->action3 ++;
			$enemy->addState($user,array('action3'=>1),3);
		}
	}	
	
	//辅：50%伤害，CD2
	class sm_301_f2 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 