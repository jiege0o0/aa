<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：升空（技）：无视接下来的5次攻击，并增加10%速度，3round
	class sm_9_0 extends SkillBase{
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('speed'=>round($self->base_speed * 0.1)),3);
			$buff->addToTarget($self);
			
			$self->missTimes += 5;
			
			$this->setSkillEffect($self);
		}
	}
	
	//龙吼：cd5,减全体速15%，2round
	class sm_9_1 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$buff = new ValueBuff(array('speed'=>-round($player->base_speed * 0.15)),2);
				$buff->isDebuff = true;
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//钢索：进入时固定对方1个回合，无法行动
	class sm_9_2 extends SkillBase{
		public $cd = 0;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			$this->setSkillEffect($enemy);
		}
	}
	
	//增速：辅助+10%速度
	class sm_9_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->base_speed += round($player->base_speed * 0.1);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//辅：--鞭笞：-8%当前血量，增加15%攻击力
	class sm_9_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.08);
			$self->atk += round($self->base_atk * 0.15);
		}
	}	
	//辅：--50%伤害
	class sm_9_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 