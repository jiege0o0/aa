<?php 
	

	//�������ѣ�������-25%����+50%Ѫ
	class sm_20_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,-$self->base_atk*0.3);
			$this->addHp($user,$self,$self->maxHp*0.5);
		}
	}
	
	//��Ѫ:����ʱ-20%��ǰ������+30%��
	class sm_20_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.4);
			$this->decHp($user,$user,$user->hp*0.2);
		}
	}
	
	//��ŭ:���������5%������
	class sm_20_2 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.12);

		}
	}
	
	//������+20%��cd2
	class sm_20_3 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//����----60%�˺�
	class sm_20_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}	
	//������Ѫ:-10%��ǰ������+10%��
	class sm_20_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.15);
			$this->decHp($user,$self,$self->hp*0.1);
		}
	}

?> 