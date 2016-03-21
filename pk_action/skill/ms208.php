<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	//�����ظ�200%�˺�HP
	class sm_208_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->atk*2);
		}
	}
	
	// С����110%�˺���CD3  
	class sm_208_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.1);
		}
	}
	
	//�أ������ظ�ʱ���Է�����-50%�ظ���
	class sm_208_2 extends SkillBase{
		public $type = 'BEHEAL';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$this->tData*0.5);
		}
	}
	
	//����100%�Ļ�Ѫ��CD3
	class sm_208_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->atk*1);
		}
	}
	

?> 