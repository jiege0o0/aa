<?php 
	require_once($filePath."pk_action/skill/skill_base.php");


	//����ȼ��״̬���Է�ÿ���ж�-10%������round3
	class sm_205_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->addcdhp($user,$enemy,-0.1*$enemy->maxHp);
			$enemy->addState($user,array('cdhp'=>$v),3);
		}
	}
	
	//С����120%�˺�
	class sm_205_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//�أ��Է�������Ч��round10
	class sm_205_2 extends SkillBase{
		public $cd=0;
		function action($user,$self,$enemy){
			$enemy->action4++;
			$enemy->addState($user,array('action4'=>1),10);
			$this->setSkillEffect($enemy);
		}
	}
	
	//�������HP-10%��cd3
	class sm_205_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.1,true);
		}
	}


?> 