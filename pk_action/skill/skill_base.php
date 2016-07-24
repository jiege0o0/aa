<?php 
	require_once($filePath."pk_action/pk_buff.php");
	class SkillBase{
		public $owner;//����������
		public $index=0;//����ID
		public $isMain = false;//�Ƿ���������
		public $isAtk = false;//�����ͼ��ܣ������߻��MP
		public $actionCount = 0;//����0��ʾCD��
		public $disabled = false;//������û��Ч
		public $isSendAtOnce = false;//�ط����ܣ�������뻥���߼�
		public $order = 0;//���ȼ�������ʱԽ���Խ������
	
	
	
		public $cd = 1;//ÿCD���غϳ�һ���֣�3��Ϊ��2���غϣ���3���غϳ��֣�0Ϊ�غ�ǰ����
		public $stopout = false;//������ܶ�������������ͻ���
		// public round = 0;//�����غ�
		public $type = '';//���Լ���
		public $leader = false;//��PKǰִ��
		public $once = false;//����ִֻ��һ��
		
		//�������ٻ������
		public $lRound = 0;//�ٻ��ߵ�ʹ�ûغ���
		public $ringLevel = 0;
		
		
		public $tData;//���Դ���ʱ�����ֵ
		
		function __construct(){
			$this->name = get_class($this);
			$this->reInit();
		}
		
		function localReInit(){

		}
		
		//�����Ƿ���ʹ��
		function canUse($user,$self=null,$enemy=null){
			return true;
		}
		
		//�ż���ǰִ�еĶ���
		function actionBefore($user,$self=null,$enemy=null){
			
		}
		
		function actionSkill($user,$self,$enemy){
			global $pkData,$PKConfig;
			
			$this->actionBefore($user,$self,$enemy);
			if($this->isAtk)
			{
				if($enemy->isMiss())
				{
					$pkData->addSkillMV(null,$enemy,pk_skillType('MISS',1));	
				}
				else
				{
					$this->action($user,$self,$enemy);
					$enemy->addMp($PKConfig->defMP);
					$pkData->addSkillMV(null,$enemy,pk_skillType('MP',$PKConfig->defMP));	
				}
			}
			else
			{
				$this->action($user,$self,$enemy);
			}
		}
		
		
		//���¸�ֵ
		function reInit(){
			$this->actionCount = $this->cd - 1;//����CDΪ1�Ļ���һ���Ϳ�������
			$this->disabled = false;
			$this->tData = null;
			if($this->type)
				$this->actionCount = 0;
				
			$this->localReInit();
		}
		
		//���ü���Ч��
		function setSkillEffect($target,$mv=null){
			global $pkData;
			$target->setRoundEffect();
			if(!$mv)
				$mv = pk_skillType('MV',1);
			$pkData->addSkillMV($user,$target,$mv);
		}
		
		//���ѷ��ٻ������Ӽ���(ͨ���˷�������ļ��ܣ���һ�غϲŻ���Ч)
		function addLeaderSkill($user,$skillName){
			array_push($user->team->totalPKAction,pk_decodeSkill($skillName));
		}
		
		//A����B��Ѫ
		function decHp($user,$target,$value,$isMax=false,$forever=false,$realHurt=false){
			$value = round(max(1,$value));
			if(!$realHurt && $user->teamID != $target->teamID)
				$value = $user->getHurt($value,$target);
				
				
			if($target->hp <= $value && $target->isDieMiss())
			{
				global $pkData;
				$value = 0;
				$pkData->addSkillMV(null,$target,pk_skillType('NOHURT',0));	
				$target->testTSkill('DMISS');
			}
			else
			{
				$value = -$value;
				if($isMax)
				{
					$target->maxHp += $value;
					if($forever)
						$target->add_hp += $value;
					$this->setSkillEffect($target,pk_skillType('MHP',$value));
				}

				$target->addHp($value);
				$this->setSkillEffect($target,pk_skillType('HP',$value));				
			}

			
			if(!$this->type && $user->teamID != $target->teamID)
			{
				if($target->hp > 0)
					$target->testTSkill('BEATK',$value);
				if($user->isPKing)
					$user->testTSkill('HURT',$value);
			}
			
			return $value;
		}
		
		//A��B��Ѫ
		function addHp($user,$target,$value,$isMax=false,$forever=false){
			global $pkData;
			$value = round(max(1,$value));
			if($isMax)
			{
				$target->maxHp += $value;
				if($forever)
					$target->add_hp += $value;
				$this->setSkillEffect($target,pk_skillType('MHP',$value));
			}
			
			$target->addHp($value);
			$this->setSkillEffect($target,pk_skillType('HP',$value));
			
			if(!$this->type)//�������Լ�Ѫ���ᴥ���¼�
			{			
				$user->testTSkill('HEAL',$value);
				$target->testTSkill('BEHEAL',$value);
				//�������������
				// $pkData->playArr1[0]->testTSkill('SHEAL',$value);
				// $pkData->playArr2[0]->testTSkill('SHEAL',$value);
			}
			
			return $value;
		}
		
		//��ħ
		function addMp($user,$target,$value){
			$target->mp += $value;
			$this->setSkillEffect($target,pk_skillType('MP',$value));
			return $value;
		}
		/*
		//�ٶȸı�
		function addSpeed($user,$target,$value,$forever=false){
			if($value > 0)
				$value = round(max(1,$value));
			else
				$value = -round(max(1,-$value));
			$target->speed += $value;
			if($forever)
				$target->add_speed += $value;
			$this->setSkillEffect($target,pk_skillType('SPD',$value));
			
			return $value;
		}
		
		//�ӹ���
		function addAtk($user,$target,$value,$forever=false){
			if($value > 0)
				$value = round(max(1,$value));
			else
			{
				$value = -round(max(1,-$value));
				if($target->atk < -$value)//��������������0
					$value = -$target->atk + 1; 
			}
				
			$target->atk += $value;
			if($forever)
				$target->add_atk += $value;
			$this->setSkillEffect($target,pk_skillType('ATK',$value));
			
			return $value;
		}
		

		
		//�Ӷ�
		function addDef($user,$target,$value){
			$target->def += $value;
			$this->setSkillEffect($target);
			return $value;
		}
		
		//����
		function addHurt($user,$target,$value){
			$target->hurt += $value;
			$this->setSkillEffect($target);
			return $value;
		}	

		//��ÿ���ж�Ѫ���ı�
		function addcdhp($user,$target,$value){
			$target->cdhp += $value;
			$this->setSkillEffect($target);
			return $value;
		}
		
		//��״̬
		function haveStat($target,$teamID){
			$len = count($this->skillAction->target->statCountArr);
			for($i=0;$i<$len && $num > 0;$i++)
			{
				if($this->skillAction->target->statCountArr[$i]['userTeamID'] == teamID)
				{
					return true;
				}
			}
			return false;	
		}*/
		
		//���״̬(˭�ӵģ������)$isDebuff==-1Ϊ����buff
		function cleanStat($target,$isDebuff,$num){
			$len = count($target->buffArr);
			$b = false;
			for($i=0;$i<$len && $num > 0;$i++)
			{
				if($isDebuff == -1 || $target->buffArr[$i]->isDebuff == $isDebuff)
				{
					$target->buffArr[$i]->cd = 0;
					$num --;
					$b = true;
				}
			}
			//trace($target->id.'-'.$len.'-'.$num);
			if($b && $target->testStateCD())
			{
				$target->testOutStat();
				$target->setRoundEffect();
				
			}
			
			return $b;	
		}
	}
?> 