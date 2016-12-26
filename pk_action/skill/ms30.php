<?php 
	
	

	//技：冲锋（技）：+20%速度，造成200%伤害，round3
	class sm_30_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.2),3);
			$buff->addToTarget($self);
			
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//猛击：+50%伤害
	class sm_30_1 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//兴奋剂：进场时+50%攻，每次行动后攻-10%
	class sm_30_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->addAtk($self->base_atk*0.6);
		}
	}	
	class sm_30_5 extends SkillBase{
		public $type='AFTER';
		function action($user,$self,$enemy){
			$self->addAtk(-$self->base_atk*0.1);
		}
	}
	
	//不屈：死后增加下一单位20%最大血量(永久)
	class sm_30_3 extends SkillBase{
		public $type='DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_30_ds3');
		}
	}
	class sm_30_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp * 0.2,true,true);
		}
	}
	
	
	
	//辅：--好战：处于2号位时，攻击+50%
	class sm_30_f1 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $user->pos == 1;
		}
		
		function action($user,$self,$enemy){
			$user->addAtk($user->base_atk*0.8);
		}
	}	
	//辅：--60%伤害
	class sm_30_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}

?> 