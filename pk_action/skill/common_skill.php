<?php
    

	class NormalAtk extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk);
		}
	}
?> 