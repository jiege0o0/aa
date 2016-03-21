<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����30%�ܣ�round3,+100%HP
	class sm_503_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->addDef($user,$self,30);
			$self->addState($user,array('def'=>$v),3);
			$this->addHp($user,$self,$user->atk*1);
		}
	}
	
	// С�������140%�˺���CD3
	class sm_503_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.4);
		}
	}
	
	//�أ����ܵ�����ʱ������5%������
	class sm_503_2 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$user->base_atk*0.05);

		}
	}
	
	//����100%�˺���CD4
	class sm_503_f1 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}	
	//�������� + 6%
	class sm_503_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.6);
		}
	}

?> 