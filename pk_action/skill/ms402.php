<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����180%�˺���-30%��ǰѪ��
	class sm_402_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.3,false,false,true);
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	// -15%��ǰѪ����+30%�ܣ�CD3��round2,110%�˺�
	class sm_402_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.15,false,false,true);
			$this->decHp($user,$enemy,$user->atk*1.1);
			$v = $this->addDef($user,$self,30);
			$self->addState($user,array('def',$v),2);
		}
	}
	
	//�أ���HP���ڶԷ�ʱ������+30%
	class sm_402_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->stat3 ++;
		}
	}
	
	//�أ������һ��λ����+30%
	class sm_402_3 extends SkillBase{
		public $type='DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_402_ds3');
		}
	}
	class sm_402_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.3);
		}
	}
	
	//����+12%����
	class sm_402_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.12);
		}
	}

?> 