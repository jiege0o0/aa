<?php 
	

	//技：结界（技）：2round，建立一个结界，在回合结速前不会受到任何伤害
	class sm_51_0 extends SkillBase{
		function action($user,$self,$enemy){
			$buff = new sm_51_0_buff(102,2);
			$buff->noClean = true;
			$buff->addToTarget($user);
			$this->setSkillEffect($user);
			
			if(!$user->temp['lastManaHP'])
				$user->temp['lastManaHP'] = $user->manaHp;
			$user->manaHp += 9999999;
		}
	}
	
	//buff
	class sm_51_0_buff extends StatBuff{
		function onEnd(){
			parent::onEnd();
			if(!$this->target->stat[102])
			{
				$this->target->manaHp = $this->target->temp['lastManaHP'];
				$this->target->temp['lastManaHP'] = 0;
			}
			
		}
	}
	
	
	//无中生有：增加10点MP，cd3
	class sm_51_1 extends SkillBase{
		public $cd = 3;
		public $order = 2;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}
	
	//冷炎：-10%甲，减20%速，round2,cd4
	class sm_51_2 extends SkillBase{
		public $cd = 4;
		public $order = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.2),'def'=>-10),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//增加已方辅助10%攻击力
	class sm_51_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->atk += round($player->base_atk * 0.1);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//辅：--无中生有：增加10点MP，cd3
	class sm_51_f1 extends SkillBase{
		public $cd = 3;
		public $order = 2;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}	
	//辅：--80%伤害
	class sm_51_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}

?> 