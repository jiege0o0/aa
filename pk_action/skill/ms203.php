<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：220%伤害
	class sm_203_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	//小技：100%伤害，回复同等血量，cd3
	class sm_203_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk*1);
			$this->addHp($user,$self,$v);
		}
	}
	
	//特：不死回20%血，round1,本轮只能触发1次
	class sm_203_2 extends SkillBase{
		public $type = 'DIE';
		public $once = true;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}
	
	//辅：80%伤害，回复同等血量，cd5
	class sm_203_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk*0.8);
			$this->addHp($user,$self,$v);
		}
	}
	
	//辅：攻+5%
	class sm_203_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.05);
		}
	}

?> 