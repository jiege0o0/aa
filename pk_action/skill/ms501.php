<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����220%�˺� 
	class sm_501_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	// С�����ظ�90%HP��CD3
	class sm_501_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*0.9);
		}
	}
	
	// �أ���HPС��30%���˺�-30%
	class sm_501_2 extends SkillBase{
		public $type="HP";
		public $temp = false;
		function action($user,$self,$enemy){
			if(!$temp && $self->getHpRate()<0.3)
			{
				$this->addDef($user,$self,30);
				$temp = true;
			}
			else if($temp && $self->getHpRate() >= 0.3)
			{
				$this->addDef($user,$self,-30);
				$temp = false;
			}
		}
	}
	
	//�����ظ�50%HP��CD4
	class sm_501_f1 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*0.5);
		}
	}	
	
	//�����˺� -5%;
	class sm_501_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addDef($user,$self,5);
		}
	}

?> 