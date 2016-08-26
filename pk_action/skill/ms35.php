<?php 
	
	
	//��������һ������������1���ԶԷ������ʧѪ��*2���˺�
	class sm_35_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,($user->hp - 1)*2);
			$this->decHp($user,$user,$user->hp-1,false,true);
		}
	}
	
	//ݱ����ʹ�Է��ж�����Ѫ��round1,cd1ATK*0.5
	class sm_35_1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){		
			$buff = new HPBuff(-$user->atk*0.5,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//������+30%�˺�,cd2
	class sm_35_2 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
			
			$buff = new HPBuff(-$user->atk*0.5,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//���ˣ�����ʱ��50%Ѫ
	class sm_35_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.5);
		}
	}
	
	//����--ݱ����60%�˺�,ʹ�Է��ж�����Ѫ��round1,cd1  ATK*0.4
	class sm_35_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
			
			$buff = new HPBuff(-$user->atk*0.4,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}	


?> 