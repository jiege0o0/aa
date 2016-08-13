<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	//������ѹ��������35%��������˺�
	class sm_38_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.35);
		}
	}
	
	//���У�����+������λ�ܺ͵�20%
	class sm_38_1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$hp = 0;
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				$hp += $player->maxHp;
			}
			$this->addHp($user,$self,$hp*0.2,true);
		}
	}
	
	//������+������λ10%����10%��
	class sm_38_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($user->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				$player->atk += round($player->base_atk*0.1);
				$player->speed += round($player->base_speed*0.1);
				$this->setSkillEffect($player);
			}
		}
	}
	
	
	
	//����--���������ϵ�λ5%��/��/�������
	class sm_38_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$self->atk += round($self->base_atk*0.05);
			$self->speed += round($self->base_speed*0.05);
			$this->addHp($user,$self,$self->base_hp*0.05,true);
		}
	}	
	//����--50%�˺�
	class sm_38_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.5);
		}
	}

?> 