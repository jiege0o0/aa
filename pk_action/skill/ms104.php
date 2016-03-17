<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����300%�˺� 
	class sm_104_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*3);
		}
	}
	
	// 200%�˺���CD3  
	class sm_104_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//�أ�-30%��ǰ����������30%�ٶ� 
	class sm_104_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.3);
			$this->addSpeed($user,$self,$self->base_speed*0.3);
		}
	}
	
	//��������10%�Ĺ�  
	class sm_104_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
		}
	}
	
	//����120%�˺���CD3 
	class sm_104_f2 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}

?> 