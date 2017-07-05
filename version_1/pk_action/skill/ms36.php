<?php 
	
	class sm_36_base extends SkillBase{
		function sm_36_addValue($self,$enemy,$rate){
			$v1 = max(1,round($enemy->base_atk * $rate));
			$v2 = max(1,round($enemy->base_speed * $rate));
			$this->addAtk($self,$enemy,-$v1);
			$this->addSpeed($self,$enemy,-$v2);
			$this->addAtk($self,$self,$v1);
			$this->addSpeed($self,$self,$v2);
		}
	}
	
	// function sm_36_addValue($self,$enemy,$rate,$thisObj){
		// $v1 = max(1,round($enemy->base_atk * $rate));
		// $v2 = max(1,round($enemy->base_speed * $rate));
		// $thisObj->addAtk($self,$enemy,-$v1);
		// $thisObj->addSpeed($self,$enemy,-$v2);
		// $thisObj->addAtk($self,$self,$v1);
		// $thisObj->addSpeed($self,$self,$v2);
	// }

	//技：重劈（技）：220%伤
	class sm_36_0 extends sm_36_base{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->sm_36_addValue($user,$enemy,0.15);
			$this->decHp($user,$enemy,$user->atk*2.5);
			
		}
	}
	
	//暴击：+30%伤害
	class sm_36_1 extends sm_36_base{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->sm_36_addValue($user,$enemy,0.1);
			$this->decHp($user,$enemy,$user->atk*1.8);
			
		}
	}
	
	//摄取：每次减速减攻，加到自己身上 3%
	class sm_36_2 extends sm_36_base{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->sm_36_addValue($user,$enemy,0.06);
			$this->decHp($user,$enemy,$user->atk);
		}
	}

	
	//辅：--摄取：每次减速减攻，加到自己身上  2%
	class sm_36_f2 extends sm_36_base{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->sm_36_addValue($user,$enemy,0.05);
			$this->decHp($user,$enemy,$user->atk*0.8);
			
		}
	}	
	//辅：--50%伤害

?> 