<?php
    require_once($filePath."pk_action/skill/skill_base.php");

	//�綾(��)���Զ���ÿ���ж���-Ѫ����-��,round2             -30%�٣�ATK*1
	class sm_1_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk,2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.3)),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}

	//��ѹ��ÿ���ι����󣬻�����ѹ�ˣ��˺�+60%
	class sm_1_1 extends SkillBase{
		public $isAtk = true;
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}

	//̫�������Ӹ�����λ15%���٣�-8%��
	class sm_1_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->atk -= round($player->base_atk * 0.08);
				$player->speed -= round($player->base_speed * 0.15);
				$this->setSkillEffect($player);
			}
			
		}
	}

	//�أ������ƣ�������˺�������ʱ�����ӱ����˺���ֻ����һ��
	class sm_1_3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			array_push($user->dieMissTimes,array("id"=>$user->id));
		}
	}

	//--ÿ�غ����60%�˺�
	class sm_1_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.6);
		}
	}

	//--�׻غ�ʹ�Է��ж���ÿ���ж���-Ѫ����-��        -10%�٣�ATK*0.3 round2
	class sm_1_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.3),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$buff = new ValueBuff(array('speed'=>-round($enemy->base_speed * 0.1)),2);
			$buff->isDebuff = true;
			$buff->addToTarget($enemy);
			
			$this->setSkillEffect($enemy);
		}
	}

?> 