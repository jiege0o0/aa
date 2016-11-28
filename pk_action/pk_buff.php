<?php 
	class BuffBase{
		public $removeAble = true;//�ɱ���������
		public $isDebuff = false;//����BUFF
		public $cd = 0;//�����غϣ�-1Ϊ���غϽ���
		public $target = 0;//Buff������Ŀ��
		public $actionTime;//�����õ�ʱ��,0Ϊ������;������
		public $icon = 0;//0Ϊû��ͼ�꣬����Ϊͼ��ID
		public $noClean = false;//BUFF���ᱻ��
		
		
		function __construct(){
		}
		
		//buff����ʱ����
		function onEnd(){
			$this -> onClean();
		}
		
		//���buffЧ��ʱ����
		function onClean(){
			
		}
		
		//buff������ʱ�Ĵ���
		function onAction(){
			return false;
		}
		
		//buff���ʱ�Ĵ���
		function onBuffAdd(){
			
		}
		
		//��buff�ӵ��������
		function addToTarget($target){
			$this->target = $target;
			$success = $target->addBuff($this);
			if($success)
				$this->onBuffAdd();
			return $success;
		}
		
		//cd��ǰ��ָ��ʱ��
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
	
	//�ı����Ե�buff
	class ValueBuff extends BuffBase{
		public $value;
		function __construct($arr,$cd){//array('spd'=>30);
			$this->value = $arr;
			$this->cd = $cd;
		}
		
		//���buffЧ��ʱ����
		function onClean(){
			foreach($this->value as $key=>$value)
			{
				$this->target->{$key} -= round($value);
				$this->addStat($key,-1);
			}
		}
		
		function addStat($key,$num){
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
			$this->target->addStat($id,$num);
			return $id;
		}
		
		//buff���ʱ�Ĵ���
		function onBuffAdd(){
			global $pkData;
			foreach($this->value as $key=>$value)
			{
				$this->target->{$key} += round($value);
				$id = $this->addStat($key,1);
				$pkData->addSkillMV(null,$this->target,pk_skillType('STAT',numToStr($id).$this->cd));
			}
		}
	}
	
	//�غϽ����ı�Ѫ����buff
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
		
		//buff���ʱ�Ĵ���
		function onBuffAdd(){
			global $pkData;
			if($this->isDebuff)	
				$pkData->addSkillMV(null,$this->target,pk_skillType('STAT',numToStr(42).$this->cd));
			else 	
				$pkData->addSkillMV(null,$this->target,pk_skillType('STAT',numToStr(41).$this->cd));
		}

		function onAction(){
			global $pkData;
			
			if($this->value<0 && $this->target->hp <= -$this->value && ($temp = $this->target->isDieMiss('buff')))
			{
				$pkData->addSkillMV(null,$target,pk_skillType('NOHURT',$temp['id']));
				$this->target->testTSkill('DMISS',$temp);
				return false;
			}
			$v = $this->target->addHp($this->value);
			$pkData->addSkillMV(null,$this->target,pk_skillType('HP',$v));
			//trace($this->target->id.'**'.$this->target->hp);			
			return true;
		}
	}
	
		
	//����״̬���buff
	class StatBuff extends BuffBase{
		public $id;
		function __construct($id,$cd){
			$this->id = $id;
			$this->cd = $cd;
		}
		
		//���buffЧ��ʱ����
		function onClean(){
			$this->target->addStat($this->id,-1);
		}
		
		//buff���ʱ�Ĵ���
		function onBuffAdd(){
			global $pkData;
			$this->target->addStat($this->id,1);
			$pkData->addSkillMV(null,$this->target,pk_skillType('STAT',numToStr($this->id).$this->cd));
			// global $pkData;
			// trace($pkData->step.':'.$this->target->id.'--'.$this->id.'--'.$this->target->stat[$this->id]);
		}
	}
?> 