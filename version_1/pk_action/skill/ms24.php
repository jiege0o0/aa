<?php 
	
	//技：160%伤害，晕一回合;
	class sm_24_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.8);
			
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.5),1);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//雷电护盾：被击中后-对方20%速度，round1,(10次)
	class sm_24_1 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$enemy = $this->tData[0];
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.3),2);//此时对方回合未结束
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			$this->temp1 ++;
			if($this->temp1 >= 8)
				$this->disabled = true;
			
		}
	}
	
	//电力激活：+辅助15%攻，round5
	class sm_24_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$buff = new ValueBuff('atk',round($player->base_atk * 0.2),5);
				$buff->addToTarget($user,$player);
			}
		}
	}
	
	//辅：-- 雷电护盾：被击中后-对方20%速度，round1,(5次)
	class sm_24_f1 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$enemy = $this->tData[0];
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.3),2);//此时对方回合未结束
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			$this->temp1 ++;
			if($this->temp1 >= 5)
				$this->disabled = true;
		}
	}	
	//辅：-- 60%伤
	class sm_24_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}

?> 