<?php 
	

	//����˺�ѣ��������˺�250%
	class sm_4_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.8);
		}
	}
	
	//��β���˺�+50%��cd3
	class sm_4_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}
	
	//�ı����Ե�buff
	class sm_4_2_buff extends ValueBuff{
		function onEnd(){
			$this -> onClean();
			$buff = new ValueBuff('atk',-round($this->target->base_atk * 0.5),10);
			$buff->addToTarget($this->target);
		}
	}
	
	//�񱩣�����������50%ʱ������+35% ��3�غϺ󣬹���-50%��10�غ�
	class sm_4_2 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate()<=0.5;
		}
		function action($user,$self,$enemy){
			$buff = new sm_4_2_buff('atk',round($self->base_atk * 0.5),3);
			$buff->addToTarget($self);
		}
	}
	
	//������ ����������һ��λ15%��
	class sm_4_3 extends SkillBase{
		public $type = 'DIE';
		public $once = true;//����ִֻ��һ��
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_4_ds3');
		}
	}
	class sm_4_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->addAtk($self->base_atk * 0.2);
		}
	}
	

	
	//����---50%�˺�
	class sm_4_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//����--+10%����
	class sm_4_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->addAtk($self->base_atk * 0.1);
		}
	}

?> 