<?php 
	

	//技：降临未日：1000%伤害
	class sm_41_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*10);
		}
	}
	
	//同归：死亡后造成100%伤害
	class sm_41_1 extends SkillBase{
		public $type='DIE';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
		}
	}
	
	//折射：对方受到自己伤害的20%
	class sm_41_2 extends SkillBase{
		public $type='BEHURT';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,-$this->tData[1]*0.2);
		}
	}
	
	//封印：特性无效
	class sm_41_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			if(!$enemy->stat[23])
				$enemy->stat[23] = 1;
			else
				$enemy->stat[23] ++;
		}
	}
	
	//辅：--封印：特性无效
	class sm_41_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			if(!$enemy->stat[23])
				$enemy->stat[23] = 1;
			else
				$enemy->stat[23] ++;
		}
	}	
	//辅：--60%伤害,cd2
	class sm_41_f2 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}

?> 