<?php

//技能执行顺序排序
function tArraySortFun($a,$b){
	if($a->order > $b->order)
		return -1;
	if($a->order < $b->order)
		return 1;
	if($a->owner->joinRound > $b->owner->joinRound)
		return -1;
	if($a->owner->joinRound < $b->owner->joinRound)
		return 1;	
	if($a->owner->id < $b->owner->id)
		return -1;
	if($a->owner->id > $b->owner->id)
		return 1;	
	if($a->index < $b->index)
		return -1;
	return 1;
}

class PKData{//主要记录一些PK中的数据
	public $team1;
	public $team2;	
	

	//当前出战单位
	public $playArr1;			
	public $playArr2;		

	public $resultArray = array();
	public $roundResultArray;
	public $roundResultCollect;
	
	public $skillRecord = array();
	public $skillUser = null;
	public $skillRecordCountDec = 0;//无效的列表个数
	
	
	public $roundNeedArray;
	public $roundObject;
	public $from = -1;
	public $to = -1;
	public $tArray = array();//要执行的特性技能	/ 单位行动前的前置技能
	public $frontArray = array();//进入PK时所有的技能
	
	public $round = 1;//当前的回合
	
	public $outDetail = false;//是否输出PK细节
	public $outResult = true;//是否输出PK结果
	public $isVedio = false;//是回录像回放
	
	public $step = 0;//在回合中的步数
	

		
	//初始化类
	function __construct($team1Data=null,$team2Data=null){
		$this->roundResultCollect = new stdClass();
		if($team1Data)
		{
			$this->team1 = new Team($team1Data);
			$this->team2 = new Team($team2Data);
			$this->team1->enemy = $this->team2;
			$this->team2->enemy = $this->team1;
		}
	}
	
	//回合开始前初始化数据
	function roundStart($playArr1,$playArr2){
		
		$this->from = -1;
		$this->to = -1;
		$this->tArray = array();
		$this->frontArray = array();
		$this->playArr1 = $playArr1;
		$this->playArr2 = $playArr2;
		$this->team1->currentMonster = $playArr1;
		$this->team2->currentMonster = $playArr2;

		
		$len = count($playArr1);
		for($i=0;$i<$len;$i++)
		{
			$playArr1[$i]->resetPKData();
		}
		
		$len = count($playArr2);
		for($i=0;$i<$len;$i++)
		{
			$playArr2[$i]->resetPKData();
		}
		
		
		

		$this->team1->resetPKData();
		$this->team2->resetPKData();
		
		//放入出场所的单位数据
		if($this->outDetail)
		{
			$this->roundResultArray = array();
		}
		if(!$this->isVedio)
		{
			$this->roundNeedArray = array();
			$this->roundObject = new stdClass();
			$this->roundObject->team1 = $this->team1->getReplayNeed();
			$this->roundObject->team2 = $this->team2->getReplayNeed();
			$this->roundObject->player1 = array();
			$this->roundObject->player2 = array();
			$len = count($playArr1);
			for($i=0;$i<$len;$i++)
			{	
				$player = $playArr1[$i];
				array_push($this->roundObject->player1,$player->getReplayNeed());
				$this->roundObject->team1->jr = $player->joinRound;
			}
			
			$len = count($playArr2);
			for($i=0;$i<$len;$i++)
			{	
				$player = $playArr2[$i];
				array_push($this->roundObject->player2,$player->getReplayNeed());
				$this->roundObject->team2->jr = $player->joinRound;
			}
			// array_push($this->roundNeedArray,$this->roundObject);
		}
		
		// trace('---------------------------1');
		// trace(count($this->team1->teamPlayer->skillArr));
		// trace(count($this->team2->teamPlayer->skillArr));
		
		//战前的相关处理
		$len = count($playArr1);
		for($i=0;$i<$len;$i++)
		{
			$playArr1[$i]->beforePK();
			// 加入种类加成
			// if($i>0)
			// {
				// $this->testKindAdd($playArr1[$i],$playArr1[0],$playArr2[0]);
			// }
		}
		
		$len = count($playArr2);
		for($i=0;$i<$len;$i++)
		{
			$playArr2[$i]->beforePK();
			//加入种类加成
			// if($i>0)
			// {
				// $this->testKindAdd($playArr2[$i],$playArr2[0],$playArr1[0]);
			// }
		}
		
		$this->team1->beforePK();
		$this->team2->beforePK();
		

		
		// trace('---------------------------2');
		// trace(count($this->team1->teamPlayer->skillArr));
		// trace(count($this->team2->teamPlayer->skillArr));
	}
	
	//加入种类加成
	// function testKindAdd($user,$self,$enemy){
		// $addSelf = false;
		// $addEnemy = false;
		// foreach($user->monsterData['effect_kind'] as $value)
		// {
			// if(!$addSelf && in_array($value,$self->monsterData['kind'],true))
			// {
				// $addSelf = true;
			// }
			// if(!$addEnemy && in_array($value,$enemy->monsterData['kind'],true))
			// {
				// $addEnemy = true;
			// }
		// }
		// if($addSelf || $addEnemy)
		// {
			// $temp = pk_decodeSkill('KindSkill');
			// $temp->addSelf = $addSelf;
			// $temp->addEnemy = $addEnemy;
			// $temp->index = 53;
			// $temp->owner = $user;
			// array_push($user->skillArrCD0,$temp);
			// array_push($this->tArray,$temp);
		// }
	// }
	
	//异步技能处理
	function dealTArray(){
		$len = count($this->tArray);
		if($len)
		{
			usort($this->tArray,tArraySortFun);
			for($i=0;$i<count($this->tArray);$i++) {//可能中途会加入技能
				$skillData = $this->tArray[$i];
				$userX = $skillData->owner;
				$enemyX = $userX->team->enemy->currentMonster[0];
				$selfX = $userX->team->currentMonster[0];
				// trace($userX->id.'-'.$selfX->id.'-'.$enemyX->id.'-');
				pk_action_skill($skillData,$userX,$selfX,$enemyX);
			}
			$this->tArray = array();
		}
	}
	
	//前置技能处理
	function dealFrontArray(){
		$len = count($this->frontArray);
		if($len)
		{
			usort($this->frontArray,tArraySortFun);
			for($i=0;$i<$len;$i++) {//可能中途会加入技能
				$skillData = $this->frontArray[$i];
				$userX = $skillData->owner;
				$enemyX = $userX->team->enemy->currentMonster[0];
				$selfX = $userX->team->currentMonster[0];
				// trace($userX->id.'-'.$selfX->id.'-'.$enemyX->id.'-');
				
				$this->startSkillMV($userX);
				pk_action_skill($skillData,$userX,$selfX,$enemyX);
				$this->dealTArray();
				$this->out_end($userX);
			}
			$this->frontArray = array();
		}
	}
	
	
	
	//测试PK是否已经结束
	function testRoundFinish(){
		$player1 = $this->playArr1[0];
		$player2 = $this->playArr2[0];
		// trace($this->round.'=='.$this->step);
		if($player1->hp == 0 || $player2->hp == 0)
		{
			$result = new stdClass();
			if($player1->hp == 0 && $player2->hp == 0)
			{
				$result->w = 0;
			}
			else if($player1->hp == 0)
			{
				$result->w = $player2->teamID;
				$result->hp = min($player2->hp,($player2->base_hp + $player2->add_hp));
			}
			else
			{
				$result->w = $player1->teamID;
				$result->hp = min($player1->hp,$player1->base_hp + $player1->add_hp);
			}
			if($player1->hp == 0)
			{
				// trace(count($this->playArr1));
				$player1->freeSkill();
			}
			
				
			if($player2->hp == 0)
			{
				
				$player2->freeSkill();
			}
				
			return $result;
		}
		return false;
	}
	
	
	function roundFinish($result){
		if($this->outDetail)
		{
			// array_push($this->resultArray,join('',$this->roundResultArray));
			$this->roundResultCollect->{$this->round} = join(',',$this->roundResultArray);
			$this->roundResultArray = array();
		}
		if($this->outResult)
		{
			$this->roundObject->result = $result;
			array_push($this->resultArray,$this->roundObject);
		}
		$this->round ++;
		
		
		
		//下面是测试代码
		// $testRound = 8;
		// if($this->round == $testRound)
			// $this->outDetail = true;
		// else
			// $this->outDetail = false;
		// if($this->round == $testRound + 1)
		// {
			// trace('=========');
			// trace(join(',',$this->roundResultArray));
		// }
			
	}
	
	
	
	//***************************************************************************** outPut
	
	//使用单个技能前进入
	function startSkillMV($user){
		if(!$this->outDetail)
			return;
		$this->skillRecord = array();
		$this->skillUser = $user;
		// $this->skillRecordCountDec = 0;
	}
	
	// function startSkillEffect(){//技能效果开始
		// if(!$this->outDetail)
			// return;
		// array_push($this->skillRecord,array(null,null,'effectStart'));
		// $this->skillRecordCountDec ++;
	// }
	// function endSkillEffect(){//技能效果结束
		// if(!$this->outDetail)
			// return;
		// array_push($this->skillRecord,array(null,null,'effectEnd'));
		// $this->skillRecordCountDec ++;
	// }
	function addSkillMV($player,$target,$skillAction){
		if(!$this->outDetail)
			return;
		array_push($this->skillRecord,array($player,$target,$skillAction));
	}
	function endSkillMV($skillID){
		if(!$this->outDetail)
			return;
		$len = count($this->skillRecord);
		$addMV = false;
		$out8 = false;
		if($len > 0)// - $this->skillRecordCountDec > 0)
		{
			$this->out_changeFrom($this->skillUser);//转换攻击者
			$this->out_str('7'.numToStr($skillID)); //技能开始
			for($i=0;$i<$len;$i++)
			{
				if($this->skillRecord[$i][0])
					$this->out_changeFrom($this->skillRecord[$i][0]);//转换攻击者
				if($this->skillRecord[$i][1])
					$this->out_changeTo($this->skillRecord[$i][1]);//转换被攻击者
				if($this->skillRecord[$i][2])
				{
					if($this->skillRecord[$i][2] == '61')//MV（6）改变值为1
					{
						$addMV = true;
					}
					// else if($this->skillRecord[$i][2] == 'effectStart')//技能效果开始
					// {
						// $this->out_str('3');
					// }
					else
					{
						$this->out_str('8'.$this->skillRecord[$i][2]);
						$out8 = true;
					}
				}	
			}
			if($addMV && !$out8)
				$this->out_str('8'.'61');
			
			$this->out_str('9');//技能结束
		}
		$this->skillRecord = array();
	}
	
	function out_debug($str){//在序列中打出debug信息
		// return;
		out_str('0'.$str);
	}
	function out_str($str){
		array_push($this->roundResultArray,$str);
	}
	function out($play1,$play2,$skillID,$value)
	{
		if(!$this->outDetail)
			return;
		$this->out_changeFrom($play1);//转换攻击者
		$this->out_changeTo($play2);//转换被攻击者
		$this->out_skill($skillID,$value);//表现使用的技能
	}
	
	function out_changeFrom($play1){
		if(!$this->outDetail)
			return;
		if($play1->id != $this->from->id)//转换攻击者
		{
			$this->out_str('1'.$play1->id);
			$this->from = $play1;
		}
	}
	
	function out_changeTo($play2){
		if(!$this->outDetail)
			return;
		if($play2->id != $this->to->id)//转换被攻击者
		{
			$this->out_str('2'.$play2->id);
			$this->to = $play2;
		}
	}
	
	//状态改变 (stateKey是一个当前点亮状态的序列)
	function out_stat($target,$stateKey){
		if(!$this->outDetail)
			return;		
			
		$this->out_changeFrom($target);//转换攻击者
		$this->out_str('4'.$stateKey);	
	}
	
	//回合计数改变
	function out_times($target,$times){
		if(!$this->outDetail)
			return;		
			
		$this->out_changeFrom($target);//转换攻击者
		$this->out_str('5'.$times);	
	}
	
	//单个玩家回合结束
	function out_end($target=null){
		$this->step ++;
		if(!$this->outDetail)
			return;		
		if($target)	
			$this->out_changeFrom($target);//转换攻击者
		$this->out_str('6');	
		// $this->out_str('6'.($this->step);	
	}
	
	//你来我往的回合开始
	function out_gameStart(){
		if(!$this->outDetail)
			return;
		$this->out_str('A');
	}
	
	// 清除技能
	// function out_cleanStat($target,$key,$time){
		// if(!$this->outDetail)
			// return;
		// $this->out_changeTo($target);//转换
		// $this->out_str('B'.numToStr($key).$time);
	// }
	
	//单位死亡
	// function out_die($target){
		// if(!$this->outDetail)
			// return;
		// $this->out_changeTo($target);//转换
		// $this->out_str('C');
	// }
	
	//***************************************************************************** end
		
	//取出场的队伍
	function XXX(){

	}
	
}

?>