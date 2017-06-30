<?php 
	

	//�������գ����������ӽ�������5�ι�����������10%�ٶȣ�3round
	class sm_9_0 extends SkillBase{
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.1),3);
			$buff->addToTarget($user,$self);
			
			$self->missTimes += 5;
		}
	}
	
	//����cd5,��ȫ����15%��2round
	class sm_9_1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$buff = new ValueBuff('speed',-round($player->base_speed * 0.15),2);
				$buff->isDebuff = true;
				$buff->addToTarget($user,$player);
			}
		}
	}
	
	//����������ʱ�̶��Է�1���غϣ��޷��ж�
	class sm_9_2 extends SkillBase{
		public $cd = 0;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new StatBuff(24,2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//���٣�����+10%�ٶ�
	class sm_9_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->base_speed += round($player->base_speed * 0.1);
			}
		}
	}
	
	//����--���ף�-8%��ǰѪ��������15%������
	class sm_9_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.1);
			$this->addAtk($user,$self,$self->base_atk * 0.20);
		}
	}	
	//����--50%�˺�
	class sm_9_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
			
			$buff = new ValueBuff('speed',round($self->base_speed * 0.1),1);
			$buff->addToTarget($user,$self);
		}
	}

?> 