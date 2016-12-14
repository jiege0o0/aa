<?php 
	

	//技：净化（技）:所有去敌方buff,已方debuff
	class sm_42_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->cleanStat($player,false,999);
			}
			
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->cleanStat($player,true,999);
			}
			
			$this->addHp($user,$self,$user->atk);
		}
	}
	
	//回血：+自己100%*atk生命，cd3
	class sm_42_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk);
		}
	}
	
	//庇佑：每次伤害不超过30%上限
	class sm_42_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->maxHurt = 0.3;
		}
	}
	
	
	//辅：--80%回血+净化debuff
	class sm_42_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1);
			$this->cleanStat($self,true,1);
		}
	}	
	//辅：--进场时回己方20%最大生命
	class sm_42_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}

?> 