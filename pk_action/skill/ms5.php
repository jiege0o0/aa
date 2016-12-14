<?php 
	
	
	//����˺�ѣ��������˺�200%
	class sm_5_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//���������󸴻�ظ�20%Ѫ����1��
	class sm_5_1 extends SkillBase{
		public $type = 'DIE';
		public $once = true;//����ִֻ��һ��
		function canUse($user,$self=null,$enemy=null){
			return $user->hp<=0;
		}
		function action($user,$self,$enemy){
			$user->reborn(0.2);
		}
	}
	
	//������ÿ�ι�����-10%�ٶȣ�1�غ�
	class sm_5_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//������ÿ��һ�������ս���˺�+20%
	class sm_5_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$num = $user->team->monsterBase->{$user->monsterID}->num;	
			$user->addAtk($user->base_atk*0.2*$num);
		}
	}
	
	//����--50%�˺���-10%�ٶȣ�1�غ�
	class sm_5_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}	
	//����������������ʱ,�ֵ�һ�Σ��Լ���2�غϣ�����һ��
	class sm_5_f2 extends SkillBase{
		public $type = 'DMISS';
		public $isSendAtOnce = true;
		function canUse($user,$self=null,$enemy=null){
			return $this->tData['id'] == $user->id;
		}
		function action($user,$self,$enemy){
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user);
		}
	}
	class sm_5_f3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			array_push($self->dieMissTimes,array("id"=>$user->id,'mid'=>$user->monsterID,"type"=>'atk'));
		}
	}


?> 