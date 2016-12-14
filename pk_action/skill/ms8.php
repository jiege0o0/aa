<?php 
	
	//����ˮ�ܣ�������-20%�˺���ÿ�غϻ�10%Ѫ��round3
	class sm_8_0 extends SkillBase{
		function action($user,$self,$enemy){
		
			$buff = new ValueBuff('def',20,3);
			$buff->addToTarget($self);
			
			$buff = new HPBuff(round($self->maxHp*0.1),3);
			$buff->addToTarget($self);
		}
	}
	
	//�ͻ���+60%�˺�,cd3
	class sm_8_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//ˮ�ף�������С��20%ʱ��-50%�˺���round2,һ��
	class sm_8_2 extends SkillBase{
		public $type = 'HP';//���Լ���
		public $once = true;//����ִֻ��һ��
		
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.2;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',50,2);
			$buff->addToTarget($self);
		}
	}

	//����--�湥�������غ����60%�˺�
	class sm_8_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		public $order = 100;
		
		function action($user,$self,$enemy){
			$this->order --;
			$this->decHp($user,$enemy,$user->atk*1);

		}
	}	
	
	//����--ż�أ�ż���غϻظ�60%��Ѫ
	class sm_8_f2 extends SkillBase{
		public $cd = 1;
		public $order = 100;
		function action($user,$self,$enemy){
			$this->order --;
			$this->addHp($user,$self,$user->atk*0.9);
		}
	}

?> 