<?php 
	

	//技：龙斩：+200%伤害
	class sm_27_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.8);
		}
	}
	
	//疾风斩：+50%伤害，cd3
	class sm_27_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			$this->addAtk($user,$user,$user->base_atk*0.05);
		}
	}
	
	//力量封印：攻击-50%
	class sm_27_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,-$user->base_atk*0.5);
		}
	}
	
	//封印解除：当生命少于40%时，封印解除
	class sm_27_3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//技能只执行一次
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate()<=0.5;
		}
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,$user->base_atk*0.5);
			$user->addStat(11,-1);
			$user->addStat(1,-1);
		}
	}
	
	//辅：--50%伤害
	class sm_27_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//辅：--力量封印：攻击-50%
	class sm_27_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,-$user->base_atk*0.5);
		}
	}
	
	//辅：封印解除：当场上生命少于30%时，自己封印解除
	class sm_27_f3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//技能只执行一次
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate()<=0.5;
		}
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,$user->base_atk*0.5);
			$user->addStat(11,-1);
			$user->addStat(1,-1);
		}
	}

?> 