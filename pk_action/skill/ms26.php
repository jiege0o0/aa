<?php 
	
	
	//技：魅惑：对方的辅助单位变成已方，2round
	class sm_26_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(25,2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//心乱：攻击-30%，2round,cd4
	class sm_26_1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('atk'=>-round($enemy->base_atk * 0.3)),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}
	
	//勾魂：进场对方MP上限+20
	class sm_26_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->maxMp += 20;
		}
	}
	
	//辅：-- 80%伤害
	class sm_26_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	
	//辅：--魅惑：对方的辅助单位变成已方，1round，cd4
	class sm_26_f2 extends SkillBase{
		public $cd = 4;
		public $order = 1;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(25,1);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}

?> 