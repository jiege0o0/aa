<?php 
	// $monster_kind = array(); 
	// $monster_kind[1] = array(101,102,103,104,105,106,107);
	// $monster_kind[2] = array(201,202,203,204,205,206,207);
	// $monster_kind[3] = array(301,302,303,304,305,306,307);
	// $monster_kind[4] = array(401,402,403,404,405,406,407);
	// $monster_kind[5] = array(501,502,503,504,505,506,507);
	
	// $monster_level = array('1'=>1,'2'=>1,'3'=>1,'4'=>1,'5'=>1); 
	
	$monster_kind = array(
	"1"=>array("id"=>1,"level"=>1,"restrain"=>array(2),"list"=>array(101,102,103,104,105,106,107)),
	"2"=>array("id"=>2,"level"=>1,"restrain"=>array(3),"list"=>array(201,202,203,204,205,206,207)),
	"3"=>array("id"=>3,"level"=>1,"restrain"=>array(4),"list"=>array(301,302,303,304,305,306,307)),
	"4"=>array("id"=>4,"level"=>1,"restrain"=>array(5),"list"=>array(401,402,403,404,405,406,407)),
	"5"=>array("id"=>5,"level"=>1,"restrain"=>array(1),"list"=>array(501,502,503,504,505,506,507)));
	


	$monster_base = array();
	$monster_base['101'] = array("id"=>101,"hp"=>100,"atk"=>30,"speed"=>51,"cost"=>10,"type"=>1,"wood"=>0,"kind"=>array(3,5),"collect"=>3,"effect_kind"=>array(3),'skill1'=>'LEADER,ONCE,MHP#1000,ATK#100,MR#99,CD#0');
	$monster_base['102'] = array("id"=>102,"hp"=>200,"atk"=>60,"speed"=>52,"cost"=>10,"type"=>1,"collect"=>3,"kind"=>array(3,5),"effect_kind"=>array(1));
	$monster_base['103'] = array("id"=>103,"hp"=>300,"atk"=>50,"speed"=>53,"cost"=>10,"type"=>1,"collect"=>3,"kind"=>array(3,5),"effect_kind"=>array(1));
	$monster_base['104'] = array("id"=>104,"hp"=>400,"atk"=>40,"speed"=>54,"cost"=>10,"type"=>1,"collect"=>3,"kind"=>array(3,5),"effect_kind"=>array(1));
	$monster_base['105'] = array("id"=>105,"hp"=>500,"atk"=>30,"speed"=>55,"cost"=>10,"type"=>1,"collect"=>3,"kind"=>array(3,5),"effect_kind"=>array(1));
	$monster_base['106'] = array("id"=>106,"hp"=>600000,"atk"=>2000,"speed"=>100,"cost"=>10,"type"=>1,"collect"=>3,"kind"=>array(3,5),"effect_kind"=>array(1));
	$monster_base['107'] = array("id"=>107,"hp"=>40000,"atk"=>100,"speed"=>57,"cost"=>10,"type"=>1,"collect"=>3,'skill'=>'ATK#30,R#3','skill1'=>'EHP#-130,CD#2','skill2'=>'EHP#-130,CD#2','skill3'=>'EHP#-130,CD#2','skill4'=>'T#DIE,NEXTV#HP#10#[MR#2~CD#0]','skill5'=>'CD#0,HP#500','skill_f1'=>'EHP#-30','skill_f2'=>'ATK#300,CD#0','skill_f3'=>'SPD#30,CD#0','skill_f4'=>'T#DIE,ATK#100',"kind"=>array(3,5),"effect_kind"=>array(1));
	
	
	$monster_base['201'] = array("id"=>201,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>2,"wood"=>1,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['202'] = array("id"=>202,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>2,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['203'] = array("id"=>203,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>2,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['204'] = array("id"=>204,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>2,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['205'] = array("id"=>205,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>2,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['206'] = array("id"=>206,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>2,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['207'] = array("id"=>207,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>2,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	
	$monster_base['301'] = array("id"=>301,"hp"=>1000,"atk"=>270,"speed"=>51,"cost"=>10,"type"=>3,"wood"=>1,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['302'] = array("id"=>302,"hp"=>100,"atk"=>10,"speed"=>51,"cost"=>10,"type"=>3,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['303'] = array("id"=>303,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>3,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['304'] = array("id"=>304,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>3,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['305'] = array("id"=>305,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>3,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['306'] = array("id"=>306,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>3,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['307'] = array("id"=>307,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>3,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	
	$monster_base['401'] = array("id"=>401,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>4,"wood"=>1,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['402'] = array("id"=>402,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>4,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['403'] = array("id"=>403,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>4,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['404'] = array("id"=>404,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>4,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['405'] = array("id"=>405,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>4,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['406'] = array("id"=>406,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>4,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['407'] = array("id"=>407,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>4,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	
	$monster_base['501'] = array("id"=>501,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>5,"wood"=>1,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['502'] = array("id"=>502,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>5,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['503'] = array("id"=>503,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>5,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['504'] = array("id"=>504,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>5,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['505'] = array("id"=>505,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>5,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['506'] = array("id"=>506,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>5,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);
	$monster_base['507'] = array("id"=>507,"hp"=>100,"atk"=>70,"speed"=>51,"cost"=>10,"type"=>5,"wood"=>0,"kind"=>array(3,5),"effect_kind"=>array(1),"collect"=>2);

	
	$ring_base = array();
	$ring_base['1'] = array("skill"=>'T#SS,EHPD#VALUE','begin'=>3,'step'=>1);
?> 