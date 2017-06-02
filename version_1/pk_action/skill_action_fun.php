<?php 
	class skillActionFunClass{
		public $user;
		public $self;
		public $enemy;
		public $skillData;
		public $statArr;
		public $eStatArr;
		
		private $classObj;
		
		function __construct(){
			$this->classObj = new stdClass();
		}
		
		function init($skillData,$user,$self,$enemy,&$statArr,&$eStatArr){
			$this->skillData = $skillData;
			$this->user = $user;
			$this->self = $self;
			$this->enemy = $enemy;
			$this->statArr = &$statArr;
			$this->eStatArr = &$eStatArr;
			$this->enemy = $enemy;
		}
		
		function getValue($type,$value1){
			return $this->getClassObject($type)->getValue($value1);
		}
		function action($type,$add){
			$this->getClassObject($type)->action($add);
		}
		function getClassObject($type){
			if($this->classObj->{$type})
				return $this->classObj->{$type};
			$refl = new ReflectionClass('Skill_'.$type);
			$instance = $refl->newInstance($this);
			$this->classObj->{$type} = $instance;
			return $instance;
		}
		
	}
	$skillActionFun = new skillActionFunClass();
	
	class BaseSkillAction{
		public $skillAction;
		function __construct($skillAction){
			$this->skillAction = $skillAction;
		}
	}
	//***********************************************************************************************************
	class Skill_HP extends BaseSkillAction{
		function getValue($value1){
			if($this->skillAction->skillData->hpRate)
				$add = round(max(1,$this->skillAction->self->maxHp*($value1)/100));
			else 
				$add = round(max(1,$this->skillAction->user->atk*($value1)/100));
			if($add > 0)
				$add = pk_healHP($this->skillAction->user,$this->skillAction->enemy,$add);
			return $add;
		}
		function action($add){
			$this->skillAction->self->addHp($add);
			if(!$this->skillAction->skillData->type && $add > 0)
			{
				$this->skillAction->user->testTSkill('HEAL',$this->skillAction->self);
				$this->skillAction->self->testTSkill('BEHEAL',$this->skillAction->self);
				$this->skillAction->enemy->testTSkill('BEHEAL',$this->skillAction->self);
			}
				
		}
	}
	
	class Skill_SPD extends BaseSkillAction{
		function getValue($value1){
			$rate = ($value1)/100;
			$add = round(($rate)*$this->skillAction->self->orginPKData['speed']);
			return $add;
		}
		function action($add){
			$this->skillAction->self->speed += $add;
			$this->skillAction->statArr['speed'] = $add;
			if($this->skillAction->skillData->forever)
				$this->skillAction->self->orginPKData['spdadd'] += $add;
		}
	
	}
	
	class Skill_ATK extends BaseSkillAction{
		function getValue($value1){
			$rate = ($value1)/100;
			$add = round(($rate)*$this->skillAction->self->orginPKData['atk']);
			return $add;
		}
		function action($add){
			$this->skillAction->self->atk += $add;
			$this->skillAction->statArr['atk'] = $add;
			if($this->skillAction->skillData->forever)
				$this->skillAction->self->orginPKData['atkadd'] += $add;
		}
	
	}
	
	class Skill_MHP extends BaseSkillAction{
		function getValue($value1){
			if($this->skillAction->skillData->hpRate)
				$add = round(max(1,$this->skillAction->self->maxHp*($value1)/100));
			else 
				$add = round(max(1,$this->skillAction->user->atk*($value1)/100));
			return $add;
		}
		function action($add){
			$this->skillAction->self->maxHp += $add;
			$this->skillAction->self->addHp($add);
			$this->skillAction->statArr['maxHp'] = $add;
			if($this->skillAction->skillData->forever)
				$this->skillAction->self->orginPKData['hpadd'] += $add;
		}
	
	}
	
	class Skill_MP extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->addMp($add);
		}
	
	}
	
	class Skill_EHP extends BaseSkillAction{
		function getValue($value1){
			if($this->skillAction->skillData->hpRate)
				$add = round($this->skillAction->enemy->maxHp*($value1)/100);
			else 
				$add = round($this->skillAction->user->atk*($value1)/100);
			if($add < 0)
				$add = pk_atkHP($this->skillAction->user,$this->skillAction->enemy,$this->skillAction->add);
			return $add;
		}
		function action($add){
			if($enemy->hp <= 0)
				return;
			$this->skillAction->enemy->addHp($add);
			if($add < 0)
			{
				$this->skillAction->enemy->atkhp = $add;
				$this->skillAction->user->atkhp = $add;
				if(!$this->skillAction->skillData->type)
				{
					$this->skillAction->enemy->testTSkill('sm_50',$this->skillAction->user);
					$this->skillAction->user->testStat2(-$add);
				}
			}	
		}
	
	}
	
	class Skill_ESPD extends BaseSkillAction{
		function getValue($value1){
			$rate = ($value1)/100;
			$add = round(($rate)*$this->skillAction->enemy->orginPKData['speed']);
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->speed += $add;
			$this->skillAction->eStatArr['speed'] = $add;
			if($this->skillAction->skillData->forever)
				$this->skillAction->enemy->orginPKData['spdadd'] += $add;
		}
	
	}
	
	class Skill_EATK extends BaseSkillAction{
		function getValue($value1){
			$rate = ($value1)/100;
			$add = round(($rate)*$this->skillAction->enemy->orginPKData['atk']);
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->atk += $add;
			$this->skillAction->eStatArr['atk'] = $add;
			if($this->skillAction->skillData->forever)
				$this->skillAction->enemy->orginPKData['atkadd'] += $add;
		}
	
	}
	
	class Skill_EMHP extends BaseSkillAction{
		function getValue($value1){
			if($this->skillAction->skillData->hpRate)
				$add = round($this->skillAction->enemy->maxHp*($value1)/100);
			else 
				$add = round($this->skillAction->user->atk*($value1)/100);
			if($add < 0)
				$add = pk_atkHP($this->skillAction->user,$this->skillAction->enemy,$this->skillAction->add);
			return $add;
		}
		function action($add){
			$this->skillAction->enemy->maxHp += $add;
			$this->skillAction->enemy->addHp($add);
			$this->skillAction->eStatArr['maxHp'] = $add;
			
			if($this->skillAction->skillData->forever)
				$this->skillAction->enemy->orginPKData['hpadd'] += $add;
		}
	
	}
	
	class Skill_EMP extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->enemy->addMp($add);
		}
	
	}
	
	class Skill_CDHP extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->cdhp += $add;
			$this->skillAction->statArr['cdhp'] = $add;
		}
	
	}
	
	class Skill_ECDHP extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->cdhp += $add;
			$this->skillAction->eStatArr['cdhp'] = $add;
		}
	
	}

	class Skill_RINGSTOP extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->enemy->team->stopRing = true;
		}
	}
	
	
	class Skill_TEMP1 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->user->temp1 = $add;
		}
	
	}
	
	class Skill_TEMP2 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->user->temp2 = $add;
		}
	
	}
	
	class Skill_TEMP3 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->user->temp3 = $add;
		}
	
	}
	
	class Skill_STAT1 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat1 += $add;
			$this->skillAction->statArr['stat1'] = $add;
		}
	
	}
	class Skill_STAT2 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat2 += $add;
			$this->skillAction->statArr['stat2'] = $add;
		}
	
	}
	class Skill_STAT3 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat3 += $add;
			$this->skillAction->statArr['stat3'] = $add;
		}
	
	}
	class Skill_STAT4 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat4 += $add;
			$this->skillAction->statArr['stat4'] = $add;
		}
	
	}
	class Skill_STAT5 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat5 += $add;
			$this->skillAction->statArr['stat5'] = $add;
		}
	
	}
	class Skill_STAT6 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat6 += $add;
			$this->skillAction->statArr['stat6'] = $add;
		}
	
	}
	class Skill_STAT7 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat7 += $add;
			$this->skillAction->statArr['stat7'] = $add;
		}
	
	}
	class Skill_STAT8 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat8 += $add;
			$this->skillAction->statArr['stat8'] = $add;
		}
	
	}
	class Skill_STAT9 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat9 += $add;
			$this->skillAction->statArr['stat9'] = $add;
		}
	
	}
	class Skill_STAT10 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->stat10 += $add;
			$this->skillAction->statArr['stat10'] = $add;
		}
	
	}
	class Skill_CLEAN extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$len = count($this->skillAction->self->statCountArr);
			for($i=0;$i<$len && $add > 0;$i++)
			{
				if($this->skillAction->self->statCountArr[$i]['userTeamID'] != $this->skillAction->self->teamID)//²»ÊÇÍ¬Ò»¶Ó
				{
					$this->skillAction->self->statCountArr[$i]['cd'] = 0;
					$add --;
				}
			}
			$this->self->testOutStat();
		}
	
	}
	class Skill_HURT extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->hurt += $add;
			$this->skillAction->statArr['hurt'] = $add;
		}
	
	}
	class Skill_DEF extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->def += $add;
			$this->skillAction->statArr['def'] = $add;
		}
	}
	//±êÇ©
	class Skill_TAG extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($index){
			if(!$this->skillAction->self->tag[$index])
				$this->skillAction->self->tag[$index] = 1;
			else 
				$this->skillAction->self->tag[$index] ++;
			$this->skillAction->statArr['tag'] = $index;
		}
	}

	class Skill_RESTRAIN extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->restrain += $add;
			$this->skillAction->statArr['restrain'] = $add;
		}
	
	}
	class Skill_UNRESTRAIN extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$this->skillAction->self->unRestrain += $add;
			$this->skillAction->statArr['unRestrain'] = $add;
		}
	
	}
	class Skill_EACTION1 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->action1 += $add;
			$this->skillAction->eStatArr['action1'] = $add;
		}
	
	}
	class Skill_EACTION2 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->action2 += $add;
			$this->skillAction->eStatArr['action2'] = $add;
		}
	
	}
	
	class Skill_EACTION3 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->action3 += $add;
			$this->skillAction->eStatArr['action3'] = $add;
		}
	
	}
	
	class Skill_EACTION4 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->action4 += $add;
			$this->skillAction->eStatArr['action4'] = $add;
		}
	
	}
	
	class Skill_EACTION5 extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->action5 += $add;
			$this->skillAction->eStatArr['action5'] = $add;
		}
	}
	
	//±êÇ©
	class Skill_ETAG extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($index){
			if($this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			if(!$this->skillAction->enemy->tag[$index])
				$this->skillAction->enemy->tag[$index] = 1;
			else 
				$this->skillAction->enemy->tag[$index] ++;
			$this->skillAction->statArr['tag'] = $index;
		}
	}
	
	class Skill_ECLEAN extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			$len = count($this->skillAction->enemy->statCountArr);
			for($i=0;$i<$len && $add > 0;$i++)
			{
				if($this->skillAction->enemy->statCountArr[$i]['userTeamID'] == $this->skillAction->enemy->teamID)//ÊÇÍ¬Ò»¶Ó
				{
					$this->skillAction->enemy->statCountArr[$i]['cd'] = 0;
					$add --;
				}
			}
			$this->self->testOutStat();
		}
	
	}
	
	class Skill_EHURT extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->hurt += $add;
			$this->skillAction->eStatArr['hurt'] = $add;
		}
	
	}
	
	
	class Skill_EDEF extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->def += $add;
			$this->skillAction->eStatArr['def'] = $add;
		}
	
	}
	
	class Skill_ERESTRAIN extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->restrain += $add;
			$this->skillAction->eStatArr['restrain'] = $add;
		}
	
	}
	
	class Skill_EUNRESTRAIN extends BaseSkillAction{
		function getValue($value1){
			$add = $value1;
			return $add;
		}
		function action($add){
			if($add < 0 && $this->skillAction->enemy->stat1 > 0)//Ä§Ãâ
				return true;
			$this->skillAction->enemy->unRestrain += $add;
			$this->skillAction->eStatArr['unRestrain'] = $add;
		}
	
	}
?> 