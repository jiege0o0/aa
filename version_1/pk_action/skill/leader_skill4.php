<?php 
	//���ͶԷ�������λ[15%]����
	class ls_31 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addAtk($user,$player,-$player->base_atk * 0.15);
			}
		}
	}
	
	//���ͶԷ�������λ[10%]�ٶ�
	class ls_32 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$this->addSpeed($user,$player,-$player->base_speed * 0.10);
			}
		}
	}
	

	
	//���ҷ���������[20%]ʱ,�Ե������һ��[r200%]�˺�
	class ls_33 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		public $isAtk = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <= 0.2;
		}
		
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*1.5);
		}
	}
	
	//����������ʱ,�ظ�����[g8%]����
	class ls_34 extends SkillBase{
		public $type = 'EDIE';
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.08);
		}
	}
	
	//���ҷ��ܵ�����ʱ������[5%]����
	class ls_35 extends SkillBase{
		public $type='BEHEAL';
		function action($user,$self,$enemy){
			$this->addAtk($user,$self,$self->base_atk * 0.06);
		}
	}
	
	
	//�ж��󽵵ͶԷ�[4]��ŭ��
	class ls_36 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$this->addMp($user,$enemy,-3);
		}
	}
	
	//���ҷ��ܵ��˺�ʱ������[1]�����
	class ls_37 extends SkillBase{
		public $type = 'BEATK';
		function action($user,$self,$enemy){
			$this->addDef($user,$self,1);
		}
	}

	//�ҷ��ж�ǰ������ҷ���������[30%]������[10%]������,����[1]�غ�
	class ls_38 extends SkillBase{
		public $type = 'BEFORE';
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',round($self->base_atk * 0.2),1);
			$buff->addToTarget($user,$self);
		}
	}
	
	//�ҷ��ж�������ҷ���������[30%]�����һ��[����]����
	class ls_39 extends SkillBase{
		public $type = 'AFTER';
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <= 0.2;
		}
		function action($user,$self,$enemy){
			$self->missTimes ++;
			$this->setSkillEffect($self,pk_skillType('MISS',-1));
		}
	}
	
	//�ҷ��ж�������ҷ���������[30%]���ظ�����[g3%]����
	class ls_40 extends SkillBase{
		public $type = 'AFTER';
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() >= 0.3;
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.03);
		}
	}
?> 