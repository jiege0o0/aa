<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�������120%�˺���������������(��Զ)
	class sm_408_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2,true,true);
		}
	}
	
	// С�������80%�˺��������������ޣ�CD3 
	class sm_408_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2,true);
		}
	}
	
	//�أ�����ʱ���ԶԷ����180%�˺�
	class sm_408_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//����ȫ���5%�������ɵ��� 
	class sm_408_f1 extends SkillBase{
		public $leader = true;//��PKǰִ��
		public $cd = 0;
		public $lRound = 999;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.5);
		}
	}

?> 