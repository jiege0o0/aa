<?php 
	

	//�������ף�220%�˺�
	class sm_19_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.6);
		}
	}
	
	//���գ�80%�˺����𶾣�2round,cd5 5%Ѫ��
	class sm_19_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			
			$buff = new HPBuff(-$enemy->maxHp*0.05,2,'19_1');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//�������Է�ȫ��-10%��-10%��-10%��
	class sm_19_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addAtk($user,$player,-$player->base_atk*0.2);
				$this->addSpeed($user,$player,-$player->base_speed*0.2);
				if($player->isPKing)
					$this->addDef($user,$player,-20);
			}
		}
	}
	
	//����--�������Է�ȫ��-5%��-5%��-5%��
	class sm_19_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addAtk($user,$player,-$player->base_atk*0.1);
				$this->addSpeed($user,$player,-$player->base_speed*0.1);
				if($player->isPKing)
					$this->addDef($user,$player,-10);
			}
		}
	}	
	//����--120%�˺���cd3
	class sm_19_f2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
			
		}
	}

?> 