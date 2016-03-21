<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：双刃剑，对方造成300%，已方250%真实伤害
	class sm_508_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*3,false,false,true);
			$this->decHp($user,$self,$user->atk*2.5,false,false,true);
		}
	}
	
	//小技：80%HURT，晕round1，CD3
	class sm_508_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
			$enemy->action5++;
			$enemy->addState($user,array('action5'=>1),1);
		}
	}
	
	//特：进场增加HP，等于辅助单位的20%
	class sm_508_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$add = 0;
			if($user->team->teamInfo['mhps'])
				$add = $user->team->teamInfo['mhps'];
			if($add)	
				$this->addHp($user,$self,$add*0.2,true);
		}
	}
	
	//辅：降已方20%生命上限，进场+35能量
	class sm_508_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->maxHp*0.2,true);
			$this->addMp($user,$self,35);
		}
	}
?> 