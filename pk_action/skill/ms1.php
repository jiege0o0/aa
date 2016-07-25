<?php
    require_once($filePath."pk_action/skill/skill_base.php");

	//喷毒(技)：对对手每次行动后-血，并-速,round2             -30%速，ATK*1
	class sm_1_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.3)),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}

	//身压：每两次攻击后，会以身压人，伤害+30%
	class sm_1_1 extends SkillBase{
		public $isAtk = true;
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.3);
		}
	}

	//太臭：增加辅助单位10%的速，-5%攻
	class sm_1_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->atk -= round($player->base_atk * 0.05);
				$player->speed -= round($player->base_speed * 0.1);
				$this->setSkillEffect($player);
			}
			
		}
	}

	//特：本命牌：当造成伤害会致命时，无视本次伤害，只触发一次
	class sm_1_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			array_push($user->dieMissTimes,$user->id);
		}
	}

	//--每回合造成50%伤害
	class sm_1_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

	//--首回合使对方中毒，每次行动后-血，并-速        -10%速，ATK*0.3 round2
	class sm_1_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.3),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.1)),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}

?> 