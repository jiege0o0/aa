<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�������ƣ��޷��չ�2�غϣ�������-Ѫ (ATK*0.6)
	class sm_13_0 extends SkillBase{
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.6),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new StatBuff(21,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
			
			
		}
	}
	
	//�ظ���ÿ3�غϻ��Լ�15%Ѫ
	class sm_13_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.15);
		}
	}
	
	//ľ�ף�����-15%�˺�
	class sm_13_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->def += 15;
		}
	}
	
	
	//����-- �ظ���6%����
	class sm_13_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.06);
		}
	}	
	//����-- +10%����
	class sm_13_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->def += 10;
		}
	}

	//����-- ���ƣ��չ�1�غ�,60%�˺���cd5
	class sm_13_f3 extends SkillBase{
		public $cd = 5;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.6),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new StatBuff(21,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}

?> 