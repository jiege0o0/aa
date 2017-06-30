<?php 
	
	
	//�����Ȼ󣺶Է��ĸ�����λ����ѷ���2round
	class sm_26_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(25,2);
				$buff->isDebuff = true;
				$buff->addToTarget($user,$player);
			}
		}
	}
	
	//���ң�����-30%��2round,cd4
	class sm_26_1 extends SkillBase{
		public $cd = 4;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$buff = new ValueBuff('atk',-round($enemy->base_atk * 0.3),2);
			$buff->isDebuff = true;
			$buff->addToTarget($user,$enemy);
		}
	}
	
	//���꣺�����Է�MP����+20
	class sm_26_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$enemy->maxMp += 20;
			$this->setSkillEffect($enemy,pk_skillType('MMP',20));
		}
	}
	
	//����-- 80%�˺�
	class sm_26_f1 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}	
	
	//����--�Ȼ󣺶Է��ĸ�����λ����ѷ���1round��cd4
	class sm_26_f2 extends SkillBase{
		public $cd = 3;
		public $order = 1;
		function action($user,$self,$enemy){
			$len = count($enemy->team->currentMonster);
			for($i=1;$i<$len;$i++)
			{
				$player = $enemy->team->currentMonster[$i];
				
				$buff = new StatBuff(25,1);
				$buff->isDebuff = true;
				$buff->addToTarget($user,$player);
			}
		}
	}

?> 