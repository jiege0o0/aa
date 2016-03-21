<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：对方or自己身上每有一个有CD的状态，伤害+20%
	class sm_302_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = count($user->statCountArr) + count($enemy->statCountArr);
			$this->decHp($user,$enemy,$user->atk*(1+$v*0.2));
		}
	}
	
	// 小技：攻击+50%，CD3，round1
	class sm_302_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$v = $this->addAtk($user,$self,$self->base_atk*0.5);
			$self->addState($user,array('atk'=>$v),1);
		}
	}
	
	//特：破甲，伤害+20%，round3
	class sm_302_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$v = $this->addDef($user,$enemy,-20);
			$enemy->addState($user,array('def'=>$v),3);
		}
	}
	
	//辅：50%伤害，cd2
	class sm_302_f1 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}
	

?> 