<?php
    require_once($filePath."pk_action/skill/skill_base.php");

	class NormalAtk extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
		}
	}
?> 