<?php 
	//出战时提升[10%]速度，持续[3]回合
	class ls_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.1),3);
			$buff->addToTarget($user,$self);
		}
	}
	
	//出战时提升[10%]攻击，持续[3]回合
	class ls_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',round($self->base_atk * 0.1),3);
			$buff->addToTarget($user,$self);
		}
	}
	
	//出战时提升[10%]防御，持续[3]回合
	class ls_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',10,3);
			$buff->addToTarget($user,$self);
		}
	}
	
	//战斗结束时，如果仍然存活，则回复[10%]生命
	class ls_4 extends SkillBase{
		public $type = 'OVER';
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
		public $order = 1000;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			array_push($self->dieMissTimes,array("id"=>$user->id,'mid'=>5,"type"=>'atk'));
		}
	}
?> 