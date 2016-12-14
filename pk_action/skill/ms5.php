<?php 
	
	
	//技：撕裂（技）：伤害200%
	class sm_5_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//重生：死后复活并回复20%血量，1次
	class sm_5_1 extends SkillBase{
		public $type = 'DIE';
		public $once = true;//技能只执行一次
		function canUse($user,$self=null,$enemy=null){
			return $user->hp<=0;
		}
		function action($user,$self,$enemy){
			$user->reborn(0.2);
		}
	}
	
	//带毒：每次攻击，-10%速度，1回合
	class sm_5_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//合作，每有一个人鱼出战，伤害+20%
	class sm_5_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$num = $user->team->monsterBase->{$user->monsterID}->num;	
			$user->addAtk($user->base_atk*0.2*$num);
		}
	}
	
	//辅：--50%伤害，-10%速度，1回合
	class sm_5_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}	
	//辅：档刀，当致死时,抵挡一次，自己晕2回合，触发一次
	class sm_5_f2 extends SkillBase{
		public $type = 'DMISS';
		public $isSendAtOnce = true;
		function canUse($user,$self=null,$enemy=null){
			return $this->tData['id'] == $user->id;
		}
		function action($user,$self,$enemy){
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user);
		}
	}
	class sm_5_f3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			array_push($self->dieMissTimes,array("id"=>$user->id,'mid'=>$user->monsterID,"type"=>'atk'));
		}
	}


?> 