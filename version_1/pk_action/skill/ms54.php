<?php 
	

	//技：印记触发（技能）：触发所有火炎印记,存在越多，伤害越大（印记不消失）
	class sm_54_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			if(!$user->temp['sm54'])
				$user->temp['sm54'] = 0;
			$this->decHp($user,$enemy,$user->atk*($user->temp['sm54'])*0.5 + 1);
		}
	}
	
	//火炎刀：每次攻击带有火炎印记
	class sm_54_1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
			if(!$user->temp['sm54'])
				$user->temp['sm54'] = 0;
			$user->temp['sm54'] ++;
		}
	}
	
	//暴击：+20%,一次附加两个印记，cd3
	class sm_54_2 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		public $order = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
			if(!$user->temp['sm54'])
				$user->temp['sm54'] = 0;
			$user->temp['sm54'] += 2;
		}
	}

	
	//辅：--30%伤害+2%最大生命值
	class sm_54_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5 + $enemy->maxHp*0.05);
		}
	}	
	//辅：--+10%攻击力
	class sm_54_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk * 0.1);
		}
	}

?> 