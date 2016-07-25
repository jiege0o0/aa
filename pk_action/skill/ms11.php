<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：猛击：230%伤害
	class sm_11_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.3);
		}
	}
	
	//嗜血:当进入回合时，如果生命低于50%，攻击+30%，round1
	class sm_11_1 extends SkillBase{
		public $type = 'BEFORE';//特性技能
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.5;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('atk'=>round($enemy->base_atk * 0.3)),1);
			$buff->addToTarget($self);
		}
	}
	
	//敏捷：当回合结束时，如果生命高于50%，速度+30%，round1
	class sm_11_2 extends SkillBase{
		public $type = 'AFTER';//特性技能
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() > 0.5;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff(array('speed'=>round($enemy->base_speed * 0.3)),1);
			$buff->addToTarget($self);
		}
	}
	
	//鼓舞：吸辅助10%攻击力
	class sm_11_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$self->atk += round($player->base_atk * 0.1);
			}
			$this->setSkillEffect($self);
		}
	}
	
	//辅：--+10%攻
	class sm_11_f1 extends SkillBase{
		public $cd = 5;
		function action($user,$self,$enemy){
			$self->atk += round($player->base_atk * 0.1);
			$this->setSkillEffect($self);
		}
	}	
	//辅：--50%伤
	class sm_11_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 