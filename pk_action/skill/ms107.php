<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：250%伤害    
	class sm_107_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//小技：100%伤害，并回复10%最大生命，CD2
	class sm_107_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//特：魔免 
	class sm_107_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat1 ++;
		}
	}
	
	//辅：清除一个负面状态，CD3
	class sm_107_f1 extends SkillBase{
		public $type = 'SKILL4';
		public $cd = 3;
		
		//技能是否能使用
		function canUse($user,$self=null,$enemy=null){
			return $this->haveStat($self,$enemy->teamID);
		}
		function action($user,$self,$enemy){
			if($this->cleanStat($self,$enemy->teamID,1))
			{
				$this->setSkillEffect($self);
			}
		}
	}
	
	//辅：50%的伤害，CD2
	class sm_107_f2 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

	//辅：血量 + 5%  
	class sm_107_f3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp*0.5,true);
		}
	}

?> 