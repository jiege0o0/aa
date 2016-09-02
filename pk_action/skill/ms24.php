<?php 
	
	//����160%�˺�����һ�غ�;
	class sm_24_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
			
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//�׵绤�ܣ������к�-�Է�20%�ٶȣ�round1,(10��)
	class sm_24_1 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$enemy = $this->tData[0];
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.2)),2);//��ʱ�Է��غ�δ����
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->temp1 ++;
			if($this->temp1 >= 10)
				$this->disabled = true;
			
		}
	}
	
	//�������+����15%����round5
	class sm_24_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$buff = new ValueBuff(array('atk'=>round($player->base_atk * 0.15)),5);
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//����-- �׵绤�ܣ������к�-�Է�20%�ٶȣ�round1,(5��)
	class sm_24_f1 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$enemy = $this->tData[0];
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.2)),2);//��ʱ�Է��غ�δ����
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->temp1 ++;
			if($this->temp1 >= 5)
				$this->disabled = true;
		}
	}	
	//����-- 60%��
	class sm_24_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}

?> 