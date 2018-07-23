<?php
	require('connect.php');
	
	//DB接続
	$result = connect();
	if($result){
		echo '{"result" : "' . $result . '"}';
		return;
	}
	//URLからのパラメーターを取得
	if(!(isset($_GET['id']))) {
		$result = '{"error" : 1 ,"message : "パラメーターが入っていません:id"}';
		echo '{"result" : ' . $result . '}';
		return;
	}
	$id = $_GET['id'];
	// 社員出退勤状況取得
	$query = "SELECT name, time, attendance FROM AttendanceTable JOIN AttendanceMaster ON AttendanceTable.attendance_id = AttendanceMaster.id JOIN EmployeeMaster ON AttendanceTable.employee_id = EmployeeMaster.id WHERE employee_id = $id ORDER BY AttendanceTable.id DESC LIMIT 1";
	$queryResult = mysql_query($query);
	if($queryResult){
		$row = mysql_fetch_array( $queryResult );
		$result = '{"error" : 0, "name" : "' . $row['name'] . '", "time" : "' . $row['time'] . '", "attendance" : ' . $row['attendance'] . '}';
	} else {
		$result = '{"error" : 1 ,"message : "クエリーが失敗しました:社員出退勤状況取得"}';
	}
	echo '{"result" : ' . $result . '}';
?>