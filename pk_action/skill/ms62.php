<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	function sm_62_king($user){
		return $user->add_hp > $user->base_hp * 2;
	}
	
	//技：猛砍:180%伤害
	class sm_62_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//复活：复活并回100%血，次数=出战的士兵数量（成王状态下不可用）
	class sm_62_1 extends SkillBase{
		public $type='DIE';
		function canUse($user,$self=null,$enemy=null){
			$num = $user->team->monsterBase->{$user->monsterID}->num;
			return $self->hp<=0 && !sm_62_king($user) && $num > $this->temp1;
		}
		function action($user,$self,$enemy){
			$user->reborn(1);
			$this->temp1 ++;
		}
	}
	
	//杀成王：上场时敌人死亡，生命 + 400%，攻击+100%，回满血（只触发一次）
	class sm_62_2 extends SkillBase{
		public $type='EDIE';
		public $once=true;
		function canUse($user,$self=null,$enemy=null){
			return !sm_62_king($user);
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->base_hp*4,true,true);
			$v = round($user->base_atk*1);  
			$user->add_atk += $v;
			$user->atk += $v;
			$this->addHp($user,$self,$self->maxHp - $self->hp,false,true);
		}
	}
	
	//辅：--80%伤害
	class sm_62_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	


?> 