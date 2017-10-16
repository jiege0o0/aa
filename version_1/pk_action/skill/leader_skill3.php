<?php 
	//移除我方一个负面状态，施法间隔：[1]
	class ls_21 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$b = $this->cleanStat($user,$player,true,1);
				if($b)
					break;
			}
		}
	}
	
	//移除敌方一个正面状态，施法间隔：[1]
	class ls_22 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$b = $this->cleanStat($user,$player,false,1);
				if($b)
					break;
			}
		}
	}
	
	//使我方出战单位获得魔免，持续[3]回合
	class ls_23 extends SkillBase{
		public $cd = 0;
		public $order = 1000;
		function action($user,$self,$enemy){
			$buff = new StatBuff(31,3);
			$buff->removeAble = false;
			$buff->addToTarget($user,$self);
		}
	}
	
	//我方出战时增加[10点]怒气
	class ls_24 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}
	
	//增加对方出战单位[5点]怒气上限
	class ls_25 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->maxMp += 5;
			$this->setSkillEffect($enemy,pk_skillType('MMP',5));
		}
	}
	
	
	//当对方回血时，对其造成[r20%]伤害
	class ls_26 extends SkillBase{
		public $type='EBEHEAL';
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*0.45);
		}
	}
	
	//我方使用绝招后回复[g10%]生命
	class ls_27 extends SkillBase{
		public $type = 'SKILL';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}

	
	//我方使用绝招后回复[10点]怒气
	class ls_28 extends SkillBase{
		public $type = 'SKILL';
		function action($user,$self,$enemy){
			$this->addMp($user,$self,15);
		}
	}
	
	//增加我方辅助单位[15%]攻击
	class ls_29 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->addAtk($user,$player,$player->base_atk * 0.15);
			}
		}
	}
	
	//增加我方辅助单位[10%]速度
	class ls_30 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->addSpeed($user,$player,$player->base_speed * 0.10);
			}
		}
	}
?> 