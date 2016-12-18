<?php 
	

	//���������𱬣�250%�˺�
	class sm_40_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*3);
		}
	}
	
	//+60%�˺� cd3
	class sm_40_1 extends SkillBase{
		public $cd = 3;
		// public $isAtk = true;
		function action($user,$self,$enemy){
			// $this->decHp($user,$enemy,$user->atk*1.6);
			$user->manaHp += round($user->base_hp*0.1);
		}
	}
	
	//+����10%������cd3,round2
	class sm_40_2 extends SkillBase{
		public $cd = 4;
		public $order = 1;
		function action($user,$self,$enemy){
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				
				$buff = new ValueBuff('atk',round($player->base_speed * 0.1),3);
				$buff->addToTarget($player);
			}
		}
	}
	
	//ħ���ܣ�����+�����Լ�����50%��ħ�ܣ��򱬺�����˵��Լ�
	class sm_40_3 extends SkillBase{
		public $cd = 0;
		public $order = 10;
		function action($user,$self,$enemy){
			$user->manaHp += round($user->base_hp*0.5);
		}
	}
	
	//����--60%�˺�
	class sm_40_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	
	//����--�Լ�����30%��ħ��
	class sm_40_f2 extends SkillBase{
		public $cd = 0;
		public $order = 9;
		function action($user,$self,$enemy){
			$self->manaHp += round($user->base_hp*0.4);
		}
	}

?> 