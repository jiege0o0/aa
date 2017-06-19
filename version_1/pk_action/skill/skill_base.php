<?php 
	require($filePath."pk_action/pk_buff.php");
	class SkillBase{
		public $orginOwnerID;//����ԭʼ������
		public $owner;//����������
		public $clientIndex=-1;//���ͻ��˵ļ���ID
		public $index=0;//����ID
		public $isMain = false;//�Ƿ���������
		public $isAtk = false;//�����ͼ��ܣ������߻��MP
		public $actionCount = 0;//����0��ʾCD��
		public $disabled = false;//������û��Ч
		public $isSendAtOnce = false;//�ط����ܣ�������뻥���߼�(��������²��ᱻ23״̬����)
		public $order = 0;//���ȼ�������ʱԽ���Խ������
	
	
	
		public $cd = 1;//ÿCD���غϳ�һ���֣�3��Ϊ��2���غϣ���3���غϳ��֣�0Ϊ�غ�ǰ����
		// public $stopout = false;//������ܶ�������������ͻ���
		// public round = 0;//�����غ�
		public $type = '';//���Լ���
		public $leader = false;//��PKǰִ��
		public $once = false;//����ִֻ��һ��
		
		//�������ٻ������
		public $lRound = 0;//�ٻ��ߵ�ʹ�ûغ���
		public $ringLevel = 0;
		
		
		public $tData;//���Դ���ʱ�����ֵ
		
		public $temp1;
		public $temp2;
		public $temp3;
		
		function __construct(){
			$this->name = get_class($this);
			$this->reInit();
		}
		
		function localReInit(){

		}
		
		function getClientIndex(){
			if($this->clientIndex == -1)
				return $this->index;
			return $this->clientIndex;
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
			
			if($user->stat[25])
			{
				$temp = $self;
				$self = $enemy;
				$enemy = $temp;
			}
			
			$this->actionBefore($user,$self,$enemy);
			if($this->isAtk)
			{
				if(!$user->mustHit() && $enemy->isMiss())
				{
					$pkData->addSkillMV(null,$enemy,pk_skillType('MISS',1));	
				}
				else
				{
					$enemy->addMp($PKConfig->defMP);
					$pkData->addSkillMV(null,$enemy,pk_skillType('HMP',$PKConfig->defMP));
					$this->action($user,$self,$enemy);
					$enemy->testTSkill('BEATK',array($user));
						
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
			$this->temp1 = 0;
			$this->temp2 = 0;
			$this->temp3 = 0;
			if($this->type)
				$this->actionCount = 0;
				
			$this->localReInit();
		}
		
		//���ü���Ч��
		function setSkillEffect($target,$mv=null){
			$target->setSkillEffect($mv);
		}
		
		//���ѷ��ٻ������Ӽ���(ͨ���˷�������ļ��ܣ���һ�غϲŻ���Ч)
		function addLeaderSkill($user,$skillName){
			$skillVO = pk_decodeSkill($skillName);
			$skillVO->orginOwnerID = $user->id;
			array_push($user->team->totalPKAction,$skillVO);
		}
		
		//A����B��Ѫ
		function decHp($user,$target,$value,$isMax=false,$forever=false,$realHurt=false){
			global $pkData;
			$value = round(max(1,$value));
			if(!$realHurt && $user->teamID != $target->teamID)
				$value = $user->getHurt($value,$target);
				
				
			if($user->teamID != $target->teamID && $target->hp <= $value && ($temp = $target->isDieMiss('atk')))
			{
				
				$value = 0;
				$pkData->addSkillMV(null,$target,pk_skillType('NOHURT',$temp['id']));	
				$target->testTSkill('DMISS',$temp);
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

				$rHp = $target->addHp($value,$user->id == $target->id);
				if($rHp == 0 && $value < 0)
					$this->setSkillEffect($target,pk_skillType('HP','-'.$rHp));
				else 
					$this->setSkillEffect($target,pk_skillType('HP',$rHp));
					
				if($target->hp == 0)
					$pkData->addSkillMV(null,$target,pk_skillType('DIE',1));				
			}

			
			if(!$this->type && $user->teamID != $target->teamID)
			{
				if($target->hp > 0)
				{
					$target->testTSkill('BEHURT',array($user,$value));
				}
					
				if($user->isPKing)
					$user->testTSkill('HURT',$value);
			}
			
			return $value;
		}
		
		//A��B��Ѫ
		function addHp($user,$target,$value,$isMax=false,$forever=false,$full=false){
			global $pkData;
			$value = round(max(1,$value));
			if($isMax)
			{	
			
				$target->maxHp += $value;
				if($forever)
					$target->add_hp += $value;
				$this->setSkillEffect($target,pk_skillType('MHP',$value));
				$user->testTSkill('MHP',$value);
				if($full)
					$value = $target->maxHp;
			}
			
			$rHp = $target->addHp($value);
			if($rHp == 0 && $value < 0)
				$this->setSkillEffect($target,pk_skillType('HP','-'.$rHp));
			else 
				$this->setSkillEffect($target,pk_skillType('HP',$rHp));
			
			if(!$this->type)//�������Լ�Ѫ���ᴥ���¼�
			{			
				$user->testTSkill('HEAL',$value);
				$target->testTSkill('BEHEAL',$value);
				$target->team->enemy->currentMonster[0]->testTSkill('EBEHEAL',$value);
				//�������������
				// $pkData->playArr1[0]->testTSkill('SHEAL',$value);
				// $pkData->playArr2[0]->testTSkill('SHEAL',$value);
			}
			
			return $value;
		}
		
		//��ħ
		function addMp($user,$target,$value){
			$value = round($value);
			$target->mp += $value;
			$this->setSkillEffect($target,pk_skillType('MP',$value));
			return $value;
		}
		
		
		/*
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
			global $pkData;
			$len = count($target->buffArr);
			$b = false;
			for($i=0;$i<$len && $num > 0;$i++)
			{
				if($target->buffArr[$i]->noClean)
					continue;
				if($isDebuff == -1 || $target->buffArr[$i]->isDebuff == $isDebuff)
				{
					$pkData->addSkillMV(null,$enemy,pk_skillType('CSTAT',numToStr($target->buffArr[$i]->id).numToStr($target->buffArr[$i]->cd)).$target->buffArr[$i]->value);
					// $pkData->out_cleanStat($target,$target->buffArr[$i]->id,$target->buffArr[$i]->cd);
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
		
		function setStat31($target)
		{
			if($target->stat[31])
				$target->stat[31] ++;
			else
				$target->stat[31] = 1;
			$this->cleanStat($target,true,999);
		}
	}
?> 