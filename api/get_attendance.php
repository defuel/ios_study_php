<?php
	require('connect.php');
	
	//DB接続
	$result = connect();
	if($result){
		echo '{"result" : "' . $result . '"}';
		return;
	}
	// 出退勤一覧取得
	$query = "SELECT employee_id, name, time, attendance FROM AttendanceTable JOIN AttendanceMaster ON AttendanceTable.attendance_id = AttendanceMaster.id JOIN EmployeeMaster ON AttendanceTable.employee_id = EmployeeMaster.id WHERE (employee_id, attendance_id) IN (SELECT employee_id, MAX(attendance_id) FROM AttendanceTable GROUP BY employee_id) ORDER BY employee_id ASC";
	$queryResult = mysql_query($query);
	if($queryResult){
		$array = array();
		while($row = mysql_fetch_assoc($queryResult)){
			$array[] = array('id'=>(int)$row['employee_id'], 'name'=>$row['name'], 'time'=>$row['time'], 'attendance'=>(int)$row['attendance']);
		}
		$result = array('error'=>0, 'employee'=>$array);
	} else {
		$result = array('error'=>1,'message'=>"クエリーが失敗しました:出退勤一覧取得");
	}
	echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
?>