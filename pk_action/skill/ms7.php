<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����������ȡ��-�Է�50%MP���Լ����Ӷ�Ӧֵ�����ظ�20%Ѫ��
	class sm_7_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->addMp($user,$enemy,-$enemy*0.5);
			$this->addMp($user,$self,-$v);
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}
	
	//������ÿ3�ι������ӳ�һ������ͷ��-10��MP��60%�˺�
	class sm_7_1 extends SkillBase{
		public $isAtk = true;
		public $cd = 3;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
			$this->addMp($user,$enemy,-10);
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
				$player->atk -= round($player->base_atk * 0.15);
				$player->speed -= round($player->base_speed * 0.15);
				$this->setSkillEffect($player);
			}
		}
	}

	//����--�ظ�����*0.6��Ѫ��
	class sm_7_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*0.6);
		}
	}	
	//����--���Է�10MP��cd3
	class sm_7_f2 extends SkillBase{
		public $cd = 3;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$this->addMp($user,$enemy,-10);
		}
	}

?> 