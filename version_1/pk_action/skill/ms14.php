<?php 
	

	//�����þ����Է��ĸ�����λ�޷��ж���2round
	class sm_14_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,3);
				$buff->isDebuff = true;
				$buff->addToTarget($user,$player);
			}
		}
	}
	
	//��ȭ��+60%�˺���cd2
	class sm_14_1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//�һ��飺˫�����ֺ�Ҫ-������*0.5����
	class sm_14_2 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$user->atk*1);
		}
	}
	class sm_14_5 extends SkillBase{
		public $type = 'EAFTER';
		public $clientIndex=2;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->atk*1);
		}
	}
	
	
	//����--�һ��飺˫�����ֺ�Ҫ-������*0.5����
	class sm_14_f1 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$user->atk*0.5);
		}
	}
	class sm_14_f5 extends SkillBase{
		public $type = 'EAFTER';
		public $clientIndex=1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->atk*0.5);
		}
	}
	
	//����--85%�˺�
	class sm_14_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}

?> 