<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	//����ˮ�ܣ�������-20%�˺���ÿ�غϻ�10%Ѫ��round3
	class sm_8_0 extends SkillBase{
		function action($user,$self,$enemy){
		
			$buff = new ValueBuff(array('def'=>20),3);
			$buff->addToTarget($self);
			
			$buff = new HPBuff(round($self->maxHp*0.1),3);
			$buff->addToTarget($self);

			$this->setSkillEffect($self);
		}
	}
	
	//�ͻ���+30%�˺�,cd3
	class sm_8_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	//ˮ�ף�������С��20%ʱ��-50%�˺���round2,һ��
	class sm_8_2 extends SkillBase{
		public $type = 'HP';//���Լ���
		public $once = true;//����ִֻ��һ��
		
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.2;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('def'=>50),2);
			$buff->addToTarget($self);
			$this->setSkillEffect($self);
		}
	}

	//����--�����������غ����60%�˺���ż�ֻغϻظ�50%��Ѫ
	class sm_8_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			if($this->temp1 == 0)
			{
				$this->temp1 = 1;
				$this->decHp($user,$enemy,$user->atk*0.6);
				$this->isAtk = false;
			}
			else
			{
				$this->temp1 = 0;
				$this->addHp($user,$self,$user->atk*0.5);
				$this->isAtk = true;
			}
		}
	}	

?> 