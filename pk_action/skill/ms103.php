<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：攻击+10%，速度 + 10%，120%伤害
	class sm_103_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$user->base_atk*0.1);
			$this->addSpeed($user,$self,$user->base_speed*0.1);
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	// 小技：130%伤害，CD3
	class sm_103_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	//特：行动后，如果当生命小于30%时，伤害+100%，速度+100%，3回合,只触发一次  
	class sm_103_2 extends SkillBase{
		public $type = 'AFTER';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <0.3;
		}
		function action($user,$self,$enemy){
			$v1 = $this->addAtk($user,$self,$user->base_atk*1);
			$v2 = $this->addSpeed($user,$self,$user->base_speed*1);
			$user->addState($user,array('atk'=>$v1,'speed'=>$v2),3);
		}
	}
	
	//辅：增加10%的速度
	class sm_103_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$self,$self->base_speed*0.1);
		}
	}

?> 