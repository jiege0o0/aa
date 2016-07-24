<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//技：电之魂(技)：速成200%伤害，并增加自己速度15%，2round
	class sm_2_0 extends SkillBase{
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			
			$buff = new ValueBuff(array('speed'=>round($self->base_speed * 0.15)),2);
			$buff->addToTarget($self);
		}
	}
	
	// 电眼：看穿别人的攻击，避开别人的一次伤害，5次闪一次
	class sm_2_1 extends SkillBase{
		public $type = 'BEATK';
		public $count = 0;
		
		function localReInit(){
			$this->count = 0;
		}
		function action($user,$self,$enemy){
			$this->count ++;
			if($this->count >=4)
			{
				$this->count = 0;
				$self->missTimes ++;
			}
		}
	}
	
	//麻痹：4次会造成麻痹，-10%速2回合
	class sm_2_2 extends SkillBase{
		public $cd = 4;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.1)),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
		}
	}
	
	//静电场：辅助单位攻击 +10%，速度+10%
	class sm_2_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->atk += round($player->base_atk * 0.1);
				$player->speed += round($player->base_speed * 0.1);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//辅：--加10%速度+10%攻击
	class sm_2_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk * 0.1);
			$self->speed += round($self->base_speed * 0.1);
		}
	}
	
	//辅：--60%伤害
	class sm_2_f2 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}

?> 