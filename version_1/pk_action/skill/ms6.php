<?php 
	

	//�����ӻ���������+220%�˺�����40%Ѫ
	class sm_6_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk*3);
			$this->addHp($user,$user,-$v*0.8);
		}
	}
	
	//�񵲣����˺�ʱ������1%�������޲���30%
	class sm_6_1 extends SkillBase{
		public $type='BEATK';
		function action($user,$self,$enemy){
			if($this->temp1<25)
			{
				$self->addDef(2);
				$this->temp1 ++;
			}
				
		}
	}
	
	//��Ѫ��ÿ���չ����Է�ʧѪ��25%
	class sm_6_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk);
			$this->addHp($user,$user,-$v*0.3);
		}
	}

	
	//����--60%�˺�
	class sm_6_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.9);
		}
	}	


?> 