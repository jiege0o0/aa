<?php 
	

	//技：血印（技）：+自己15%攻，-对方15%攻，200%伤害,round2
	class sm_31_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			
		
			$buff = new ValueBuff('atk',-round($enemy->base_atk * 0.2),2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			
			$buff = new ValueBuff('atk',round($self->base_atk * 0.2),2);
			$buff->addToTarget($user,$self);
			
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//变身:当生命少于30%时，化鹰，闪开接下来的5次攻击
	class sm_31_1 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.2;
		}
		function action($user,$self,$enemy){
			$user->missTimes += 5;
		}
	}
	
	//加速：行动后加速+2
	class sm_31_2 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->addSpeed($user,$user,2);//round($user->base_speed*0.02);
		}
	}
	
	
	//辅：--血印：+自己10%攻，-对方10%攻，round2,cd4
	class sm_31_f1 extends SkillBase{
		public $cd = 4;
		public $order = 1;
		function action($user,$self,$enemy){
			
			$buff = new ValueBuff('atk',-round($enemy->base_atk * 0.1),2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			
			$buff = new ValueBuff('atk',round($self->base_atk * 0.1),2);
			$buff->addToTarget($user,$self);
		}
	}	
	//辅：--50%伤
	class sm_31_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 