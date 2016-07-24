<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����˺�ѣ��������˺�250%
	class sm_4_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//��β���˺�+50%��cd3
	class sm_4_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//�ı����Ե�buff
	class sm_4_2_buff extends ValueBuff{
		function onEnd(){
			$this -> onClean();
			$buff = new ValueBuff(array('atk'=>-round($this->target->base_atk * 0.5)),10);
			$buff->addToTarget($this->target);
		}
	}
	
	//�񱩣�����������30%ʱ������+30% ��2�غϺ󣬹���-50%��10�غ�
	class sm_4_2 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate()<=0.3;
		}
		function action($user,$self,$enemy){
			$buff = new sm_4_2_buff(array('atk'=>round($self->base_atk * 0.3)),2);
			$buff->addToTarget($self);
		}
	}
	
	//������ ����������һ��λ15%����һ��
	class sm_4_3 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_4_ds3');
		}
	}
	class sm_4_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk * 0.15);
		}
	}
	

	
	//����---50%�˺�
	class sm_4_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//����--+10%����
	class sm_4_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk * 0.1);
		}
	}

?> 