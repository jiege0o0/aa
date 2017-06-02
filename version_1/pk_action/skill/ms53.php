<?php 
	

	//技：大爆炸（技）：造成1000%伤害
	class sm_53_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*10);
		}
	}
	
	//复活：死后回复50%的血
	class sm_53_1 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->hp<=0;
		}
		function action($user,$self,$enemy){
			$user->reborn(0.4);
			$this->addMp($user,$self,40);
		}
	}
	
	//精血流失：被攻击时，mp-5
	class sm_53_2 extends SkillBase{
		public $type='BEATK';
		function action($user,$self,$enemy){
			$this->addMp($user,$self,-15);
		}
	}
	
	//精血流失：被攻击时，mp-5
	class sm_53_3 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			$this->addMp($user,$self,5);
			$this->addMp($user,$enemy,-5);
		}
	}
	
	
	//辅：--50%伤害
	class sm_53_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//辅：--复活，20%血，1次
	class sm_53_f2 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->hp<=0;
		}
		function action($user,$self,$enemy){
			$self->reborn(0.2);
		}
	}

?> 