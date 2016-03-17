<?php 
	class SkillBase{
		public $owner;//技能所有者
		public $index=0;//技能ID
		public $isMain = false;//是否能量技能
		public $actionCount = 0;//大于0表示CD中
		public $disabled = false;//技能有没有效
	
	
	
		public $cd = 1;//每CD个回合出一次手，3即为隔2个回合，第3个回合出手，0为回合前出手
		public $stopout = false;//这个技能动画不会输出到客户端
		// public round = 0;//持续回合
		public $type = '';//特性技能
		public $leader = false;//总PK前执行
		public $once = false;//技能只执行一次
		
		//下面是召唤者相关
		public $lRound = 0;//召唤者的使用回合数
		public $ringLevel = 0;
		
		
		function __construct(){
			$this->reInit();
			if($this->type)
				$this->actionCount = 0;
		}
		
		//技能是否能使用
		function canUse($user,$self=null,$enemy=null){
			return true;
		}
		
		//重新赋值
		function reInit(){
			$this->actionCount = $this->cd - 1;//比如CD为1的话，一来就可以用了
		}
		
		//作用技能效果
		function setSkillEffect($target,$mv=false){
			global $pkData;
			$target->setRoundEffect();
			if(!$mv)
				$mv = pk_skillType('MV',1);
			$pkData->addSkillMV($user,$target,$mv);
		}
		
		//给已方召唤者增加技能(通过此方法加入的技能，下一回合才会生效)
		function addLeaderSkill($user,$skillName){
			array_push($user->team->totalPKAction,pk_decodeSkill($skillName));
		}
		
		//A攻击B扣血
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
		
		//A结B加血
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
			
			if(!$this->type)//不是特性加血，会触发事件
			{
				$user->testTSkill('HEAL',$target);
				$target->testTSkill('BEHEAL',$user);
			}
			
			return $value;
		}
		
		//速度改变
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
		
		//加攻击
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
		}
		
		//清除状态(谁加的，清多少)
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