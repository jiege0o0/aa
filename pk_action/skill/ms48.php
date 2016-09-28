<?php 
	

	//技：单挑：双方辅助停止行动2回合，+30%攻 ,3round
	class sm_48_0 extends SkillBase{
		
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
			
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				
				$buff = new StatBuff(24,2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
			
				
			$buff = new ValueBuff(array('atk'=>round($user->base_atk * 0.3)),3);
			$buff->addToTarget($user);
			$this->setSkillEffect($user);
			
		}
	}
	
	//反震：对方同样受到伤害的35%（只对主角）
	class sm_48_1 extends SkillBase{
		public $type="BEHURT";
		function canUse($user,$self=null,$enemy=null){
			return $this->tData[0]->isPKing;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,-$this->tData[1]*0.35);
		}
	}
	
	//重击：60%伤害,cd3
	class sm_48_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}
	
	//鼓舞：辅助+10%攻
	class sm_48_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->atk += round($player->base_atk * 0.1);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//辅：-- 70%伤害
	class sm_48_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
		}
	}	
	//辅：--鼓舞：+15%攻
	class sm_48_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk * 0.15);
		}
	}

?> 