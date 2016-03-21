<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�������220%�˺�
	class sm_406_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	//С����CD3��200%�˺���+10����
	class sm_406_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			$this->addMp($user,$self,10);
		}
	}
	
	//�أ�HP<50%ʱ������+30%
	class sm_406_1 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function action($user,$self,$enemy){
			$this->decAtk($user,$self,$self->base_atk*0.3);
		}
	}
	
	
	//����+10������CD3
	class sm_406_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}

?> 