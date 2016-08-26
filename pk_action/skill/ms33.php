<?php 
	

	//��������(��)��220%�˺�
	class sm_33_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
			
			$buff = new ValueBuff(array('def'=>-3),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//������������ʱ�ԶԷ����60%�˺�
	class sm_33_1 extends SkillBase{
		public $type = 'BEATK';
		function canUse($user,$self=null,$enemy=null){
			return $this->tData[0]->isPKing;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
			
			$buff = new ValueBuff(array('def'=>-3),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//��ʴ��ÿ�ι���-3%����round3,�ɵ���
	class sm_33_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			
			$buff = new ValueBuff(array('def'=>-3),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//����--��ʴ��50%�˺���ÿ�ι���-3%����round3,�ɵ���
	class sm_33_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
			
			$buff = new ValueBuff(array('def'=>-3),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}	

?> 