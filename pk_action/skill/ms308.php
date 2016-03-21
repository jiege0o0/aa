<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�����ظ�100%Ѫ������+10%
	class sm_308_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
			$this->addHp($user,$self,$user->atk*1);
		}
	}
	
	//С����150%�˺�
	class sm_308_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//�أ������Լ���λ�� + 30%
	class sm_308_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$target = $user->team->currentMonster[$i];
				$this->addAtk($user,$target,$target->base_atk*0.3);
			}
				
		}
	}
	
	//������ + 30%��cd3��round1  
	class sm_308_f1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$v = $this->addAtk($user,$self,$self->base_atk*0.3);
			$self->addState($user,array('atk'=>$v),1);
		}
	}
	
?> 