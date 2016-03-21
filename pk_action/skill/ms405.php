<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：造成220%伤害
	class sm_405_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	//小技：110%伤害，CD3
	class sm_405_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.1);
		}
	}
	
	//特：HP<50%时，防+30%，伤-30%
	class sm_405_2 extends SkillBase{
		public $type = 'HP';
		public $temp = false;
		function action($user,$self,$enemy){
			if(!$temp && $self->getHpRate() < 0.5)
			{
				$this->addDef($user,$self,30);
				$this->addHurt($user,$self,-30);
				$temp = true;
			}
			else if($temp && $self->getHpRate() >= 0.5)
			{
				$this->addDef($user,$self,-30);
				$this->addHurt($user,$self,30);
				$temp = false;
			}
		}
	}

	
	//辅：速度+10%，攻击-10%
	class sm_405_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$self,$self->base_speed*0.1);
			$this->addAtk($user,$self,-$self->base_atk*0.1);
		}
	}
	//辅：30%伤害，CD2
	class sm_405_f2 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.3);
		}
	}


?> 