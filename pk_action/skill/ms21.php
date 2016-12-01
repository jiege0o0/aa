<?php 
	

	//����ն����������180%��
	class sm_21_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//ͻ��������ʱ+50%�ٶȣ�+50%������round3
	class sm_21_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.5),3);
			$buff->addToTarget($self);
			
			$buff = new ValueBuff('atk',round($self->base_atk * 0.5),3);
			$buff->addToTarget($self);
		}
	}
	
	//�ػ���+20%��cd2;
	class sm_21_2 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//ս�𣺸���+30%�٣�20%����round3
	class sm_21_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
					
				$buff = new ValueBuff('speed',round($player->base_speed * 0.3),3);
				$buff->addToTarget($player);
				
				$buff = new ValueBuff('atk',round($player->base_atk * 0.2),3);
				$buff->addToTarget($player);
			}
		}
	}
	
	//����-- ս��+20%�٣�10%����round3
	class sm_21_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.2),3);
			$buff->addToTarget($self);
			
			$buff = new ValueBuff('atk',round($self->base_atk * 0.1),3);
			$buff->addToTarget($self);
		}
	}	
	//����-- 60%��
	class sm_21_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}

?> 