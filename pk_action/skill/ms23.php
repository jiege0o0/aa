<?php 
	

	//技：吸血(技)：-对方180%血，回复对应血量
	class sm_23_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = -$this->decHp($user,$enemy,$user->atk*1.8);
			$this->addHp($user,$self,$v);
		}
	}
	
	//电击：130%伤害，-20%攻，round1,cd3
	class sm_23_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
			$self->speed += 5;//round($self->base_speed*0.05);
			
			$buff = new ValueBuff(array('atk'=>-round($enemy->base_atk * 0.2)),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//疾速：行动后+5速度
	class sm_23_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			$self->speed += 5;//round($self->base_speed*0.05);
		}
	}
	
	//激活：辅助单位速度+10%
	class sm_23_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->speed += round($player->base_speed * 0.1);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//辅：-- 电击：40%伤害，-10%攻，round1,cd2
	class sm_23_f1 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.4);
			
			$buff = new ValueBuff(array('atk'=>-round($enemy->base_atk * 0.1)),1);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}	
	//辅：-- 50%伤
	class sm_23_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 