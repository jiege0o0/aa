<?php 
	class ls_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.1),3);
			$buff->addToTarget($user,$self);
		}
	}
?> 