<?php 
	
	
	function sm_36_addValue($self,$enemy,$rate){
		$v1 = max(1,round($enemy->base_atk * $rate));
		$v2 = max(1,round($enemy->base_speed * $rate));
		$enemy->addAtk(-$v1);
		$enemy->addSpeed(-$v2);
		$self->addAtk($v1);
		$self->addSpeed($v2);
	}

	//����������������220%��
	class sm_36_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
			sm_36_addValue($user,$enemy,0.08);
			
		}
	}
	
	//������+30%�˺�
	class sm_36_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
			
			
			sm_36_addValue($user,$enemy,0.08);
		}
	}
	
	//��ȡ��ÿ�μ��ټ������ӵ��Լ����� 3%
	class sm_36_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			sm_36_addValue($user,$enemy,0.05);
		}
	}

	
	//����--��ȡ��ÿ�μ��ټ������ӵ��Լ�����  2%
	class sm_36_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
			sm_36_addValue($user,$enemy,0.03);
		}
	}	
	//����--50%�˺�

?> 