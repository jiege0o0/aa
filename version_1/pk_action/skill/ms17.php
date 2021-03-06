<?php 
	

	//技：圣光（技）：加30%攻击力，回25%血，3round
	class sm_17_0 extends SkillBase{
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',round($self->base_atk * 0.3),3);
			$buff->addToTarget($user,$self);
			
			$this->addHp($user,$self,$self->maxHp*0.3);
		}
	}
	
	//血斩：减少[5%]当前生命，造成[230%]伤害#CD|3
	class sm_17_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$user,$user->hp*0.1);
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//恐吓:对方-10%攻
	class sm_17_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$enemy,-$enemy->base_atk*0.15);
		}
	}

	
	//辅：当HP》50%，60%伤害 否则60%回血
	class sm_17_f1 extends SkillBase{
		public $cd = 1;
		function canUse($user,$self=null,$enemy=null){
			if($self->getHpRate()>0.5)
				$this->isAtk = true;
			else
				$this->isAtk = false;
			return true;
		}
		function action($user,$self,$enemy){
			if($this->isAtk)
				$this->decHp($user,$enemy,$user->atk*1);	
			else
				$this->addHp($user,$self,$user->atk*1);	
		}
	}	
	//辅：--对方-10%攻
	class sm_17_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$enemy,-$enemy->base_atk*0.1);
		}
	}

?> 