<?php 
	//�з��ж����������������[30%]���Ե������һ��[r100%]�˺�
	class ls_41 extends SkillBase{
		public $type = 'EAFTER';
		public $isAtk = true;
		function canUse($user,$self=null,$enemy=null){
			return $enemy->getHpRate() <= 0.3;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*1);
		}
	}
	
	//�з��ж�������������ٷֱȸ����ҷ����򽵵���[6%]����
	class ls_42 extends SkillBase{
		public $type = 'EAFTER';
		public $isAtk = true;
		function canUse($user,$self=null,$enemy=null){
			return $enemy->getHpRate() > $self->getHpRate();
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.06);
		}
	}
	
	//�����ҷ��������Ƶ�λ[10%]������
	class ls_43 extends SkillBase{
		public $type='HEAL';
		function action($user,$self,$enemy){
			$player = $this->tData['user'];
			$this->addAtk($user,$player,$player->base_atk * 0.12);
		}
	}
	
	//���ͶԷ�[5%]���������ظ��ѷ���Ӧ����ֵ��ʩ�������[3]
	class ls_44 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			
			$v = -$this->decHp($user,$enemy,$enemy->maxHp*0.05);
			$this->addHp($user,$self,$v);
		}
	}
	
	//�з��ж��󽵵���[3%]����
	class ls_45 extends SkillBase{
		public $type='EAFTER';
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.04);
		}
	}
	
	
	//ÿ���ж��������ҷ����е�λ[5%]�Ĺ�����
	class ls_46 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$this->addAtk($user,$player,$player->base_atk * 0.05);
			}
		}
	}

	
	//����ҷ���������[30%]�����ħ��Ч��
	class ls_47 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate() <= 0.4;
		}
		function action($user,$self,$enemy){
			$buff = new StatBuff(31,5);
			$buff->removeAble = false;
			$buff->addToTarget($user,$self);
		}
	}
	
	//�Ƴ��Է���Ѫ������,����[5]�غ�
	class ls_48 extends SkillBase{
		public $cd = 0;
		public $order = 1000;
		function action($user,$self,$enemy){
			$buff = new StatBuff(23,4);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//���[r80%]�˺�����ѣ[1]�غϣ�ʩ�������[5]
	class ls_49 extends SkillBase{
		public $cd = 5;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*1);
		
			$buff = new StatBuff(24,1);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//���[r5000%]�˺���ʩ�������[12]
	class ls_50 extends SkillBase{
		public $cd = 9;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*50);
		}
	}
?> 