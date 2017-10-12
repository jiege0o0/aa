<?php 
	require($filePath."pk_action/pk_buff.php");
	class SkillBase{
		public $orginOwnerID;//技能原始所有者
		public $owner;//技能所有者
		public $clientIndex=-1;//给客户端的技能ID
		public $index=0;//技能ID
		public $isMain = false;//是否能量技能
		public $isAtk = false;//攻击型技能，被打者会加MP
		public $actionCount = 0;//大于0表示CD中
		public $disabled = false;//技能有没有效
		public $isSendAtOnce = false;//必发技能，不会参与互斥逻辑(特性情况下不会被23状态限制)
		public $order = 0;//优先级，互斥时越大的越起作用
	
	
	
		public $cd = 1;//每CD个回合出一次手，3即为隔2个回合，第3个回合出手，0为回合前出手
		// public $stopout = false;//这个技能动画不会输出到客户端
		// public round = 0;//持续回合
		public $type = '';//特性技能
		public $leader = false;//总PK前执行
		public $once = false;//技能只执行一次
		
		//下面是召唤者相关
		public $lRound = 0;//召唤者的使用回合数
		public $ringLevel = 0;
		
		
		public $tData;//特性触发时传入的值
		
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
		
		//技能是否能使用
		function canUse($user,$self=null,$enemy=null){
			return true;
		}
		
		//放技能前执行的动作
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
			$user->skillID = $this->index;
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
		
		
		//重新赋值
		function reInit(){
			$this->actionCount = $this->cd - 1;//比如CD为1的话，一来就可以用了
			$this->disabled = false;
			$this->tData = null;
			$this->temp1 = 0;
			$this->temp2 = 0;
			$this->temp3 = 0;
			// if($this->type)
				// $this->actionCount = 0;
				
			$this->localReInit();
		}
		
		//作用技能效果
		function setSkillEffect($target,$mv=null){
			$target->setSkillEffect($mv);
		}
		
		//给已方召唤者增加技能(通过此方法加入的技能，下一回合才会生效)
		function addLeaderSkill($user,$skillName){
			$skillVO = pk_decodeSkill($skillName);
			$skillVO->orginOwnerID = $user->id;
			array_push($user->team->totalPKAction,$skillVO);
		}
		
		//A攻击B扣血
		function decHp($user,$target,$value,$isMax=false,$forever=false,$realHurt=false){
			global $pkData;
			$orginValue = $value;
			$value = round(max(1,$value));
			
			if($user->teamID != $target->teamID)
				$target->addHpCount($value);
			
			
			
			
			if(!$realHurt && $user->teamID != $target->teamID)
				$value = $user->getHurt($value,$target);
				
				
			if($user->hitTimes <= 0 && $user->teamID != $target->teamID && $target->hp <= $value && ($temp = $target->isDieMiss('atk')))
			{
				$value = 0;
				$pkData->addSkillMV(null,$target,pk_skillType('NOHURT',$temp['id']));	
				$temp['decHp'] = $value;
				$target->testTSkill('DMISS',$temp);
				if($temp['skill'])
					$temp['skill']->onDMiss();
			}
			else
			{
				if($user->teamID != $target->teamID)
					$user->addAtkCount($orginValue);
				$value = -$value;
				if($isMax)
				{
					$maxHpAdd = $value;
					$target->maxHp += $value;
					if($target->maxHp < 1)
					{
						$maxHpAdd += 1-$target->maxHp;
						$target->maxHp = 1;
					}
					if($forever)
						$target->add_hp += $maxHpAdd;
					$this->setSkillEffect($target,pk_skillType('MHP',$maxHpAdd));
					
					if($user->teamID != $target->teamID)
					{
						if($forever)
							$user->addEffectCount(abs($maxHpAdd));
						else
							$user->addEffectCount(abs($maxHpAdd)*0.5);
					}
				}

				$rHp = $target->addHp($value,$user->id == $target->id);
				if($rHp == 0 && $value < 0)
					$this->setSkillEffect($target,pk_skillType('HP','-'.$rHp));
				else 
					$this->setSkillEffect($target,pk_skillType('HP',$rHp));
					
				if($target->hp <= 0)
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
		
		//A结B加血
		function addHp($user,$target,$value,$isMax=false,$forever=false,$full=false){
			global $pkData;
			$value = round(max(1,$value));
			if($user->teamID == $target->teamID && $user->id != $target->id)
				$user->addHealCount($value);
			if($isMax)
			{	
			
				$target->maxHp += $value;
				if($forever)
					$target->add_hp += $value;
				$this->setSkillEffect($target,pk_skillType('MHP',$value));
				$user->testTSkill('MHP',$value);
				if($full)
					$value = $target->maxHp - $target->hp;
					
				if($user->teamID == $target->teamID && $user->id != $target->id)
				{
					if($forever)
						$user->addEffectCount($value);
					else
						$user->addEffectCount($value*0.5);
				}
			}
			
			$rHp = $target->addHp($value);
			if($rHp == 0 && $value < 0)
				$this->setSkillEffect($target,pk_skillType('HP','-'.$rHp));
			else 
				$this->setSkillEffect($target,pk_skillType('HP',$rHp));
			
			if(!$this->type)//不是特性加血，会触发事件
			{			
				$user->testTSkill('HEAL',array('value'=>$value,'user'=>$user));
				$target->testTSkill('BEHEAL',$value);
				$target->team->enemy->currentMonster[0]->testTSkill('EBEHEAL',$value);
				//有治疗情况发生
				// $pkData->playArr1[0]->testTSkill('SHEAL',$value);
				// $pkData->playArr2[0]->testTSkill('SHEAL',$value);
			}
			
			return $value;
		}
		
		//加魔
		function addMp($user,$target,$value){
			if($target->hp <= 0)
				return 0;
			$user->addEffectCount(abs($value)*2*($user->getForceRate()));
			$value = round($value);
			$target->addMP($value);
			$this->setSkillEffect($target,pk_skillType('MP',$value));
			return $value;
		}
		
		function addSpeed($user,$target,$value){
			if($target->hp <= 0)
				return 0;
			if($user->id!==$target->id)
				$user->addEffectCount(abs($value)*3*($user->getForceRate())*3);
			$target->addSpeed($value);
		}
		
		function addAtk($user,$target,$value){
			if($target->hp <= 0)
				return 0;
			if($user->id!==$target->id)
				$user->addEffectCount(abs($value)*3);
			$target->addAtk($value);
		}
		
		function addDef($user,$target,$value){
			if($target->hp <= 0)
				return 0;
			if($user->id!==$target->id)
				$user->addEffectCount(abs($value)/100*$target->maxHp*3);
			$target->addDef($value);
		}
		
		function addHurt($user,$target,$value){
			$target->addHurt($value);
		}
		
		
		/*
		//加每次行动血量改变
		function addcdhp($user,$target,$value){
			$target->cdhp += $value;
			$this->setSkillEffect($target);
			return $value;
		}
		
		//有状态
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
		
		//清除状态(谁加的，清多少)$isDebuff==-1为任意buff
		function cleanStat($user,$target,$isDebuff,$num){
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
					
					
					if($user->id!==$target->id)
						$user->addEffectCount($target->buffArr[$i]->cd*$user->getForceRate()*50);
						
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
		
		function setStat31($user,$target)
		{
			if($target->stat[31])
				$target->stat[31] ++;
			else
				$target->stat[31] = 1;
			$this->cleanStat($user,$target,true,999);
		}
	}
?> 