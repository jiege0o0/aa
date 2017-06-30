<?php 
	

	//����ը������ظ��Է�10%�������Է��غϽ���ʱ���ظ�ֵ*250%�˺���round1
	class sm_39_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->addHp($user,$enemy,$enemy->maxHp*0.25);
			$buff = new HPBuff(-$v*3,1,'39_0');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			if(!$user->temp['sendGift'])
				$user->temp['sendGift'] = 0;
			$user->temp['sendGift'] ++;
		}
	}
	
	//�ǹ����ظ��Է�10%������-10%�ٶȣ�cd3
	class sm_39_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$enemy,$enemy->maxHp*0.15);
			$this->addSpeed($user,$enemy,-$enemy->base_speed*0.1);
			$this->addAtk($user,$enemy,-$enemy->base_atk*0.1);
			
			if(!$user->temp['sendGift'])
				$user->temp['sendGift'] = 0;
			$user->temp['sendGift'] ++;
		}
	}
	
	//ͬ�֣����Է��ظ�����ʱ���Լ��ظ���ظ�ֵ50%����
	class sm_39_2 extends SkillBase{
		public $type='EBEHEAL';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$this->tData*0.8);
		}
	}
	
	//�������ˣ���Ϊ�Է��͵�����>5��ʱ��ÿ��һ�����������Լ�10%������
	class sm_39_3 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			if($user->temp['sendGift'] != $this->temp)
			{
				$this->temp = $user->temp['sendGift'];
				if($this->temp > 5)
				{
					$this->addAtk($user,$user,$user->base_atk*0.1);
				}
			}
		}
	}
	
	//����--�ظ�������100%Ѫ����5�κ���80%�˺�
	class sm_39_f1 extends SkillBase{
		public $cd = 1;
		function canUse($user,$self=null,$enemy=null){
			return !$user->temp['giftatk'] || $user->temp['giftatk'] < 5;
		}
		function action($user,$self,$enemy){
			if(!$user->temp['giftatk'])
				$user->temp['giftatk'] = 0;
			$user->temp['giftatk'] ++;
			$this->addHp($user,$self,$user->atk);
		}
	}	
	
	class sm_39_f5 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		public $clientIndex=1;
		public $isSendAtOnce = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->temp['giftatk'] && $user->temp['giftatk'] >= 5;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 