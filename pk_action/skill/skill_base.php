<?php 
	class SkillBase{
		public $owner;//����������
		public $index=0;//����ID
		public $isMain = false;//�Ƿ���������
		public $actionCount = 0;//����0��ʾCD��
		public $disabled = false;//������û��Ч
	
	
	
		public $cd = 1;//ÿCD���غϳ�һ���֣�3��Ϊ��2���غϣ���3���غϳ��֣�0Ϊ�غ�ǰ����
		public $stopout = false;//������ܶ�������������ͻ���
		// public round = 0;//�����غ�
		public $type = '';//���Լ���
		public $leader = false;//��PKǰִ��
		public $once = false;//����ִֻ��һ��
		
		//�������ٻ������
		public $lRound = 0;//�ٻ��ߵ�ʹ�ûغ���
		public $ringLevel = 0;
		
		
		function __construct(){
			$this->reInit();
			if($this->type)
				$this->actionCount = 0;
		}
		
		//�����Ƿ���ʹ��
		function canUse($user,$self=null,$enemy=null){
			return true;
		}
		
		//���¸�ֵ
		function reInit(){
			$this->actionCount = $this->cd - 1;//����CDΪ1�Ļ���һ���Ϳ�������
		}
		
		//���ü���Ч��
		function setSkillEffect($target,$mv=false){
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
				$value = -pk_atkHP($user,$target,$value);
			else
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
			
			if($user->teamID != $target->teamID)
			{
				$target->testTSkill('BEATK');
			}
			
			return $value;
		}
		
		//A��B��Ѫ
		function addHp($user,$target,$value,$isMax=false,$forever=false){
		
			$value = round(max(1,$value));
			$value = pk_healHP($user,$target,$value);
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
				$user->testTSkill('HEAL',$target);
				$target->testTSkill('BEHEAL',$user);
			}
			
			return $value;
		}
		
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
				$value = -round(max(1,-$value));
			$target->atk += $value;
			if($forever)
				$target->add_atk += $value;
			$this->setSkillEffect($target,pk_skillType('ATK',$value));
			
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
		}
		
		//���״̬(˭�ӵģ������)
		function cleanStat($target,$teamID,$num){
			$len = count($this->skillAction->target->statCountArr);
			$b = false;
			for($i=0;$i<$len && $num > 0;$i++)
			{
				if($this->skillAction->target->statCountArr[$i]['userTeamID'] == teamID)
				{
					$this->skillAction->target->statCountArr[$i]['cd'] = 0;
					$num --;
					$b = true;
				}
			}
			if($target->testStateCD())
			{
				$this->self->testOutStat();
				$target->setRoundEffect();
			}
			return $b;	
		}
		
		
	}
?> 