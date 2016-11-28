<?php 
	

	//技：吞吃（技）：-13%对方生命上限，增加自己对应生命上限
	class sm_16_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$enemy->maxHp*0.13,true);
			$this->addHp($user,$self,-$v,true);
			
		}
	}
	
	//积肉:增加6%生命上限并回血，cd3
	class sm_16_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.06,true);
		}
	}
	
	//厚血：防 = 增加的生命上限/100，上限为30%
	class sm_16_2 extends SkillBase{
		public $type = 'MHP';
		
		function action($user,$self,$enemy){
			$this->temp1 += $this->tData;
			$def = min(30,round($this->temp1/$user->base_hp*20));
			$add = $def - $this->temp2;
			$this->temp2 = $def;
			$self->addDef($add);
		}
	}
	
	//亡语：永久加血上限=当前上限*0.2
	class sm_16_3 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_16_ds3#'.round($user->maxHp*0.2));
		}
	}
	class sm_16_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$v = $this->addHp($user,$self,$this->tData,true,true);
		}
	}
	
	
	//辅：--80%伤害
	class sm_16_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	

?> 