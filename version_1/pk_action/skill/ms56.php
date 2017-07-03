<?php 
	

	//技：沉默：对方无法使用绝招，round5
	class sm_56_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			
			$buff = new StatBuff(22,5);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//衰弱:减对方辅助30%攻击力
	class sm_56_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addAtk($user,$player,-$player->base_atk * 0.3);
			}
		}
	}
	
	//灵弹：+80%伤害,cd3
	class sm_56_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}

	
	//辅：--沉默：对方无法使用绝招，round3,cd5
	class sm_56_f1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new StatBuff(22,4);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}	
	//辅：--响应：当已方使用绝招时，追加一次150%的输出
	class sm_56_f2 extends SkillBase{
		public $type = 'SKILL';
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//辅：--70%伤害
	class sm_56_f3 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 