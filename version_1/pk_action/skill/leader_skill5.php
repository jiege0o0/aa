<?php 
	//敌方行动后，如果其生命低于[30%]，对敌人造成一次[r100%]伤害
	class ls_41 extends SkillBase{
		public $type = 'EAFTER';
		public $isAtk = true;
		function canUse($user,$self=null,$enemy=null){
			return $enemy->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*1);
		}
	}
	
	//敌方行动后，如果其生命百分比高于我方，则降低其[6%]生命
	class ls_42 extends SkillBase{
		public $type = 'EAFTER';
		public $isAtk = true;
		function canUse($user,$self=null,$enemy=null){
			return $enemy->getHpRate() > $self->getHpRate();
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.06);
		}
	}
	
	//增加我方发动治疗单位[10%]攻击力
	class ls_43 extends SkillBase{
		public $type='HEAL';
		function action($user,$self,$enemy){
			$player = $this->tData['user'];
			$this->addAtk($user,$player,$player->base_atk * 0.12);
		}
	}
	
	//降低对方[5%]生命，并回复已方对应生命值，施法间隔：[3]
	class ls_44 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			
			$v = -$this->decHp($user,$enemy,$enemy->maxHp*0.05);
			$this->addHp($user,$self,$v);
		}
	}
	
	//敌方行动后降低其[3%]生命
	class ls_45 extends SkillBase{
		public $type='EAFTER';
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.04);
		}
	}
	
	
	//每次行动后增加我方所有单位[5%]的攻击力
	class ls_46 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->addAtk($user,$player,$player->base_atk * 0.05);
			}
		}
	}

	
	//如果我方生命低于[30%]，获得魔免效果
	class ls_47 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <= 0.4;
		}
		function action($user,$self,$enemy){
			$buff = new StatBuff(31,5);
			$buff->removeAble = false;
			$buff->addToTarget($user,$self);
		}
	}
	
	//移除对方的血脉技能,持续[5]回合
	class ls_48 extends SkillBase{
		public $cd = 0;
		public $order = 1000;
		function action($user,$self,$enemy){
			$buff = new StatBuff(23,4);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//造成[r80%]伤害并晕眩[1]回合，施法间隔：[5]
	class ls_49 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*1);
		
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//造成[r5000%]伤害，施法间隔：[12]
	class ls_50 extends SkillBase{
		public $cd = 9;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*50);
		}
	}
?> 