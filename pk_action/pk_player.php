<?php
class player{
	
	public $hp;			
	public $maxHp;			
	public $speed;			
	public $atk;		
	public $mp;	
	
	public $action1 = 0;//���Թ���(�������Թ���ʱ��С��Ҳ������)
	public $action2 = 0;//����С��
	public $action3 = 0;//���Դ�
	public $action4 = 0;//���Լ���
	public $action5 = 0;//ȫ��
	public $def = 0;//���˺��ӳ�
	public $hurt = 0;//�˺��ӳ�
	public $tag = array();//״̬��ǣ���������������߼�
	
	public $healAdd = 0;//���Ƽӳ�
	public $restrain = 0;//���Ƽӳ�
	public $unRestrain = 0;//�����Ƽӳ�
	
	
	
	public $cdhp = 0;//��ʱѪ���ı�
	
	
	public $monsterData;	//ԭʼ�Ĺ�������		
	public $orginPKData;	//��ʼ���������	

	public $isPKing = false;//�Ƿ���PK�е����
	public $teamID = 0;//��������
	public $team;//��������
	
	public $cdCount = 0;//���ڳ�������
	public $mpAction = 0;//������������
	private $__cdCount = 0;//ʵ�ʳ��ֵ�ʱ��
	public $actionCount = 0;//���ִ�������60�α�ʤ
	public $statCountArr = array();//״̬�غϼ���
	public $skillArr = array();//��ѡ��ļ���
	public $skillArrCD0 = array();//��ʼ�����˵ļ���

	//
	public $stat1 = 0;//ħ��
	public $stat2 = 0;//��Ѫ�ٷֱ�
	public $stat3 = 0;//�ȶԷ�Ѫ���˺���ǿ
	public $stat4 = 0;//�ȶԷ�Ѫ���˺���ǿ
	public $stat5 = 0;//�ƶܣ����ӶԷ�����
	public $stat6 = 0;//unUse
	public $stat7 = 0;//unUse
	public $stat8 = 0;//unUse
	public $stat9 = 0;//unUse
	public $stat10 = 0;//unUse
	
	public $temp = 0;
	
	// public $atkhp = 0;//������ι�����ɵ��˺�����˫������
	// public $temp1 = 0;//�����ϵ���ʱ����
	// public $temp2 = 0;//�����ϵ���ʱ����
	// public $temp3 = 0;//�����ϵ���ʱ����
	
	public $pkRound = 0;//����PK�Ļغϼ���
	public $haveSetCDCount = false;//�Ѿ�������غ��ж�����
	
	
	public $monsterID = 0;//��Ӧ����ID
	public $id = 0;//ΨһID
	public $joinRound = 0;//����ս��ʱ�Ļغ�
	public $pos = 0;//�ڶ����е�λ��
	public $lastStatKey;
	public $statKey = array('stat1','stat2','stat3','stat4','stat5','stat6','stat7','stat8','stat9','stat10',
	'atk','speed','maxHp','action1','action2','action3','action4','action5','hurt','def','cdhp','healAdd');
	
		
	//��ʼ����
	function __construct($id){
		global $monster_base,$filePath;
		$data = $monster_base[$id];
		$this->monsterID = $id;
		$this->monsterData = $data;
		// trace($this->monsterID);
		pk_requireSkill($this->monsterID);
		// $this->hp = $data['hp'];
		// $this->maxHp = $data['hp'];
		// $this->speed = $data['speed'];
		// $this->atk = $data['atk'];
	}
	

	//��ʼ��Ϊ����ս����ֵ
	function initData($add,$fight){
		global $equalPK;
		if($equalPK)
		{
			$add = new stdClass();
			$add->hp = 300;
			$add->atk = 300;
			$add->spd = 0;
		}
		else if(!$add)
		{
			$add = new stdClass();
			$add->hp = 0;
			$add->atk = 0;
			$add->spd = 0;
		}
		else
		{	
			if(!$add->hp)
				$add->hp = 0;
			if(!$add->atk)
				$add->atk = 0;
			if(!$add->spd)
				$add->spd = 0;
		}
		

		
			
			
		$this->base_hp = round($this->monsterData['hp'] * (1+$add->hp/100));
		$this->base_atk = round($this->monsterData['atk'] * (1+$add->atk/100));
		$this->base_speed = round($this->monsterData['speed'] * (1+$add->spd/100));
		
		if($fight)
		{
			$this->base_hp = round($this->base_hp * (1+$fight/100));
			$this->base_atk = round($this->base_atk * (1+$fight/100));
			// $this->base_speed = round($this->base_speed * (1+$fight/100));
		}
		
		$this->add_hp = 0;
		$this->atk_add= 0;
		$this->speed_add = 0;
		
		
		$this->hp = $this->base_hp;
		
		$data = new stdClass();
		$data->hp = $this->base_hp;
		$data->atk = $this->base_atk;
		$data->speed = $this->base_speed;
		return $data;
	}
	
	function setTecEffect($tec){
		if(!$tec)
			return;
		//16�˺���ǿ��17������ǿ��18�ظ���ǿ��19���Ƽ�ǿ��20����ѹ��
		if($tec->m16)
			$this->$hurt += $tec->m16;
		if($tec->m17)
			$this->$def += $tec->m17;
		if($tec->m18)
			$this->$healAdd += $tec->m18;
		if($tec->m19)
			$this->$restrain += $tec->m19;
		if($tec->m20)
			$this->$unRestrain += $tec->m20;
	}

	//ȡ�ط���������
	function getReplayNeed(){
		$oo = new stdClass();
		$oo->hp = $this->hp;
		$oo->id = $this->id;
		$oo->mid = $this->monsterID;
		
		if($this->add_hp)
			$oo->add_hp = $this->add_hp;	
		if($this->add_atk)
			$oo->add_atk = $this->add_atk;	
		if($this->add_speed)
			$oo->add_speed = $this->add_speed;
		return $oo;
	}
	
	//ͨ���ط������ع�team����
	function fromReplayNeed($oo){
		global $monster_base;

		$this->hp = $oo->hp;
		$this->id = $oo->id;
		$this->add_hp = (int)$oo->add_hp;
		$this->add_atk = (int)$oo->add_atk;
		$this->add_speed = (int)$oo->add_speed;
		
		
		$this->base_hp = $oo->base_hp;
		$this->base_atk = $oo->base_atk;
		$this->base_speed = $oo->base_speed;
	}
	
	//����PKǰ�����ݻ�ԭ
	function resetPKData(){
		$this->maxHp = $this->base_hp + $this->add_hp;
		$this->hp = min($this->maxHp,$this->hp);
		$this->speed = $this->base_speed + $this->add_speed;
		$this->atk = $this->base_atk + $this->add_atk;
		$this->__cdCount = 0;
		$this->actionCount = 0;
		$this->statCountArr = array();
		$this->mp = 0;
		$this->hurt = 0;
		$this->def = 0;
		
		$this->action1 = 0;
		$this->action2 = 0;
		$this->action3 = 0;
		$this->action4 = 0;
		$this->action5 = 0;	
		$this->stat1 = 0;
		$this->stat2 = 0;
		$this->stat3 = 0;
		$this->stat4 = 0;
		$this->stat5 = 0;
		$this->stat6 = 0;
		$this->stat7 = 0;
		$this->stat8 = 0;
		$this->stat9 = 0;
		$this->stat10 = 0;
		
		
		$this->healAdd = 0;
		$this->restrain = 0;
		$this->unRestrain = 0;
		$this->cdhp = 0;
		// $this->temp1 = 0;
		// $this->temp2 = 0;
		
		$this->haveSetCDCount = false;
		
		$this->freeSkill();
		
		$this->lastStatKey = '';
		$this->skill = null;
		$this->skillArr = array();
		$this->skillArrCD0 = array();
		$this->tag = array();
	}
	
	//���Skill������
	function freeSkill(){
		pk_freeSkill($this->skill);
		foreach($this->skillArr as $key=>$value)
		{
			pk_freeSkill($value);
		}
		foreach($this->skillArrCD0 as $key=>$value)
		{
			pk_freeSkill($value);
		}
	}
	
	
	//����PKǰ�������
	function beforePK(){
		$this->setTecEffect($this->team->tecLevel);


		
		if($this->isPKing)
		{
			$this->skill = pk_monsterSkill($this->monsterID,'0');
			$this->skill->owner = $this;
			$this->skill->index = 0;
			$this->skill->isMain = true;
			
			$this->addSkill(1,'1');
			$this->addSkill(2,'2');
			$this->addSkill(3,'3');
			$this->addSkill(4,'4');
			$this->addSkill(5,'5');
			$this->pkRound ++;
		}
		else
		{
			$this->addSkill(1,'f1');
			$this->addSkill(2,'f2');
			$this->addSkill(3,'f3');
			$this->addSkill(4,'f4');
			$this->addSkill(5,'f5');
		}	
	}
	
	
	//�Ӽ���
	function addSkill($index,$key){	
		global $pkData;	
		$temp = pk_monsterSkill($this->monsterID,$key);
		if($temp && !$temp->leader)
		{
			$temp->index = $index;
			$temp->owner = $this;
			if($temp->cd == 0)//PKǰִ��
			{
				array_push($pkData->tArray,$temp);
				array_push($this->skillArrCD0,$temp);
			}
			else 
			{
				$this->skillArr[$index] = $temp;
				if($temp->type)//���Լ���
				{
					if(!$this->team->tArr[$temp->type])
						$this->team->tArr[$temp->type] = array();
					array_push($this->team->tArr[$temp->type],$temp);
				}
			}
		}
		return $temp;
	}
	
	//�������Լ����Ƿ񴥷�
	function testTSkill($type,$data=null){
		global $pkData;
		if(!$this->team->tArr[$type])
			return;
		$len = count($this->team->tArr[$type]);
		if($len == 0)
			return;
		for($i=0;$i<$len;$i++)
		{
			$skillData = $this->team->tArr[$type][$i];
			if($skillData->type == $type)
			{
				if($skillData->actionCount > 0)//CD��
					continue;	
				if($skillData->owner->action4 != 0 || $skillData->owner->action5 != 0)
					continue;
					
				if(!in_array($skillData,$pkData->tArray,true))//һ�غ�ֻ�ܴ���һ�����ԣ���ͬ�Ļᱻ�ϲ�(��ֵ��)	
				{
					$skillData->tData = $data;
					array_push($pkData->tArray,$skillData);
				}
				else if($data)
				{
					$skillData->tData += $data;
				}
				
			}
		}
	}
		
	//ȡ�غ���ʱ��cdԽС������Խ��
	function getCD(){
		return 2310 / max(1,$this->speed);
	}
	
	//����غ����ܵ�Ӱ��
	function setRoundEffect(){
		$this->haveSetCDCount = false;
	}
	
	//���ʱ��,���ڳ�������
	function setCDCount(){
		if($this->haveSetCDCount)
			return;
		global $PKConfig;
		$this->cdCount = $this->__cdCount + $this->getCD();
		
		if($this->mp >= $PKConfig->skillMP && $action3 <= 0 && $action5 <= 0)
			$this->mpAction = $this->mp;
		else
			$this->mpAction = 0;
		$this->haveSetCDCount = true;
	}
	
	//ȡ������Ϊ
	function getSkill(){
		$arr = array();
		foreach($this->skillArr as $key=>$value)
		{
			if(!$value->type && $value->actionCount <= 0)//��ʹ��
			{
				// trace($value->actionCount);
				if($this->action2 <= 0 && $this->action5 <= 0 && $this->action1 <= 0)
				{
					array_push($arr,$value);
				}
				else//����˾Ͳ���ʹ����
					$this->setSkillUse($value->index);
			}
		}
		return $arr;
	}
	
	//��ü�����ʹ��
	function setSkillUse($index){
		$skillItem = $this->skillArr[$index];
		if($skillItem)
		{
			$skillItem->actionCount = $skillItem->cd;
		}
	}	
	//���뼼��״̬
	function addState($user,$statObj,$round){
		if($this->stat1 > 0 && $user->teamID != $this->teamID)//ħ��״̬�£�����������쳣
		{
			$round = 0;
		}
		
		$statObj['cd'] = $round;
		$statObj['userTeamID'] = $user->teamID; 
		if($statObj['tag'])//�����ӵı��
		{
			if(!$this->tag[$statObj['tag']])
				$this->tag[$statObj['tag']] = 0;
			$this->tag[$statObj['tag']] ++;
		}
		
		if(!$statObj['stopTag'])//ֹͣ�Զ����
		{
			$this->setStateTag($statObj);
		}
		
		array_push($this->statCountArr,$statObj);
		if($round == 0)
			$this->testStateCD(0);
		$this->testOutStat();
	}
	
	//ͨ���ڵ�ı�Tag
	function setStateTag($statObj,$num = 1)
	{
		$len=count($this->statKey);	
		for($i=0;$i<$len;$i++)
		{
			$key = $this->statKey[$i];
			if($statObj[$key])
			{
				if($statObj[$key] > 0)
				{
					if(!$this->tag[$i])
						$this->tag[$i] = 0;
					$this->tag[$i] += $num;
				}
				else
				{
					if(!$this->tag[$i + 30])
						$this->tag[$i + 30] = 0;
					$this->tag[$i + 30] += $num;
				}
			}
		}
	}
	
	//����Ѿ��ж�����
	function setHaveAction($haveAction){
		global $PKConfig,$pkData;
		$this->__cdCount = $this->cdCount;
		$this->setRoundEffect();
		
		//����CD
		foreach($this->skillArr as $key=>$value)
		{
			$value->actionCount --; 
		}
		
		//����״̬
		$b = $this->testStateCD(1);
		if($b)
			$this->testOutStat();
		
		
		//���
		if($haveAction)//���ж�
		{
			$this->actionCount ++;			
			$pkData->out_times($this,$this->actionCount);
		}
		
		return $this->actionCount >= $PKConfig->actionRound;
	}
	
	//�����Ƿ���Ҫ��ļ���
	function testStateCD($decNum = 0){
		$len=count($this->statCountArr);	
		$b = false;
		for($i=0;$i<$len;$i++)
		{
			if($decNum)
				$this->statCountArr[$i]['cd'] -= $decNum; 
			if($this->statCountArr[$i]['cd'] <= 0)//״̬����
			{
				foreach($this->statKey as $value)
				{
					if($this->statCountArr[$i][$value])
					{
						$this->{$value} -= $this->statCountArr[$i][$value];
						if($value == 'maxHp')
						{
							if($this->hp > $this->maxHp)
								$this->hp = $this->maxHp;
						}
					}
				}
				//���ǩ
				if(!$this->statCountArr[$i]['stopTag'])
				{
					$this->setStateTag($this->statCountArr[$i],-1);
				}
				if($this->statCountArr[$i]['tag'])
				{
					$this->tag[$this->statCountArr[$i]['tag']] --;
				}
				
				//Ч������ʱִ�е��߼�
				if($this->statCountArr[$i]['ac_end'])
				{
					$this->statCountArr[$i]['skillObj']->onEnd($this->statCountArr[$i]['skillValue']);
				}
				
				array_splice($this->statCountArr,$i,1);	
				$i--;
				$len--;	
				$b = true;
			}
			else //Ч����ִ�е��߼�
			{
				if($this->statCountArr[$i]['ac_round'])
				{
					$this->statCountArr[$i]['skillObj']->onRound($this->statCountArr[$i]['skillValue']);
				}
			}
		}
		return $b;
	}
	
	
	//����Ҫ��Ҫ���״̬����
	function testOutStat()
	{
		$this->testTSkill('STAT');
		global $pkData;
		if(!$pkData->outDetail)
			return;

		$statKey = '';//�Զ�״̬
		$statKey2 = '';//�ֶ�״̬
		foreach($this->tag as $key=>$value)
		{
			if($value)
			{
				if($key < 100)
					$statKey .= numToStr($key);
				else
					$statKey2 .= numToStr($key - 100);
			}
		}
		if($statKey2)
			$statKey .= '|'.$statKey2;
		
		if($this->lastStatKey == $statKey)//״̬�иı�
			return;
			
		
		$this->lastStatKey = $statKey;
		$pkData->out_stat($this,$statKey);
	}
	
	//�����˺��ӳɸı��Ѫ��
	function changeByHurt($v,$enemy){
		$temp = $this->hurt;
		if(isRestrain($this,$enemy))//���
		{
			$temp += max(0,8 + $this->restrain - $enemy->unRestrain);
		}
		if($this->stat3 > 0 && ($this->hp/$this->maxHp) < ($enemy->hp/$enemy->maxHp))
		{
			$temp += $this->stat3;
		}
		if($this->stat4 > 0 && ($this->hp/$this->maxHp) > ($enemy->hp/$enemy->maxHp))
		{
			$temp += $this->stat4;
		}
		if($temp != 0)
			return max(1,round(($temp/100+1)*$v));
		return $v;
	}
	//���ݼ��˸ı��Ѫ��
	function changeByDef($v,$enemy){
		if($enemy->stat5 <= 0 && $this->def != 0)
			return max(1,round((1-$this->def/100)*$v));
		return $v;
	}
	
	//�����Ƿ���Ѫ
	function testStat2($v){
		if($this->stat2 > 0 && $v >0)
		{
			$this->addHp(round(($this->stat2/100)*$v));
		}
	}
	
	function addHp($v){
		$this->hp += $v;
		if($this->hp > $this->maxHp)
			$this->hp = $this->maxHp;
		else if($this->hp < 0)
			$this->hp = 0;				
			
		if($this->hp > 0)	
			$this->testTSkill('HP');
		else
		{
			$this->testTSkill('DIE');
		}
	}
	
	function getHpRate(){
		return $this->hp / $this->maxHp;
	}
		
	function addMp($v){
		global $PKConfig;
		$this->mp += $v;
		if($this->mp > $PKConfig->maxMP)
			$this->mp = $PKConfig->maxMP;
	}
	
	
	
}

?>