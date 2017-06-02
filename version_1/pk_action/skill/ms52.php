<?php 
	

	//技：召唤人偶替身(技)：替身可叠加，每个替身增加自己30%攻击力
	class sm_52_0 extends SkillBase{
		function action($user,$self,$enemy){
			$user->addAtk($user->base_atk*0.3);
			array_push($user->dieMissTimes,array("id"=>$user->id,'mid'=>$user->monsterID));
		}
	}
	
	//精神控制：100%伤害，-10MP，cd3
	class sm_52_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->addMp($user,$enemy,-10);
			$this->decHp($user,$enemy,$user->atk);
		}
	}
	
	//转移：当伤害会致死时，替身挡一次并少一个
	class sm_52_2 extends SkillBase{
		public $type = 'DMISS';
		public $isSendAtOnce = true;
		function canUse($user,$self=null,$enemy=null){
			return $this->tData['id'] == $user->id;
		}
		function action($user,$self,$enemy){
			$user->addAtk(-$user->base_atk*0.3);
			$user->addStat(11,-1);
			$user->addStat(1,-1);
			$this->addHp($user,$user,$user->maxHp*0.1);
		}
	}
	
	
	//辅：--神行：已方最大MP值下降20点
	class sm_52_f1 extends SkillBase{
		public $cd = 0;
		public $order = 10;
		function action($user,$self,$enemy){
			$self->maxMp -= 20;
		}
	}	
	
	//辅：--60%伤害
	class sm_52_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 