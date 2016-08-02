<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//������׼�����200%����������
	class sm_18_0 extends SkillBase{
		public $isAtk = true;
		function canUse($user,$self=null,$enemy=null){
			$user->hitTimes ++;
			return true;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//������100%�˺����ٶ�+30% ��cd3
	class sm_18_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			
			$buff = new ValueBuff(array('speed'=>round($self->base_speed * 0.3)),2);
			$buff->addToTarget($self);
		}
	}
	
	//״̬����
	class sm_18_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->setStat31($self);
		}
	}

	
	//����100%�˺�
	class sm_18_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
		}
	}	
	//����--רע�����и���+10%����
	class sm_18_f2 extends SkillBase{
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

?> 