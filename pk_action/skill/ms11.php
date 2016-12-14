<?php 
	

	//�����ͻ���230%�˺�
	class sm_11_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//��Ѫ:������غ�ʱ�������������50%������+30%��round1
	class sm_11_1 extends SkillBase{
		public $type = 'BEFORE';//���Լ���
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.5;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',round($self->base_atk * 0.3),1);
			$buff->addToTarget($self);
		}
	}
	
	//���ݣ����غϽ���ʱ�������������50%���ٶ�+30%��round1
	class sm_11_2 extends SkillBase{
		public $type = 'AFTER';//���Լ���
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() > 0.5;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.3),2);
			$buff->addToTarget($self);
		}
	}
	
	//���裺������10%������
	class sm_11_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$self->addAtk($player->base_atk * 0.1);
			}
		}
	}
	
	//����--+10%��
	class sm_11_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->addAtk($self->base_atk * 0.15);
		}
	}	
	//����--50%��
	class sm_11_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 