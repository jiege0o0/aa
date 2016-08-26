<?php 
	

	//技：幻境：对方的辅助单位无法行动，2round
	class sm_14_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//火拳：+50%伤害，cd2
	class sm_14_1 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//烈火考验：双方出手后，要-自身攻击*0.4生命
	class sm_14_2 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$user->atk*0.4);
		}
	}
	class sm_14_5 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->atk*0.4);
		}
	}
	
	
	//辅：--烈火考验：双方出手后，要-自身攻击*0.4生命
	class sm_14_f1 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$user->atk*0.4);
		}
	}
	class sm_14_f5 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->atk*0.4);
		}
	}
	
	//辅：--70%伤害
	class sm_14_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
		}
	}

?> 