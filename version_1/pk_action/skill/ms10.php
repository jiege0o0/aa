<?php 
	

	//����ʯ��������������ͣ2�غ�
	class sm_10_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new StatBuff(24,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
				
		}
	}
	
	//�������ߣ���+50%��cd3
	class sm_10_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.3);
		}
	}
	
	//�������ж���ظ�5%Ѫ��
	class sm_10_2 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.50);
		}
	}
	
	
	//����ʯ����������ͣ1�غϣ�cd5
	class sm_10_f1 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}	
	//����--60%�˺�
	class sm_10_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}
	//����--����ʱ�ظ�20%����
	class sm_10_f3 extends SkillBase{
		public $cd = 0;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}

?> 