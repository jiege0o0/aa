<?php 
	

	//����Ѫӡ��������+�Լ�15%����-�Է�15%����200%�˺�,round2
	class sm_31_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			
		
			$buff = new ValueBuff('atk',-round($enemy->base_atk * 0.2),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			
			$buff = new ValueBuff('atk',round($self->base_atk * 0.2),2);
			$buff->addToTarget($self);
			
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//����:����������30%ʱ����ӥ��������������5�ι���
	class sm_31_1 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$user->missTimes += 6;
		}
	}
	
	//���٣��ж������+2
	class sm_31_2 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$user->addSpeed(2);//round($user->base_speed*0.02);
		}
	}
	
	
	//����--Ѫӡ��+�Լ�10%����-�Է�10%����round2,cd4
	class sm_31_f1 extends SkillBase{
		public $cd = 4;
		public $order = 1;
		function action($user,$self,$enemy){
			
			$buff = new ValueBuff('atk',-round($enemy->base_atk * 0.1),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			
			$buff = new ValueBuff('atk',round($self->base_atk * 0.1),2);
			$buff->addToTarget($self);
		}
	}	
	//����--50%��
	class sm_31_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 