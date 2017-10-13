<?php 
	//降低对方辅助单位[15%]攻击
	class ls_31 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addAtk($user,$player,-$player->base_atk * 0.15);
			}
		}
	}
	
	//降低对方辅助单位[10%]速度
	class ls_32 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addSpeed($user,$player,-$player->base_speed * 0.10);
			}
		}
	}
	

	
	//当我方生命低于[20%]时,对敌人造成一次[r200%]伤害
	class ls_33 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		public $isAtk = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <= 0.2;
		}
		
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*1.5);
		}
	}
	
	//当敌人死亡时,回复自身[g8%]生命
	class ls_34 extends SkillBase{
		public $type = 'EDIE';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.08);
		}
	}
	
	//当我方受到治疗时，增加[5%]攻击
	class ls_35 extends SkillBase{
		public $type='BEHEAL';
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk * 0.06);
		}
	}
	
	
	//行动后降低对方[4]点怒气
	class ls_36 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->addMp($user,$enemy,-3);
		}
	}
	
	//当我方受到伤害时，增加[1]点防御
	class ls_37 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$this->addDef($user,$self,1);
		}
	}

	//我方行动前，如果我方生命低于[30%]，增加[10%]攻击力,持续[1]回合
	class ls_38 extends SkillBase{
		public $type = 'BEFORE';
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',round($self->base_atk * 0.2),1);
			$buff->addToTarget($user,$self);
		}
	}
	
	//我方行动后，如果我方生命低于[30%]，获得一次[闪避]能力
	class ls_39 extends SkillBase{
		public $type = 'AFTER';
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <= 0.2;
		}
		function action($user,$self,$enemy){
			$self->missTimes ++;
			$this->setSkillEffect($self,pk_skillType('MISS',-1));
		}
	}
	
	//我方行动后，如果我方生命高于[30%]，回复自身[g3%]生命
	class ls_40 extends SkillBase{
		public $type = 'AFTER';
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() >= 0.3;
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.03);
		}
	}
?> 