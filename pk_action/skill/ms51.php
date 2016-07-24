<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：心灵控制(技)：所有单位禁固一回合
	class sm_51_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//每3次攻击，为自己回复10MP
	class sm_51_1 extends SkillBase{
		public $cd = 3;
		public $isSendAtOnce = true;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}
	
	//每次攻击，可净化对方一个BUFF（无论好坏）
	class sm_51_2 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			$this->cleanStat($enemy,-1,1);
		}
	}
	
	//增加辅助5%攻击
	class sm_51_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->atk += round($player->base_atk * 0.05);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//辅：--心灵控制：所有单位禁固一回合，5CD
	class sm_51_f1 extends SkillBase{
		public $cd = 5;
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
	//辅：--每次攻击50%，可净化对方一个BUFF（无论好坏）
	class sm_51_f2 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$$this->decHp($user,$enemy,$user->atk*0.5);
			$this->cleanStat($enemy,-1,1);
		}
	}

?> 