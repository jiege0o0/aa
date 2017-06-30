<?php 
	

	//������֮��(��)���ٳ�200%�˺����������Լ��ٶ�15%��2round
	class sm_2_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			
			$buff = new ValueBuff('speed',round($self->base_speed * 0.2),2);
			$buff->addToTarget($user,$self);
		}
	}
	
	// ���ۣ��������˵Ĺ������ܿ����˵�һ���˺���5����һ��
	class sm_2_1 extends SkillBase{
		public $type = 'BEATK';		
		function action($user,$self,$enemy){
			$this->temp1 ++;
			if($this->temp1 >=3)
			{
				$this->temp1 = 0;
				$self->missTimes ++;
			}
		}
	}
	
	//��ԣ�5�λ������ԣ�-15%��2�غ�
	class sm_2_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//���糡��������λ���� +10%���ٶ�+10%
	class sm_2_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->addAtk($user,$player,$player->base_atk * 0.1);
				$this->addSpeed($user,$player,$player->base_speed * 0.1);
			}
		}
	}
	
	//����--��10%�ٶ�+10%����
	class sm_2_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk * 0.1);
			$this->addSpeed($user,$self,$self->base_speed * 0.1);
		}
	}
	
	//����--50%�˺�
	class sm_2_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.9);
		}
	}

?> 