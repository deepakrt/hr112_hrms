<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>PHPExcel Reader Example #01</title>

</head>
<body>

<h1>PHPExcel Reader Example #01</h1>
<h2>Simple File Reader using PHPExcel_IOFactory::load()</h2>
<?php

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');

/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';


$inputFileName = './sampleData/student_data.xls';
//echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
echo "Start ".date('Y-m-d H:i:s');

echo '<hr />';
$objPHPExcel->setActiveSheetIndex(0);
$worksheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);;
echo "111111<pre>";print_r($worksheet);die;
$i=0;
$data = array();
foreach($worksheet as $sheetDatas){
	$data[$i]['Registration_Number'] = $sheetDatas['A'];
	$data[$i]['Registration_Number']= $sheetDatas['B'];
	$data[$i]['Roll_Number']= $sheetDatas['C'];
	$data[$i]['Course_Id']= $sheetDatas['D'];
	$data[$i]['Department_Id']= $sheetDatas['E'];
	$data[$i]['address']= $sheetDatas['F'];
	$data[$i]['gender']= $sheetDatas['G'];
	$data[$i]['imagePath']= $sheetDatas['H'];
	$data[$i]['lastName']= $sheetDatas['I'];
	$data[$i]['Blood_group']= $sheetDatas['J'];
	$data[$i]['Contact_Number']= $sheetDatas['K'];
	$data[$i]['Dob']= $sheetDatas['L'];
	$data[$i]['Email_Id']= $sheetDatas['M'];
	$data[$i]['First_Name']= $sheetDatas['N'];
	$data[$i]['Image_Path']= $sheetDatas['O'];
	$data[$i]['Last_name']= $sheetDatas['P'];
	$data[$i]['College_Id']= $sheetDatas['Q'];
	$data[$i]['user_id']= $sheetDatas['R'];
	$data[$i]['father_nanme']= $sheetDatas['S'];
	$data[$i]['mother_name']= $sheetDatas['S'];
	$data[$i]['father_DOB']= $sheetDatas['T'];
	$data[$i]['father_emailId']= $sheetDatas['U'];
	$data[$i]['Batch']= $sheetDatas['V'];
	
	// if(!empty($sheetDatas['B'])){
		// echo 'E-mail ID '.$i.' :: '.$sheetDatas['B'].'<br>';
	// }
	$i++;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$j = 0;

//echo "<pre>";print_r($data);die;
foreach($data as $da){
	$Registration_Number = $da['Registration_Number'];
	$Roll_Number = $da['Roll_Number'];
	$Course_Id = $da['Course_Id'];
	$Department_Id = $da['Department_Id'];
	$address = $da['address'];
	$gender = $da['gender'];
	$imagePath = $da['imagePath'];
	$lastName = $da['lastName'];
	$Blood_group = $da['Blood_group'];
	$Contact_Number = $da['Contact_Number'];
	$Dob = $da['Dob'];
	$Email_Id = $da['Email_Id'];
	$First_Name = $da['First_Name'];
	$Image_Path = $da['Image_Path'];
	$Last_name = $da['Last_name'];
	$College_Id = $da['College_Id'];
	$user_id = $da['user_id'];
	$father_nanme = $da['father_nanme'];
	$mother_name = $da['mother_name'];
	$father_DOB = $da['father_DOB'];
	$father_emailId = $da['father_emailId'];
	$Batch = $da['Batch'];
	
	$sql = "INSERT INTO student_master (`Registration_Number`, `Roll_Number`, `Course_Id`, `Department_Id`, `address`, `gender`, `imagePath`, `lastName`, `Blood_group`, `Contact_Number`, `Dob`, `Email_Id`, `First_Name`, `Image_Path`, `Last_name`, `College_Id`, `user_id`, `father_nanme`, `mother_name`, `father_DOB`, `father_emailId`, `Batch`) 
VALUES ('$Registration_Number', '$Roll_Number', '$Course_Id', '$Department_Id', '$address', '$gender', '$imagePath', '$lastName', '$Blood_group', '$Contact_Number', '$Dob', '$Email_Id', '$First_Name', '$Image_Path', '$Last_name', '$College_Id', '$user_id', '$father_nanme', '$mother_name', '$father_DOB', '$father_emailId', '$Batch')";

//$conn->query($sql);

	if ($conn->query($sql) === TRUE) {
		$last_id[$j] = $conn->insert_id."<br>";
		/*$sql1 = "INSERT INTO tbl_emp_mapping (`employee_id`, `fla_emp_id`, `sla_emp_id`) 
VALUES ('$last_id', '$fla_id', '$sla_id')";
		$conn->query($sql1);*/
	}else{
		die($conn->error);
		
	}
	
	$j++;
	
}

/*
if ($conn->query($sql) === TRUE) {
	$last_id = $conn->insert_id;
    echo "New record created successfully $last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}*/
echo "End TIME : ".date('Y-m-d H:i:s');
die("Done");

//
?>
<body>
</html>
