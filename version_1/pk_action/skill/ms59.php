<?php 
	
	//�����Ա�(��):���1000%�˺�
	class sm_59_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*12);
			$self->dieMissTimes = array();
			$this->decHp($user,$user,999999,false,true);
		}
	}
	
	//ħ�⣺����ʱ��3round
	class sm_59_1 extends SkillBase{
		public $cd = 0;
		public $order = 10;
		function action($user,$self,$enemy){
			$buff = new StatBuff(31,3);
			$buff->addToTarget($user,$user);
		}
	}
	
	//���䣺�˺�-10%
	class sm_59_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addDef($user,$user,15);
		}
	}
	
	
	//����--60%�˺�
	class sm_59_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.9);
		}
	}	

?> 