<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：降40%速度，round2 
	class sm_108_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->addSpeed($user,$enemy,-$enemy->base_speed*0.4);
			$enemy->addState($user,array('speed'=>$v),2);
		}
	}
	
	// 小技：180%伤害，晕round1，cd5    
	class sm_108_1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
			$enemy->action5++;
			$enemy->addState($user,array('action5'=>1),1);
		}
	}
	
	//特：加辅助20%攻
	class sm_108_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$add = 0;
			if($user->team->teamInfo['atks'])
				$add = $user->team->teamInfo['atks'];
			if($add)	
				$this->addAtk($user,$self,$add*0.2);
		}
	}
	
	//辅：晕1回合，cd5     
	class sm_108_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$enemy->action5++;
			$enemy->addState($user,array('action5'=>1),1);
			$this->setSkillEffect($enemy);
		}
	}
	
	//辅：30%的伤害，CD1 
	class sm_108_f2 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.3);
		}
	}
	
	//辅：-对方10%速度
	class sm_108_f3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$enemy,-$enemy->base_speed*0.1);
		}
	}

?> 