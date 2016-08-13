<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	function sm_62_king($user){
		return $user->add_hp > $user->base_hp * 2;
	}
	
	//�����Ϳ�:180%�˺�
	class sm_62_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//��������100%Ѫ������=��ս��ʿ������������״̬�²����ã�
	class sm_62_1 extends SkillBase{
		public $type='DIE';
		function canUse($user,$self=null,$enemy=null){
			$num = $user->team->monsterBase->{$user->monsterID}->num;
			return $self->hp<=0 && !sm_62_king($user) && $num > $this->temp1;
		}
		function action($user,$self,$enemy){
			$user->reborn(1);
			$this->temp1 ++;
		}
	}
	
	//ɱ����������ܻ�ɱ���ˣ����� + 400%������+100%������Ѫ��ֻ����һ�Σ�
	class sm_62_2 extends SkillBase{
		public $type='EDIE';
		public $once=true;
		function canUse($user,$self=null,$enemy=null){
			return !sm_62_king($user);
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->base_hp*4,true,true);
			$v = round($user->base_atk*1);  
			$user->add_atk += $v;
			$user->atk += $v;
			$this->addHp($user,$self,$self->maxHp - $self->hp);
		}
	}
	
	//����--60%�˺�
	class sm_62_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}	


?> 