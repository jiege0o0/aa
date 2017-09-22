<?php 
	//复活[攻]类型单位，并回复[g5%]血量
	class ls_11 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->hp<=0 && $self->monsterData['mtype'] == 1;
		}
		function action($user,$self,$enemy){
			$self->reborn(0.05);
			$user->addEffectCount($self->maxHp*0.05);
		}
	}
	
	//复活[盾]类型单位，并回复[g5%]血量
	class ls_12 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->hp<=0 && $self->monsterData['mtype'] == 2;
		}
		function action($user,$self,$enemy){
			$self->reborn(0.05);
			$user->addEffectCount($self->maxHp*0.05);
		}
	}
	
	//复活[辅]类型单位，并回复[g5%]血量
	class ls_13 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->hp<=0 && $self->monsterData['mtype'] == 3;
		}
		function action($user,$self,$enemy){
			$self->reborn(0.05);
			$user->addEffectCount($self->maxHp*0.05);
		}
	}
	
	//出战时，对出战双方造成其当前生命[r20%]的伤害
	class ls_14 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.15);
			$this->decHp($user,$enemy,$enemy->hp*0.2);
		}
	}
	
	//行动后额外回复[5]点怒气
	class ls_15 extends SkillBase{
		public $type='AFTER';
		function action($user,$self,$enemy){
			$this->addMp($user,$self,4);
		}
	}
	
	
	//当生命下低于[10%]时,增加[30]点防御，并回复[g10%]生命,持续[1]回合
	class ls_16 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate()<0.1;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',20,2);
			$buff->addToTarget($user,$self);
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//使出战单位能闪开下一次攻击，施法间隔：[3]
	class ls_17 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$self->missTimes ++;
			$this->setSkillEffect($self,pk_skillType('MISS',-1));
		}
	}
	
	//我方所有单位的攻击都不能被[闪避]
	class ls_18 extends SkillBase{
		public $cd = 0;
		public $order = 1000;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->hitTimes = 9999;
				$this->setSkillEffect($player,pk_skillType('MISS',-2));
			}
			
		}
	}
	
	//沉默对方出战单位[5]个回合，本效果不能被净化
	class ls_19 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new StatBuff(22,3);
			$buff->isDebuff = true;
			$buff->removeAble = false;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//敌方出战单位每次行动后都会下降[4%]的攻击力
	class ls_20 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->addAtk($user,$enemy,-$enemy->base_atk*0.06);
		}
	}
?> 