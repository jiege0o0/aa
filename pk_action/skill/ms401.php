<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：150%伤害，-10%攻击
	class sm_401_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			$this->addAtk($user,$enemy,-$enemy->base_atk*0.1);
		}
	}
	
	//小技：+10%攻击，CD3
	class sm_401_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
		}
	}
	
	//特：回合进入时，HP35%以下时，攻击+20%
	class sm_401_2 extends SkillBase{
		public $type = 'BEFORE';
		function canUse($user,$self=null,$enemy=null){
			if($user->getHpRate() < 0.35)
				return true;
			return false;
		}
		function action($user,$self,$enemy){
			$value = $this->addAtk($user,$self,$self->base_atk*0.2);
			$self->addState($user,array('atk'=>$value),1);
		}
	}
	
	//辅：+10%攻击，CD5
	class sm_401_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
		}
	}

?> 