<?php 
	

	//¼¼£ºÍÂÑ×£º220%ÉËº¦
	class sm_19_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//ìÌÉÕ£º80%ÉËº¦£¬»ð¶¾£¬2round,cd5 5%ÑªÁ¿
	class sm_19_1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
			
			$buff = new HPBuff(-$enemy->maxHp*0.05,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//ÁúÍþ£º¶Ô·½È«Ìå-10%¹¥-10%·À-10%ËÙ
	class sm_19_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$player->addAtk(-$player->base_atk*0.15);
				$player->addSpeed(-$player->base_speed*0.15);
				$player->addDef(-15);
			}
		}
	}
	
	//¸¨£º--ÁúÍþ£º¶Ô·½È«Ìå-5%¹¥-5%·À-5%ËÙ
	class sm_19_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$player->addAtk(-$player->base_atk*0.08);
				$player->addSpeed(-$player->base_speed*0.08);
				$player->addDef(-8);
			}
		}
	}	
	//¸¨£º--120%ÉËº¦£¬cd3
	class sm_19_f2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.2);
		}
	}

?> 