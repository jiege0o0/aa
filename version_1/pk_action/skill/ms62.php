<?php 
	
	
	function sm_62_king($user){
		return $user->add_hp > $user->base_hp * 2;
	}
	
	//�����Ϳ�:160%�˺�
	class sm_62_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}
	
	//��������100%Ѫ������=��ս��ʿ������������״̬�²����ã�
	class sm_62_1 extends SkillBase{
		public $type='DIE';
		function canUse($user,$self=null,$enemy=null){
			$num = $user->team->monsterBase->{$user->monsterID}->num + 1;
			return $self->hp<=0 && !sm_62_king($user) && $num > $this->temp1;
		}
		function action($user,$self,$enemy){
			$user->reborn(1);
			$this->temp1 ++;
		}
	}
	
	//ɱ�������ϳ�ʱ�������������� + 300%������+100%������Ѫ��ֻ����һ�Σ�
	class sm_62_2 extends SkillBase{
		public $type='EDIE';
		public $once=true;
		function canUse($user,$self=null,$enemy=null){
			return !sm_62_king($user);
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->base_hp*4,true,true,true);
			$v = round($user->base_atk*1);  
			$user->add_atk += $v;
			$this->addAtk($user,$user,$v);
			
		}
	}
	
	class sm_62_3 extends SkillBase{
		public $cd = 0;
		public $order = 20;
		function action($user,$self,$enemy){
			$this->setStat31($user);
		}
	}
	
	//����--80%�˺�
	class sm_62_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	
	class sm_62_f2 extends SkillBase{
		public $cd = 0;
		public $order = 20;
		function action($user,$self,$enemy){
			$this->setStat31($user);
		}
	}


?> 