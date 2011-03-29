<?php
/**
 * 告警处理
@copyright  秦运恒, 2011
 * @author 　　赵将伟
 * @create date 　 2011.03.28
 */

$act = $GLOBALS ["all"] ["operate"];
$region = new Region ();

switch ($act) {
	case "main" :
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	
	case "create" :
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	
	case "save_region" :
		$region_object ['name'] = $GLOBALS ['db']->prepare_value ( $_REQUEST ['region_name'], "VARCHAR" );
		$region_object ['xMin'] = $GLOBALS ['db']->prepare_value ( $_REQUEST ['xMin'], "INT" );
		$region_object ['yMin'] = $GLOBALS ['db']->prepare_value ( $_REQUEST ['yMin'], "INT" );
		$region_object ['xMax'] = $GLOBALS ['db']->prepare_value ( $_REQUEST ['xMax'], "INT" );
		$region_object ['yMax'] = $GLOBALS ['db']->prepare_value ( $_REQUEST ['yMax'], "INT" );
		$region_object ['userId'] = $GLOBALS ['db']->prepare_value ( get_session ( "user_id" ), "INT" );
		$region_object ['create_time'] = $GLOBALS ['db']->prepare_value ( get_sysdate (), "VARCHAR" );
		
		if ($region->save_region ( $region_object )) {
			echo "success";
		} else {
			echo "fail";
		}
		
		break;
	
	case "manage" :
		$result = $region->query_regions ();
		
		foreach ( $result as $key => $value ) {
			$arr ['regiones'] .= "<tr id='" . $value ['id'] . "'><td>" . $value ['name'] . "</td><td>" . $value ['login_name'] . "</td><td>" . $value ['create_time'] . "</td><td>" . $value ['description'] . "</td><td><a href='javascript:set_vehicles(" . $value ['id'] . ");'>指定车辆</a></td><td><a href='javascript:exam_region(" . $value ['id'] . "," . $value ['xMin'] . "," . $value ['yMin'] . "," . $value ['xMax'] . "," . $value ['yMax'] . ")'>查看</a></td>
								<td><a href='javascript:delete_region(" . $value ['id'] . ");'>删除</a></td></tr>";
		}
		
		echo $GLOBALS ['db']->display ( $arr, $act );
		break;
	
	case "delete" :
		$id = $_REQUEST ['id'];
		
		if ($region->delete_region ( $id )) {
			echo "success";
		} else {
			echo "fail";
		}
		
		break;
	
	case "update" :
		$param ['id'] = $_REQUEST ['region_id'];
		$param ['xMin'] = $_REQUEST ['xMin'];
		$param ['yMin'] = $_REQUEST ['yMin'];
		$param ['xMax'] = $_REQUEST ['xMax'];
		$param ['yMax'] = $_REQUEST ['yMax'];
		
		$result_update = $region->update_region ( $param );
		
		if ($result_update) {
			echo "success";
		} else {
			echo "fail";
		}
		
		break;
	
	case set_vehicle_region :
		$vehicle_ids = $_REQUEST ['vehicle_ids'];
		$region_id = $_REQUEST ['region_id'];
		
		$vehicle_ids = explode ( ",", $vehicle_ids );
		
		if ($region->set_vehicle_region ( $region_id, $vehicle_ids )) {
			echo "success";
		} else {
			echo "fail";
		}
		
		break;
		
		break;

}

?>