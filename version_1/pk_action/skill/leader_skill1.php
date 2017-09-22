<?php 
	//出战时提升[10%]速度，持续[3]回合
	class ls_1 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.1),5);
			$buff->addToTarget($user,$self);
		}
	}
	
	//出战时提升[10%]攻击，持续[3]回合
	class ls_2 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',round($self->base_atk * 0.15),5);
			$buff->addToTarget($user,$self);
		}
	}
	
	//出战时提升[10%]防御，持续[3]回合
	class ls_3 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',8,5);
			$buff->addToTarget($user,$self);
		}
	}
	
	//战斗结束时，如果仍然存活，则回复[10%]生命
	class ls_4 extends SkillBase{
		public $type = 'OVER';
		public $order = 1000;//优先级，互斥时越大的越起作用
		function canUse($user,$self=null,$enemy=null){
			return $self->hp > 0;
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//如果攻击伤害置死，则抵挡一次伤害
	class ls_5 extends SkillBase{
		public $cd = 0;
		public $order = 100;//优先级，互斥时越大的越起作用
		private $target;
		private $user;
		function action($user,$self,$enemy){
			array_push($self->dieMissTimes,array("id"=>$user->id,'mid'=>5,"type"=>'atk',"skill"=>$this));
			$this->setSkillEffect($self,pk_skillType('NOHURT',-1));
			$this->target = $self;
			$this->user = $user;
		}
		
		function onDMiss(){
			$this->addAtk($this->user,$this->target,-$this->target->base_atk * 0.6);
			
		}
	}
	
	
	//行动后对敌方额外造成一次[r100%]伤害，施法间隔：[3]
	class ls_6 extends SkillBase{
		// public $type = 'AFTER';
		public $order = -1000;//优先级，互斥时越大的越起作用
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*1.2);
		}
	}
	
	//出战时对敌人造成[r80%]伤害,并降低其[10%]防御,持续[2]回合
	class ls_7 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*0.5);
			$buff = new ValueBuff('def',-5,2);
			$buff->isDebuff = true; 
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//回复[g3%]生命，施法间隔：[2]
	class ls_8 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.05);
		}
	}
	
	//出战时降低所有敌人[10%]速度，持续[3]回合
	class ls_9 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$buff = new ValueBuff('speed',-round($player->base_speed * 0.05),2);
				$buff->isDebuff = true; 
				$buff->addToTarget($user,$player);
				
				$buff = new ValueBuff('def',-5,2);
				$buff->isDebuff = true; 
				$buff->addToTarget($user,$player);
			}
		}
	}
	
	//为出战单位添加一个相当于[10%]血量的能量护盾
	class ls_10 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$v = round($self->base_hp*0.08);
			$self->manaHp += $v;
			$self->setSkillEffect(pk_skillType('MANAHP',$v));
		}
	}
?> 