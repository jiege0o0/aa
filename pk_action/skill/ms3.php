<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
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
				$this->setSkillEffect($player);
			}
		}
	}
	
	//每3次攻击，为自己回复15MP
	class sm_3_1 extends SkillBase{
		public $cd = 3;
		public $isSendAtOnce = true;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,15);
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
				$player->atk += round($player->base_atk * 0.08);
				$this->setSkillEffect($player);
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
				$this->setSkillEffect($player);
			}
		}
	}	
	//辅：--每次攻击60%，可净化对方一个BUFF
	class sm_3_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
			$this->cleanStat($enemy,false,1);
		}
	}

?> 