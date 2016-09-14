<?php 
	

	//技：挥击（技）：+220%伤害，吸40%血
	class sm_6_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk*2.2);
			$this->addHp($user,$user,-$v*0.4);
		}
	}
	
	//格挡：被伤害时，增加1%防，上限不过50%
	class sm_6_1 extends SkillBase{
		public $type='BEATK';
		function action($user,$self,$enemy){
			if($this->temp1<30)
			{
				$self->def += 1;
				$this->temp1 ++;
			}
				
		}
	}
	
	//吸血：每次普攻吸对方失血的25%
	class sm_6_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk);
			$this->addHp($user,$user,-$v*0.25);
		}
	}

	
	//辅：--60%伤害
	class sm_6_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}	


?> 