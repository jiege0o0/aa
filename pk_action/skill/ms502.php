<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：回复 220% 攻血量 
	class sm_502_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*2.2);
		}
	}
	
	// 小技：140%伤害，CD3
	class sm_502_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.4);
		}
	}
	
	//特：当受到伤害时，对对方造后50%反弹
	class sm_502_2 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$tData*0.5);
		}
	}
	
	//特：进场增加HP，等于辅助单位的10%
	class sm_502_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$add = 0;
			if($user->team->teamInfo['mhps'])
				$add = $user->team->teamInfo['mhps']* 0.1;
			$this->addHp($user,$self,$add,true);
		}
	}
	
	//辅：160%伤害，CD5 
	class sm_502_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}


?> 