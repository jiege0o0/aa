<?php 
	do{
		if(!$userData->pk_common->pk_jump)
		{
			$returnData->fail = 1;//没有跳过次数了
			break;
		}
		$userData->pk_common->pk_jump --;
		$userData->setChangeKey('pk_common');
		$userData->write2DB();	
		$returnData->data = 'ok';
	}while(false);
		
?> 