<?php 
	require_once($filePath."pk_action/skill/skill_base.php");

	//�������ø�����λ���Լ�CD��//�������ϳ���
	class sm_504_0 extends SkillBase{
		function action($user,$self,$enemy){
			$len = count($user->team->currentMonster);
			for($i=0;$i<$len;$i++)
			{
				$player = $user->team->currentMonster[$i];
				$len2 = count($player->skillArr);
				foreach($player->skillArr as $key=>$value)
				{
					$player->skillArr[$key]->actionCount = 0;
				}
			}
		}
	}
	
	//С����160%�˺���CD3
	class sm_504_1 extends SkillBase{
		public $cd = 3;
		function action($user,$self,$enemy){
			$this->decHp($user,$enemy,$user->atk*1.6);
		}
	}
	
	//�أ���������HP�����ڸ�����λ��20%
	class sm_504_2 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$add = 0;
			if($user->team->teamInfo['mhps'])
				$add = $user->team->teamInfo['mhps']* 0.2;
			$this->addHp($user,$self,$add,true);
		}
	}
	
	//�������Ƶȼ�+3
	class sm_504_f1 extends SkillBase{
		public $cd = 0;
		function action($user,$self,$enemy){
			$user->team->ringLevelAdd += 3;
		}
	}

?> 