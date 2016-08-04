<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//����ʥ�⣨�������ظ�����30%Ѫ
	class sm_25_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.3);
		}
	}
	
	//�ػ���+70%��
	class sm_25_1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.7);
		}
	}
	
	//��ף���+20%
	class sm_25_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->def += 20;
		}
	}
	
	//����������ʱ�ظ�20%Ѫ
	class sm_25_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.3);
		}
	}
	
	//����--ʥ�⣺��20%Ѫ��cd6
	class sm_25_f1 extends SkillBase{
		public $cd = 6;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.20);
		}
	}	
	//����--50%�˺�
	class sm_25_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 