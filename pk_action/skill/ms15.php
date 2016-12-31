<?php 
	

	//技：放蛇：130%伤害，中毒液（-速-血），round2（变身后不能用）     -15%速，ATK*0.5
	class sm_15_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk*0.5,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.15),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//暗箭：+30%伤害（变身后不能用）-8%速度
	class sm_15_1 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//先机：对对手造成100%伤害
	class sm_15_2 extends SkillBase{
		public $cd = 0;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//变身：进入时，当生命少于15%，与蛇合体，生命上限+60%，攻击+20%，回满血
	class sm_15_3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.2;
		}
		
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp*0.8,true,false,true);
			$self->addAtk($self->base_atk*0.5);
			$self->skill->disabled = true;
			$self->skillArr[1]->disabled = true;
		}
	}
	
	//辅：--60%伤害
	class sm_15_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//辅：--喷毒，5cd,round3   -10%速，ATK*0.3
	class sm_15_f2 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk*0.4,3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}

?> 