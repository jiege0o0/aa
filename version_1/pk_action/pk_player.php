<?php
class player{
	
	public $hp;			
	public $maxHp;			
	public $speed;			
	public $atk;		
	public $mp;	
	public $maxMp = 100;
	
	//��������
	public $base_hp;	
	public $base_atk;	
	public $base_speed;	
	public $add_hp;	
	public $atk_add;	
	public $speed_add;	

	

	public $def = 0;//���˺��ӳ�
	public $hurt = 0;//�˺��ӳ�
	public $manaHp = 0;//ħ����
	public $maxHurt = 999;//����˺�ϵ��

	
	
	public $monsterData;	//ԭʼ�Ĺ�������		
	public $orginPKData;	//��ʼ���������	

	public $isPKing = false;//�Ƿ���PK�е����
	public $isDie = false;//
	public $teamID = 0;//��������
	public $team;//��������
	
	public $cdCount = 0;//���ڳ�������
	public $mpAction = 0;//������������
	private $__cdCount = 0;//ʵ�ʳ��ֵ�ʱ��
	public $actionCount = 0;//���ִ�������60�α�ʤ
	public $buffArr = array();//״̬�غϼ���
	public $skillArr = array();//��ѡ��ļ���
	public $skillArrCD0 = array();//��ʼ�����˵ļ���
	public $allSkillArr = array();//���еļ���

	
	public $temp = array();
	
	public $atkhp = 0;//������ι�����ɵ��˺�����˫������
	
	public $pkRound = 0;//����PK�Ļغϼ���
	public $haveSetCDCount = false;//�Ѿ�������غ��ж�����
	
	
	public $monsterID = 0;//��Ӧ����ID
	public $id = 0;//ΨһID
	public $joinRound = 0;//����ս��ʱ�Ļغ�
	public $pos = 0;//�ڶ����е�λ��
	public $lastStatKey;
	// public $statKey = array('stat1','stat2','stat3','stat4','stat5','stat6','stat7','stat8','stat9','stat10',
	// 'atk','speed','maxHp','action1','action2','action3','action4','action5','hurt','def','cdhp','healAdd');
	public $stat = array();//����״̬���
	//1������2���٣�3������4���� 5:Ѫ����    --+10��ɼ�ֵ
	//21�����չ���22�������ܣ�23�������ԣ�24����,25:�Ȼ�
	//31:ħ��
	//41��HP+��42��HP-��
	//101:��Ѫ����(mid:45)��102����磨mid:51��
	
	
	public $missTimes = 0;//�����ܵĴ���
	public $hitTimes = 0;//���еĴ���
	public $dieMissTimes = array();//�����ܵ���������
	
	
	public $skillID = 0;//�����ͷŵļ���
	public $atkCount = 0;//�˺��ۼ�
	public $hpCount = 0;//���ۼ�
	public $healCount = 0;//����
	public $effectCount = 0;//����
	
		
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
	
	//ս������
	function getForceRate(){
		return $this->base_atk / $this->monsterData['atk'];
	}
	
	function addAtkCount($v){
		$this->getSkillUser()->atkCount += $v;
	}
	function addHpCount($v){
		$this->getSkillUser()->hpCount += $v;
	}
	function addHealCount($v){
		$this->getSkillUser()->healCount += $v;
	}
	function addEffectCount($v){
		$this->getSkillUser()->effectCount += $v;
	}
	
	function getSkillUser(){
		
		if($this->id >= 10)
			return $this;
		//throw new Exception(count($this->allSkillArr));	
		$skillItem = $this->allSkillArr[$this->skillID];
		//trace($skillItem);
		
		if($skillItem)
		{
			$skillUser = $this->team->allMonsterList[$skillItem->orginOwnerID%10];
			if($skillUser)
				return $skillUser;
		}
		return $this;
	}
	

	//��ʼ��Ϊ����ս����ֵ $add����ս���ӳ� $fight����ս���ӳ�
	function initData($add,$fight,$leader){
		global $equalPK;
		
		if($equalPK)
		{
			$add = 1000;
		}
		if(!$fight)
		{
			$fight = 0;
		}
		
		
		$fight += $add;
		
		
		
			
			
		$this->base_hp = round($this->monsterData['hp'] * 1);
		$this->base_atk = round($this->monsterData['atk'] * 1);
		$this->base_speed = round($this->monsterData['speed'] * 1);
		
		if($fight)
		{
			$this->base_hp = round($this->base_hp * (1+$fight/100));
			$this->base_atk = round($this->base_atk * (1+$fight/100));
			// $this->base_speed = round($this->base_speed * (1+$fight/100));
		}
		if($leader)
		{
			if($leader->{'2'})
				$this->base_hp = round($this->base_hp * (1+$leader->{'2'}/100));
			if($leader->{'1'})
				$this->base_atk = round($this->base_atk * (1+$leader->{'1'}/100));
			if($leader->{'3'})
				$this->base_speed = round($this->base_speed * (1+$leader->{'3'}/100));
		}
		
		$this->add_hp = 0;
		$this->atk_add= 0;
		$this->speed_add = 0;
		
		
		$this->hp = $this->base_hp;
		
		$data = new stdClass();
		$data->hp = $this->base_hp;
		$data->atk = $this->base_atk;
		$data->speed = $this->base_speed;
		$data->num = 1; 
		return $data;
	}
	
	//�ٶȸı�
	function addSpeed($value){
		if(!$value || $this->hp <= 0)
			return 0;

		$id = 2;
		if($value > 0)
			$value = round(max(1,$value));
		else
		{	
			$value = -round(max(1,-$value));
			$id += 10;
		}
		$this->speed += $value;
		$this->setSkillEffect(pk_skillType('STAT',numToStr($id).'0'.$value));
		$this->setRoundEffect();
		return $value;
	}
	
	//�ӹ���
	function addAtk($value){
		if(!$value || $this->hp <= 0)
			return 0;
		$id = 1;
		if($value > 0)
			$value = round(max(1,$value));
		else
		{
			$id += 10;
			$value = -round(max(1,-$value));
			if($this->atk < -$value)//��������������0
				$value = -$this->atk + 1; 
		}
		$this->atk += $value;
		$this->setSkillEffect(pk_skillType('STAT',numToStr($id).'0'.$value));
		return $value;
	}
	

	
	//�Ӷ�
	function addDef($value){
		if(!$value || $this->hp <= 0)
			return 0;
		$id = 3;
		$this->def += $value;
		if($value < 0)
			$id += 10;
		$this->setSkillEffect(pk_skillType('STAT',numToStr($id).'0'.$value));
		return $value;
	}
	
	//����
	function addHurt($value){
		if(!$value || $this->hp <= 0)
			return 0;
		$id = 4;
		$this->hurt += $value;
		if($value < 0)
			$id += 10;
		$this->setSkillEffect(pk_skillType('STAT',numToStr($id).'0'.$value));
		return $value;
	}	
	
	//���ü���Ч��
	function setSkillEffect($mv=null){
		global $pkData;
		$this->setRoundEffect();
		if(!$mv)
			$mv = pk_skillType('MV',1);
		$pkData->addSkillMV(null,$this,$mv);
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
		$this->mp = 0;
		$this->maxMp = $this->monsterData['mp'];
		if(!$this->maxMp)
			$this->maxMp = 999;
		$this->hurt = 0;
		$this->def = 0;
		$this->manaHp = 0;
		$this->maxHurt = 999;
		$this->missTimes = 0;
		$this->hitTimes = 0;
		$this->dieMissTimes = array();
	
		$this->temp = array();
		
		$this->haveSetCDCount = false;
		
		$this->freeSkill();
		
		$this->lastStatKey = '';
		$this->skill = null;
		$this->skillArr = array();
		$this->skillArrCD0 = array();
		$this->allSkillArr = array();
		$this->tag = array();
		$this->buffArr = array();
		$this->stat = array();
		$this->isDie = false;
	}
	
	function addStat($id,$num){
		if(!$this->stat[$id])
		{
			$this->stat[$id] = 0;
		}
		$this->stat[$id] += $num;
	}
	
	//���Skill������
	function freeSkill(){
		pk_freeSkill($this->skill);
		pk_freeSkill($this->atkAction);
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
			
			$this->atkAction = pk_decodeSkill('NormalAtk');
			$this->atkAction->owner = $this;
			$this->atkAction->index = 50;
			
			$this->addSkill(1,'1');
			$this->addSkill(2,'2');
			$this->addSkill(3,'3');
			$this->addSkill(4,'4');
			$this->addSkill(5,'5');
			$this->pkRound ++;
		}
		else
		{
			// $this->speed = floor($this->speed/2);
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
			$this->allSkillArr[$index] = $temp;
			$temp->index = $index;
			$temp->owner = $this;
			if($temp->cd == 0)//PKǰִ��
			{
				array_push($pkData->frontArray,$temp);
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
				{
					//$skillData->actionCount --;
					
					continue;
				}					
				if($skillData->owner->stat[23])
					continue;
					
				// if(!in_array($skillData,$pkData->tArray,true))//һ�غ�ֻ�ܴ���һ�����ԣ���ͬ�Ļᱻ�ϲ�(��ֵ��)	
				// {
					$skillData->tData = $data;
					array_push($pkData->tArray,$skillData);
					
				// }
				// else if($data)
				// {
					// $skillData->tData += $data;
				// }
				
			}
		}
	}
		
	//ȡ�غ���ʱ��cdԽС������Խ��
	function getCD(){
		return 2310 / min(max(10,$this->speed),200);
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
		
		if($this->mp >= $this->maxMp && !$this->stat[22] && !$this->stat[24] && !$this->skill->disabled && $this->skill->canUse($this))
			$this->mpAction = $this->mp;
		else
			$this->mpAction = 0;
		$this->haveSetCDCount = true;
	}
	
	//ȡ������Ϊ
	function getSkill(){
		$arr = array();
		$skill;
		foreach($this->skillArr as $key=>$value)
		{
			if(!$value->type && $value->actionCount <= 0)//��ʹ��
			{
				if($value->isSendAtOnce)
					array_push($arr,$value);
				else if(!$skill)
					$skill = $value;
				else if($value->order > $skill->order)
					$skill = $value;
			}
		}
		if($skill)
			array_push($arr,$skill);
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
	
	function addBuff($buff){
		if($this->hp <= 0)
			return;
		if($buff->isDebuff && $this->stat[31])
		{
			global $pkData;
			$pkData->addSkillMV(null,$this,pk_skillType('NOMAGIC',1));
			return false;
		}
		
		array_push($this->buffArr,$buff);
		$this->setRoundEffect();
		return true;
	}
	
	//���뼼��״̬
	// function addState($user,$statObj,$round){
		// if($this->stat1 > 0 && $user->teamID != $this->teamID)//ħ��״̬�£�����������쳣
		// {
			// $round = 0;
		// }
		
		// $statObj['cd'] = $round;
		// $statObj['userTeamID'] = $user->teamID; 
		// if($statObj['tag'])//�����ӵı��
		// {
			// if(!$this->tag[$statObj['tag']])
				// $this->tag[$statObj['tag']] = 0;
			// $this->tag[$statObj['tag']] ++;
		// }
		
		// if(!$statObj['stopTag'])//ֹͣ�Զ����
		// {
			// $this->setStateTag($statObj);
		// }
		
		// array_push($this->buffArr,$statObj);
		// if($round == 0)
			// $this->testStateCD(0);
		// $this->testOutStat();
	// }
	
	//ͨ���ڵ�ı�Tag
	// function setStateTag($statObj,$num = 1)
	// {
		// $len=count($this->statKey);	
		// for($i=0;$i<$len;$i++)
		// {
			// $key = $this->statKey[$i];
			// if($statObj[$key])
			// {
				// if($statObj[$key] > 0)
				// {
					// if(!$this->tag[$i])
						// $this->tag[$i] = 0;
					// $this->tag[$i] += $num;
				// }
				// else
				// {
					// if(!$this->tag[$i + 30])
						// $this->tag[$i + 30] = 0;
					// $this->tag[$i + 30] += $num;
				// }
			// }
		// }
	// }
	
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
		$pkData->startSkillMV($this);
		// $pkData->startSkillEffect();
		$this->buffAction('AFTER');
		$pkData->endSkillMV(52);	
		
		
		
		$b = $this->testStateCD(1);
		if($b)
			$this->testOutStat();
		
		
		//���
		if($haveAction)//���ж�
		{
			$this->actionCount ++;			
		}
		$pkData->out_times($this,$this->actionCount);
		return $this->actionCount >= $PKConfig->actionRound;
	}
	
	//buff��;����Ч��
	function buffAction($key){
		$len=count($this->buffArr);	
		$b = false;
		for($i=0;$i<$len;$i++)
		{
			if($this->buffArr[$i]->actionTime == $key)
			{
				if($this->buffArr[$i]->onAction())
					$b = true;
			}	
		}
		return $b;
	}
	
	//�����Ƿ���Ҫ��ļ��ܣ�$decNum��ȥ��CD,�����Ƿ��м��ܱ���
	function testStateCD($decNum = 0){
		$len=count($this->buffArr);	
		$b = false;
		for($i=0;$i<$len;$i++)
		{
			
			$temp = $this->buffArr[$i]->cdRun($decNum); 
			if($temp)//״̬����
			{
				array_splice($this->buffArr,$i,1);	
				$i--;
				$len--;	
				$b = true;
			}
		}
		return $b;
	}
	
	
	//����Ҫ��Ҫ���״̬����
	function testOutStat()//100���ϵĲ������
	{
		$this->testTSkill('STAT');
		return;
		
		global $pkData;
		if(!$pkData->outDetail)
			return;

		$statKey = '';//�Զ�״̬
		$statKey2 = '';//�ֶ�״̬
		foreach($this->stat as $key=>$value)
		{
			if($value)
			{
				if($key < 100)
					$statKey .= numToStr($key);
			}
		}
		
		if($this->lastStatKey == $statKey)//״̬�иı�
			return;
			
		
		$this->lastStatKey = $statKey;
		$pkData->out_stat($this,$statKey);
	}
	
	
	
	//ȡ����ҵ������˺�
	function getHurt($v,$enemy){
		$hurt = $this->hurt;
		$def = $enemy->def;
		return max(1,round($v * (1+($hurt - $def)/100)));
		// return round($v * (1+($hurt - $def)/100));
		
	}
	
	function addHp($v,$isSelf=false){
		if($v < 0 && $this->maxHurt!= 999 && -$v > $this->maxHurt*$this->maxHp)
			$v = -round($this->maxHurt*$this->maxHp);
		if($v < 0 && $this->manaHp > 0 && !$isSelf)
		{
			$this->manaHp += $v;
			if($this->manaHp < 0)
			{
				$v = $this->manaHp;
				$this->manaHp = 0;
			}
			else
				$v = 0;
		}
		$this->hp += $v;
		if($this->hp > $this->maxHp)
		{
			$v -= $this->hp - $this->maxHp;
			$this->hp = $this->maxHp;
			
		}
		else if($this->hp < 0)
		{
			$v -= $this->hp;
			$this->hp = 0;	
		}
						
			
		if($this->hp > 0 && $v < 0)	
			$this->testTSkill('HP');			
		return $v;
	}
	
	function testDie(){
		if($this->hp <= 0 && !$this->isDie)	
		{
			$this->isDie = true;
			$this->testTSkill('DIE');
			$this->team->enemy->currentMonster[0]->testTSkill('EDIE');
			
			//�������BUFF
			$len = count($this->buffArr);
			for($i=0;$i<$len;$i++)
			{
				$this->buffArr[$i]->cd = 0;
			}
			$b = $this->testStateCD(0);
			if($b)
				$this->testOutStat();
		}
	}
	
	function getHpRate(){
		return $this->hp / $this->maxHp;
	}
		
	function addMp($v){
		$this->mp += $v;
		if($this->mp > $this->maxMp)
			$this->mp = $this->maxMp;
		else if($this->mp < 0)
			$this->mp = 0;	
			
		if($v)
			$this->setRoundEffect();
		
	}
	
	function reborn($v){
		$this->isDie = false;
		$lastMaxHp = $this->maxHp;
		$this->maxHp = max($this->maxHp,$this->base_hp + $this->add_hp);
		$this->hp = round($this->maxHp*$v);	
		global $pkData;
		if($lastMaxHp != $this->maxHp)
			$pkData->addSkillMV(null,$this,pk_skillType('MHP',$this->maxHp - $lastMaxHp));
		$pkData->addSkillMV(null,$this,pk_skillType('HP',$this->hp));	
	}
	
	//���У�������
	function mustHit(){
		if($this->hitTimes > 0)
		{
			$this->hitTimes --;
			return true;
		}
		return false;
	}
	
	//�������ܹ���
	function isMiss(){
		if($this->missTimes > 0)
		{
			$this->missTimes --;
			return true;
		}
		return false;
	}
	
	//������������
	function isDieMiss($type){
		$len = count($this->dieMissTimes);
		if($len > 0)
		{
			for($i=0;$i<$len;$i++)
			{
				if(!$this->dieMissTimes[$i]['type'] || $this->dieMissTimes[$i]['type'] == $type)
				{
					$temp = $this->dieMissTimes[$i];
					array_splice($this->dieMissTimes,$i,1);
					return $temp;
				}
			}
		}
		return null;
	}
	
}

?>