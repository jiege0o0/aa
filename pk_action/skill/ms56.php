<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//������Ĭ���Է��޷�ʹ�þ��У�round5
	class sm_56_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new StatBuff(22,5);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
		}
	}
	
	//˥��:���Է�����15%������
	class sm_56_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$player->atk -= round($player->base_atk * 0.15);
			}
		}
	}
	
	//�鵯��+80%�˺�,cd3
	class sm_56_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}

	
	//����--��Ĭ���Է��޷�ʹ�þ��У�round3,cd5
	class sm_56_f1 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new StatBuff(22,3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
		}
	}	
	//����--��Ӧ�����ѷ�ʹ�þ���ʱ��׷��һ��100%�����
	class sm_56_f2 extends SkillBase{
		public $type = 'SKILL';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	//����--70%�˺�
	class sm_56_f3 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
		}
	}

?> 