<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//�����ͻ���������100%�˺�����һ�غ�
	class sm_49_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
		
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//����������������30%ʱ������ÿ���ж����10%Ѫ
	class sm_49_1 extends SkillBase{
		public $type = 'AFTER';
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate()<=0.3;
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	
	//����-- 50%�˺�
	class sm_49_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//����--�ͻ���100%�˺���cd4
	class sm_49_f2 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
		}
	}

?> 