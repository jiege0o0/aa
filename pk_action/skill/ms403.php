<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：当生命>50%,200%伤害，else 150%回血
	class sm_403_0 extends SkillBase{
		function action($user,$self,$enemy){
			if($self->getHpRate() > 0.5)
				$this->decHp($user,$enemy,$user->atk*2);
			else
				$this->addHp($user,$self,$user->atk*1.5);
		}
	}
	
	// 小技：150%伤害，CD3
	class sm_403_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//特：当HP大于70%，攻击+30%
	class sm_403_2 extends SkillBase{
		public $type = 'BEFORE';
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() > 0.7;
		}
		function action($user,$self,$enemy){
			$v1 = $this->addAtk($user,$self,$self->base_atk*0.3);
			$user->addState($user,array('atk'=>$v1),1);
		}
	}
	
	//辅：+10%攻击
	class sm_403_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
		}
	}	
	//辅：40%伤害，CD3
	class sm_403_f2 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.4);
		}
	}

?> 