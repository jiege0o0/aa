<?php 
	//领取可以出的牌
	$otherid = $msg->otherid;
	do{
		if($otherid > $userData->gameid)
		{
			$chooseKey = 'friend2_cards';
			$friendKey = $otherid.$userData->gameid;
		}
		else
		{
			$chooseKey = 'friend1_cards';
			$friendKey = $userData->gameid.$otherid;
		}
		$sql = "select ".$chooseKey." as choose from ".$sql_table."friend_together where friend_key = '".$friendKey."'";
		$result = $conne->getRowsRst($sql);
	
		if($result && $result['choose'])//有数据
		{
			$choose = json_decode($result['choose']);
		}
		else
		{

			require_once($filePath."pk_action/get_pk_card.php");
			$choose = array(getPKCard($userData->level),getPKCard($userData->level));
			
			if(!$result)//没数据库
			{
				$sql = "insert into ".$sql_table."friend_together(friend_key,".$chooseKey.") values('".$friendKey."','".json_encode($choose)."')";
			}
			else
			{
				$sql = "update ".$sql_table."friend_together set ".$chooseKey."='".json_encode($choose)."' where friend_key = '".$friendKey."'";
			}
			
			
			
			if(!$conne->uidRst($sql))//写数据库失败
			{
				$returnData->fail =2;
				break;
			}
			
			
		}
		$returnData->choose = $choose;
	}while(false);	
?> 