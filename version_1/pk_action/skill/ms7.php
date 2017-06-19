<?php 
	

	//����������ȡ��-�Է�50%MP���Լ����Ӷ�Ӧֵ�����ظ�20%Ѫ��
	class sm_7_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->addMp($user,$enemy,-$enemy->mp*0.5);
			
			$this->addHp($user,$self,$self->maxHp*0.3);
			$this->addMp($user,$self,-$v);
		}
	}
	
	//�����󣬰��Լ�ʣ���ŭ��ֵ���ӵ���һ��ս��λ����
	class sm_7_1 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_7_ds1#'.min(round($user->mp),50));
		}
	}

	class sm_7_ds1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$v = $this->addMp($user,$self,$this->tData);
		}
	}
	
	//ƽ�������Լ���������30%��-�Է�ȫ��15%����15%�٣�����һ��
	class sm_7_2 extends SkillBase{
		public $type = 'HP';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$player->addAtk(-$player->base_atk * 0.2);
				$player->addSpeed(-$player->base_speed * 0.15);
			}
		}
	}

	//����--�ظ�����*0.6��Ѫ��
	class sm_7_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1.5);
		}
	}	
	//����--���Է�10MP��cd3
	class sm_7_f2 extends SkillBase{
		public $cd = 3;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			
			$v = $this->addMp($user,$enemy,-10);
			$this->addMp($user,$self,-$v);
		}
	}

?> 