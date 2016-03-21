<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//���Է�10%�����������Լ���Ӧ����
	class sm_407_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->addAtk($user,$enemy,-$enemy->atk*0.1);
			$this->addAtk($user,$user,$v);
		}
	}
	
	//С����20%�˺���CD3
	class sm_407_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//�أ�����-20%����������50������
	class sm_407_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,-$self->hp*0.2,false,false,true);
			$this->addMp($user,$self,50);
		}
	}
	
	//�������Է�10%������CD5
	class sm_407_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$this->addAtk($user,$enemy,-$enemy->atk*0.1);
		}
	}
	
	//����30%�˺���CD2
	class sm_407_f2 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.3);
		}
	}


?> 