<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�������紸��120%�˺�����2�غ�
	class sm_46_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
			
			$buff = new StatBuff(24,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//����ȫ��-��20%��round2,cd5
	class sm_46_1 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$buff = new ValueBuff(array('speed'=>-round($player->base_speed * 0.2)),2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//�����·���10���ж��󣬷�+20%����+50%
	class sm_46_2 extends SkillBase{
		public $type='AFTER';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			$this->temp1 ++;
			return $this->temp1 >= 10;
		}
		function action($user,$self,$enemy){
			$user->atk += round($user->base_atk*0.5);
			$user->def += 20;
			$this->setSkillEffect($user);
			debug($this->temp1);
		}
	}
	
	//+20%��
	class sm_46_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->def += 20;
		}
	}
	
	//����--50%�˺�
	class sm_46_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}	
	
	//����--+10%��
	class sm_46_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->def += 10;
		}
	}

?> 