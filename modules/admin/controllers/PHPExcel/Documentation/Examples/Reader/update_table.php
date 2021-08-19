<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "emp_master_test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
	// echo "connected";
}

$sql = "select * from dob";
$dobTable = $conn->query($sql);
$dob_records = array();
$i = 0;
while($row = $dobTable->fetch_assoc()) {
	$dob_records[$i]['emp_id'] = $row['emp_id'];
	$dob_records[$i]['date_of_birth'] = $row['date_of_birth'];
	$dob_records[$i]['joining_date'] = $row['joining_date'];
	$i++;
}


$sql1 = "select * from tbl_employee";
$dobTable1 = $conn->query($sql);
$emp_records = array();
$i = 0;
while($row = $dobTable1->fetch_assoc()) {
	$emp_records[$i]['employee_id'] = $row['emp_id'];
	$i++;
}


// echo "<pre>";print_r($emp_records);

foreach($emp_records as $key=>$emp_record){
	$employeeID = $emp_record['employee_id'];
	$dob = $dob_records[$key]['date_of_birth'];
	$joining_date = $dob_records[$key]['joining_date'];
	
	$sqll = "UPDATE `tbl_employee` SET `date_of_birth` = '$dob', `joining_date` = '$joining_date' WHERE `employee_id` = $employeeID";
		//echo $sqll; die;
		if ($conn->query($sqll) === TRUE) {
			echo "Updated ".$employeeID."<br><br>";
			
		}else{
			var_dump($conn->error);
			die($conn->error);
			
		}
}

die("DONE");
