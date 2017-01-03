<?php

//����ִ��˳������
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

class PKData{//��Ҫ��¼һЩPK�е�����
	public $team1;
	public $team2;	
	

	//��ǰ��ս��λ
	public $playArr1;			
	public $playArr2;		

	public $resultArray = array();
	public $roundResultArray;
	public $roundResultCollect;
	
	public $skillRecord = array();
	public $skillUser = null;
	public $skillRecordCountDec = 0;//��Ч���б����
	
	
	public $roundNeedArray;
	public $roundObject;
	public $from = -1;
	public $to = -1;
	public $tArray = array();//Ҫִ�е����Լ���	/ ��λ�ж�ǰ��ǰ�ü���
	public $frontArray = array();//����PKʱ���еļ���
	
	public $round = 1;//��ǰ�Ļغ�
	
	public $outDetail = false;//�Ƿ����PKϸ��
	public $outResult = true;//�Ƿ����PK���
	public $isVedio = false;//�ǻ�¼��ط�
	
	public $step = 0;//�ڻغ��еĲ���
	

		
	//��ʼ����
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
	
	//�غϿ�ʼǰ��ʼ������
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
		
		//����������ĵ�λ����
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
		
		//սǰ����ش���
		$len = count($playArr1);
		for($i=0;$i<$len;$i++)
		{
			$playArr1[$i]->beforePK();
			// ��������ӳ�
			// if($i>0)
			// {
				// $this->testKindAdd($playArr1[$i],$playArr1[0],$playArr2[0]);
			// }
		}
		
		$len = count($playArr2);
		for($i=0;$i<$len;$i++)
		{
			$playArr2[$i]->beforePK();
			//��������ӳ�
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
	
	//��������ӳ�
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
	
	//�첽���ܴ���
	function dealTArray(){
		$len = count($this->tArray);
		if($len)
		{
			usort($this->tArray,tArraySortFun);
			for($i=0;$i<count($this->tArray);$i++) {//������;����뼼��
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
	
	//ǰ�ü��ܴ���
	function dealFrontArray(){
		$len = count($this->frontArray);
		if($len)
		{
			usort($this->frontArray,tArraySortFun);
			for($i=0;$i<$len;$i++) {//������;����뼼��
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
	
	
	
	//����PK�Ƿ��Ѿ�����
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
		
		
		
		//�����ǲ��Դ���
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
	
	//ʹ�õ�������ǰ����
	function startSkillMV($user){
		if(!$this->outDetail)
			return;
		$this->skillRecord = array();
		$this->skillUser = $user;
		// $this->skillRecordCountDec = 0;
	}
	
	// function startSkillEffect(){//����Ч����ʼ
		// if(!$this->outDetail)
			// return;
		// array_push($this->skillRecord,array(null,null,'effectStart'));
		// $this->skillRecordCountDec ++;
	// }
	// function endSkillEffect(){//����Ч������
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
			$this->out_changeFrom($this->skillUser);//ת��������
			$this->out_str('7'.numToStr($skillID)); //���ܿ�ʼ
			for($i=0;$i<$len;$i++)
			{
				if($this->skillRecord[$i][0])
					$this->out_changeFrom($this->skillRecord[$i][0]);//ת��������
				if($this->skillRecord[$i][1])
					$this->out_changeTo($this->skillRecord[$i][1]);//ת����������
				if($this->skillRecord[$i][2])
				{
					if($this->skillRecord[$i][2] == '61')//MV��6���ı�ֵΪ1
					{
						$addMV = true;
					}
					// else if($this->skillRecord[$i][2] == 'effectStart')//����Ч����ʼ
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
			
			$this->out_str('9');//���ܽ���
		}
		$this->skillRecord = array();
	}
	
	function out_debug($str){//�������д��debug��Ϣ
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
		$this->out_changeFrom($play1);//ת��������
		$this->out_changeTo($play2);//ת����������
		$this->out_skill($skillID,$value);//����ʹ�õļ���
	}
	
	function out_changeFrom($play1){
		if(!$this->outDetail)
			return;
		if($play1->id != $this->from->id)//ת��������
		{
			$this->out_str('1'.$play1->id);
			$this->from = $play1;
		}
	}
	
	function out_changeTo($play2){
		if(!$this->outDetail)
			return;
		if($play2->id != $this->to->id)//ת����������
		{
			$this->out_str('2'.$play2->id);
			$this->to = $play2;
		}
	}
	
	//״̬�ı� (stateKey��һ����ǰ����״̬������)
	function out_stat($target,$stateKey){
		if(!$this->outDetail)
			return;		
			
		$this->out_changeFrom($target);//ת��������
		$this->out_str('4'.$stateKey);	
	}
	
	//�غϼ����ı�
	function out_times($target,$times){
		if(!$this->outDetail)
			return;		
			
		$this->out_changeFrom($target);//ת��������
		$this->out_str('5'.$times);	
	}
	
	//������һغϽ���
	function out_end($target=null){
		$this->step ++;
		if(!$this->outDetail)
			return;		
		if($target)	
			$this->out_changeFrom($target);//ת��������
		$this->out_str('6');	
		// $this->out_str('6'.($this->step);	
	}
	
	//���������ĻغϿ�ʼ
	function out_gameStart(){
		if(!$this->outDetail)
			return;
		$this->out_str('A');
	}
	
	// �������
	// function out_cleanStat($target,$key,$time){
		// if(!$this->outDetail)
			// return;
		// $this->out_changeTo($target);//ת��
		// $this->out_str('B'.numToStr($key).$time);
	// }
	
	//��λ����
	// function out_die($target){
		// if(!$this->outDetail)
			// return;
		// $this->out_changeTo($target);//ת��
		// $this->out_str('C');
	// }
	
	//***************************************************************************** end
		
	//ȡ�����Ķ���
	function XXX(){

	}
	
}

?>