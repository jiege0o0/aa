<?php 
	

	//�������ߣ�130%�˺����ж�Һ��-��-Ѫ����round2����������ã�     -15%�٣�ATK*0.5
	class sm_15_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk*0.5,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.15),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//������+30%�˺�����������ã�-8%�ٶ�
	class sm_15_1 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//�Ȼ����Զ������100%�˺�
	class sm_15_2 extends SkillBase{
		public $cd = 0;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//��������ʱ������������15%�����ߺ��壬��������+60%������+20%������Ѫ
	class sm_15_3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.2;
		}
		
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp*0.8,true,false,true);
			$self->addAtk($self->base_atk*0.5);
			$self->skill->disabled = true;
			$self->skillArr[1]->disabled = true;
		}
	}
	
	//����--60%�˺�
	class sm_15_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//����--�綾��5cd,round3   -10%�٣�ATK*0.3
	class sm_15_f2 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk*0.4,3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}

?> 