<?php 
	

	//技：斩杀（技）：+（对方损伤百分比*5）%伤
	class sm_22_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*(0.5 + (1-$enemy->getHpRate())*4.5));
			$this->addDef($user,$enemy,-2);
		}
	}
	
	//重击：+50%，cd3;
	class sm_22_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			$this->addDef($user,$enemy,-2);
		}
	}
	
	//焯烧伤口：每次攻击-2%防
	class sm_22_2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			$this->addDef($user,$enemy,-2);
		}
	}
	
	//战吼：进场对方所有-20%速，-15%攻，round3
	class sm_22_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$buff = new ValueBuff('speed',-round($player->base_speed * 0.3),3);
				$buff->isDebuff = true;
				$buff->addToTarget($user,$player);
				
				$buff = new ValueBuff('atk',-round($player->base_speed * 0.3),3);
				$buff->isDebuff = true;
				$buff->addToTarget($user,$player);
				
			}
		}
	}
	
	//辅：-- 50%伤，如果对方生命低于50%，120%伤
	class sm_22_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			if($enemy->getHpRate() <= 0.3)
				$this->decHp($user,$enemy,$user->atk*2);
			else
				$this->decHp($user,$enemy,$user->atk*0.8);
			$this->addDef($user,$enemy,-2);
		}
	}	

?> 