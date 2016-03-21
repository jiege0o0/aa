<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�����˺�+40%��round3
	class sm_202_0 extends SkillBase{
		function action($user,$self,$enemy){
			$value = $this->addHurt($user,$self,40);
			$self->addState($user,array('hurt'=>40),3);
		}
	}
	
	// С����150%�˺�
	class sm_202_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//�أ�����ʱ�ظ�50%�������
	class sm_202_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.5);
		}
	}
	
	//�����˺�+20%��round2��cd5
	class sm_202_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$value = $this->addHurt($user,$self,20);
			$self->addState($user,array('hurt'=>20),2);
		}
	}
	
	//����100%�˺���CD4
	class sm_202_f2 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 