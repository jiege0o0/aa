<?php 
	class BuffBase{
		public $removeAble = true;//可被技能清理
		public $isDebuff = false;//负面BUFF
		public $cd = 0;//持续回合，-1为到回合结束
		public $target = 0;//Buff依附的目标
		public $actionTime;//起作用的时机,0为不会中途起作用
		public $icon = 0;//0为没有图标，其它为图标ID
		public $noClean = false;//BUFF不会被清
		
		
		function __construct(){
		}
		
		//buff结束时调用
		function onEnd(){
			$this -> onClean();
		}
		
		//清除buff效果时调用
		function onClean(){
			
		}
		
		//buff起作用时的处理
		function onAction(){
			return false;
		}
		
		//buff添加时的处理
		function onBuffAdd(){
			
		}
		
		//把buff加到玩家身上
		function addToTarget($target){
			$this->target = $target;
			$success = $target->addBuff($this);
			if($success)
				$this->onBuffAdd();
			return $success;
		}
		
		//cd向前跑指定时间
		function cdRun($cd=1){
			if($this->cd < 0)
				return false;
			$this->cd -= $cd;
			if($this->cd <= 0)
			{
				$this->onEnd();
				return true;
			}
			return false;	
		}
	}
	
	//改变属性的buff
	class ValueBuff extends BuffBase{
		public $value;
		function __construct($key,$value,$cd){//array('spd'=>30);
			$this->key = $key;
			$this->value = round($value);
			$this->cd = $cd;
			
		}
		
		//清除buff效果时调用
		function onClean(){
			$this->target->{$this->key} -= round($this->value);
			// $this->addStat($key,-1);
		}
		
		function getID($key){
			$id = 0;
			switch($key){
				case 'atk':
					$id = 1;
					break;
				case 'speed':
					$id = 2;
					break;	
				case 'def':
					$id = 3;
					break;
				case 'hurt':
					$id = 4;
					break;
				default:trace($key);
			}
			if($this->isDebuff)
				$id += 10;
			return $id;
		}
		
		//buff添加时的处理
		function onBuffAdd(){
			global $pkData;
			$this->id = $this->getID($this->key);
			$this->target->{$this->key} += $this->value;
			// $this->target->addStat($this->id,1);
			$pkData->addSkillMV(null,$this->target,pk_skillType('STAT',numToStr($this->id).numToStr($this->cd).$this->value));
		}
	}
	
	//回合结束改变血量的buff
	class HPBuff extends BuffBase{
		public $value;
		function __construct($value,$cd,$skillID){
			$this->actionTime = 'AFTER';
			$this->value = round($value);
			$this->cd = $cd;
			$this->skillID = str_replace('f',"1",$skillID);
			if($value > 0)
			{
				$this->isDebuff = false;
				$this->id = 41;
			}
			else
			{
				$this->isDebuff = true;
				$this->id = 42;
			}
			
		}
		
		//buff添加时的处理
		function onBuffAdd(){
			global $pkData;
			$pkData->addSkillMV(null,$this->target,pk_skillType('STAT',numToStr($this->id).numToStr($this->cd)).$this->value);
		}

		function onAction(){
			global $pkData;
			
			if($this->value<0 && $this->target->hp <= -$this->value && ($temp = $this->target->isDieMiss('buff')))
			{
				$pkData->addSkillMV(null,$this->target,pk_skillType('NOHURT',$temp['id']));
				$this->target->testTSkill('DMISS',$temp);
				return false;
			}
			$v = $this->target->addHp($this->value);
			if($v == 0)
				$pkData->addSkillMV(null,$this->target,pk_skillType('CDHP','-'.$v.'#'.$this->skillID));
			else 
				$pkData->addSkillMV(null,$this->target,pk_skillType('CDHP',$v.'#'.$this->skillID));
			if($this->target->hp == 0)
				$pkData->addSkillMV(null,$this->target,pk_skillType('DIE',1));	
			
			//trace($this->target->id.'**'.$this->target->hp);			
			return true;
		}
	}
	
		
	//特殊状态类的buff
	class StatBuff extends BuffBase{
		public $id;
		public $value;
		function __construct($id,$cd){
			$this->id = $id;
			$this->cd = $cd;
		}
		
		//清除buff效果时调用
		function onClean(){
			$this->target->addStat($this->id,-1);
		}
		
		//buff添加时的处理
		function onBuffAdd(){
			global $pkData;
			$this->target->addStat($this->id,1);
			if($this->id < 100)
				$pkData->addSkillMV(null,$this->target,pk_skillType('STAT',numToStr($this->id).numToStr($this->cd).$this->value));
			else
				$pkData->addSkillMV(null,$this->target,pk_skillType('STAT2',numToStr($this->id-100).numToStr($this->cd).$this->value));
			// global $pkData;
			// trace($pkData->step.':'.$this->target->id.'--'.$this->id.'--'.$this->target->stat[$this->id]);
		}
	}
?> 