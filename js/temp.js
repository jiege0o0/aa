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
http://172.17.196.195:90/game/_create_monster_db.php

http://172.17.196.195:90/user/_create_db.php?serverid=1
http://qxu1606510485.my3w.com/game/_create_db.php?serverid=1
http://qxu1606510485.my3w.com/user/_create_db.php?serverid=1
http://172.17.196.195:90/test.php

http://172.17.196.195:90/test.php


http://172.17.196.195:90/new_index.php
http://hangegame.com/new_index.php
http://hangegame.com/user/_create_db.php


http://hangegame.com/game/_create_db.php?serverid=1
http://hangegame.com/game/_create_dungeon_db.php?serverid=1&month=6
http://hangegame.com/game/_create_talk_db.php
http://hangegame.com/game/_create_monster_db.php?table=server_monster,server_equal_monster


http://hangegame.com/game/_clean_server.php?serverid=1

http://hangegame.com/index.html?host1=com&openid=h1
http://hangegame.com/client/index.html

http://172.17.196.195:9010/index.html?host1=com&openid=h1
http://172.17.196.195:9010/index.html?host=com&openid=h1&serverid=1

{"isagain":false,"landid":1458716565,"gameid":"1_10011"}


http://hangegame.com/client/index.html?new_version=9
http://hangegame.com/client/index.html?new_version=11
http://hangegame.com/client/index_test.html
http://hangegame.com/pay_qunhei

http://172.17.196.195:90/game/index.php?username=419104&serverid=1&time=1506668204&isadult=1&flag=dacecc074b603e0a377b47ad352dd522&unid=&uimg=http://m.qunhei.com/Static/Default/Home/images/bg_touxiang_wutouxiang.png&nname=张晓杰


蔡伟汉  |  研发
待命名工作室
Add:广东省佛山市汾江南路131号1区5座501
T:13925490910
QQ: 284303917
E-Mail：284303917@qq.com
地址：http://hangegame.com/client_test.php

http://hangegame.com/client_qunhei.php?username=5028262&serverid=1&time=1506653351&isadult=1&flag=001dd884dd89702aebb6007e78c4fac7&unid=&uimg=http://m.qunhei.com/Static/Default/Home/images/bg_touxiang_wutouxiang.png&nname=卡斗士
http://172.17.196.195:9010/index_test.html




aa('friend_log',{"landid":1449732148,"gameid":"1_10001","lasttime":0})
aa('friend_refuse',{"landid":1449732148,"gameid":"1_10001","logid":2})
aa('friend_agree',{"landid":1449732148,"gameid":"1_10001","logid":1})
aa('friend_delete',{"landid":1449732148,"gameid":"1_10001","otherid":"1_10005"})
aa('friend_list',{"landid":1449732148,"gameid":"1_10001","friends":[],"lasttime":0})
aa('friend_pk_answer',{"landid":1449732148,"gameid":"1_10001","choose":{"list":[103],"ring":1},"logid":1})




http://172.17.196.195:9010/index.html?debug_server=1&debug=1&openid2=1_10243&openkey=hange0o0
http://172.17.196.195:9010/index.html?debug_server=1&debug=1&openid2=1_10066&openkey=hange0o0
http://172.17.196.195:9010/index.html?debug_server=1&debug=1&openid2=1_10176&openkey=hange0o0//幻魔之眼
http://172.17.196.195:90/game/egret_pay.php

http://172.17.196.195:9010/index.html?host=com&debug_server=1&debug=1


http://172.17.196.195:9010/index.html?host=com&openid=han2&debug_server=1&debug=1
http://172.17.196.195:9010/index.html?host=com&openid=han3&debug_server=1&debug=1
http://172.17.196.195:9010/index.html?host=com&openid=han4&debug_server=1&debug=1
http://172.17.196.195:9010/index.html?host=com&openid=han5&debug_server=1&debug=1
http://172.17.196.195:9012/index.html?host=com&openid=han2&debug_server=1&debug=1

http://172.17.196.195:9010/index.html?host=com&openid=n2&debug_server=1&debug=1



var PVEM = TeamPVEManager.getInstance();
PVEM.data.player2 = PVEM.data.player1;
PVEM.data.player3 = PVEM.data.player1;

DM.debugFromFile({"team1":{"list":[7,7,7],"fight":0,"tec":{"7":0}},"team2":{"list":[7,7,7],"fight":0,"tec":{"1":0,"7":0,"15":0,"56":0}},"isequal":true})
PKManager.getInstance().onPK(PKManager.PKType.REPLAY,); PKMainUI.getInstance().show(null,true);//播放动画
PKManager.getInstance().pkResult.detail = {};VideoManager.getInstance().videoData = {}//清录像，以重新请求
DM.recallPK()


//user:gender,hp,year,hp,atk,speed,face,

// city :id,name,posX,poxY,
//task: id,city,name,need1,need2,need3,des,pic,award1,award2,award3
//npc:id,name,hp,atk,speed,skill

//prop: id,name
//skill:id,name,des,effect1,effect2,effect3,beforevalue,actionvalue
//equip: id,name,hp,atk,speed,skill



ttp://open.qunhei.com/

http://172.17.196.195:9010/index.html?host=com&debug_server=1&debug=1

1、找对接人申请账号
2、用账号登录后台，在后台添加游戏以及相关配置
3、在游戏列表里面查询游戏ID
4、用测试地址调试接口
5、测试成功通知对接人测试
6、切换正式服务器，游戏上线
7、整体流程完毕
注：登录测试地址->http://m.qunhei.com/game/login/gid/游戏id.html
登录测试账号-> qhtest 密码->123456

http://172.17.196.195:9010/index_qunhei.html?host=com&openid=han2&debug_server=1&debug=1


http://m.qunhei.com/game/login/gid/3697.html?username=62&serverid=1&time=134234562&isadult=1&nname=张晓珊&unid=qhtest&flag=test111111&uimg=xxxx

http://m.qunhei.com/game/login/gid/3697.html


http://m.qunhei.com/game/login/gid/3697.html

http://172.17.196.195:9010/index.html?host=com&openid=han2&debug_server=1&debug=1

http://172.17.196.195:9010/index_qunhei.html?host=com&debug_server=1&debug=1&username=5028262&serverid=1&time=1506653351&isadult=1&flag=001dd884dd89702aebb6007e78c4fac7&unid=&uimg=http://m.qunhei.com/Static/Default/Home/images/bg_touxiang_wutouxiang.png&nname=卡斗士


http://172.17.196.195:9010/index_qunhei.html?host=com&debug_server=1&debug=1&username=419104&serverid=1&time=1506668204&isadult=1&flag=dacecc074b603e0a377b47ad352dd522&unid=&uimg=http://m.qunhei.com/Static/Default/Home/images/bg_touxiang_wutouxiang.png&nname=张晓杰

http://m.qunhei.com/game/login/gid/3697.html

http://172.17.196.195:90/pay_qunhei.php?ext=1_10024|1|101|99362&orderno=123
http://hangegame.com/pay_qunhei.php?ext=1_10024|1|101
http://hangegame.com/pay_qunhei.php?ext=1_10024|1|101|99362&orderno=123


http://hangegame.com/pay_qunhei.php?serverid=1&orderno=20171010103716CU&username=5028262&addgold=0&rmb=1&ext=1_qh5028262|1|105|03110&paytime=1507603063&sign=e75503511c821d824f9cdb36029b71a4 














