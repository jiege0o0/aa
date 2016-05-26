<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：增加自血上限10%
	class sm_507_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp*0.1,true);
		}
	}
	
	//小技：150%伤害，CD3
	class sm_507_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//特：死亡加血上限（自身20%）
	class sm_507_2 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_507_ds2#'.(round($user->maxHp*0.2)));
		}
	}
	class sm_507_ds2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$this->tData,true);
		}
	}
	
	//辅：对方攻击-20%
	class sm_507_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$enemy,-$enemy->base_atk*0.2);
		}
	}
	
	

?> 