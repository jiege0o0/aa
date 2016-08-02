<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//������ն��+200%�˺�
	class sm_27_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//����ն��+50%�˺���cd3
	class sm_27_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//������ӡ������-50%
	class sm_27_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->atk -= round($user->base_atk*0.5);
		}
	}
	
	//��ӡ���������������40%ʱ����ӡ���
	class sm_27_3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate()<=0.4;
		}
		function action($user,$self,$enemy){
			$user->atk += round($user->base_atk*0.5);
		}
	}
	
	//����--50%�˺�
	class sm_27_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//����--������ӡ������-50%
	class sm_27_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->atk -= round($user->base_atk*0.5);
		}
	}
	
	//������ӡ�������������������30%ʱ���Լ���ӡ���
	class sm_27_f3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate()<=0.3;
		}
		function action($user,$self,$enemy){
			$user->atk += round($user->base_atk*0.5);
		}
	}

?> 