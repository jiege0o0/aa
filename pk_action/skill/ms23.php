<?php 
	

	//������Ѫ(��)��-�Է�180%Ѫ���ظ���ӦѪ��
	class sm_23_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = -$this->decHp($user,$enemy,$user->atk*1.8);
			$this->addHp($user,$self,$v);
		}
	}
	
	//�����130%�˺���-20%����round1,cd3
	class sm_23_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
			$self->speed += 5;//round($self->base_speed*0.05);
			
			$buff = new ValueBuff(array('atk'=>-round($enemy->base_atk * 0.2)),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//���٣��ж���+5�ٶ�
	class sm_23_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			$self->speed += 5;//round($self->base_speed*0.05);
		}
	}
	
	//���������λ�ٶ�+10%
	class sm_23_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->speed += round($player->base_speed * 0.1);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//����-- �����40%�˺���-10%����round1,cd2
	class sm_23_f1 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.4);
			
			$buff = new ValueBuff(array('atk'=>-round($enemy->base_atk * 0.1)),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}	
	//����-- 50%��
	class sm_23_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 