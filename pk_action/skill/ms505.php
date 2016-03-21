<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�����Ӷܣ�50%��round3
	class sm_505_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->decDef($user,$self,50);
			$self->addState($user,array('def'=>$v),3);
		}
	}
	
	//С����200%�˺���CD4
	class sm_505_1 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//�أ�ÿ���ж���ظ��������10%
	class sm_505_2 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//����+20%HP��CD1
	class sm_505_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*0.2);
		}
	}	
	
	//����maxHP + 10%
	class sm_505_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp*0.1,true);
		}
	}


?> 