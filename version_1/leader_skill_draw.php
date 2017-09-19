<?php 
	require_once($filePath."cache/monster.php");
	$num=$msg->num;//抽的次数
	$needProp = $num;
	$userDiamond=$msg->diamond;//用钻石补充
	
	do{
		$propNum = $userData->getPropNum(41);
		if($propNum < $needProp && !$userDiamond)
		{
			$returnData->fail = 1;
			$returnData->sync_prop = new stdClass();
			$returnData->sync_prop->{'41'} = $propNum;
			break;
		}
		if($propNum < $needProp)
		{
			$needDiamond = ($needProp - $propNum)*100;
			$needProp = $propNum;
			if($userData->getDiamond() < $needDiamond)//钻石不够
			{
				$returnData->fail = 2;//钻石不够
				$returnData->sync_diamond = $userData->diamond;
				break;
			}
			$userData->addDiamond(-$needDiamond);
		}
		if($needProp)
			$userData->addProp(41,-$needProp);

			
		if(!$userData->active->skill_draw)
		{
			$userData->active->skill_draw = new stdClass();
			$userData->active->skill_draw->fail = 0;
		}
		$drawSkill;
		$award = array();
		
		
		
		
		for($i=0;$i<$msg->num;$i++)
		{
			$base = $userData->active->skill_draw->fail + 10;
			// $base = 9999999;
			if(rand(0,100000) < $base)//抽中技能
			{
				$userData->active->skill_draw->fail = 0;
				if(!$userData->tec->skill)
					$userData->tec->skill = array();
				//可抽的技能
				if(!$drawSkill)
				{
					$drawSkill = array();
					$day = (int)((time() - $serverOpenTime)/(24*3600));
					$tableName = $sql_table.'skill_total';
					$sql = "select * from ".$tableName." where num>0";
					$sqlResult = $conne->getRowsArray($sql);
					$drawNum = array();
					foreach($sqlResult as $key=>$value)
					{
						$drawNum[$value['id']] = $value['num'];
					}
					foreach($leader_skill as $key=>$value)
					{
						if($day >= $value['day'] && $drawNum[$key] < $value['num'])
						{	
							$skillID = (int)$key;
							if(!in_array($skillID,$userData->tec->skill,true))
								array_push($drawSkill,$skillID);
						}
					}
					usort($drawSkill,randomSortFun);
				}
				//没技能奖一个32（高级学习卡）
				if(count($drawSkill) == 0)
				{
					$userData->addProp(32,1);
					array_push($award,array('type'=>'prop','id'=>32));
				}
				else
				{
					
					$skillID = array_pop($drawSkill);
					array_push($userData->tec->skill,$skillID);
					
					array_push($award,array('type'=>'skill','id'=>$skillID));
					$userData->setChangeKey('tec');
					
					//加日志
					$oo = new stdClass();
					$oo->head = $userData->head;
					$oo->nick = base64_encode($userData->nick);
					$oo = json_encode($oo);
					$sql = "insert into ".$sql_table."skill_log(skillid,gameid,content,time) values(".$skillID.",'".$userData->gameid."','".$oo."',".time().")";
					$conne->uidRst($sql);
					
					
					$sql = "update ".$sql_table."skill_total set num=num+1 where id=".$skillID;
					$conne->uidRst($sql);
				}
			}
			else
			{
				$userData->active->skill_draw->fail += 1 + min(100,ceil($userData->rmb/100)) + 5000;//暂时更容易抽到
				//round(pow(1.2,$userLevel+3)*1000*$rate);coin
				// round(pow(1.2,$userLevel+3)*10*$rate);card
				//diamond*100,energy*40,energy*50,21*6,31*1
				//energy*60,diamond*150,31*2,41*2,21*10
				$rate = rand(0,1050);
				if($rate < 300)//coin
				{
					$coin = round(pow(1.2,$userData->level+4)*1000*(0.8+lcg_value()*0.8));
					array_push($award,array('type'=>'coin','value'=>$coin));
					$userData->addCoin($coin);
				}
				else if($rate < 600)//card
				{
					$card = round(pow(1.2,$userData->level+3)*10*(0.8+lcg_value()*0.8));
					array_push($award,array('type'=>'card','value'=>$card));
					$userData->addCollect(0,$card);
				}
				else if($rate < 700)//energy
				{
					$energy = round(30 + lcg_value()*30);
					array_push($award,array('type'=>'energy','value'=>$energy));
					$userData->addEnergy($energy);
				}
				else if($rate < 800)//修正
				{
					$pNum = round(5 + lcg_value()*5);
					$userData->addProp(21,$pNum);
					array_push($award,array('type'=>'prop','id'=>21,'value'=>$pNum));
				}
				else if($rate < 860)//diamond
				{
					$diamond = round(80 + lcg_value()*50);
					array_push($award,array('type'=>'diamond','value'=>$diamond));
					$userData->addDiamond($diamond);
				}
				else if($rate < 960)//初级卡
				{
					$r = lcg_value();
					if($r > 0.9)
						$pNum = 3;
					else if($r > 0.7)
						$pNum = 2;
					else
						$pNum = 1;
					$userData->addProp(31,$pNum);
					array_push($award,array('type'=>'prop','id'=>31,'value'=>$pNum));
				}
				else if($rate < 999)//抽奖机会
				{
					$pNum = 8 + rand(0,7);
					$userData->addProp(42,$pNum);
					array_push($award,array('type'=>'prop','id'=>42,'value'=>$pNum));
				}
				else if($rate < 1049)//技能分身
				{
					$pNum = 1 + rand(0,1);
					$userData->addProp(22,$pNum);
					array_push($award,array('type'=>'prop','id'=>22,'value'=>$pNum));
				}
				else //高级学习卡
				{
					$userData->addProp(32,1);
					array_push($award,array('type'=>'prop','id'=>32));
				}
			}
		}
		
		$returnData->award = $award;
		$userData->setChangeKey('active');
		$userData->write2DB();

		
	}while(false)	
			

?> 