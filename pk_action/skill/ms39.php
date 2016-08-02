<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����ը������ظ��Է�10%�������Է��غϽ���ʱ��300%�˺���round1
	class sm_39_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->addHp($user,$enemy,$enemy->maxHp*0.1);
			$buff = new HPBuff(-$user->atk*3,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			if(!$user->temp['sendGift'])
				$user->temp['sendGift'] = 0;
			$user->temp['sendGift'] ++;
		}
	}
	
	//�ǹ����ظ��Է�10%������-10%�ٶȣ�cd3
	class sm_39_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->addHp($user,$enemy,$enemy->maxHp*0.1);
			$enemy->speed -= round($enemy->base_speed*0.1);
			
			if(!$user->temp['sendGift'])
				$user->temp['sendGift'] = 0;
			$user->temp['sendGift'] ++;
		}
	}
	
	//ͬ�֣����Է��ظ�����ʱ���Լ��ظ���ظ�ֵ50%����
	class sm_39_2 extends SkillBase{
		public $type='EBEHEAL';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$this->tData*0.5);
		}
	}
	
	//�������ˣ���Ϊ�Է��͵�����>5��ʱ��ÿ��һ�����������Լ�10%������
	class sm_39_3 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			if($user->temp['sendGift'] != $this->temp)
			{
				$this->temp = $user->temp['sendGift'];
				if($this->temp > 2)
				{
					$user->atk += round($user->base_atk*0.1);
					$this->setSkillEffect($user);
				}
			}
		}
	}
	
	//����--�ظ�������100%Ѫ����5�κ���80%�˺�
	class sm_39_f1 extends SkillBase{
		public $cd = 1;
		function canUse($user,$self=null,$enemy=null){
			if($this->temp1 <5)
				$this->isAtk = true;
			else
				$this->isAtk = false;
			return true;
		}
		function action($user,$self,$enemy){
			if($this->temp1 <5)
			{
				$this->addHp($user,$self,$user->atk);
			}
			else
			{
				$this->decHp($user,$enemy,$user->atk*0.8);
			}
			$this->temp1 ++;
		}
	}	

?> 