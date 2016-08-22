<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：沉默：对方无法使用绝招，round5
	class sm_56_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new StatBuff(22,5);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
		}
	}
	
	//衰弱:减对方辅助20%攻击力
	class sm_56_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$player->atk -= round($player->base_atk * 0.2);
			}
		}
	}
	
	//灵弹：+70%伤害,cd3
	class sm_56_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.7);
		}
	}

	
	//辅：--沉默：对方无法使用绝招，round3,cd5
	class sm_56_f1 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new StatBuff(22,3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
		}
	}	
	//辅：--响应：当已方使用绝招时，追加一次130%的输出
	class sm_56_f2 extends SkillBase{
		public $type = 'SKILL';
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	//辅：--70%伤害
	class sm_56_f3 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
		}
	}

?> 