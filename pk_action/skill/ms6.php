<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�����ӻ���������+200%�˺�����30%Ѫ
	class sm_6_0 extends SkillBase{
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk*2);
			$this->addHp($user,$user,-$v*0.3);
		}
	}
	
	//�񵲣����˺�ʱ������1%�������޲���50%
	class sm_6_1 extends SkillBase{
		public $type='BEATK';
		function action($user,$self,$enemy){
			if($this->temp1<50)
			{
				$self->def += 1;
				$this->temp1 ++;
			}
				
		}
	}
	
	//��Ѫ��ÿ���չ����Է�ʧѪ��20%
	class sm_6_2 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$v = $this->decHp($user,$enemy,$user->atk);
			$this->addHp($user,$user,-$v*0.2);
		}
	}

	
	//����--60%�˺�
	class sm_6_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}	


?> 