<?php 
	

	//�������ƣ��޷��չ�2�غϣ�������-Ѫ (ATK*0.5)
	class sm_13_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*1.5),2,'13_0');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new StatBuff(21,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);		
			
		}
	}
	
	//�ظ���ÿ3�غϻ��Լ�10%Ѫ
	class sm_13_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}
	
	//ľ�ף�����-10%�˺�
	class sm_13_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addDef($user,$user,15);
		}
	}
	
	
	//����-- �ظ���7%����
	class sm_13_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.05);
		}
	}	
	//����-- +5%����
	class sm_13_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addDef($user,$self,10);
		}
	}

	//����-- ���ƣ��չ�1�غ�,50%�˺���cd5
	class sm_13_f3 extends SkillBase{
		public $isAtk = true;
		public $cd = 3;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*1),1,'13_f3');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new StatBuff(21,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
		}
	}

?> 