<?php 
	//����[��]���͵�λ�����ظ�[g5%]Ѫ��
	class ls_11 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->hp<=0 && $self->monsterData['mtype'] == 1;
		}
		function action($user,$self,$enemy){
			$self->reborn(0.05);
			$user->addEffectCount($self->maxHp*0.05);
		}
	}
	
	//����[��]���͵�λ�����ظ�[g5%]Ѫ��
	class ls_12 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->hp<=0 && $self->monsterData['mtype'] == 2;
		}
		function action($user,$self,$enemy){
			$self->reborn(0.05);
			$user->addEffectCount($self->maxHp*0.05);
		}
	}
	
	//����[��]���͵�λ�����ظ�[g5%]Ѫ��
	class ls_13 extends SkillBase{
		public $type='DIE';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->hp<=0 && $self->monsterData['mtype'] == 3;
		}
		function action($user,$self,$enemy){
			$self->reborn(0.05);
			$user->addEffectCount($self->maxHp*0.05);
		}
	}
	
	//��սʱ���Գ�ս˫������䵱ǰ����[r20%]���˺�
	class ls_14 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$self,$self->hp*0.15);
			$this->decHp($user,$enemy,$enemy->hp*0.2);
		}
	}
	
	//�ж������ظ�[5]��ŭ��
	class ls_15 extends SkillBase{
		public $type='AFTER';
		function action($user,$self,$enemy){
			$this->addMp($user,$self,4);
		}
	}
	
	
	//�������µ���[10%]ʱ,����[30]����������ظ�[g10%]����,����[1]�غ�
	class ls_16 extends SkillBase{
		public $type = 'HP';
		public $once = true;
		function canUse($user,$self=null,$enemy=null){
			return $self->getHpRate()<0.1;
		}
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',20,2);
			$buff->addToTarget($user,$self);
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//ʹ��ս��λ��������һ�ι�����ʩ�������[3]
	class ls_17 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$self->missTimes ++;
			$this->setSkillEffect($self,pk_skillType('MISS',-1));
		}
	}
	
	//�ҷ����е�λ�Ĺ��������ܱ�[����]
	class ls_18 extends SkillBase{
		public $cd = 0;
		public $order = 1000;
		function action($user,$self,$enemy){
			$len = count($self->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $self->team->currentMonster[$i];
				$player->hitTimes = 9999;
				$this->setSkillEffect($player,pk_skillType('MISS',-2));
			}
			
		}
	}
	
	//��Ĭ�Է���ս��λ[5]���غϣ���Ч�����ܱ�����
	class ls_19 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$buff = new StatBuff(22,3);
			$buff->isDebuff = true;
			$buff->removeAble = false;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//�з���ս��λÿ���ж��󶼻��½�[4%]�Ĺ�����
	class ls_20 extends SkillBase{
		public $type = 'EAFTER';
		function action($user,$self,$enemy){
			$this->addAtk($user,$enemy,-$enemy->base_atk*0.06);
		}
	}
?> 