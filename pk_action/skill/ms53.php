<?php 
	

	//������ը�����������1000%�˺�
	class sm_53_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*10);
		}
	}
	
	//�������ظ�50%��Ѫ
	class sm_53_1 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->hp<=0;
		}
		function action($user,$self,$enemy){
			$user->reborn(0.5);
		}
	}
	
	//��Ѫ��ʧ��������ʱ��mp-5
	class sm_53_2 extends SkillBase{
		public $type='BEATK';
		function action($user,$self,$enemy){
			$this->addMp($user,$self,-5);
		}
	}
	
	//����--50%�˺�
	class sm_53_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//����--���20%Ѫ��1��
	class sm_53_f2 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->hp<=0;
		}
		function action($user,$self,$enemy){
			$self->reborn(0.2);
		}
	}

?> 