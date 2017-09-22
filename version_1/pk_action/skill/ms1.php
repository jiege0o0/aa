<?php
	//喷毒(技)：对对手每次行动后-血，并-速,round2             -30%速，ATK*1.3
	class sm_1_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk*1.8,2,'1_0');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.3),2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
		}
	}

	//身压：每两次攻击后，会以身压人，伤害+60%
	class sm_1_1 extends SkillBase{
		public $isAtk = true;
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
		}
	}

	//太臭：增加辅助单位15%的速，-8%攻
	class sm_1_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->addAtk($user,$player,-$player->base_atk * 0.1);
				$this->addSpeed($user,$player,$player->base_speed * 0.2);
			}
			
		}
	}

	//特：本命牌：当造成伤害会致命时，无视本次伤害，只触发一次
	class sm_1_3 extends SkillBase{
		public $cd = 0;
		public $order = 1001;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			array_push($user->dieMissTimes,array("id"=>$user->id,'mid'=>$user->monsterID));
		}
	}

	//--每回合造成60%伤害
	class sm_1_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

	//--首回合使对方中毒，每次行动后-血，并-速        -10%速，ATK*0.3 round2
	class sm_1_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.5),3,'1_f2');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
		}
	}

?> 