<?php 
	

	//�����ͻ���+180%�˺�������ʴ-20%�ף�3round
	class sm_12_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			
			$buff = new ValueBuff('def',-20,3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//�ػ� +40%�ˣ�cd3
	class sm_12_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
			$this->addDef($user,$enemy,-5);
		}
	}
	
	//���أ�������+30%�ܣ�round3
	class sm_12_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',30,3);
			$buff->addToTarget($user,$self);
		}
	}
	
	
	
	//����--50%��
	class sm_12_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.9);
		}
	}	
	//����--60%�� + 2round����ʴ-20%�ף�cd5
	class sm_12_f2 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
			$buff = new ValueBuff('def',-20,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}

?> 