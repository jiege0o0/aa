<?php 
	

	//技：电之魂(技)：速成200%伤害，并增加自己速度15%，2round
	class sm_2_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			
			$buff = new ValueBuff('speed',round($self->base_speed * 0.15),2);
			$buff->addToTarget($self);
		}
	}
	
	// 电眼：看穿别人的攻击，避开别人的一次伤害，5次闪一次
	class sm_2_1 extends SkillBase{
		public $type = 'BEATK';		
		function action($user,$self,$enemy){
			$this->temp1 ++;
			if($this->temp1 >=4)
			{
				$this->temp1 = 0;
				$self->missTimes ++;
			}
		}
	}
	
	//麻痹：5次会造成麻痹，-15%速2回合
	class sm_2_2 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.15),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//静电场：辅助单位攻击 +10%，速度+10%
	class sm_2_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->addAtk($player->base_atk * 0.1);
				$player->addSpeed($player->base_speed * 0.1);
			}
		}
	}
	
	//辅：--加10%速度+10%攻击
	class sm_2_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->addAtk($self->base_atk * 0.1);
			$self->addSpeed($self->base_speed * 0.1);
		}
	}
	
	//辅：--50%伤害
	class sm_2_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 