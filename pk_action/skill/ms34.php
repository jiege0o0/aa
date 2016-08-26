<?php 
	

	//技：连击(技)：100%伤害 + 10%最大生命
	class sm_34_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk + $enemy->maxHp*0.1,true);
		}
	}
	
	//浴火重生：死后-对方10%血，复活10%血
	class sm_34_1 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->hp<=0;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.1);
			$user->reborn(0.1);
		}
	}
	
	//灼烧：100%伤害，最大生命伤害
	class sm_34_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk,true);
		}
	}
	
	
	//辅：--灼烧：50%伤害，最大生命伤害
	class sm_34_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5,true);
		}
	}	
?> 