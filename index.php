<?php
	header('Access-Control-Allow-Origin:*');
	// set_error_handler("customError");
	// $filePath = dirname(__FILE__).'/game/';
	require_once($filePath."_config.php");
	require_once($filePath."tool/tool.php");
	
	//error1:�汾��,2��½״̬,3����,4д�û�ʧ��
	
	function  customError($errno, $errstr, $errfile, $errline)
	{ 
		global $_POST,$sendData,$debugC;
		errorLog("#".$_POST['head'].$_POST['msg'].$errstr."=>code:".$errstr."=>code:".$errno.'=>file:'.$errfile."=>line:".$errline);//.$errstr."=>code:".$errno'=>file:'.$errfile."=>line:".$errline
		$sendData->error = 3;
		if($debugC)
			echo $errstr."=>code:".$errno.'=>file:'.$errfile."=>line:".$errline; 	
		sendToClient($sendData);			
		die();
	}

	$head = $_POST['head'];
	$msg = json_decode($_POST['msg']);
	$debugC = $_POST['debug_client'];//�ͻ��˷����DEBUG
	
	$returnData = new stdClass();
	$sendData = new stdClass();
	$sendData->head = $head;
	$sendData->msg = $returnData;
	try{	
		do{
			//���԰汾��
			if($_POST['version'] != '1'){
				$sendData->error = 1;
				break;
			}
			
			//���Ե�½״̬,���趨�û�����
			if($msg->landid && $msg->gameid)
			{
				require_once($filePath."tool/conn.php");
				require_once($filePath."object/game_user.php");
				$sql = "select * from ".$sql_table."user_data where gameid='".$msg->gameid."' and land_key=".$msg->landid;
				$userData = $conne->getRowsRst($sql);
				if(!$userData)//��¼ʧЧ
				{
					$sendData->error = 2;
					$sendData->aql = $sql;
					break;
				}
				$userData = new GameUser($userData);
			}
			
			if($debugC){
				$startT = microtime(true);
				$debugArr = array();
			}
			switch($head)
			{
				case 'debug':
				{
					$sendData->msg = $msg;
					break;
				}
				case 'login_server':
				{
					if(testCDKey($msg->id,$msg->cdkey))
					{
						require_once($filePath."login_server.php");
					}
					else
					{
						$returnData->fail = 1;
					}
						
					break;
				}
				case 'register_server':
				{
					if(testCDKey($msg->id,$msg->cdkey))
					{
						require_once($filePath."register_server.php");
					}
					else
						$returnData->fail = 1;
					break;
				}
				case 'test':
				{
				
					// $team1Data = json_decode('{"list":[101,101,101,101,101,101,101,101,101,101],"ring":{"id":1,"level":10},"fight":3,"tec":{"107":{"hp":15,"atk":15,"spd":15}}}');
					// $team2Data = json_decode('{"list":[101,101,101,101,101,101,101,101,101,101],"ring":{"id":1,"level":1},"fight":3,"tec":{"107":{"hp":15,"atk":15,"spd":17}}}');
					$team1Data = $msg->team1;
					$team2Data = $msg->team2;
					require_once($filePath."pk_action/pk.php");
					//sendToClient($returnData);
				// [{"list":[102,302,501,203,105,107,307,104,502,101],"ring":[1,16]}],
				
					// require_once($filePath."pk_action/change_fight_data.php");
					// $v1 = new stdClass();
					// $v1->list = array(302,302,302); 
					// $v1->ring = 1; 
					// $returnData->data = (changePKData($v1,'main_game'));
					// $userData->addCoin(99);
					//$userData->addExp(999);
					// $userData->addProp(1,100);
					// $userData->addProp(2,100);
					// $userData->addProp(3,100);
					//$userData->write2DB();
					
					break;
				}
				default:
				{
					if(is_file($filePath.$head.".php"))
					{
						require_once($filePath.$head.".php");
					}
					else
					{
						$sendData->result = 'fail';
						$sendData->msg = 'fun not found';
					}				
					break;
				}
			}
			
			if($debugC){
				$sendData->runtime = microtime(true) - $startT;
				$sendData->debug = $debugArr;
			}
		}while(false);
	}
	catch(Exception $e){
		errorLog("#".$_POST['head'].$_POST['msg'].$e->getMessage()."=>code:".$e->getCode().'=>file:'.$e->getFile()."=>line:".$e->getLine());
		$sendData->error = 3;
		if($debugC)
			echo $e->__toString(); 			
	}
	
	if($returnData->fail)
	{
		errorLog("#".$_POST['head'].$_POST['msg'].json_encode($returnData));
		unset($returnData->failDebug);
	}
	
	sendToClient($sendData);
?>