<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：撕裂（技）：伤害250%
	class sm_4_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//摆尾，伤害+50%，cd3
	class sm_4_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//改变属性的buff
	class sm_4_2_buff extends ValueBuff{
		function onEnd(){
			$this -> onClean();
			$buff = new ValueBuff(array('atk'=>-round($this->target->base_atk * 0.5)),10);
			$buff->addToTarget($this->target);
		}
	}
	
	//狂暴：当生命少于30%时，攻击+30% ，2回合后，攻击-50%，10回合
	class sm_4_2 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//技能只执行一次
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate()<=0.3;
		}
		function action($user,$self,$enemy){
			$buff = new sm_4_2_buff(array('atk'=>round($self->base_atk * 0.3)),2);
			$buff->addToTarget($self);
		}
	}
	
	//不屈， 死后增加下一单位15%攻，一次
	class sm_4_3 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_4_ds3');
		}
	}
	class sm_4_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk * 0.15);
		}
	}
	

	
	//辅：---50%伤害
	class sm_4_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//辅：--+10%攻击
	class sm_4_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk * 0.1);
		}
	}

?> 