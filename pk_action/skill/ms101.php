<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//����200%�˺� 
	class sm_101_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	// С����130%�˺���CD3
	class sm_101_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	// �أ�ͬʱ����3�����ٶȼӳ�20%
	class sm_101_2 extends SkillBase{
		public $cd = 0;
		
		function canUse($user,$self=null,$enemy=null){
			if($user->team->teamInfo['num'][101] == 3)
				return true;
			return false;
		}
		function action($user,$self,$enemy){
			$value = $this->addSpeed($user,$self,$self->base_speed*0.2);
		}
	}
	
	//�����ҷ�ȫ���ٶ�+8%�����ɵ���
	class sm_101_f1 extends SkillBase{
		public $leader = true;
		public $once = true;
		public $cd = 0;
		public $lRound = 999;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$self,$self->base_speed*0.08);
		}
	}

?> 