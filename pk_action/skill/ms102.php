<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//�����ظ� 200% ��Ѫ�� 
	class sm_102_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*2);
		}
	}
	
	// С����160%�˺���CD3
	class sm_102_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}
	
	//�أ�ÿ��һ��ͬ���嵥λ�������ӳ�5% 
	class sm_102_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$add = 0;
			if($user->team->teamInfo['tnum'][102])
				$add = $user->team->teamInfo['tnum'][102] * 0.05;
			$this->addHp($user,$self,$self->base_hp*$add,true);
		}
	}
	
	//�����ظ� 100% ��Ѫ����CD��4  
	class sm_102_f1 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1);
		}
	}
	
	//��������4%����  
	class sm_102_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.04);
		}
	}

?> 