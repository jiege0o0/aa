<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//����220%�˺�
	class sm_106_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	//С����100%�˺���������10%�ٶȣ�CD2
	class sm_106_1 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			$this->addSpeed($user,$self,$self->base_speed*0.1);
		}
	}
	
	
	//�����з�ȫ���ٶ�-5%�����ɵ��� 
	class sm_106_f1 extends SkillBase{
		public $leader = true;
		public $once = true;
		public $cd = 0;
		public $lRound = 999;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$enemy,-$enemy->base_speed*0.05);
		}
	}

?> 