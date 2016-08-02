<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	function sm_43_resetHurt($self,$v){
		return round((2-$self->getHpRate())*$v);
	}
	
	
	//����Ѫ�Ĵ��ۣ��Լ�����-20%�����200%�˺�
	class sm_43_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->maxHp*0.2);
			$this->decHp($user,$enemy,sm_43_resetHurt($user,$user->atk*2));
			
		}
	}
	
	//��棺����ʱ+30%�ܣ�round3��-�Լ�10%Ѫ���ԶԷ����60%�˺�;
	class sm_43_1 extends SkillBase{
		public $cd = 0;
		public $isAtk = true;
		public $order = -10;
		function action($user,$self,$enemy){
			$this->setSkillEffect($enemy);
			$this->decHp($user,$self,$self->maxHp*0.1);
			$this->decHp($user,$enemy,sm_43_resetHurt($user,$user->atk*0.6));
			
			$buff = new ValueBuff(array('def'=>30),4);
			$buff->addToTarget($self);
		}
	}
	
	//Ѫ��ζ�����Լ�����Խ�٣��˺�Խ��
	class sm_43_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,sm_43_resetHurt($user,$user->atk));
		}
	}
	
	
	//����--+10%��
	class sm_43_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->speed += round($self->base_speed*0.1);
			$this->setSkillEffect($self);
		}
	}	
	//����--50%�˺�
	class sm_43_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,sm_43_resetHurt($self,$user->atk*0.5));
		}
	}
	//����--Ѫ��ζ�����Լ����ϵ�λ����Խ�٣��˺�Խ��
	// class sm_43_f2 extends SkillBase{
		// public $cd = 1;
		// function action($user,$self,$enemy){
			// $this->decHp($user,$enemy,$user->atk*0.5);
			// $this->cleanStat($enemy,-1,1);
		// }
	// }

?> 