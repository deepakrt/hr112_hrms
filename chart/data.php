<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost","root","myRoot@1","eoffice");

$sqlQuery = "SELECT project_id,project_name,project_cost FROM pr_project_list where manager_dept=1";

$result = mysqli_query($conn,$sqlQuery);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

mysqli_close($conn);
// echo "<pre>==";print_r($data);die;
echo json_encode($data);
?>