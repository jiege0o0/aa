<?php 
	

	//����������220%�˺�
	class sm_45_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = -$this->decHp($user,$enemy,$user->atk*2.5);
			$this->addHp($user,$self,$v*($user->stat[101]?3:1));
		}
	}
	
	//����������30%ʱ����Ѫ220%��round3
	class sm_45_1 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$buff = new StatBuff(101,3);
			$buff->noClean = true;
			$buff->value = '45_1_1';
			$buff->addToTarget($user,$self);
			$this->setSkillEffect($self);
			
			// global $pkData;
			// $pkData->out_debug('QQQQQ');
		}
	}
	
	//��Ѫ��ÿ�ι�����Ѫ50%
	class sm_45_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = -$this->decHp($user,$enemy,$user->atk);
			$this->addHp($user,$self,$v*($user->stat[101]?3:1));
		}
	}
	
	
	//����--40%�˺�
	class sm_45_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//����--+10%��
	class sm_45_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk*0.1);
		}
	}

?> 