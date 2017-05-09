<?php
class player{
	
	public $hp;			
	public $maxHp;			
	public $speed;			
	public $atk;		
	public $mp;	
	public $maxMp = 100;
	
	//基础数据
	public $base_hp;	
	public $base_atk;	
	public $base_speed;	
	public $add_hp;	
	public $atk_add;	
	public $speed_add;	

	

	public $def = 0;//被伤害加成
	public $hurt = 0;//伤害加成
	public $manaHp = 0;//魔法盾
	public $maxHurt = 999;//最大伤害系数

	
	
	public $monsterData;	//原始的怪物数据		
	public $orginPKData;	//初始化后的数据	

	public $isPKing = false;//是否场上PK中的玩家
	public $teamID = 0;//所属队伍
	public $team;//所属队伍
	
	public $cdCount = 0;//用于出手排序
	public $mpAction = 0;//可以能量出手
	private $__cdCount = 0;//实际出手的时间
	public $actionCount = 0;//出手次数，满60次必胜
	public $buffArr = array();//状态回合计算
	public $skillArr = array();//可选择的技能
	public $skillArrCD0 = array();//开始就用了的技能

	
	public $temp = array();
	
	public $atkhp = 0;//就是这次攻击造成的伤害，在双方身上
	
	public $pkRound = 0;//参与PK的回合计数
	public $haveSetCDCount = false;//已经计算过回合行动数据
	
	
	public $monsterID = 0;//对应怪物ID
	public $id = 0;//唯一ID
	public $joinRound = 0;//加入战斗时的回合
	public $pos = 0;//在队伍中的位置
	public $lastStatKey;
	// public $statKey = array('stat1','stat2','stat3','stat4','stat5','stat6','stat7','stat8','stat9','stat10',
	// 'atk','speed','maxHp','action1','action2','action3','action4','action5','hurt','def','cdhp','healAdd');
	public $stat = array();//特殊状态标记
	//1：攻，2：速，3：防，4：伤 5:血上限    --+10变成减值
	//21：禁普攻，22：禁技能，23：禁特性，24：晕,25:魅惑
	//31:魔免
	//41：HP+，42：HP-，
	//101:吸血增加(mid:45)，102：结界（mid:51）
	
	
	public $missTimes = 0;//可闪避的次数
	public $hitTimes = 0;//必中的次数
	public $dieMissTimes = array();//可闪避的死亡次数
	
		
	//初始化类
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
	

	//初始化为宠物战斗数值 $add宠物战力加成 $fight总体战力加成
	function initData($add,$fight){
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
		debug($fight);
		
			
			
		$this->base_hp = round($this->monsterData['hp'] * 1);
		$this->base_atk = round($this->monsterData['atk'] * 1);
		$this->base_speed = round($this->monsterData['speed'] * 1);
		
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
		$data->num = 1; 
		return $data;
	}
	
	//速度改变
	function addSpeed($value){
		if(!$value || $this->hp == 0)
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
		return $value;
	}
	
	//加攻击
	function addAtk($value){
		if(!$value || $this->hp == 0)
			return 0;
		$id = 1;
		if($value > 0)
			$value = round(max(1,$value));
		else
		{
			$id += 10;
			$value = -round(max(1,-$value));
			if($this->atk < -$value)//攻击力不能少于0
				$value = -$this->atk + 1; 
		}
		$this->atk += $value;
		$this->setSkillEffect(pk_skillType('STAT',numToStr($id).'0'.$value));
		return $value;
	}
	

	
	//加盾
	function addDef($value){
		if(!$value || $this->hp == 0)
			return 0;
		$id = 3;
		$this->def += $value;
		if($value < 0)
			$id += 10;
		$this->setSkillEffect(pk_skillType('STAT',numToStr($id).'0'.$value));
		return $value;
	}
	
	//加伤
	function addHurt($value){
		if(!$value || $this->hp == 0)
			return 0;
		$id = 4;
		$this->hurt += $value;
		if($value < 0)
			$id += 10;
		$this->setSkillEffect(pk_skillType('STAT',numToStr($id).'0'.$value));
		return $value;
	}	
	
	//作用技能效果
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
		//16伤害增强，17防御增强，18回复增强，19克制加强，20克制压制
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

	//取回放所需数据
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
	
	//通过回放数据重构team对象
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
	
	//进入PK前的数据还原
	function resetPKData(){
		$this->maxHp = $this->base_hp + $this->add_hp;
		$this->hp = min($this->maxHp,$this->hp);
		$this->speed = $this->base_speed + $this->add_speed;
		$this->atk = $this->base_atk + $this->add_atk;
		$this->__cdCount = 0;
		$this->actionCount = 0;
		// $this->buffArr = array();
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
		
		// $this->action1 = 0;
		// $this->action2 = 0;
		// $this->action3 = 0;
		// $this->action4 = 0;
		// $this->action5 = 0;	
		// $this->stat1 = 0;
		// $this->stat2 = 0;
		// $this->stat3 = 0;
		// $this->stat4 = 0;
		// $this->stat5 = 0;
		// $this->stat6 = 0;
		// $this->stat7 = 0;
		// $this->stat8 = 0;
		// $this->stat9 = 0;
		// $this->stat10 = 0;
		
		
		// $this->healAdd = 0;
		// $this->restrain = 0;
		// $this->unRestrain = 0;
		// $this->cdhp = 0;
		// $this->temp1 = 0;
		// $this->temp2 = 0;
		$this->temp = array();
		
		$this->haveSetCDCount = false;
		
		$this->freeSkill();
		
		$this->lastStatKey = '';
		$this->skill = null;
		$this->skillArr = array();
		$this->skillArrCD0 = array();
		$this->tag = array();
		$this->buffArr = array();
		$this->stat = array();
	}
	
	function addStat($id,$num){
		if(!$this->stat[$id])
		{
			$this->stat[$id] = 0;
		}
		$this->stat[$id] += $num;
	}
	
	//清除Skill的引用
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
	
	
	//进入PK前的最后处理
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
			$this->addSkill(1,'f1');
			$this->addSkill(2,'f2');
			$this->addSkill(3,'f3');
			$this->addSkill(4,'f4');
			$this->addSkill(5,'f5');
		}	
	}
	
	
	//加技能
	function addSkill($index,$key){	
		global $pkData;	
		$temp = pk_monsterSkill($this->monsterID,$key);
		if($temp && !$temp->leader)
		{
			$temp->index = $index;
			$temp->owner = $this;
			if($temp->cd == 0)//PK前执行
			{
				array_push($pkData->frontArray,$temp);
				array_push($this->skillArrCD0,$temp);
			}
			else 
			{
				$this->skillArr[$index] = $temp;
				if($temp->type)//特性技能
				{
					if(!$this->team->tArr[$temp->type])
						$this->team->tArr[$temp->type] = array();
					array_push($this->team->tArr[$temp->type],$temp);
				}
			}
		}
		return $temp;
	}
	
	//测试特性技能是否触发
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
				if($skillData->actionCount > 0)//CD中
					continue;	
				if($skillData->owner->stat[23])
					continue;
					
				// if(!in_array($skillData,$pkData->tArray,true))//一回合只能触发一次特性，相同的会被合并(数值上)	
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
		
	//取回合用时，cd越小，出手越快
	function getCD(){
		return 2310 / max(10,$this->speed);
	}
	
	//这个回合有受到影响
	function setRoundEffect(){
		$this->haveSetCDCount = false;
	}
	
	//设计时器,用于出手排序
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
	
	//取技能行为
	function getSkill(){
		$arr = array();
		$skill;
		foreach($this->skillArr as $key=>$value)
		{
			if(!$value->type && $value->actionCount <= 0)//可使用
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
	
	//设该技能已使用
	function setSkillUse($index){
		$skillItem = $this->skillArr[$index];
		if($skillItem)
		{
			$skillItem->actionCount = $skillItem->cd;
		}
	}
	
	function addBuff($buff){
		if($buff->isDebuff && $this->stat[31])
			return false;
		if($this->hp == 0)
			return false;
		array_push($this->buffArr,$buff);
		$this->setRoundEffect();
		return true;
	}
	
	//加入技能状态
	// function addState($user,$statObj,$round){
		// if($this->stat1 > 0 && $user->teamID != $this->teamID)//魔免状态下，会清除所有异常
		// {
			// $round = 0;
		// }
		
		// $statObj['cd'] = $round;
		// $statObj['userTeamID'] = $user->teamID; 
		// if($statObj['tag'])//主动加的标记
		// {
			// if(!$this->tag[$statObj['tag']])
				// $this->tag[$statObj['tag']] = 0;
			// $this->tag[$statObj['tag']] ++;
		// }
		
		// if(!$statObj['stopTag'])//停止自动标记
		// {
			// $this->setStateTag($statObj);
		// }
		
		// array_push($this->buffArr,$statObj);
		// if($round == 0)
			// $this->testStateCD(0);
		// $this->testOutStat();
	// }
	
	//通过节点改变Tag
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
	
	//玩家已经行动过了
	function setHaveAction($haveAction){
		global $PKConfig,$pkData;
		$this->__cdCount = $this->cdCount;
		$this->setRoundEffect();
		
		//技能CD
		foreach($this->skillArr as $key=>$value)
		{
			$value->actionCount --; 
		}
		
		//技能状态
		$pkData->startSkillMV($this);
		// $pkData->startSkillEffect();
		$this->buffAction('AFTER');
		$pkData->endSkillMV(52);	
		
		$b = $this->testStateCD(1);
		if($b)
			$this->testOutStat();
		
		
		//结果
		if($haveAction)//有行动
		{
			$this->actionCount ++;			
		}
		$pkData->out_times($this,$this->actionCount);
		return $this->actionCount >= $PKConfig->actionRound;
	}
	
	//buff中途作用效果
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
	
	//测试是否有要清的技能，$decNum减去的CD,返回是否有技能被清
	function testStateCD($decNum = 0){
		$len=count($this->buffArr);	
		$b = false;
		for($i=0;$i<$len;$i++)
		{
			
			$temp = $this->buffArr[$i]->cdRun($decNum); 
			if($temp)//状态到期
			{
				array_splice($this->buffArr,$i,1);	
				$i--;
				$len--;	
				$b = true;
			}
		}
		return $b;
	}
	
	
	//测试要不要输出状态数据
	function testOutStat()//100以上的不会输出
	{
		$this->testTSkill('STAT');
		return;
		
		global $pkData;
		if(!$pkData->outDetail)
			return;

		$statKey = '';//自动状态
		$statKey2 = '';//手动状态
		foreach($this->stat as $key=>$value)
		{
			if($value)
			{
				if($key < 100)
					$statKey .= numToStr($key);
			}
		}
		
		if($this->lastStatKey == $statKey)//状态有改变
			return;
			
		
		$this->lastStatKey = $statKey;
		//debug($statKey);
		$pkData->out_stat($this,$statKey);
	}
	
	
	
	//取对玩家的最终伤害
	function getHurt($v,$enemy){
		$hurt = $this->hurt;
		$def = $enemy->def;
		return round($v * (1+($hurt - $def)/100));
		
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
						
			
		if($this->hp > 0)	
			$this->testTSkill('HP');
		/*else
		{
			$this->testTSkill('DIE');
			$this->team->enemy->currentMonster[0]->testTSkill('EDIE');
			
			//死后清除BUFF
			$len = count($this->buffArr);
			for($i=0;$i<$len && $num > 0;$i++)
			{
				$this->buffArr[$i]->cd = 0;
			}
			$b = $this->testStateCD(0);
			if($b)
				$this->testOutStat();
		}*/
		
		// if($this->id == 10)
		// {
			// global $pkData;
			// debug($pkData->step.'-'.$this->hp.'_'.$v);
		// }
			
		return $v;
	}
	
	function testDie(){
		if($this->hp <= 0)	
		{
			$this->testTSkill('DIE');
			$this->team->enemy->currentMonster[0]->testTSkill('EDIE');
			
			//死后清除BUFF
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
		
	}
	
	function reborn($v){
		$this->hp = round($this->maxHp*$v);	
		global $pkData;
		$pkData->addSkillMV(null,$this,pk_skillType('HP',$this->hp));	
	}
	
	//必中，不可闪
	function mustHit(){
		if($this->hitTimes > 0)
		{
			$this->hitTimes --;
			return true;
		}
		return false;
	}
	
	//可以闪避攻击
	function isMiss(){
		if($this->missTimes > 0)
		{
			$this->missTimes --;
			return true;
		}
		return false;
	}
	
	//可以闪避死亡
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