<?php
class Team{
	public $ring=1;	//令牌（召唤者技能）
	public $ringLevel=1;	//令牌等级（召唤者技能等级）	
	public $ringLevelAdd=0;	//令牌等级增加值
	public $teamPlayer;//通用技能发送者(召唤者)
	public $stopRing;//令牌技能无效
	public $monsterBase;//备份怪物的战斗数据，用于传去客户端
	
	//16伤害增强，17防御增强，18回复增强，19克制加强，20克制压制
	public $tecLevel = array();
	//队伍信息
	public $teamInfo = array();
	public $list;//对方怪物的排序
	
	// 最开始计算出的加成
	// public $speedRateOrgin = 1;			
	// public $hpRateOrgin = 1;			
	// public $atkRateOrgin = 1;
	
	// 过程中产生的，不会失效的加成
	// public $speedRate = 1;			
	// public $hpRate = 1;			
	// public $atkRate = 1;	
	
	public $fight;//出场时的战力加成（牛牛）
	
	//过程中产生的，只作用一次的加成
	public $nextPKAction=array();//本次上场时，会产生的加成[{skillid,level,value},...],使用一次就会清掉
	public $totalPKAction=array();//过程中产生的，会在PK前填充到nextPKAction['V,atk#456#CD@5*Round@6']
	public $tArr = array();//特性技能
	
	public $monsterList = array();//等待出战的宠物
	public $currentMonster = array();//当前出战的宠物
	public $teamID;
	public $enemy;
	
	


		
	//初始化类
	function __construct($data=null){
		global $equalPK;
		if($data)
		{
			$this->teamID = $data->teamID;
			$this->ring = $data->ring->id;
			$this->ringLevel = $data->ring->level;
			$this->fight = $data->fight;
			$this->tecLevel = $data->stec;
			$this->list = $data->list;
			if($equalPK)
				$this->ringLevel = 10;
			$tec = $data->tec;//"tec":{"107":{"hp":15,"atk":15,"sp":15}}}
			$list = $data->list;
			$len = count($list);
			$skill = array();//回合前执行的技能
			// $indexArray = $data->index;
			// if(!$indexArray)
				// $indexArray = new stdClass();
			$this->monsterBase = new stdClass();
			$this->initTeamPlayer();
			for($i=0;$i<$len;$i++)
			{
				if(!$list[$i])
				{
					continue;
				}
					
				$player = new Player($list[$i]);
				$player->teamID = $this->teamID;
				$player->team = $this;
				$player->id = 10 + ($this->teamID-1)*20 + $i;
				if($this->monsterBase->{''.$player->monsterID})
				{
					$player->initData($tec->{$player->monsterID},$this->fight);
					$this->monsterBase->{''.$player->monsterID}->num++;
				}
				else
					$this->monsterBase->{''.$player->monsterID} = $player->initData($tec->{$player->monsterID},$this->fight);

				// for($j=1;$j<=5;$j++)//PK前置技能
				// {
					// $temp = pk_monsterSkill($list[$i],'f'.$j);
					// if($temp && $temp->leader)
					// {
						// if($temp->once && $skill[$player->monsterID.'_'.$j])//只能有一个生效的技能
						// {
							// pk_freeSkill($temp);
							// continue;
						// }
							
						// array_push($this->totalPKAction,$temp);
						// $skill[$player->monsterID.'_'.$j] = true;
					// }
					// else
					// {
						// pk_freeSkill($temp);
					// }
				// }	
				
				array_push($this->monsterList,$player);
			}

		}
	}
	
	function initTeamPlayer(){
		$this->teamPlayer = new Player(1);
		$this->teamPlayer->teamID = $this->teamID;
		$this->teamPlayer->id = $this->teamID;
		$this->teamPlayer->team = $this;
	}
	
	//重置战斗数据  
	function resetPKData(){
		global $pkData,$monster_base;
		$this->ringLevelAdd = 0;
		
		$this->tArr = array();
		$this->stopRing = false;
		
		$this->teamInfo = array('num'=>array());
		$this->teamInfo['atks'] = 0;
		$this->teamInfo['mhps'] = 0;
		$this->teamInfo['spds'] = 0;
		$len = count($this->currentMonster);
		for($i=1;$i<$len;$i++)
		{
			$player = $this->currentMonster[$i];
			$this->teamInfo['atks'] += $player->atk;
			$this->teamInfo['mhps'] += $player->maxHp;
			$this->teamInfo['spds'] += $player->speed;
		}
		
		$list = $this->list;
		$len = count($list);
		for($i=0;$i<$len;$i++)
		{
			$monsterID = $list[$i];
			$monsterData = $monster_base[$monsterID];
			if(!$this->teamInfo['num'][$monsterID])
				$this->teamInfo['num'][$monsterID] = 1;
			else
				$this->teamInfo['num'][$monsterID] ++;
		}
	
		if($pkData->isVedio)
			return;
			
		
	
		//下面都是处理nextPKAction 和 totalPKAction，回放逻辑不进入
	
		//填充nextPKAction;
		$newArray = array();//新的totalPKAction
		
		foreach($this->nextPKAction as $key=>$value)
		{
			if (in_array($value, $this->totalPKAction,true))
				continue;
			pk_freeSkill($value);
		}
		$this->nextPKAction = array();
		
		foreach($this->totalPKAction as $key=>$value)
		{
			$value->lRound --;
			array_push($this->nextPKAction,$value);
			if($value->lRound > 0)
			{
				array_push($newArray,$value);
			}
		}		
		$this->totalPKAction = $newArray;
	}
	
	//战斗前的处理
	function beforePK(){
		global $pkData,$ring_base;
		$player = $this->teamID == 1?$pkData->playArr1[0]:$pkData->playArr2[0];
		$skillIndex = 1;
		
		//初始化召唤师，与当前出战单位基础相当
		$this->teamPlayer->resetPKData();
		$this->teamPlayer->atk = $player->atk;
		$this->teamPlayer->maxHp = $player->maxHp;
		$this->teamPlayer->speed = $player->speed;
		
		//令牌相关		
		/*if($this->ring && !$this->stopRing)
		{
			$ringData = $ring_base[$this->ring];
			$level = $ringData['begin'] + ($this->ringLevel + $this->ringLevelAdd)*$ringData['step'];
			
			$otherRingData = $ring_base[$this->enemy->ring];
			$otherLevel = $otherRingData['begin'] + ($this->enemy->ringLevel + $this->enemy->ringLevelAdd)*$otherRingData['step'];
			
			if(!$this->enemy->stopRing && $this->enemy->ring == 13)//13令牌克制（减去对应等级）
			{
				$level -= $otherLevel;
			}
			if($this->ring == 14)//14令牌复制(最多高一级)
			{
				$ringData = $ring_base[$this->enemy->ring];
				$level = $ringData['begin'] + $this->ringLevel*$ringData['step'];
				$level = min($level,$otherLevel + 1);
			}
			
			if($level > 0 && $this->ring != 14 && $this->ring != 13)
			{
				// $skillDes = $ringData['skill'];
				// $skillDes = str_replace("VALUE",$level,$skillDes);
				$skill = pk_decodeSkill('RSkill_'.$this->ring); 
				$skill->owner = $this->teamPlayer;
				$skill->index = $skillIndex;
				$skill->ringLevel = $level;
				
				
				if($skill->cd == 0)
				{
					array_push($pkData->tArray,$skill);
					array_push($this->teamPlayer->skillArrCD0,$skill);
				}
				else 
				{
					$this->teamPlayer->skillArr[$skill->index] = $skill;
					if($skill->type)//特性技能
					{
						if(!$this->tArr[$skill->type])
							$this->tArr[$skill->type] = array();
						array_push($this->tArr[$skill->type],$skill);
					}
						
				}
			}
			
		}*/
		$skillIndex++;
		
		//遗留技能相关
		foreach($this->nextPKAction as $key => $value)
		{
			$skill = $value;
			$skill->owner = $this->teamPlayer;
			$skill->index = $skillIndex;
			$skillIndex++;
			if($skill->cd == 0)
			{
				array_push($pkData->frontArray,$skill);
			}
			else 
			{
				$this->teamPlayer->skillArr[$skill->index] = $skill;
				if($skill->type)//特性技能
				{
					if(!$this->tArr[$skill->type])
						$this->tArr[$skill->type] = array();
					array_push($this->tArr[$skill->type],$skill);
				}
			}
		}
		
		// $this->nextPKAction = $this->totalPKAction;
	}
	
	//取回放所需数据
	function getReplayNeed(){
		$oo = new stdClass();
		$len = count($this->nextPKAction);
		$arr = array();
		for($i=0;$i<$len;$i++)
		{
			array_push($arr,$this->nextPKAction[$i]->name);
		}
		$oo->ac = $arr;
		// $oo->ti = $this->teamInfo;
		return $oo;
	}
	
	//取回放所需数据2
	function getTeamBase(){
		$oo = new stdClass();
		$oo->rl = $this->ringLevel;
		$oo->r = $this->ring;
		// $oo->f = $this->fight;
		$oo->tl = $this->tecLevel;
		$oo->list = $this->list;
		$oo->mb = $this->monsterBase;
		return $oo;
	}
	
	//通过回放数据重构team对象
	function fromReplayNeed($teamID,$oo,$baseoo,$playerArr){
		$this->ringLevel = $baseoo->rl;
		$this->ring = $baseoo->r;
		// $this->fight = $baseoo->f;
		$this->tecLevel = $baseoo->tl;
		$this->list = $baseoo->list;
		$this->monsterBase = $baseoo->mb;
		
		$len = count($oo->ac);
		$arr = array();
		for($i=0;$i<$len;$i++)
		{
			$mid = pk_skillMonster($oo->ac[$i]);
			if($mid)
			{
				pk_requireSkill($mid);
			}
			$skill = pk_decodeSkill($oo->ac[$i]);
			array_push($arr,$skill);
		}
		$this->nextPKAction = $arr;
		
		// $this->teamInfo = $oo->ti;
		$this->teamID = $teamID;
		$this->initTeamPlayer();
		$len = count($playerArr);
		for($i=0;$i<$len;$i++)
		{
			$player = new Player($playerArr[$i]->mid);
			$player->teamID = $this->teamID;
			$player->team = $this;
			$player->joinRound = $oo->jr;
			$player->pos = $i;
			if($i==0)
				$player->isPKing = true;
				
			$playerArr[$i]->base_hp = $baseoo->mb->{''.$player->monsterID}->hp;
			$playerArr[$i]->base_atk = $baseoo->mb->{''.$player->monsterID}->atk;
			$playerArr[$i]->base_speed = $baseoo->mb->{''.$player->monsterID}->speed;
			
			$player->fromReplayNeed($playerArr[$i]);
			array_push($this->monsterList,$player);
		}
	}
	
	//是否有下一个出战单位
	function haveNexPlayer(){
		return count($this->monsterList) > 0;
	}
	
		
	//取出场的队伍
	function getFightArr(){
	
		$arr = array();
		if(count($this->monsterList) == 0)
			return $arr;
		global $pkData;
		array_push($arr,array_shift($this->monsterList));
		if($this->monsterList[0])
			array_push($arr,$this->monsterList[0]);
		if($this->monsterList[1])
			array_push($arr,$this->monsterList[1]);
		$arr[0]->isPKing = true;
		$len = count($arr);
		for($i=0;$i<$len;$i++)
		{
			$arr[$i]->joinRound = $pkData->round;
			$arr[$i]->pos = $i;
		}
		return $arr;
	}
	
}

?>