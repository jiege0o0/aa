<?php 
	require_once($filePath."pk_action/skill/skill_base.php");


	//技：燃烧状态，对方每次行动-10%生命，round3
	class sm_205_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->addcdhp($user,$enemy,-0.1*$enemy->maxHp);
			$enemy->addState($user,array('cdhp'=>$v),3);
		}
	}
	
	//小技：120%伤害
	class sm_205_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//特：对方特性无效，round10
	class sm_205_2 extends SkillBase{
		public $cd=0;
		function action($user,$self,$enemy){
			$enemy->action4++;
			$enemy->addState($user,array('action4'=>1),10);
			$this->setSkillEffect($enemy);
		}
	}
	
	//辅：最大HP-10%，cd3
	class sm_205_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.1,true);
		}
	}


?> 