<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����300%�˺�
	class sm_306_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*3);
		}
	}
	
	//С����200%�˺�
	class sm_306_1 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}	
	
	//�أ�-50%��ǰ����������50%������50%�ٶȣ�30������round3
	class sm_306_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.5,false,false,true);
			$v2 = $this->addSpeed($user,$self,$self->base_speed*0.5);
			$v1 = $this->addAtk($user,$self,$self->base_atk*0.5);
			$this->addMp($user,$self,50);
			$self->addState($user,array('atk'=>$v1,'speed'=>$v2),3);
		}
	}
	
	
	//�������Է�30%������cd3��round1
	class sm_306_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$v1 = $this->addAtk($user,$enemy,-$enemy->base_atk*0.3);
			$enemy->addState($user,array('atk'=>$v1),1);
		}
	}

?> 