<?php 
	

	//技：猛击：+180%伤害，带腐蚀-20%甲，3round
	class sm_12_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
			
			$buff = new ValueBuff('def',-20,3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//重击 +40%伤，cd3
	class sm_12_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.4);
		}
	}
	
	//坚守：进入是+30%盾，round3
	class sm_12_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',30,3);
			$buff->addToTarget($self);
		}
	}
	
	
	
	//辅：--50%伤
	class sm_12_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//辅：--60%伤 + 2round带腐蚀-20%甲，cd5
	class sm_12_f2 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
			$buff = new ValueBuff('def',-20,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}

?> 