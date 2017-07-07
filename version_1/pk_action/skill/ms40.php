<?php 
	

	//技：心灵震爆：250%伤害
	class sm_40_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*3);
		}
	}
	
	//+60%伤害 cd3
	class sm_40_1 extends SkillBase{
		public $cd = 3;
		// public $isAtk = true;
		function action($user,$self,$enemy){
			// $this->decHp($user,$enemy,$user->atk*1.6);
			$user->manaHp += round($user->base_hp*0.1);
		}
	}
	
	//+辅助10%攻击，cd3,round2
	class sm_40_2 extends SkillBase{
		public $cd = 4;
		public $order = 1;
		function action($user,$self,$enemy){
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				
				$buff = new ValueBuff('atk',round($player->base_atk * 0.15),3);
				$buff->addToTarget($user,$player);
			}
		}
	}
	
	//魔法盾：进场+等于自己生命50%的魔盾，打爆后才能伤到自己
	class sm_40_3 extends SkillBase{
		public $cd = 0;
		public $order = 10;
		function action($user,$self,$enemy){
			$v = round($user->base_hp*0.5);
			$user->manaHp += $v;
			$user->setSkillEffect(pk_skillType('MANAHP',$v));
		}
	}
	
	//辅：--60%伤害
	class sm_40_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}	
	//辅：--自己生命30%的魔盾
	class sm_40_f2 extends SkillBase{
		public $cd = 0;
		public $order = 9;
		function action($user,$self,$enemy){
			$v = round($user->base_hp*0.5);
			$user->addEffectCount($v);
			$self->manaHp += $v;
			$self->setSkillEffect(pk_skillType('MANAHP',$v));
		}
	}

?> 