<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//¼¼£ºÍÂÑ×£º200%ÉËº¦
	class sm_19_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}
	
	//ìÌÉÕ£º60%ÉËº¦£¬»ð¶¾£¬2round,cd4 5%ÑªÁ¿
	class sm_19_1 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
			
			$buff = new HPBuff(-$enemy->maxHp*0.05,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//ÁúÍþ£º¶Ô·½È«Ìå-8%¹¥-8%·À-8%ËÙ
	class sm_19_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$player->atk -= round($player->base_atk*0.08);
				$player->speed -= round($player->base_speed*0.08);
				$player->def -= 8;
			}
		}
	}
	
	//¸¨£º--ÁúÍþ£º¶Ô·½È«Ìå-4%¹¥-4%·À-4%ËÙ
	class sm_19_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$player->atk -= round($player->base_atk*0.04);
				$player->speed -= round($player->base_speed*0.04);
				$player->def -= 4;
			}
		}
	}	
	//¸¨£º--100%ÉËº¦£¬cd3
	class sm_19_f2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 