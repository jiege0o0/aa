<?php 
	//��սʱ����[10%]�ٶȣ�����[3]�غ�
	class ls_1 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$buff = new ValueBuff('speed',round($self->base_speed * 0.1),5);
			$buff->addToTarget($user,$self);
		}
	}
	
	//��սʱ����[10%]����������[3]�غ�
	class ls_2 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',round($self->base_atk * 0.15),5);
			$buff->addToTarget($user,$self);
		}
	}
	
	//��սʱ����[10%]����������[3]�غ�
	class ls_3 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$buff = new ValueBuff('def',8,5);
			$buff->addToTarget($user,$self);
		}
	}
	
	//ս������ʱ�������Ȼ����ظ�[10%]����
	class ls_4 extends SkillBase{
		public $type = 'OVER';
		public $order = 1000;//���ȼ�������ʱԽ���Խ������
		function canUse($user,$self=null,$enemy=null){
			return $self->hp > 0;
		}
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.1);
		}
	}
	
	//��������˺���������ֵ�һ���˺�
	class ls_5 extends SkillBase{
		public $cd = 0;
		public $order = 100;//���ȼ�������ʱԽ���Խ������
		private $target;
		private $user;
		function action($user,$self,$enemy){
			array_push($self->dieMissTimes,array("id"=>$user->id,'mid'=>5,"type"=>'atk',"skill"=>$this));
			$this->setSkillEffect($self,pk_skillType('NOHURT',-1));
			$this->target = $self;
			$this->user = $user;
		}
		
		function onDMiss(){
			$this->addAtk($this->user,$this->target,-$this->target->base_atk * 0.6);
			
		}
	}
	
	
	//�ж���Եз��������һ��[r100%]�˺���ʩ�������[3]
	class ls_6 extends SkillBase{
		// public $type = 'AFTER';
		public $order = -1000;//���ȼ�������ʱԽ���Խ������
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*1.2);
		}
	}
	
	//��սʱ�Ե������[r80%]�˺�,��������[10%]����,����[2]�غ�
	class ls_7 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$self->atk*0.5);
			$buff = new ValueBuff('def',-5,2);
			$buff->isDebuff = true; 
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//�ظ�[g3%]������ʩ�������[2]
	class ls_8 extends SkillBase{
		public $cd = 2;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.05);
		}
	}
	
	//��սʱ�������е���[10%]�ٶȣ�����[3]�غ�
	class ls_9 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				$buff = new ValueBuff('speed',-round($player->base_speed * 0.05),2);
				$buff->isDebuff = true; 
				$buff->addToTarget($user,$player);
				
				$buff = new ValueBuff('def',-5,2);
				$buff->isDebuff = true; 
				$buff->addToTarget($user,$player);
			}
		}
	}
	
	//Ϊ��ս��λ���һ���൱��[10%]Ѫ������������
	class ls_10 extends SkillBase{
		public $cd = 0;
		public $order = 1000;//���ȼ�������ʱԽ���Խ������
		function action($user,$self,$enemy){
			$v = round($self->base_hp*0.08);
			$self->manaHp += $v;
			$self->setSkillEffect(pk_skillType('MANAHP',$v));
		}
	}
?> 