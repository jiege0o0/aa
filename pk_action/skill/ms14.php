<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�����þ����Է��ĸ�����λ�޷��ж���2round
	class sm_14_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//��ȭ��+50%�˺���cd3
	class sm_14_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//�һ��飺˫�����ֺ�Ҫ-3%����
	class sm_14_2 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->maxHp*0.03);
		}
	}
	class sm_14_5 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.03);
		}
	}
	
	
	//����--�һ��飺˫�����ֺ�Ҫ-3%����
	class sm_14_f1 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->maxHp*0.03);
		}
	}
	class sm_14_f5 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.03);
		}
	}
	
	//����--70%�˺�
	class sm_14_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.7);
		}
	}

?> 