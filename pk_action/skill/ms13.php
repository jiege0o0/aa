<?php 
	

	//�������ƣ��޷��չ�2�غϣ�������-Ѫ (ATK*0.5)
	class sm_13_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.6),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new StatBuff(21,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);		
			
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
			$user->addDef(15);
		}
	}
	
	
	//����-- �ظ���7%����
	class sm_13_f1 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.08);
		}
	}	
	//����-- +5%����
	class sm_13_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->addDef(10);
		}
	}

	//����-- ���ƣ��չ�1�غ�,50%�˺���cd5
	class sm_13_f3 extends SkillBase{
		public $isAtk = true;
		public $cd = 4;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.5),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new StatBuff(21,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
		}
	}

?> 