<?php 
	//��սʱ����[10%]�ٶȣ�����[3]�غ�
	class ls_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.1),3);
			$buff->addToTarget($user,$self);
		}
	}
	
	//��սʱ����[10%]����������[3]�غ�
	class ls_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',round($self->base_atk * 0.1),3);
			$buff->addToTarget($user,$self);
		}
	}
	
	//��սʱ����[10%]����������[3]�غ�
	class ls_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',10,3);
			$buff->addToTarget($user,$self);
		}
	}
	
	//ս������ʱ�������Ȼ����ظ�[10%]����
	class ls_4 extends SkillBase{
		public $type = 'OVER';
		function canUse($user,$self=null,$enemy=null){
			return $self->hp > 0;
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//��������˺���������ֵ�һ���˺�
	class ls_5 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			array_push($self->dieMissTimes,array("id"=>$user->id,'mid'=>5,"type"=>'atk'));
		}
	}
?> 