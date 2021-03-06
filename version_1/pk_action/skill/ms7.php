<?php 
	

	//技：能量吸取：-对方50%MP，自己增加对应值，并回复20%血量
	class sm_7_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$v = $this->addMp($user,$enemy,-$enemy->mp*0.5);
			
			$this->addHp($user,$self,$self->maxHp*0.3);
			$this->addMp($user,$self,-$v);
		}
	}
	
	//死亡后，把自己剩余的怒气值增加到下一出战单位身上
	class sm_7_1 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_7_ds1#'.min(round($user->mp),30));
		}
	}

	class sm_7_ds1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$v = $this->addMp($user,$self,$this->tData);
		}
	}
	
	//平静：当自己生命少于30%后，-对方全体15%攻，15%速，触发一次
	class sm_7_2 extends SkillBase{
		public $type = 'HP';
		public $once = true;//技能只执行一次
		function canUse($user,$self=null,$enemy=null){
			return $user->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addAtk($user,$player,-$player->base_atk * 0.2);
				$this->addSpeed($user,$player,-$player->base_speed * 0.15);
			}
		}
	}

	//辅：--回复攻击*0.6的血量
	class sm_7_f1 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$user->atk*1.2);
		}
	}	
	//辅：--减对方10MP，cd3
	class sm_7_f2 extends SkillBase{
		public $cd = 3;
		public $order = 1;//优先级，互斥时越大的越起作用
		function action($user,$self,$enemy){
			
			$v = $this->addMp($user,$enemy,-10);
			$this->addMp($user,$self,-$v);
		}
	}

?> 