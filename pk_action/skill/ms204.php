<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����220%�˺���-10�Է�����
	class sm_204_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
			$this->addMp($user,$enemy,-10);
		}
	}
	
	//С����+15������cd1
	class sm_204_1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,15);
		}
	}
	
	//�أ�����+50����
	class sm_204_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,50);
		}
	}
	
	//����50%�ظ���10��������cd3
	class sm_204_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_atk*0.5);
			$this->addMp($user,$self,10);
		}
	}
	
	//��������ʱHP-10%
	class sm_204_f2 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.1,false,false,true);
		}
	}

?> 