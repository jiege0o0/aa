<?php
class GameUser{
	public $gameid;
	public $nick;
	public $exp;
	public $next_exp;
	public $level;
	public $tec_force;
	public $award_force;
	public $tec;
	public $server_game;
	public $server_game_equal;
	public $main_game;
	public $day_game;
	public $honor;
	public $last_land;
	public $land_key;
	public $coin;
	public $prop;
	public $energy;
	public $collect;
	
	private $changeKey = array();

	//初始化类
	function __construct($data,$isOther=false){
		$this->gameid = $data['gameid'];
		$this->nick = $data['nick'];
		$this->head = $data['head'];
		$this->exp = (int)$data['exp'];
		$this->level = (int)$data['level'];
		$this->tec_force = (int)$data['tec_force'];
		$this->award_force = (int)$data['award_force'];
		$this->last_land = $data['last_land'];
		$this->coin = (int)$data['coin'];
		

		$this->server_game = $this->decode($data['server_game'],'{"choose":null,"exp":0,"win":0,"total":0,"last":0,"time":0,"pkdata":null,"enemy":null,"pk":0}');
		$this->server_game_equal = $this->decode($data['server_game_equal'],'{"choose":null,"exp":0,"win":0,"total":0,"last":0,"max":0,"time":0,"pkdata":null,"enemy":null,"pk":0}');
		$this->main_game = $this->decode($data['main_game'],'{"choose":null,"level":1,"kill":[],"awardtime":0,"time":0,"pkdata":null}');

		
		if($isOther)
			return;
		$this->next_exp = $this->getNextExp();
		$this->land_key = $data['land_key'];	
		$this->tec = $this->decode($data['tec'],'{"main":{},"ring":{},"monster":{}}');
		$this->collect = $this->decode($data['collect'],'{"level":{},"num":{}}');//碎片合成等级	
		$this->day_game = $this->decode($data['day_game'],'{"level":0,"lasttime":0,"times":0,"pkdata":null}');
		$this->honor = $this->decode($data['honor'],'{"monster":{},"ring":{}}');
		$this->prop = $this->decode($data['prop']);
		$this->energy = $this->decode($data['energy'],'{"v":0,"t":0,"rmb":0}');
		$this->diamond = $this->decode($data['diamond'],'{"free":0,"rmb":0}');
		$this->friends = $this->decode($data['friends'],'{"v":0,"t":0,"tk":0}');//好友相关
		$this->active = $this->decode($data['active'],'{"task":{}}');//活动
	}
	
	function decode($v,$default = null){
		if(!$v)
		{
			if($default)
				$v = $default;
				else
			$v = '{}';
		}
		return json_decode($v);
	}
	
	function setChangeKey($key){
		$this->changeKey[$key] = 1;
	}
	
	//有可领奖的成就
	function honorIsRed()
	{
		return false;
	}
	
	//有可合成的碎片
	function collectIsRed()
	{
		return false;
	}
	
	
	//体力相关==============================================
	function getEnergy(){
		$this->resetEnergy();
		return $this->energy->v + $this->energy->rmb;
	}
	function addEnergy($v){
		global $returnData;
		if($v)
		{
			$this->resetEnergy();
			$this->energy->v += $v;
			if($this->energy->v < 0)
			{
				$this->energy->rmb += $this->energy->v;
				$this->energy->v = 0;
			}
			$this->setChangeKey('energy');
			$returnData->sync_energy = $this->energy;
		}
	}
	function resetEnergy(){//不是同一天，能量回复为最大值
		if(!isSameDate($this->energy->t))
		{
			$this->energy->v = min(50,$this->energy->v + 30);
			$this->energy->t = time();
		}
	}
	//==============================================   end
	
	//好友PK次数相关==============================================
	function getFriendPKTimes(){
		$this->resetFriend();
		return $this->friends->v;
	}
	function addFriendPKTimes($v){
		global $returnData;
		if($v)
		{
			$this->resetFriend();
			$this->friends->v += $v;
			$this->setChangeKey('friends');
			$returnData->sync_friends = $this->friends;
		}
	}
	function getFriendTalk(){
		$this->resetFriend();
		return $this->friends->tk;
	}
	function addFriendTalk($v){
		global $returnData;
		if($v)
		{
			$this->resetFriend();
			$this->friends->tk += $v;
			$this->setChangeKey('friends');
			$returnData->sync_friends = $this->friends;
		}
	}
	function resetFriend(){//不是同一天，好友PK次数回复为最大值
		if(!isSameDate($this->friends->t))
		{
			$this->friends->v = max(10,$this->friends->v);
			$this->friends->tk = max(30,$this->friends->tk);
			$this->friends->t = time();
		}
	}
	//==============================================   end
	function getDiamond(){
		return $this->diamond->free + $this->diamond->rmb;
	}
	function addDiamond($v,$rmb = false){
		if(!$v)
			return;
		global $returnData;
		if($rmb)
		{
			$this->diamond->rmb += $v;
		}
		else
		{
			$this->diamond->free += $v;
			if($this->diamond->free < 0)
			{
				$this->diamond->rmb += $this->diamond->free;
				$this->diamond->free = 0;
			}
		}
		$this->setChangeKey('diamond');
		$returnData->sync_diamond = $this->diamond;
	}
	
	//加奖励的战力
	function addAwardForce($v){
		if($v < 0)
			return;
		global $returnData;
		$this->award_force += $v;
		$this->setChangeKey('award_force');
		$returnData->sync_award_force = $this->award_force;
	}
	//加经验
	function addExp($v){
		global $returnData;
		if($v)
		{
			$maxLevel = 50;
			$this->exp += $v;
			$this->setChangeKey('exp');
			$returnData->sync_exp = $this->exp;
			if($this->level >= $maxLevel)
				return;
			
			$upExp = $this->getNextExp();
			$up = false;
			while($this->exp >= $upExp && $this->level < $maxLevel)
			{
				$this->exp -= $upExp;
				$this->level ++;
				$upExp = $this->getNextExp();
				$this->setChangeKey('level');
				$up = true;
				$returnData->sync_exp = $this->exp;
				$returnData->sync_level = $this->level;
				$returnData->sync_next_exp = $upExp;
			}
			if($up)
			{
				if($this->level== 5 || $this->level== 8 || $this->level>= 10)//创建升级任务
				{
					if(!$this->active->task->doing)//是否有进行中的任务
					{
						$this->active->task->doing = true;
						$this->active->task->type = 'server_game';
						if($this->level >=10 && lcg_value()>0.6)
						{
							$this->active->task->type = 'server_game_equal';
						}
						$this->active->task->win = 0;
						$this->active->task->total = 0;
						$this->active->task->targettotal = rand(10,50);
						$this->active->task->targetwin = round($this->active->task->targettotal*rand(5,8)/10);
						$this->active->task->award = 1 + ceil($this->level/10);
						$this->setChangeKey('task');
						$returnData->sync_active_task = $this->active->task;
					}
				}
			}
		}
	}
	
	function getNextExp(){
		return (pow($this->level+1,3) - pow($this->level,3))*10;
	}
	
	//加钱
	function addCoin($v){
		global $returnData;
		$this->coin += $v;
		$this->setChangeKey('coin');
		$returnData->sync_coin = $this->coin;
	}
	
	//升级科技
	function levelUpTec($type,$id){
		global $returnData;
		if($this->tec->{$type}->{$id})
			$this->tec->{$type}->{$id} ++;
		else
			$this->tec->{$type}->{$id} = 1;
		$this->setChangeKey('tec');
		
		if(!$returnData->{'sync_tec_'.$type})
		{
			$returnData->{'sync_tec_'.$type} = new stdClass();
		}
		$returnData->{'sync_tec_'.$type}->{$id} = $this->tec->{$type}->{$id};
	}
	
	//取道具数量
	function getPropNum($propID){
		if($this->prop->{$propID})
			return $this->prop->{$propID}->num;
		return 0;
	}
	
	//改变道具数量
	function addProp($propID,$num){
		global $returnData;
		if(!$this->prop->{$propID})
		{
			$this->prop->{$propID} = new stdClass();
			$this->prop->{$propID}->num = 0;
		}
			
		$this->prop->{$propID}->num += $num;
		$this->setChangeKey('prop');	
		
		if(!$returnData->sync_prop)
		{
			$returnData->sync_prop = new stdClass();
		}
		$returnData->sync_prop->{$propID} = $this->prop->{$propID};
	}
	
	
	//取碎片数量
	function getCollectNum($id){
		if($this->collect->num->{$id})
			return $this->collect->num->{$id};
		return 0;
	}
	
	//改变碎片数量
	function addCollect($id,$num){
		global $returnData;
		if(!$this->collect->num->{$id})
		{
			$this->collect->num->{$id} = 0;
		}
			
		$this->collect->num->{$id} += $num;
		$this->setChangeKey('collect');	
		
		if(!$returnData->sync_collect_num)
		{
			$returnData->sync_collect_num = new stdClass();
		}
		$returnData->sync_collect_num->{$id} = $this->collect->num->{$id};
	}
	
	//取怪物星星等级
	function getCollectLevel($monsterID){
		global $monster_base;
		$monster = $monster_base[$monsterID];
		$cLevel = $monster['collect'];
		if($this->collect->level->{$monsterID})
			$cLevel = max($cLevel,$this->collect->level->{$monsterID});
		return $cLevel;
	}
	
	//重新计算战力
	function resetForce(){
		global $returnData;
		$this->tec_force = 0;
		//等级影响
		for($i=1;$i<=$this->level;$i++)
		{
			$this->tec_force += ceil(($i+1)/10);
		}
		
		
		$this->tec_force += $this->_getTecAdd($this->tec->main);
		$this->tec_force += $this->_getTecAdd($this->tec->ring);
		$this->tec_force += $this->_getTecAdd($this->tec->monster);

		$returnData->sync_tec_force= $this->tec_force;
		$this->setChangeKey('tec_force');
	}
	
	//科技影响
	function _getTecAdd($data){
		$count = 0;
		foreach($data as $key=>$value)
		{
			$count += $value;
		}
		return $count;
	}
	
	
	//把结果写回数据库
	function write2DB(){
		//return false;
		function addKey($key,$value,$needEncode=false){
			if($needEncode)
				return $key."='".json_encode($value)."'";
			else 
				return $key."=".$value;
		}
		
		global $conne,$msg,$sendData,$sql_table;
		$arr = array();
		
		if($this->changeKey['level'] || $this->changeKey['tec'])//会影响战力
			$this->resetForce();
		
		if($this->changeKey['exp'])
			array_push($arr,addKey('exp',$this->exp));
		if($this->changeKey['level'])
			array_push($arr,addKey('level',$this->level));
		if($this->changeKey['tec_force'])
			array_push($arr,addKey('tec_force',$this->tec_force));
		if($this->changeKey['award_force'])
			array_push($arr,addKey('award_force',$this->award_force));
		if($this->changeKey['coin'])
			array_push($arr,addKey('coin',$this->coin));
			
		if($this->changeKey['tec'])
			array_push($arr,addKey('tec',$this->tec,true));
		if($this->changeKey['server_game'])
			array_push($arr,addKey('server_game',$this->server_game,true));
		if($this->changeKey['server_game_equal'])
			array_push($arr,addKey('server_game_equal',$this->server_game_equal,true));
		if($this->changeKey['main_game'])
			array_push($arr,addKey('main_game',$this->main_game,true));
		if($this->changeKey['day_game'])
			array_push($arr,addKey('day_game',$this->day_game,true));
		if($this->changeKey['honor'])
			array_push($arr,addKey('honor',$this->honor,true));
		if($this->changeKey['prop'])
			array_push($arr,addKey('prop',$this->prop,true));
		if($this->changeKey['energy'])
			array_push($arr,addKey('energy',$this->energy,true));	
		if($this->changeKey['collect'])
			array_push($arr,addKey('collect',$this->collect,true));
		if($this->changeKey['diamond'])
			array_push($arr,addKey('diamond',$this->diamond,true));
		if($this->changeKey['friends'])
			array_push($arr,addKey('friends',$this->friends,true));
		if($this->changeKey['active'])
			array_push($arr,addKey('active',$this->active,true));
			
			
			
		if(count($arr) == 0)
			return true;
			
			
		$sql = "update ".$sql_table."user_data set ".join(",",$arr)." where gameid='".$msg->gameid."'";
		 debug($sql);
		if(!$conne->uidRst($sql))//写用户数据失败
		{
			$sendData->error = 4;
			return false;
		}
		
		return true;
			
	}
}

//获取其它玩家的数据
function getUser($gameid){
	global $conne;
	$sql = "select * from ".$sql_table."user_data where id='".$gameid."'";
	$result = $conne->getRowsRst($sql);
	if($result)
		return new GameUser($result);
	return null;
}
?>