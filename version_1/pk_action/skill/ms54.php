<?php 
	

	//����ӡ�Ǵ��������ܣ����������л���ӡ��,����Խ�࣬�˺�Խ��ӡ�ǲ���ʧ��
	class sm_54_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			if(!$user->temp['sm54'])
				$user->temp['sm54'] = 0;
			$this->decHp($user,$enemy,$user->atk*($user->temp['sm54'])*0.5 + 1);
		}
	}
	
	//���׵���ÿ�ι������л���ӡ��
	class sm_54_1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			if(!$user->temp['sm54'])
				$user->temp['sm54'] = 0;
			$user->temp['sm54'] ++;
		}
	}
	
	//������+20%,һ�θ�������ӡ�ǣ�cd3
	class sm_54_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			if(!$user->temp['sm54'])
				$user->temp['sm54'] = 0;
			$user->temp['sm54'] += 2;
		}
	}

	
	//����--30%�˺�+2%�������ֵ
	class sm_54_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5 + $enemy->maxHp*0.05);
		}
	}	
	//����--+10%������
	class sm_54_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk * 0.1);
		}
	}

?> 