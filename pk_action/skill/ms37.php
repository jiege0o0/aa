<?php 
	

	//����˥����-15%���������Ѫ��
	class sm_37_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.15,true);
		}
	}
	
	//���������ӶԷ�20%������-10%���Ѫ����cd3
	class sm_37_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$enemy->atk += round($enemy->base_atk*0.2);
			$this->decHp($user,$enemy,$enemy->maxHp*0.1,true);
		}
	}
	
	//��ʳ������ʱ��-������λ10%�������ظ��Լ�������λ�������20%��Ѫ��
	class sm_37_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$hp = 0;
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				$player->atk -= round($player->base_atk*0.1);
				$hp += $player->maxHp;
			}
			$this->addHp($user,$self,$hp*0.2);
		}
	}
	
	
	//����50%�˺�
	class sm_37_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//����--���Է�10%��ǰ�������ޣ����ӵ���������
	class sm_37_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$v = -$this->decHp($user,$enemy,$enemy->maxHp*0.1,true);
			$this->addHp($user,$self,$v,true);
		}
	}

?> 