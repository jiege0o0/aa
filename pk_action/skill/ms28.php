<?php 
	

	//技：寒潮：120%伤害，全体-50%速度，round2
	class sm_28_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new ValueBuff(array('speed'=>-round($player->base_speed * 0.5)),2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
			
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//猛击：伤害+60%,cd3
	class sm_28_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}
	
	//战吼：当生命少于30%时，全体攻击+30%
	class sm_28_2 extends SkillBase{
		public $type = 'HP';
		public $once = true;//技能只执行一次
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->addAtk($player->base_atk*0.3);
			};
		}
	}

	
	//辅：--寒潮：全体-30%速度，round1,cd3
	class sm_28_f1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new ValueBuff(array('speed'=>-round($player->base_speed * 0.3)),1);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
		}
	}	
	//辅：--70%伤害
	class sm_28_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
		}
	}

?> 