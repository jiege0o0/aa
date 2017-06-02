<?php 
	
	
	//技：心灵控制(技)：所有单位禁固1回合
	class sm_3_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,1);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
		}
	}
	
	//第3次攻击同时为自己回复15MP
	class sm_3_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,20);
			$this->decHp($user,$enemy,$user->atk);
			$this->cleanStat($enemy,false,1);
		}
	}
	
	//每次攻击，可净化对方一个BUFF
	class sm_3_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			$this->cleanStat($enemy,false,1);
		}
	}
	
	//增加辅助8%攻击
	class sm_3_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->addAtk($player->base_atk * 0.12);
			}
		}
	}
	
	//辅：--心灵控制：所有单位禁固一回合，4CD
	class sm_3_f1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,1);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
		}
	}	
	//辅：--每次攻击60%，可净化对方一个BUFF
	class sm_3_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			$this->cleanStat($enemy,false,1);
		}
	}

?> 