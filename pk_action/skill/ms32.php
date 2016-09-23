<?php 
	

	//�������磺�ٶ�-50%�������ٷֱ�����10%��round3
	class sm_32_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($enemy->maxHp*0.1),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.5)),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}
	
	//����֮�𣺷�-30%��round2,cd3
	class sm_32_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('def'=>-30),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
		}
	}
	
	//����:����������30%ʱ����ӥ���ظ�50%����+20%
	class sm_32_2 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$user->speed += round($user->base_speed*0.2);
			$this->addHp($user,$user,$user->maxHp*0.5);
		}
	}
	
	//����-- ����֮�𣺷�-30%��round2,cd3
	class sm_32_f1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('def'=>-30),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
		}
	}	
	//����--80%�˺�
	class sm_32_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}

?> 