<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�����̳ԣ�������-15%�Է��������ޣ������Լ�15%��������
	class sm_16_0 extends SkillBase{
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$enemy->maxHp*0.15,true);
			$v = $this->addHp($user,$self,$self->maxHp*0.15,true);
			
		}
	}
	
	//����:����5%�������޲���Ѫ��cd3
	class sm_16_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->addHp($user,$self,$self->maxHp*0.05,true);
		}
	}
	
	//��Ѫ���� = ���ӵ���������/100������Ϊ30%
	class sm_16_2 extends SkillBase{
		public $type = 'MHP';
		
		function action($user,$self,$enemy){
			$this->temp1 += $this->tData;
			$def = min(30,round($this->temp1/100));
			$add = $def - $this->temp2;
			$this->temp2 = $def;
			$self->def += $add;
		}
	}
	
	//������ü�Ѫ����=��ǰ����*0.2
	class sm_16_3 extends SkillBase{
		public $type = 'DIE';
		function action($user,$self,$enemy){
			$this->addLeaderSkill($user,'sm_16_ds3#'.round($user->maxHp*0.2));
		}
	}
	class sm_16_ds3 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$v = $this->addHp($user,$self,$this->tData,true,true);
		}
	}
	
	
	//����--80%�˺�
	class sm_16_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*0.8);
		}
	}	

?> 