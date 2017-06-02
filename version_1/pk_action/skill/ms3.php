<?php 
	
	
	//�����������(��)�����е�λ����1�غ�
	class sm_3_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,1);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
		}
	}
	
	//��3�ι���ͬʱΪ�Լ��ظ�15MP
	class sm_3_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,20);
			$this->decHp($user,$enemy,$user->atk);
			$this->cleanStat($enemy,false,1);
		}
	}
	
	//ÿ�ι������ɾ����Է�һ��BUFF
	class sm_3_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			$this->cleanStat($enemy,false,1);
		}
	}
	
	//���Ӹ���8%����
	class sm_3_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->addAtk($player->base_atk * 0.12);
			}
		}
	}
	
	//����--������ƣ����е�λ����һ�غϣ�4CD
	class sm_3_f1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,1);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
		}
	}	
	//����--ÿ�ι���60%���ɾ����Է�һ��BUFF
	class sm_3_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			$this->cleanStat($enemy,false,1);
		}
	}

?> 