<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//��������δ�գ�800%�˺�
	class sm_41_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*8);
		}
	}
	
	//ͬ�飺���������100%�˺�
	class sm_41_1 extends SkillBase{
		public $type='DIE';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
		}
	}
	
	//���䣺�Է��ܵ��Լ��˺���10%
	class sm_41_2 extends SkillBase{
		public $type='BEHURT';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,-$this->tData[1]*0.1);
		}
	}
	
	//��ӡ��������Ч
	class sm_41_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			if(!$enemy->stat[23])
				$enemy->stat[23] = 1;
			else
				$enemy->stat[23] ++;
		}
	}
	
	//����--��ӡ��������Ч
	class sm_41_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			if(!$enemy->stat[23])
				$enemy->stat[23] = 1;
			else
				$enemy->stat[23] ++;
		}
	}	
	//����--50%�˺�
	class sm_41_f2 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 