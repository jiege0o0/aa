<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：挥击（技）：+200%伤害，吸30%血
	class sm_6_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk*2);
			$this->addHp($user,$user,-$v*0.3);
		}
	}
	
	//格挡：被伤害时，增加1%防，上限不过50%
	class sm_6_1 extends SkillBase{
		public $type='BEATK';
		function action($user,$self,$enemy){
			if($this->temp1<50)
			{
				$self->def += 1;
				$this->temp1 ++;
			}
				
		}
	}
	
	//吸血：每次普攻吸对方失血的20%
	class sm_6_2 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk);
			$this->addHp($user,$user,-$v*0.2);
		}
	}

	
	//辅：--60%伤害
	class sm_6_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}	


?> 