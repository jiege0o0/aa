<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	

	//����200%�˺�
	class sm_206_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//С�������Լ�30%��ǰ�������ظ�120%Ѫ��cd3
	class sm_206_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$user->hp*0.3,false,false,true);
			$this->addHp($user,$self,$self->atk*1.2);
		}
	}
	
	//�أ�˫����Ѫʱ����+5%��Ѫ��
	class sm_206_2 extends SkillBase{
		public $type = 'SHEAL';
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,$this->tData*0.5);
		}
	}
	
	
	//����30%�˺���cd1
	class sm_206_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.3);
		}
	}

?> 