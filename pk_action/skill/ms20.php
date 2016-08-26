<?php 
	

	//¼¼£º¾ªÐÑ£¨¼¼£©£º-25%¹¥£¬+50%Ñª
	class sm_20_0 extends SkillBase{
		function action($user,$self,$enemy){
			$self->atk -= round($self->base_atk*0.25);
			$this->addHp($user,$self,$self->maxHp*0.5);
		}
	}
	
	//ÊÈÑª:½øÈëÊ±-20%µ±Ç°ÉúÃü£¬+30%¹¥
	class sm_20_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk*0.3);
			$this->decHp($user,$self,$self->hp*0.2);
		}
	}
	
	//¼¤Å­:±»¹¥»÷ºó¼Ó5%¹¥»÷Á¦
	class sm_20_2 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk*0.05);

		}
	}
	
	//±¬»÷£º+20%£¬cd2
	class sm_20_3 extends SkillBase{
		public $cd = 2;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.2);
		}
	}
	
	//¸¨£º----60%ÉËº¦
	class sm_20_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}	
	//¸¨£ºÊÈÑª:-10%µ±Ç°ÉúÃü£¬+15%¹¥
	class sm_20_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk*0.15);
			$this->decHp($user,$self,$self->hp*0.1);
		}
	}

?> 