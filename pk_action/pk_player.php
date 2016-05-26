<?php
class player{
	
	public $hp;			
	public $maxHp;			
	public $speed;			
	public $atk;		
	public $mp;	
	
	public $action1 = 0;//可以攻击(当不可以攻击时，小技也不能用)
	public $action2 = 0;//可以小技
	public $action3 = 0;//可以大技
	public $action4 = 0;//特性技能
	public $action5 = 0;//全禁
	public $def = 0;//被伤害加成
	public $hurt = 0;//伤害加成
	public $tag = array();//状态标记，不会参与主程序逻辑
	
	public $healAdd = 0;//治疗加成
	public $restrain = 0;//克制加成
	public $unRestrain = 0;//被克制加成
	
	
	
	public $cdhp = 0;//定时血量改变
	
	
	public $monsterData;	//原始的怪物数据		
	public $orginPKData;	//初始化后的数据	

	public $isPKing = false;//是否场上PK中的玩家
	public $teamID = 0;//所属队伍
	public $team;//所属队伍
	
	public $cdCount = 0;//用于出手排序
	public $mpAction = 0;//可以能量出手
	private $__cdCount = 0;//实际出手的时间
	public $actionCount = 0;//出手次数，满60次必胜
	public $statCountArr = array();//状态回合计算
	public $skillArr = array();//可选择的技能
	public $skillArrCD0 = array();//开始就用了的技能

	//
	public $stat1 = 0;//魔免
	public $stat2 = 0;//吸血百分比
	public $stat3 = 0;//比对方血少伤害加强
	public $stat4 = 0;//比对方血多伤害加强
	public $stat5 = 0;//破盾，无视对方防御
	public $stat6 = 0;//unUse
	public $stat7 = 0;//unUse
	public $stat8 = 0;//unUse
	public $stat9 = 0;//unUse
	public $stat10 = 0;//unUse
	
	public $temp = 0;
	
	// public $atkhp = 0;//就是这次攻击造成的伤害，在双方身上
	// public $temp1 = 0;//人身上的临时变量
	// public $temp2 = 0;//人身上的临时变量
	// public $temp3 = 0;//人身上的临时变量
	
	public $pkRound = 0;//参与PK的回合计数
	public $haveSetCDCount = false;//已经计算过回合行动数据
	
	
	public $monsterID = 0;//对应怪物ID
	public $id = 0;//唯一ID
	public $joinRound = 0;//加入战斗时的回合
	public $pos = 0;//在队伍中的位置
	public $lastStatKey;
	public $statKey = array('stat1','stat2','stat3','stat4','stat5','stat6','stat7','stat8','stat9','stat10',
	'atk','speed','maxHp','action1','action2','action3','action4','action5','hurt','def','cdhp','healAdd');
	
		
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
	

	//初始化为宠物战斗数值
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
	
	//清除Skill的引用
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
	
	
	//进入PK前的最后处理
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
				array_push($pkData->tArray,$temp);
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
				if($skillData->owner->action4 != 0 || $skillData->owner->action5 != 0)
					continue;
					
				if(!in_array($skillData,$pkData->tArray,true))//一回合只能触发一次特性，相同的会被合并(数值上)	
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
		
	//取回合用时，cd越小，出手越快
	function getCD(){
		return 2310 / max(1,$this->speed);
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
		
		if($this->mp >= $PKConfig->skillMP && $action3 <= 0 && $action5 <= 0)
			$this->mpAction = $this->mp;
		else
			$this->mpAction = 0;
		$this->haveSetCDCount = true;
	}
	
	//取技能行为
	function getSkill(){
		$arr = array();
		foreach($this->skillArr as $key=>$value)
		{
			if(!$value->type && $value->actionCount <= 0)//可使用
			{
				// trace($value->actionCount);
				if($this->action2 <= 0 && $this->action5 <= 0 && $this->action1 <= 0)
				{
					array_push($arr,$value);
				}
				else//错过了就不能使用了
					$this->setSkillUse($value->index);
			}
		}
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
	//加入技能状态
	function addState($user,$statObj,$round){
		if($this->stat1 > 0 && $user->teamID != $this->teamID)//魔免状态下，会清除所有异常
		{
			$round = 0;
		}
		
		$statObj['cd'] = $round;
		$statObj['userTeamID'] = $user->teamID; 
		if($statObj['tag'])//主动加的标记
		{
			if(!$this->tag[$statObj['tag']])
				$this->tag[$statObj['tag']] = 0;
			$this->tag[$statObj['tag']] ++;
		}
		
		if(!$statObj['stopTag'])//停止自动标记
		{
			$this->setStateTag($statObj);
		}
		
		array_push($this->statCountArr,$statObj);
		if($round == 0)
			$this->testStateCD(0);
		$this->testOutStat();
	}
	
	//通过节点改变Tag
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
		$b = $this->testStateCD(1);
		if($b)
			$this->testOutStat();
		
		
		//结果
		if($haveAction)//有行动
		{
			$this->actionCount ++;			
			$pkData->out_times($this,$this->actionCount);
		}
		
		return $this->actionCount >= $PKConfig->actionRound;
	}
	
	//测试是否有要清的技能
	function testStateCD($decNum = 0){
		$len=count($this->statCountArr);	
		$b = false;
		for($i=0;$i<$len;$i++)
		{
			if($decNum)
				$this->statCountArr[$i]['cd'] -= $decNum; 
			if($this->statCountArr[$i]['cd'] <= 0)//状态到期
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
				//设标签
				if(!$this->statCountArr[$i]['stopTag'])
				{
					$this->setStateTag($this->statCountArr[$i],-1);
				}
				if($this->statCountArr[$i]['tag'])
				{
					$this->tag[$this->statCountArr[$i]['tag']] --;
				}
				
				//效果结束时执行的逻辑
				if($this->statCountArr[$i]['ac_end'])
				{
					$this->statCountArr[$i]['skillObj']->onEnd($this->statCountArr[$i]['skillValue']);
				}
				
				array_splice($this->statCountArr,$i,1);	
				$i--;
				$len--;	
				$b = true;
			}
			else //效果中执行的逻辑
			{
				if($this->statCountArr[$i]['ac_round'])
				{
					$this->statCountArr[$i]['skillObj']->onRound($this->statCountArr[$i]['skillValue']);
				}
			}
		}
		return $b;
	}
	
	
	//测试要不要输出状态数据
	function testOutStat()
	{
		$this->testTSkill('STAT');
		global $pkData;
		if(!$pkData->outDetail)
			return;

		$statKey = '';//自动状态
		$statKey2 = '';//手动状态
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
		
		if($this->lastStatKey == $statKey)//状态有改变
			return;
			
		
		$this->lastStatKey = $statKey;
		$pkData->out_stat($this,$statKey);
	}
	
	//根据伤害加成改变扣血量
	function changeByHurt($v,$enemy){
		$temp = $this->hurt;
		if(isRestrain($this,$enemy))//相克
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
	//根据减伤改变扣血量
	function changeByDef($v,$enemy){
		if($enemy->stat5 <= 0 && $this->def != 0)
			return max(1,round((1-$this->def/100)*$v));
		return $v;
	}
	
	//测试是否吸血
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