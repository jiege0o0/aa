<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//����������Ѫ����10%
	class sm_507_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp*0.1,true);
		}
	}
	
	//С����150%�˺���CD3
	class sm_507_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//�أ�������Ѫ���ޣ�����20%��
	class sm_507_2 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_507_ds2#'.(round($user->maxHp*0.2)));
		}
	}
	class sm_507_ds2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$this->tData,true);
		}
	}
	
	//�����Է�����-20%
	class sm_507_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$enemy,-$enemy->base_atk*0.2);
		}
	}
	
	

?> 