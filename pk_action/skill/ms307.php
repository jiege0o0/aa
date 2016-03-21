<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����200%�˺������Է��ж���round2   
	class sm_307_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			$enemy->action1 ++;
			$enemy->addState($user,array('action1'=>1),2);
		}
	}
	
	//С����150%�˺�
	class sm_307_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//�أ�����ԶԷ����200%�˺�
	class sm_307_2 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//������30%��cd3��round1
	class sm_307_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$v = $self->addDef($user,$self,30);
			$enemy->addState($user,array('def'=>$v),1);
		}
	}
	
	//����60%�˺���cd2
	class sm_307_f2 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}


?> 