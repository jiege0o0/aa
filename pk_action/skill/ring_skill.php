<?php 
	require_once($filePath."pk_action/skill/skill_base.php");
	
	//���Ƽӳɼ���
	class KindSkill extends SkillBase{
		public $cd = 0;
		public $addEnemy = false;
		public $addSelf = false;
		function action($user,$self,$enemy){
			if($this->addEnemy)
			{	
				$this->addSpeed($user,$enemy,$enemy->base_speed*0.1);
				$this->addHp($user,$enemy,$enemy->base_hp*0.1,true);
				$this->addAtk($user,$enemy,$enemy->base_atk*0.1);
			}
			if($this->addSelf)
			{	
				$this->addSpeed($user,$self,$self->base_speed*0.1);
				$this->addHp($user,$self,$self->base_hp*0.1,true);
				$this->addAtk($user,$self,$self->base_atk*0.1);
			}
			
		}
	}
	
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_1 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 1;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_2 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 2;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_3 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 3;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_4 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 4;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_5 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 5;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_6 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 6;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_7 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 7;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_8 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 8;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_9 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 9;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_10 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 10;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_11 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 11;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�ӳɽ����Կ���$$%��������Ѫ��
	class RSkill_12 extends SkillBase{
		public $cd = 0;
		function canUse($user,$self=null,$enemy=null){
			return $self->monsterData['type'] == 12;
		}
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//�Է����ܵȼ���ȥ$$��������Ϊ0��
	class RSkill_13 extends SkillBase{

	}	
	
	//���ƶԷ��ļ��ܣ��ȼ����Ϊ$$������߲��ܳ����Է����ܵȼ�+1
	class RSkill_14 extends SkillBase{

	}
	
	//PK��ʼǰ���ظ��ѷ�$$%������ֵ
	class RSkill_15 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$rate = (10 + $this->ringLevel*0.5)/100;
			$this->addHp($user,$self,$self->maxHp*$rate);
		}
	}
	
	//PK��ʼǰ�����ٶԷ�$$%������ֵ
	class RSkill_16 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$rate = (5 + $this->ringLevel*0.3)/100;
			$this->decHp($user,$enemy,$self->maxHp*$rate);
		}
	}
	
	//���ӳ��Ͽ���$$%������
	class RSkill_17 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$rate = (3 + $this->ringLevel*1)/100;
			$this->addAtk($user,$self,$self->base_atk*$rate);
		}
	}
	
	//����$$%����������5�غ�
	class RSkill_18 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$rate = (10 + $this->ringLevel*1);
			$v = $this->addDef($user,$self,$rate);
			$self->addState($user,array('def'=>$v),5);
		}
	}
	
	//�ж�����������Է�Ѫ������$$%ʱ��ֱ����ɱ
	class RSkill_19 extends SkillBase{
		public $type = 'AFTER';
		function canUse($user,$self=null,$enemy=null){
			$rate = (3 + $this->ringLevel*0.2)/100;
			return $enemy->getHpRate() < $rate;
		}
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp,false,false,true);
		}
	}
		
	//����$$%��Ѫ��
	class RSkill_20 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$rate = (5 + $this->ringLevel*1)/100;
			$this->addHp($user,$self,$self->base_hp*$rate,true);
		}
	}
	
	//����$$%�ٶȣ�����5�غ�
	class RSkill_21 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$rate = (5 + $this->ringLevel*1)/100;
			$v = $this->addSpeed($user,$self,$self->base_speed*$rate);
			$self->addState($user,array('speed'=>$v),5);
		}
	}
	
	//���ٶԷ�$$%���������ٶȣ�����3�غ�
	class RSkill_22 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$rate = (5 + $this->ringLevel*1)/100;
			$v1 = $this->addAtk($user,$enemy,-$enemy->base_atk*$rate);
			$v2 = $this->addSpeed($user,$enemy,-$enemy->base_speed*$rate);
			$enemy->addState($user,array('speed'=>$v2,'atk'=>$v1),3);
		}
	}
	
	//�ҷ����Ͻ�ɫ�ж��󣬻ظ�$$%����ֵ
	class RSkill_23 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$rate = (1 + $this->ringLevel*0.1)/100;
			$this->addHp($user,$self,$self->base_atk*$rate);
		}
	}
	
	//�ҷ����Ͻ�ɫ�ж��󣬶ԶԷ���������൱���ҷ���ɫ$$%���˺�
	class RSkill_24 extends SkillBase{
		public $type = 'AFTER';
		function action($user,$self,$enemy){
			$rate = (10 + $this->ringLevel*2)/100;
			$this->decHp($user,$enemy,$self->atk*$rate);
		}
	}
?> 