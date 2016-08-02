<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//���������𱬣�250%�˺�
	class sm_40_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2.5);
		}
	}
	
	//+50%�˺� cd3
	class sm_40_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.5);
		}
	}
	
	//+����20%������cd3,round2
	class sm_40_2 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				
				$buff = new ValueBuff(array('atk'=>round($player->base_speed * 0.2)),2);
				$buff->addToTarget($player);
				$this->setSkillEffect($player);
			}
		}
	}
	
	//ħ���ܣ�����+�����Լ�����80%��ħ�ܣ��򱬺�����˵��Լ�
	class sm_40_3 extends SkillBase{
		public $cd = 0;
		public $order = 10;
		function action($user,$self,$enemy){
			$user->manaHp += round($user->base_hp*0.8);
			$this->setSkillEffect($user);
		}
	}
	
	//����--80%�˺�
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
		function action($user,$self,$enemy){
			$self->manaHp += round($user->base_hp*0.3);
			$this->setSkillEffect($self);
		}
	}

?> 