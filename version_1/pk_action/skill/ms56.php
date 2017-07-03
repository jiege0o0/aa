<?php 
	

	//������Ĭ���Է��޷�ʹ�þ��У�round5
	class sm_56_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			
			$buff = new StatBuff(22,5);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//˥��:���Է�����30%������
	class sm_56_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addAtk($user,$player,-$player->base_atk * 0.3);
			}
		}
	}
	
	//�鵯��+80%�˺�,cd3
	class sm_56_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}

	
	//����--��Ĭ���Է��޷�ʹ�þ��У�round3,cd5
	class sm_56_f1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new StatBuff(22,4);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}	
	//����--��Ӧ�����ѷ�ʹ�þ���ʱ��׷��һ��150%�����
	class sm_56_f2 extends SkillBase{
		public $type = 'SKILL';
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//����--70%�˺�
	class sm_56_f3 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 