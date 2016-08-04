<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//技：圣光（技）：回复自身30%血
	class sm_25_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.3);
		}
	}
	
	//重击：+70%伤
	class sm_25_1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.7);
		}
	}
	
	//厚甲：盾+20%
	class sm_25_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->def += 20;
		}
	}
	
	//光明：进场时回复20%血
	class sm_25_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.3);
		}
	}
	
	//辅：--圣光：回20%血，cd6
	class sm_25_f1 extends SkillBase{
		public $cd = 6;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.20);
		}
	}	
	//辅：--50%伤害
	class sm_25_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 