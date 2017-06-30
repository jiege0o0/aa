<?php 
	

	//������ն��+200%�˺�
	class sm_27_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.8);
		}
	}
	
	//����ն��+50%�˺���cd3
	class sm_27_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			$this->addAtk($user,$user,$user->base_atk*0.05);
		}
	}
	
	//������ӡ������-50%
	class sm_27_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,-$user->base_atk*0.5);
		}
	}
	
	//��ӡ���������������40%ʱ����ӡ���
	class sm_27_3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate()<=0.5;
		}
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,$user->base_atk*0.5);
			$user->addStat(11,-1);
			$user->addStat(1,-1);
		}
	}
	
	//����--50%�˺�
	class sm_27_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//����--������ӡ������-50%
	class sm_27_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,-$user->base_atk*0.5);
		}
	}
	
	//������ӡ�������������������30%ʱ���Լ���ӡ���
	class sm_27_f3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate()<=0.5;
		}
		function action($user,$self,$enemy){
			$this->addAtk($user,$user,$user->base_atk*0.5);
			$user->addStat(11,-1);
			$user->addStat(1,-1);
		}
	}

?> 