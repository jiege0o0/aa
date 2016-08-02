<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//������Ȼ֮�ף�������Ϊ�Լ��ֵ�3���˺�����10%Ѫ
	class sm_50_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
			$self->missTimes += 3;
		}
	}
	
	//��Ȼ֮�����ж�������ظ��Լ�5%Ѫ
	class sm_50_1 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.05);
		}
	}
	
	//ħ��
	class sm_50_2 extends SkillBase{
		public $cd = 0;
		public $order = 10;
		function action($user,$self,$enemy){
			$this->setStat31($user);
		}
	}
	
	
	//����--�ظ�2% + atk*0.5Ѫ
	class sm_50_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.02 + $user->atk*0.5);
		}
	}	
	//����--��Ȼ֮�ף��ֵ�2���˺���cd5
	class sm_50_f2 extends SkillBase{
		public $cd = 5;
		public $order = 1;
		function action($user,$self,$enemy){
			$self->missTimes += 2;
		}
	}

?> 