<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����նɱ��������+���Է����˰ٷֱ�*5��%��
	class sm_22_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*(0 + (1-$enemy->getHpRate())*5));
		}
	}
	
	//�ػ���+50%��cd3;
	class sm_22_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			$enemy->def -= 2;
		}
	}
	
	//�����˿ڣ�ÿ�ι���-2%��
	class sm_22_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			$enemy->def -= 2;
		}
	}
	
	//ս�𣺽����Է�����-20%�٣�-15%����round3
	class sm_22_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$buff = new ValueBuff(array('speed'=>-round($player->base_speed * 0.2),'atk'=>-round($player->base_speed * 0.15)),3);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//����-- 50%�ˣ�����Է���������50%��100%��
	class sm_22_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			if($enemy->getHpRate() <= 0.5)
				$this->decHp($user,$enemy,$user->atk);
			else
				$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	

?> 