<?php 
	

	//�����ӻ���������+220%�˺�����40%Ѫ
	class sm_6_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk*2.2);
			$this->addHp($user,$user,-$v*0.4);
		}
	}
	
	//�񵲣����˺�ʱ������1%�������޲���50%
	class sm_6_1 extends SkillBase{
		public $type='BEATK';
		function action($user,$self,$enemy){
			if($this->temp1<30)
			{
				$self->def += 1;
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
			$this->addHp($user,$user,-$v*0.25);
		}
	}

	
	//����--60%�˺�
	class sm_6_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}	


?> 