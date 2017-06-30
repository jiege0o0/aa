<?php 
	

	//技：猛击：+180%伤害，带腐蚀-20%甲，3round
	class sm_12_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			
			$buff = new ValueBuff('def',-20,3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//重击 +40%伤，cd3
	class sm_12_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
			$this->addDef($user,$enemy,-5);
		}
	}
	
	//坚守：进入是+30%盾，round3
	class sm_12_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',30,3);
			$buff->addToTarget($user,$self);
		}
	}
	
	
	
	//辅：--50%伤
	class sm_12_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.9);
		}
	}	
	//辅：--60%伤 + 2round带腐蚀-20%甲，cd5
	class sm_12_f2 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
			$buff = new ValueBuff('def',-20,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}

?> 