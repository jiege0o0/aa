<?php 
	
	

	//������棨������+20%�ٶȣ����200%�˺���round3
	class sm_30_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('speed'=>round($self->base_speed * 0.2)),3);
			$buff->addToTarget($self);
			
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//�ͻ���+50%�˺�
	class sm_30_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//�˷ܼ�������ʱ+50%����ÿ���ж���-10%
	class sm_30_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk*0.5);
		}
	}	
	class sm_30_5 extends SkillBase{
		public $type='AFTER';
		function action($user,$self,$enemy){
			$self->atk -= round($self->base_atk*0.1);
		}
	}
	
	//����������������һ��λ20%���Ѫ��(����)
	class sm_30_3 extends SkillBase{
		public $type='DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_30_ds3');
		}
	}
	class sm_30_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp * 0.2,true,true);
		}
	}
	
	
	
	//����--��ս������2��λʱ������+20%
	class sm_30_f1 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $user->pos == 1;
		}
		
		function action($user,$self,$enemy){
			$user->atk += round($user->base_atk*0.2);
		}
	}	
	//����--60%�˺�
	class sm_30_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);;
		}
	}

?> 