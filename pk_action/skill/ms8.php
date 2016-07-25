<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	//技：水盾（技）：-20%伤害，每回合回10%血，round3
	class sm_8_0 extends SkillBase{
		function action($user,$self,$enemy){
		
			$buff = new ValueBuff(array('def'=>20),3);
			$buff->addToTarget($self);
			
			$buff = new HPBuff(round($self->maxHp*0.1),3);
			$buff->addToTarget($self);

			$this->setSkillEffect($self);
		}
	}
	
	//猛击：+30%伤害,cd3
	class sm_8_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	//水甲：当生命小于20%时，-50%伤害，round2,一次
	class sm_8_2 extends SkillBase{
		public $type = 'HP';//特性技能
		public $once = true;//技能只执行一次
		
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.2;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('def'=>50),2);
			$buff->addToTarget($self);
			$this->setSkillEffect($self);
		}
	}

	//辅：--治愈：奇数回合造成60%伤害，偶手回合回复50%攻血
	class sm_8_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			if($this->temp1 == 0)
			{
				$this->temp1 = 1;
				$this->decHp($user,$enemy,$user->atk*0.6);
				$this->isAtk = false;
			}
			else
			{
				$this->temp1 = 0;
				$this->addHp($user,$self,$user->atk*0.5);
				$this->isAtk = true;
			}
		}
	}	

?> 