<?php 
	
	//技：镇压（技）：40%最大生命伤害
	class sm_38_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.4);
		}
	}
	
	//号招：生命+辅助单位总和的30%
	class sm_38_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$hp = 0;
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				$hp += $player->maxHp;
			}
			$this->addHp($user,$self,$hp*0.3,true);
		}
	}
	
	//增幅：+辅助单位10%攻和10%速
	class sm_38_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				$player->addAtk($player->base_atk*0.1);
				$player->addSpeed($player->base_speed*0.1);
			}
		}
	}
	
	
	
	//辅：--增幅：场上单位5%攻/速/最大生命
	class sm_38_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->addAtk($self->base_atk*0.05);
			$self->addSpeed($self->base_speed*0.05);
			$this->addHp($user,$self,$self->base_hp*0.05,true);
		}
	}	
	//辅：--50%伤害
	class sm_38_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 