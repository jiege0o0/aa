<?php 
	

	function sm_43_resetHurt($self,$v){
		return round((2-$self->getHpRate()*1)*$v);
	}
	
	
	//技：血的代价：自己生命-20%，造成200%伤害
	class sm_43_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$user,$user->hp*0.25);
			$this->decHp($user,$enemy,sm_43_resetHurt($user,$user->atk*2));
			
		}
	}
	
	//冲锋：进场时+20%盾，round3，-自己20%血，对对方造成60%伤害;
	class sm_43_1 extends SkillBase{
		public $cd = 0;
		public $isAtk = true;
		public $order = -10;
		function action($user,$self,$enemy){
			$this->decHp($user,$user,$user->hp*0.3,false,true);
			$this->decHp($user,$enemy,sm_43_resetHurt($user,$user->atk*1));
			
			$buff = new ValueBuff('def',20,3);
			$buff->addToTarget($user,$self);
		}
	}
	
	//血的味道：自己生命越少，伤害越大
	class sm_43_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,sm_43_resetHurt($user,$user->atk));
		}
	}
	
	
	//辅：--+10%速
	class sm_43_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$self,$self->base_speed*0.1);

		}
	}	
	//辅：--50%伤害
	class sm_43_f3 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,sm_43_resetHurt($self,$user->atk*0.8));
		}
	}
	//辅：--血的味道：自己场上单位生命越少，伤害越大
	// class sm_43_f2 extends SkillBase{
		// public $cd = 1;
		// function action($user,$self,$enemy){
			// $this->decHp($user,$enemy,$user->atk*0.5);
			// $this->cleanStat($enemy,-1,1);
		// }
	// }

?> 