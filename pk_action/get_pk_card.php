<?php 
	require_once($filePath."cache/monster.php");
	//�������
	function randomSortFun($a,$b){
		return lcg_value()>0.5?1:-1;
	}
	
	//ȡ������õĹ������
	function getPKCard($userLevel){
		global $monster_base;
		
		//�õ��õ���5�����Գ���
		$levelArr1 = array();
		$levelArr2 = array();
		$levelArr3 = array();
		foreach($monster_base as $key=>$value)
		{
			if($userLevel >= $value['level'])
			{	
				if($value['cost'] < 10)
					array_push($levelArr1,$key);
				else if($value['cost'] < 20)
					array_push($levelArr2,$key);
				else
					array_push($levelArr3,$key);
			}
		}
		usort($levelArr1,randomSortFun);
		usort($levelArr2,randomSortFun);
		usort($levelArr3,randomSortFun);
		
		$returnMonsterArr = array();//���صĳ�������
		array_push($returnMonsterArr,$levelArr1[0]);
		array_push($returnMonsterArr,$levelArr1[1]);
		array_push($returnMonsterArr,$levelArr2[0]);
		array_push($returnMonsterArr,$levelArr2[1]);
		array_push($returnMonsterArr,$levelArr2[2]);
		array_push($returnMonsterArr,$levelArr2[3]);
		array_push($returnMonsterArr,$levelArr3[0]);
		array_push($returnMonsterArr,$levelArr3[1]);
		
		//����
		usort($returnMonsterArr,randomSortFun);
		$obj = new stdClass();
		$obj->list = $returnMonsterArr;
		return $obj;
	}

	
?> 