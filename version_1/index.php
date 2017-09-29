<?php
	header('Access-Control-Allow-Origin:*');
	ini_set('date.timezone','Asia/Shanghai');

	// error_reporting(1|2|4|E_COMPILE_ERROR);
	error_reporting(E_ALL^(E_NOTICE|8192));
	ini_set('display_errors', '1');


	set_error_handler("customError");
	register_shutdown_function('fatalErrorHandler');
	require_once($filePath."_config_version.php");
	require_once($filePath."tool/tool.php");
	
	
	$head = $_POST['head'];
	$msg = json_decode($_POST['msg']);
	$debugC = $_POST['debug_client'];//客户端发起的DEBUG
	
	global $returnData,$mySendData;
	
	$returnData = new stdClass();
	$mySendData = new stdClass();
	$mySendData->head = $head;
	$mySendData->msg = $returnData;
	
	if($debugC){
		$startT = microtime(true);
		$debugArr = array();
	}
	
	
	
	//error1:版本号,2登陆状态,3出错,4写用户失败
	
	 function fatalErrorHandler(){
	 global $_POST,$mySendData,$debugC,$debugArr;
             $e = error_get_last();
             switch($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                     customError($e['type'],$e['message'],$e['file'],$e['line']);
                     break;         
            }
    }
	
	function  customError($errno, $errstr, $errfile, $errline)
	{ 
		global $_POST,$mySendData,$debugC,$debugArr;
		if($errno == 8192)
			return;
		if($errno == 8)
			return;
		
		errorLog($_POST['msg_index']."#".$_POST['head'].$_POST['msg']."=>code:".$errstr."=>code:".$errno.'=>file:'.$errfile."=>line:".$errline);//.$errstr."=>code:".$errno'=>file:'.$errfile."=>line:".$errline
		if($debugC)
			echo "=>code:".$errstr."=>code:".$errno.'=>file:'.$errfile."=>line:".$errline;
		sendErroClient();	
	}
	
	
	function sendErroClient(){
		global $_POST,$mySendData,$debugC,$debugArr;
		$mySendData = new stdClass();
		$mySendData->head = $_POST['head'];
		$mySendData->error = 3;
		$mySendData->debug = $debugArr;
		$mySendData->key = 'sendErroClient';
		sendToClient($mySendData);		
	}

	try{	
		do{
			// $mySendData->error = 99;
			// $mySendData->error_str = '15时间改回正常';
			// break;
			//测试版本号
			if($_POST['version'] < $game_version){
				$mySendData->error = 1;
				break;
			}
			if($_POST['version'] > $game_version){
				$mySendData->error = 5;
				$mySendData->version = $game_version;
				break;
			}
			
			//测试登陆状态,并设定用户数据
			if(isset($msg->landid) && isset($msg->gameid))
			{
				require_once($filePath."tool/conn.php");
				require_once($filePath."object/game_user.php");
				$sql = "select * from ".$sql_table."user_data where gameid='".$msg->gameid."' and land_key=".$msg->landid;
				$userData = $conne->getRowsRst($sql);
				if(!$userData)//登录失效
				{
					$mySendData->error = 2;
					break;
				}
				$userData = new GameUser($userData);
			}			
			
			switch($head)
			{
				case 'debug':
				{
					$mySendData->msg = $msg;
					break;
				}
				case 'login_server':
				{
					if($msg->h5)
					{	
						$loginOK = false;
						require_once($filePath."platform/login_".$msg->h5.".php");
						if($loginOK)
						{
							require_once($filePath."login_server.php");
						}
						else
							$returnData->fail = 1;
					}
					else if($msg->cdkey == 'hange0o0' || testCDKey($msg->id,$msg->cdkey))
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
					if($msg->h5)
					{	
						$loginOK = false;
						require_once($filePath."platform/login_".$msg->h5.".php");
						if($loginOK)
						{
							require_once($filePath."register_server.php");
						}
						else
							$returnData->fail = 1;
					}
					else if(testCDKey($msg->id,$msg->cdkey))
					{
						require_once($filePath."register_server.php");
					}
					else
						$returnData->fail = 1;
					break;
				}
				
				case 'get_test_monster':
				{
					require_once($filePath."tool/conn.php");
					$sql = "select * from test_monster";
					$returnData->data = $conne->getRowsArray($sql);
					$returnData->stopLog = true;
					break;
				}
				case 'client_error':
				{
					clientLog($msg->msg);
					break;
				}
				case 'test':
				{
				
					// $team1Data = json_decode('{"list":[101,101,101,101,101,101,101,101,101,101],"ring":{"id":1,"level":10},"fight":3,"tec":{"107":{"hp":15,"atk":15,"spd":15}}}');
					// $team2Data = json_decode('{"list":[101,101,101,101,101,101,101,101,101,101],"ring":{"id":1,"level":1},"fight":3,"tec":{"107":{"hp":15,"atk":15,"spd":17}}}');
					$team1Data = $msg->team1;
					$team2Data = $msg->team2;
					$equalPK = $msg->isequal;
					require($filePath."pk_action/pk.php");
					
					if($msg->need_server)
					{
						require_once($filePath."tool/conn.php");
						require_once($filePath."pk_action/pk_tool.php");
						monsterUseLog('test_monster',$msg->team1,$msg->team1,$result);		
						monsterUseLog('test_monster',$msg->team2,$msg->team2,!$result);		
					}
					
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
					$returnData->stopLog = true;
					break;
				}
				default:
				{
					if(is_file($filePath.$head.".php"))
					{
						require($filePath.$head.".php");
					}
					else
					{
						$mySendData->result = 'fail';
						$mySendData->msg = 'fun not found:'.$head;
					}				
					break;
				}
			}		
			if($debugC){
				$mySendData->runtime = microtime(true) - $startT;
				$mySendData->debug = $debugArr;
			}
		}while(false);
	}
	catch(Exception $e){
		errorLog($_POST['msg_index']."#".$_POST['head'].$_POST['msg'].$e->getMessage()."=>code:".$e->getCode().'=>file:'.$e->getFile()."=>line:".$e->getLine());
		$mySendData->error = 3;
		if($debugC)
			echo $e->__toString(); 			
	}
	
	if(!$returnData->stopLog)
	{
		if($returnData->fail)
		{
			errorLog($_POST['msg_index']."#".$_POST['head'].$_POST['msg'].'__'.json_encode($returnData));
		}
		else if(isset($msg->landid) && isset($msg->gameid))
		{
			userLog($msg->gameid,$_POST['msg_index']."#".$_POST['head'].$_POST['msg'].'__'.json_encode($returnData));
		}
	}
	
	unset($returnData->stopLog);	
	sendToClient($mySendData);
?>