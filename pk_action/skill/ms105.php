<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����240%�˺�
	class sm_105_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.4);
		}
	}
	
	//С����120%�˺���CD3
	class sm_105_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//�أ���������������һ��ս��λ30%��
	class sm_105_2 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_105_ds1');
		}
	}
	class sm_105_ds1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$self,$self->base_speed*0.3,true);
		}
	}
	
	//����30%���˺� 30%�Ļظ���CD1,�������� 
	class sm_105_f1 extends SkillBase{
		public $cd = 1;
		public $temp = true;
		function action($user,$self,$enemy){
			if($this->temp)
				$this->decHp($user,$enemy,$user->atk*0.3);
			else	
				$this->addHp($user,$self,$user->atk*0.3);
			$this->temp = !$this->temp;
		}
	}


?> 