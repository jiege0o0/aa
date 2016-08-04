<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����������180%�˺�
	class sm_47_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//�򵥰�������20%Ѫ��cd4
	class sm_47_1 extends SkillBase{
		public $cd = 4;
		public $isSendAtOnce = true;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->maxHp*0.2);
		}
	}
	
	//������������ʿ��ͬʱ��ս����+10%��Ѫ����+20%
	class sm_47_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$num = $user->team->monsterBase->{$user->monsterID}->num;	
			$user->atk += round($user->base_atk*0.1*$num);
			$this->addHp($user,$self,$user->base_hp*0.2*$num,true);
		}
	}
	
	
	//����--60%�˺�
	class sm_47_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}	
	//����--�򵥰�������atk*0.7Ѫ��cd3
	class sm_47_f2 extends SkillBase{
		public $cd = 3;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*0.7);
		}
	}

?> 