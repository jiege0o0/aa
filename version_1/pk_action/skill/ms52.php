<?php 
	

	//�����ٻ���ż����(��)������ɵ��ӣ�ÿ�����������Լ�30%������
	class sm_52_0 extends SkillBase{
		function action($user,$self,$enemy){
			$user->addAtk($user->base_atk*0.3);
			array_push($user->dieMissTimes,array("id"=>$user->id,'mid'=>$user->monsterID));
		}
	}
	
	//������ƣ�100%�˺���-10MP��cd3
	class sm_52_1 extends SkillBase{
		public $cd = 3;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->addMp($user,$enemy,-10);
			$this->decHp($user,$enemy,$user->atk);
		}
	}
	
	//ת�ƣ����˺�������ʱ������һ�β���һ��
	class sm_52_2 extends SkillBase{
		public $type = 'DMISS';
		public $isSendAtOnce = true;
		function canUse($user,$self=null,$enemy=null){
			return $this->tData['id'] == $user->id;
		}
		function action($user,$self,$enemy){
			$user->addAtk(-$user->base_atk*0.3);
			$user->addStat(11,-1);
			$user->addStat(1,-1);
			$this->addHp($user,$user,$user->maxHp*0.1);
		}
	}
	
	
	//����--���У��ѷ����MPֵ�½�20��
	class sm_52_f1 extends SkillBase{
		public $cd = 0;
		public $order = 10;
		function action($user,$self,$enemy){
			$self->maxMp -= 20;
		}
	}	
	
	//����--60%�˺�
	class sm_52_f2 extends SkillBase{
		public $cd = 1;
		public $isAtk = true;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1);
		}
	}

?> 