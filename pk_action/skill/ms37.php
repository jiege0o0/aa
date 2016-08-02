<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：衰弱：-15%最大生命和血量
	class sm_37_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.15,true);
		}
	}
	
	//交换：增加对方15%攻击，-10%最大血量，cd3
	class sm_37_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$enemy->atk += round($enemy->base_atk*0.15);
			$this->decHp($user,$enemy,$enemy->maxHp*0.1,true);
		}
	}
	
	//吞食：进入时，-辅助单位10%攻击，回复自己辅助单位最大生命20%的血量
	class sm_37_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$hp = 0;
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				$player->atk -= round($player->base_atk*0.1);
				$hp += $player->maxHp;
			}
			$this->addHp($user,$self,$hp*0.2);
		}
	}
	
	
	//辅：50%伤害
	class sm_37_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//辅：--减对方10%当前生命上限，增加到己方身上
	class sm_37_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$v = -$this->decHp($user,$enemy,$enemy->maxHp*0.1,true);
			$this->addHp($user,$self,$v,true);
		}
	}

?> 