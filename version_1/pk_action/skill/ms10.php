<?php 
	

	//技：石化（技）：对手停2回合
	class sm_10_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new StatBuff(24,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
				
		}
	}
	
	//死亡射线：伤+50%，cd3
	class sm_10_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.3);
		}
	}
	
	//自愈：行动后回复5%血量
	class sm_10_2 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.50);
		}
	}
	
	
	//辅：石化（技）：停1回合，cd5
	class sm_10_f1 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}	
	//辅：--60%伤害
	class sm_10_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}
	//辅：--进入时回复20%生命
	class sm_10_f3 extends SkillBase{
		public $cd = 0;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}

?> 