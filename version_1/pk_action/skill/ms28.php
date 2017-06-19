<?php 
	

	//����������120%�˺���ȫ��-50%�ٶȣ�round2
	class sm_28_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new ValueBuff('speed',-round($player->base_speed * 0.5),2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
			
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//�ͻ����˺�+60%,cd3
	class sm_28_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
			$enemy->addSpeed(-5);
		}
	}
	
	//ս�𣺵���������30%ʱ��ȫ�幥��+30%
	class sm_28_2 extends SkillBase{
		public $type = 'HP';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->addAtk($player->base_atk*0.3);
				$player->addSpeed($player->base_speed*0.2);
			};
		}
	}

	
	//����--������ȫ��-30%�ٶȣ�round1,cd3
	class sm_28_f1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new ValueBuff('speed',-round($player->base_speed * 0.3),1);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
			}
		}
	}	
	//����--70%�˺�
	class sm_28_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.9);
		}
	}

?> 