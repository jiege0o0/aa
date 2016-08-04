<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����ʥ�⣨��������50%����������20%Ѫ��3round
	class sm_17_0 extends SkillBase{
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('atk'=>round($self->base_speed * 0.5)),3);
			$buff->addToTarget($self);
			
			$this->addHp($user,$self,$self->maxHp*0.2);
		}
	}
	
	//Ѫն��+60%�˺���cd3
	class sm_17_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}
	
	//����:�Է�-10%��
	class sm_17_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->atk -= round($enemy->base_atk*0.1);
			$this->setSkillEffect($enemy);
		}
	}

	
	//������HP��50%��60%�˺� ����60%��Ѫ
	class sm_17_f1 extends SkillBase{
		public $cd = 1;
		function canUse($user,$self=null,$enemy=null){
			if($self->getHpRate()>0.5)
				$this->isAtk = true;
			else
				$this->isAtk = false;
			return true;
		}
		function action($user,$self,$enemy){
			if($this->isAtk)
				$this->decHp($user,$enemy,$user->atk*0.6);	
			else
				$this->addHp($user,$self,$user->atk*0.6);	
		}
	}	
	//����--�Է�-10%��
	class sm_17_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->atk -= round($enemy->base_atk*0.1);
			$this->setSkillEffect($enemy);
		}
	}

?> 