<?php 
	

	//技：斩击（技）：180%伤
	class sm_21_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
		}
	}
	
	//突击：进入时+50%速度，+50%攻击，round3
	class sm_21_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.5),3);
			$buff->addToTarget($self);
			
			$buff = new ValueBuff('atk',round($self->base_atk * 0.5),3);
			$buff->addToTarget($self);
		}
	}
	
	//重击：+20%，cd2;
	class sm_21_2 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//战吼：辅助+30%速，20%攻，round3
	class sm_21_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
					
				$buff = new ValueBuff('speed',round($player->base_speed * 0.3),3);
				$buff->addToTarget($player);
				
				$buff = new ValueBuff('atk',round($player->base_atk * 0.2),3);
				$buff->addToTarget($player);
			}
		}
	}
	
	//辅：-- 战吼：+20%速，10%攻，round3
	class sm_21_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.2),3);
			$buff->addToTarget($self);
			
			$buff = new ValueBuff('atk',round($self->base_atk * 0.1),3);
			$buff->addToTarget($self);
		}
	}	
	//辅：-- 60%伤
	class sm_21_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}

?> 