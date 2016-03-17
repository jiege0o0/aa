<?php 
	class skillValueFunClass{
		private $enemy;		
		private $self;		

		//大队伍中怪物的数量
		function NUM($monsterID = null){
			if(!$monsterID)
				$monsterID = $this->self->monsterID;
			if($self->team->teamInfo['num'][monsterID])
				return $this->self->team->teamInfo['num'][monsterID];
			return 0;
		}
		
		//大队伍中某怪物类型的数量
		function TNUM($type = null){
			if(!$type)
				$type = $this->self->monsterData['type'];
			if($self->team->teamInfo['tnum'][$type])
				return $this->self->team->teamInfo['tnum'][$type];
			return 0;
		}
		
		//出战的怪物ID
		function MID(){
			return $this->self->monsterID;
		}
		
		//出战的怪物类型
		function TYPE(){
			return $this->self->monsterData['type'];
		}
		
		//出战的怪物性格
		function KIND($v){
			if($v)
				return in_array($v,$this->self->monsterData['kind']);
			return $this->self->monsterData['kind'];
		}
		
		function HP(){
			return $this->self->hp;
		}
		
		function MHP($orgin = 0){
			if($orgin == 0)
				return $this->self->maxHp;
			if($orgin == 1)
				return $this->self->orginPKData['hp'];
			if($orgin == 2)
				return $this->self->orginPKData['hp'] + $this->self->orginPKData['hpadd'];
		}
		
		function SPD($orgin = 0){
			if($orgin == 0)
				return $this->self->speed;
			if($orgin == 1)
				return $this->self->orginPKData['speed'];
			if($orgin == 2)
				return $this->self->orginPKData['speed'] + $this->self->orginPKData['spdadd'];
		}
		function ATK($orgin = 0){
			if($orgin == 0)
				return $this->self->atk;
			if($orgin == 1)
				return $this->self->orginPKData['atk'];
			if($orgin == 2)
				return $this->self->orginPKData['atk'] + $this->self->orginPKData['atkadd'];
		}
		function MP(){
			return $this->self->mp;
		}
		function TEMP($v = 1){
			if($v == 1)
				return $this->self->temp1;
			if($v == 2)
				return $this->self->temp2;
			if($v == 3)
				return $this->self->temp3;
		}
		function ATKHP(){
			return $this->self->atkHp;
		}
		//在队伍中的排序
		function INDEX(){
			$id = $this->self->id;
			if($id<10)
				return $id;
			if($id<30)
				return $id - 10;
			return $id - 30;	
		}
		
		function ACTION1(){
			return $this->self->action1;
		}
		function ACTION2(){
			return $this->self->action2;
		}
		function ACTION3(){
			return $this->self->action3;
		}
		function ACTION4(){
			return $this->self->action4;
		}	
		function ACTION5(){
			return $this->self->action5;
		}
		
		//在小队伍中的排序
		function POS(){
			return $this->self->pos;
		}
		
		function HPR(){
			return $this->self->hp/$this->self->maxHp*100;
		}
		function TAG($v){
			return $this->self->tag[$v];
		}
		
		function MHPS(){
			return $this->self->team->teamInfo['mhps'];
		}
		function ATKS(){
			return $this->self->team->teamInfo['atks'];
		}
		function SPDS(){
			return $this->self->team->teamInfo['spds'];
		}
		
		
		////////////////////////////////////////////// enemy //////////////////////////////
		
		//出战的怪物ID
		function EMID(){
			return $this->enemy->monsterID;
		}
		
		//出战的怪物类型
		function ETYPE(){
			return $this->enemy->monsterData['type'];
		}
		
		//出战的怪物性格
		function EKIND($v){
			if($v)
				return in_array($v,$this->enemy->monsterData['kind']);
			return $this->enemy->monsterData['kind'];
		}
		
		
		function EMHP($orgin = 0){
			if($orgin == 0)
				return $this->enemy->maxHp;
			if($orgin == 1)
				return $this->enemy->orginPKData['hp'];
			if($orgin == 2)
				return $this->enemy->orginPKData['hp'] + $this->enemy->orginPKData['hpadd'];
		}
		function ESPD($orgin = 0){
			if($orgin == 0)
				return $this->enemy->speed;
			if($orgin == 1)
				return $this->enemy->orginPKData['speed'];
			if($orgin == 2)
				return $this->enemy->orginPKData['speed'] + $this->enemy->orginPKData['spdadd'];
		}
		function EATK($orgin = 0){
			if($orgin == 0)
				return $this->enemy->atk;
			if($orgin == 1)
				return $this->enemy->orginPKData['atk'];
			if($orgin == 2)
				return $this->enemy->orginPKData['atk'] + $this->enemy->orginPKData['atkadd'];
		}
		
		function EHP(){
			return $this->enemy->hp;
		}
		
		function EMP(){
			return $this->enemy->mp;
		}
		function ETAG($v){
			return $this->enemy->tag[$v];
		}
		
		function EHPR(){
			return $this->enemy->hp/$this->enemy->maxHp*100;
		}
		
		function EACTION1(){
			return $this->enemy->action1;
		}
		function EACTION2(){
			return $this->enemy->action2;
		}
		function EACTION3(){
			return $this->enemy->action3;
		}
		function EACTION4(){
			return $this->enemy->action4;
		}	
		function EACTION5(){
			return $this->enemy->action5;
		}
		
		function EMHPS(){
			return $this->enemy->team->teamInfo['mhps'];
		}
		function EATKS(){
			return $this->enemy->team->teamInfo['atks'];
		}
		function ESPDS(){
			return $this->enemy->team->teamInfo['spds'];
		}
		
		function EINDEX(){
			$id = $this->enemy->id;
			if($id<10)
				return $id;
			if($id<30)
				return $id - 10;
			return $id - 30;	
		}
		
	}
	$skillValueFun = new skillValueFunClass();
?> 