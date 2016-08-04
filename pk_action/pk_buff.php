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
			}
		}
		
		//buff���ʱ�Ĵ���
		function onBuffAdd(){
			foreach($this->value as $key=>$value)
			{
				$this->target->{$key} += round($value);
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

		function onAction(){
			global $pkData;
			
			if($this->value<0 && $this->target->hp <= -$this->value && ($temp = $this->target->isDieMiss('buff')))
			{
				$pkData->addSkillMV(null,$target,pk_skillType('NOHURT',0));
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
			
			$this->target->stat[$this->id] -= 1;
		}
		
		//buff���ʱ�Ĵ���
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