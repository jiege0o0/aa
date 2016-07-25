<?php 
	class BuffBase{
		public $removeAble = true;//可被技能清理
		public $isDebuff = false;//负面BUFF
		public $cd = 0;//持续回合，-1为到回合结束
		public $target = 0;//Buff依附的目标
		public $actionTime;//起作用的时机,0为不会中途起作用
		public $icon = 0;//0为没有图标，其它为图标ID
		
		
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
		function __construct($arr,$cd){//array('spd'=>30);
			$this->value = $arr;
			$this->cd = $cd;
		}
		
		//清除buff效果时调用
		function onClean(){
			foreach($this->value as $key=>$value)
			{
				$this->target->{$key} -= $value;
			}
		}
		
		//buff添加时的处理
		function onBuffAdd(){
			foreach($this->value as $key=>$value)
			{
				$this->target->{$key} += $value;
			}
		}
	}
	
	//回合结束改变血量的buff
	class HPBuff extends BuffBase{
		public $value;
		function __construct($value,$cd){
			$this->actionTime = 'AFTER';
			$this->value = round($value);
			$this->cd = $cd;
			if($value > 0)
				$this->isDebuff = false;
			else
				$this->isDebuff = true;
		}
		
		//清除buff效果时调用
		function onAction(){
			global $pkData;
			if($this->value<0 && $this->target->hp <= -$value && ($temp = $this->target->isDieMiss()))
			{
				$pkData->addSkillMV(null,$target,pk_skillType('NOHURT',0));
				$this->target->testTSkill('DMISS',$temp);
				return false;
			}
			$this->target->addHp($this->value);
			$pkData->addSkillMV(null,$this->target,pk_skillType('HP',$this->value));
			//trace($this->target->id.'**'.$this->target->hp);			
			return true;
		}
	}
	
		
	//特殊状态类的buff
	class StatBuff extends BuffBase{
		public $id;
		function __construct($id,$cd){
			$this->id = $id;
			$this->cd = $cd;
		}
		
		//清除buff效果时调用
		function onClean(){
			
			$this->target->stat[$this->id] -= 1;
		}
		
		//buff添加时的处理
		function onBuffAdd(){
			if(!$this->target->stat[$this->id])
			{
				$this->target->stat[$this->id] = 0;
			}
			$this->target->stat[$this->id] += 1;
			// global $pkData;
			// trace($pkData->step.':'.$this->target->id.'--'.$this->id.'--'.$this->target->stat[$this->id]);
		}
	}
?> 