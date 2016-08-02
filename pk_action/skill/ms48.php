<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//����������˫������ֹͣ�ж�2�غϣ�+30%�� ,3round
	class sm_48_0 extends SkillBase{
		
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(24,2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
			
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				
				$buff = new StatBuff(24,2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
			
				
			$buff = new ValueBuff(array('atk'=>round($user->base_atk * 0.3)),3);
			$buff->addToTarget($user);
			$this->setSkillEffect($user);
			
		}
	}
	
	//���𣺶Է�ͬ���ܵ��˺���20%��ֻ�����ǣ�
	class sm_48_1 extends SkillBase{
		public $type="BEATK";
		function canUse($user,$self=null,$enemy=null){
			return $this->tData[0]->isPKing;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,-$this->tData[1]*0.2);
		}
	}
	
	//�ػ���30%�˺�,cd3
	class sm_48_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}
	
	//���裺����+10%��
	class sm_48_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->atk += round($player->base_atk * 0.1);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//����-- 50%�˺�
	class sm_48_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	//����--���裺+10%��
	class sm_48_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk * 0.1);
		}
	}

?> 