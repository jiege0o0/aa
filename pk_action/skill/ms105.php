<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：240%伤害
	class sm_105_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.4);
		}
	}
	
	//小技：120%伤害，CD3
	class sm_105_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//特：死后永久增加下一出战单位30%速
	class sm_105_2 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_105_ds1');
		}
	}
	class sm_105_ds1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$self,$self->base_speed*0.3,true);
		}
	}
	
	//辅：30%的伤害 30%的回复，CD1,轮流触发 
	class sm_105_f1 extends SkillBase{
		public $cd = 1;
		public $temp = true;
		function action($user,$self,$enemy){
			if($this->temp)
				$this->decHp($user,$enemy,$user->atk*0.3);
			else	
				$this->addHp($user,$self,$user->atk*0.3);
			$this->temp = !$this->temp;
		}
	}


?> 