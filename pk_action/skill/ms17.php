<?php 
	

	//����ʥ�⣨��������30%����������25%Ѫ��3round
	class sm_17_0 extends SkillBase{
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('atk'=>round($self->base_atk * 0.3)),3);
			$buff->addToTarget($self);
			
			$this->addHp($user,$self,$self->maxHp*0.25);
		}
	}
	
	//Ѫն������[5%]��ǰ���������[230%]�˺�#CD|3
	class sm_17_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.05);
			$this->decHp($user,$enemy,$user->atk*2.3);
		}
	}
	
	//����:�Է�-10%��
	class sm_17_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->addAtk(-$enemy->base_atk*0.1);
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
			$enemy->addAtk(-$enemy->base_atk*0.1);
		}
	}

?> 