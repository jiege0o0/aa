<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�������ߣ�130%�˺����ж�Һ��-��-Ѫ����round2�����������ã�     -15%�٣�ATK*0.5
	class sm_15_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk*0.5,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.15)),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	//������+30%�˺������������ã�
	class sm_15_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	//�������ڳ���ǰ�������ܵ��κ��˺��ͼ���Ӱ��
	class sm_15_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
		}
	}
	
	//����������ʱ������������10%�����ߺ��壬��������+100%������+20%������Ѫ
	class sm_15_3 extends SkillBase{
		public $type = 'BEFORE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.1;
		}
		
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->base_hp,true);
			$this->addHp($user,$self,$self->maxHp - $self->hp);
			$self->atk += round($self->base_atk*0.2);
			$self->skill->disabled = true;
			$self->skillArr[1]->disabled = true;
		}
	}
	
	//����--60%�˺�
	class sm_15_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}	
	//����--�綾��5cd,round3   -10%�٣�ATK*0.5
	class sm_15_f2 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk*0.5,3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.1)),3);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}

?> 