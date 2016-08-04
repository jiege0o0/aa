<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����������200%�˺�
	class sm_45_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = -$this->decHp($user,$enemy,$user->atk);
			$this->addHp($user,$self,$v*($user->stat[101]?2:0.3));
		}
	}
	
	//����������30%ʱ����Ѫ200%��round2
	class sm_45_1 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$buff = new StatBuff(101,2);
			$buff->noClean = true;
			$buff->addToTarget($self);
		}
	}
	
	//��Ѫ��ÿ�ι�����Ѫ30%
	class sm_45_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = -$this->decHp($user,$enemy,$user->atk);
			$this->addHp($user,$self,$v*($user->stat[101]?2:0.3));
		}
	}
	
	
	//����--50%�˺�
	class sm_45_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//����--+10%��
	class sm_45_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk*0.1);
			$this->setSkillEffect($self);
		}
	}

?> 