<?php 
	

	function sm_43_resetHurt($self,$v){
		return round((2-$self->getHpRate()*1)*$v);
	}
	
	
	//����Ѫ�Ĵ��ۣ��Լ�����-20%�����200%�˺�
	class sm_43_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$user,$user->hp*0.25);
			$this->decHp($user,$enemy,sm_43_resetHurt($user,$user->atk*2));
			
		}
	}
	
	//��棺����ʱ+20%�ܣ�round3��-�Լ�20%Ѫ���ԶԷ����60%�˺�;
	class sm_43_1 extends SkillBase{
		public $cd = 0;
		public $isAtk = true;
		public $order = -10;
		function action($user,$self,$enemy){
			$this->decHp($user,$user,$user->hp*0.3,false,true);
			$this->decHp($user,$enemy,sm_43_resetHurt($user,$user->atk*1));
			
			$buff = new ValueBuff('def',20,3);
			$buff->addToTarget($user,$self);
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
			$this->addSpeed($user,$self,$self->base_speed*0.1);

		}
	}	
	//����--50%�˺�
	class sm_43_f3 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,sm_43_resetHurt($self,$user->atk*0.8));
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