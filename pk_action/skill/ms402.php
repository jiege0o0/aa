<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：180%伤害，-30%当前血量
	class sm_402_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.3,false,false,true);
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	// -15%当前血量，+30%盾，CD3，round2,110%伤害
	class sm_402_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.15,false,false,true);
			$this->decHp($user,$enemy,$user->atk*1.1);
			$v = $this->addDef($user,$self,30);
			$self->addState($user,array('def',$v),2);
		}
	}
	
	//特：当HP少于对方时，攻击+30%
	class sm_402_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat3 ++;
		}
	}
	
	//特：亡语，下一单位攻击+30%
	class sm_402_3 extends SkillBase{
		public $type='DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_402_ds3');
		}
	}
	class sm_402_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.3);
		}
	}
	
	//辅：+12%攻击
	class sm_402_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.12);
		}
	}

?> 