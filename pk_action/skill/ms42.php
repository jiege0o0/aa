<?php 
	

	//��������������:����ȥ�з�buff,�ѷ�debuff
	class sm_42_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->cleanStat($player,false,999);
			}
			
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->cleanStat($player,true,999);
			}
			
			$this->addHp($user,$self,$user->atk);
		}
	}
	
	//��Ѫ��+�Լ�100%*atk������cd3
	class sm_42_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk);
		}
	}
	
	//���ӣ�ÿ���˺�������30%����
	class sm_42_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->maxHurt = 0.3;
		}
	}
	
	
	//����--80%��Ѫ+����debuff
	class sm_42_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1);
			$this->cleanStat($self,true,1);
		}
	}	
	//����--����ʱ�ؼ���20%�������
	class sm_42_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}

?> 