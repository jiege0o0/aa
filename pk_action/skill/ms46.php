<?php 
	

	//技：暴风锤：120%伤害，晕2回合
	class sm_46_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			
			$buff = new StatBuff(24,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//地震：全体-速20%，round2,cd5
	class sm_46_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$buff = new ValueBuff('speed',-round($player->base_speed * 0.2),2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
		}
	}
	
	//天神下凡：10次行动后，防+20%，攻+50%
	class sm_46_2 extends SkillBase{
		public $type='AFTER';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->actionCount >= 8;
		}
		function action($user,$self,$enemy){
			$user->atk += round($user->base_atk*0.5);
			$user->addDef(30);
			$this->addHp($user,$user,$user->maxHp*0.3);
		}
	}
	
	//+20%防
	class sm_46_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->addDef(20);
		}
	}
	
	//辅：--50%伤害
	class sm_46_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	
	//辅：--+10%防
	class sm_46_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->addDef(10);
		}
	}

?> 