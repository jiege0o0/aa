<?php 
	//�Ƴ��ҷ�һ������״̬��ʩ�������[1]
	class ls_21 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$b = $this->cleanStat($user,$player,true,1);
				if($b)
					break;
			}
		}
	}
	
	//�Ƴ��з�һ������״̬��ʩ�������[1]
	class ls_22 extends SkillBase{
		public $cd = 1;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$b = $this->cleanStat($user,$player,false,1);
				if($b)
					break;
			}
		}
	}
	
	//ʹ�ҷ���ս��λ���ħ�⣬����[3]�غ�
	class ls_23 extends SkillBase{
		public $cd = 0;
		public $order = 1000;
		function action($user,$self,$enemy){
			$buff = new StatBuff(31,3);
			$buff->removeAble = false;
			$buff->addToTarget($user,$self);
		}
	}
	
	//�ҷ���սʱ����[10��]ŭ��
	class ls_24 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->addMp($user,$self,10);
		}
	}
	
	//���ӶԷ���ս��λ[5��]ŭ������
	class ls_25 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->maxMp += 5;
			$this->setSkillEffect($enemy,pk_skillType('MMP',5));
		}
	}
	
	
	//���Է���Ѫʱ���������[r20%]�˺�
	class ls_26 extends SkillBase{
		public $type='EBEHEAL';
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*0.45);
		}
	}
	
	//�ҷ�ʹ�þ��к�ظ�[g10%]����
	class ls_27 extends SkillBase{
		public $type = 'SKILL';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}

	
	//�ҷ�ʹ�þ��к�ظ�[10��]ŭ��
	class ls_28 extends SkillBase{
		public $type = 'SKILL';
		function action($user,$self,$enemy){
			$this->addMp($user,$self,15);
		}
	}
	
	//�����ҷ�������λ[15%]����
	class ls_29 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->addAtk($user,$player,$player->base_atk * 0.15);
			}
		}
	}
	
	//�����ҷ�������λ[10%]�ٶ�
	class ls_30 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->addSpeed($user,$player,$player->base_speed * 0.10);
			}
		}
	}
?> 