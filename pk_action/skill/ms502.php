<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�����ظ� 220% ��Ѫ�� 
	class sm_502_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*2.2);
		}
	}
	
	// С����140%�˺���CD3
	class sm_502_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.4);
		}
	}
	
	//�أ����ܵ��˺�ʱ���ԶԷ����50%����
	class sm_502_2 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$tData*0.5);
		}
	}
	
	//�أ���������HP�����ڸ�����λ��10%
	class sm_502_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$add = 0;
			if($user->team->teamInfo['mhps'])
				$add = $user->team->teamInfo['mhps']* 0.1;
			$this->addHp($user,$self,$add,true);
		}
	}
	
	//����160%�˺���CD5 
	class sm_502_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}


?> 