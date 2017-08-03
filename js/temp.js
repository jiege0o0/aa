function aa(head,msg){
	var loader = new egret.URLLoader();
	loader.dataFormat = egret.URLLoaderDataFormat.TEXT;
	var request = new egret.URLRequest('http://172.17.196.195:90/gameindex.php');
	request.method = egret.URLRequestMethod.POST;
	var variables = new egret.URLVariables('head='+head);
	variables.variables.msg = JSON.stringify(msg);
	variables.variables.debug_client = 1;
	variables.variables.version = '1';
	request.data = variables;
	loader.load(request);
}

aa('pk_server',{"landid":1449731763,"gameid":"1_10000","choose":{"list":[107,107,107],"ring":2,"index":0}})
aa('pk_server_equal',{"landid":1449731763,"gameid":"1_10000","choose":{"list":[101],"ring":15,"index":0}})
aa('get_my_card',{"landid":1449731763,"gameid":"1_10000","type":"server_game"})
aa('pk_main',{"landid":1449731763,"gameid":"1_10000","choose":{"list":[101],"ring":1}})
aa('get_my_card',{"landid":1449731763,"gameid":"1_10000","type":"main_game"})
aa('main_kill',{"landid":1449731763,"gameid":"1_10000","kill":1})
aa('main_award',{"landid":1449731763,"gameid":"1_10000"})
aa('get_day_game',{"serverid":1})
aa('pk_day_game',{"landid":1449731763,"gameid":"1_10000","choose":{"list":[201],"ring":5},"level":1})
aa('friend_apply',{"landid":1449731763,"gameid":"1_10000","otherid":"1_10001"})
aa('friend_log',{"landid":1449731763,"gameid":"1_10000","lasttime":0})
aa('friend_refuse',{"landid":1449731763,"gameid":"1_10000","logid":1})
aa('friend_pk_get_card',{"landid":1449731763,"gameid":"1_10000","otherid":"1_10001"})
aa('get_other_info',{"serverid":1,"otherid":"1_10000"})
aa('friend_pk_ask',{"landid":1449731763,"gameid":"1_10000","choose":{"list":[103],"ring":1},"otherid":"1_10001","isequal":true})

aa('pk_result',{"team1":{"list":[103],"ring":{"id":1},"fight":3},"isequal":true,"team2":{"list":[103],"ring":{"id":1},"fight":3}})

aa('get_rank',{"rank_type":1})
aa('create_rank',{"serverid":1})

aa('add_user_server',{"serverid":1,"id":10000,"password":"111111"})

http://172.17.196.195:90/game/_create_monster_db.php?table=server_monster,server_equal_monster
http://172.17.196.195:90/game/_create_monster_db.php?table=test_monster

http://172.17.196.195:90/game/_clean_server.php?serverid=1
http://172.17.196.195:90/game/_create_db.php?serverid=1
http://172.17.196.195:90/game/_create_dungeon_db.php?serverid=1&month=6
http://172.17.196.195:90/game/_create_talk_db.php
http://172.17.196.195:90/game/_create_monster_db.php?serverid=1
http://172.17.196.195:90/user/_create_db.php?serverid=1
http://qxu1606510485.my3w.com/game/_create_db.php?serverid=1
http://qxu1606510485.my3w.com/user/_create_db.php?serverid=1
http://172.17.196.195:90/test.php

http://172.17.196.195:90/test.php

http://172.17.196.195:90/new_index.php
http://hangegame.com/new_index.php
http://hangegame.com/user/_create_db.php
http://hangegame.com/game/_create_db.php?serverid=1
http://hangegame.com/game/_create_talk_db.php
http://hangegame.com/game/_create_dungeon_db.php?serverid=1&month=6
http://hangegame.com/game/_clean_server.php?serverid=1
http://hangegame.com/game/_create_monster_db.php?table=server_monster,server_equal_monster
http://hangegame.com/index.html?host1=com&openid=h1
http://hangegame.com/client/index.html

http://172.17.196.195:9010/index.html?host1=com&openid=h1
http://172.17.196.195:9010/index.html?host=com&openid=h1&serverid=1

{"isagain":false,"landid":1458716565,"gameid":"1_10011"}



aa('friend_log',{"landid":1449732148,"gameid":"1_10001","lasttime":0})
aa('friend_refuse',{"landid":1449732148,"gameid":"1_10001","logid":2})
aa('friend_agree',{"landid":1449732148,"gameid":"1_10001","logid":1})
aa('friend_delete',{"landid":1449732148,"gameid":"1_10001","otherid":"1_10005"})
aa('friend_list',{"landid":1449732148,"gameid":"1_10001","friends":[],"lasttime":0})
aa('friend_pk_answer',{"landid":1449732148,"gameid":"1_10001","choose":{"list":[103],"ring":1},"logid":1})




http://172.17.196.195:9010/index.html?debug_server=1&debug=1&openid2=1_10232&openkey=hange0o0
http://172.17.196.195:90/game/egret_pay.php


http://172.17.196.195:9010/index.html?host=com&openid=han2&debug_server=1&debug=1
http://172.17.196.195:9010/index.html?host=com&openid=han3&debug_server=1&debug=1
http://172.17.196.195:9010/index.html?host=com&openid=han4&debug_server=1&debug=1
http://172.17.196.195:9010/index.html?host=com&openid=han5&debug_server=1&debug=1
http://172.17.196.195:9012/index.html?host=com&openid=han2&debug_server=1&debug=1

http://172.17.196.195:9010/index.html?host=com&openid=n2&debug_server=1&debug=1

















