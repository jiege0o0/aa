<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//��������(��)��100%�˺� + 10%�������
	class sm_34_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk + $enemy->maxHp*0.1,true);
		}
	}
	
	//ԡ������������-�Է�10%Ѫ������10%Ѫ
	class sm_34_1 extends SkillBase{
		public $type='DIE';
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.1);
			$user->reborn(0.1);
		}
	}
	
	//���գ�100%�˺�����������˺�
	class sm_34_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk,true);
		}
	}
	
	
	//����--���գ�30%�˺�����������˺�
	class sm_34_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.3,true);
		}
	}	
?> 