<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//类似加成技能
	class KindSkill extends SkillBase{
		public $cd = 0;
		public $addEnemy = false;
		public $addSelf = false;
		function action($user,$self,$enemy){
			if($this->addEnemy)
			{	
				$this->addSpeed($user,$enemy,$enemy->base_speed*0.1);
				$this->addHp($user,$enemy,$enemy->base_hp*0.1,true);
				$this->addAtk($user,$enemy,$enemy->base_atk*0.1);
			}
			if($this->addSelf)
			{	
				$this->addSpeed($user,$self,$self->base_speed*0.1);
				$this->addHp($user,$self,$self->base_hp*0.1,true);
				$this->addAtk($user,$self,$self->base_atk*0.1);
			}
			
		}
	}
	
	
	//
	class RSkill_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addSpeed($user,$self,$self->base_speed*0.3);
		}
	}
?> 