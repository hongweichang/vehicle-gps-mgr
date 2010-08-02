<?php
/** 
 * 用户处理
 * @copyright		秦运恒, 2010
 * @author			叶稳
 * @create date		2010.07.30
 * @modify			修改人
 * @modify date		修改日期
 * @modify describe	修改内容
 */
$act = $GLOBALS ["all"] ["operate"];

$page = $_REQUEST ['page']; // get the requested page
$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST ['sord']; // get the direction


if (! $sidx)
	$sidx = 1;

switch ($act) {
	case "select" ://选择车辆		
		$vehicle_console = new vehicle_console ();		
		
        /**获取所有车辆组*/
		$vehicle_group = $vehicle_console->get_all_vehicle_group ();
		
		/**生成车辆组标题*/
		$str = "<div style='font-size:13px;'><ul>";
		foreach ( $vehicle_group as $value ) {
			$str = $str . "<li><a href='#tabs" . $value [0] . "'>" . $value [1] . "</a></li>";
		}
		$str = $str."</ul></div>";
		
		/**在车辆组下显示所有的车辆*/
		foreach ( $vehicle_group as $values ) {
			$vehicles = $vehicle_console->get_group_vehicle ( "where vehicle_group_id=" . $values [0] );
			$str = $str . "<div id='tabs" . $values [0] . "'>".
							"&nbsp<input type='checkbox' value=" . $values [0] . " name='selectall' class='selectall' id='selectall" . $values [0] . 
							"'>全选</input><div class='scroll'>";
			
			/**遍历每个车辆组，得到每组的所有车辆*/
			foreach ( $vehicles as $vehicle ) {
				$str = $str . "<input type='checkbox' name='" . $values [0] . "'>" . $vehicle [1] . "</input>";
			}
			$str = $str . "</div></div>";
		}
		$arr ['vehicle_group_data'] = $str;
		echo $db->display ( $arr, "select" );
		break;
	
}

?>