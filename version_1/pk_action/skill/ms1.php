<?php
	//�綾(��)���Զ���ÿ���ж���-Ѫ����-��,round2             -30%�٣�ATK*1.3
	class sm_1_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-$user->atk*1.8,2,'1_0');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.3),2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
		}
	}

	//��ѹ��ÿ���ι����󣬻�����ѹ�ˣ��˺�+60%
	class sm_1_1 extends SkillBase{
		public $isAtk = true;
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*2);
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
				$this->addAtk($user,$player,-$player->base_atk * 0.1);
				$this->addSpeed($user,$player,$player->base_speed * 0.2);
			}
			
		}
	}

	//�أ������ƣ�������˺�������ʱ�����ӱ����˺���ֻ����һ��
	class sm_1_3 extends SkillBase{
		public $cd = 0;
		public $order = 1001;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			array_push($user->dieMissTimes,array("id"=>$user->id,'mid'=>$user->monsterID));
		}
	}

	//--ÿ�غ����60%�˺�
	class sm_1_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

	//--�׻غ�ʹ�Է��ж���ÿ���ж���-Ѫ����-��        -10%�٣�ATK*0.3 round2
	class sm_1_f2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new HPBuff(-round($user->atk*0.5),3,'1_f2');
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
			$buff = new ValueBuff('speed',-round($enemy->base_speed * 0.1),3);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
			
		}
	}

?> 