<?php 
	$url = 'Location:http://hangegame.com/client/version1/index_qunhei.html?';
	$arr = array();
	foreach($_GET as $key=>$value)
	{
		array_push($arr,$key.'='.$value);
	}
	$url = $url.implode('&',$arr);
	header($url);
?> 