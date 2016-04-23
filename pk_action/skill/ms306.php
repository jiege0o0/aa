<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：300%伤害
	class sm_306_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*3);
		}
	}
	
	//小技：200%伤害
	class sm_306_1 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}	
	
	//特：-50%当前生命，增加50%攻击，50%速度，30能量，round3
	class sm_306_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.5,false,false,true);
			$v2 = $this->addSpeed($user,$self,$self->base_speed*0.5);
			$v1 = $this->addAtk($user,$self,$self->base_atk*0.5);
			$this->addMp($user,$self,50);
			$self->addState($user,array('atk'=>$v1,'speed'=>$v2),3);
		}
	}
	
	
	//辅：减对方30%攻击，cd3，round1
	class sm_306_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$v1 = $this->addAtk($user,$enemy,-$enemy->base_atk*0.3);
			$enemy->addState($user,array('atk'=>$v1),1);
		}
	}

?> 