<?php 
define("CompanyName", "ERSS, Haryana");
define("CompanyNameLoginPage", "Emergency Response Support System (ERSS)<br>Dial-112<br>Haryana"); 
define("CompanyNameHeader", "Emergency Response Support System (ERSS), Dial-112, Haryana"); 
define("DB_Name", "e_office");
define("DB_Username", "root");
define("DB_Password", "root!@#321");
define("DefaultImageEmployee", "images/default_user.png");
define("PDF_Company_Logo", "images/logo_cdac.png");

/****************** Upload Documents **************/
define("Employees_Photo_Sign", "/other_files/Employees_Photo_Sign/");
define("Photo_Sign_Size", "500"); //in KB
define("Employee_Documents", "/other_files/Employee_Documents/");
define("Inventory_Docs", "other_files/inventory_docs/");
define("FTS_Documents", "/other_files/FTS_Documents/");
define("FTS_Doc_Size", "5"); //in MB
define("FTS_Image_Size", "1"); //in MB
define("PDF_File_Size", "5"); //in MB
define("FILE_MOVEMENT", "/file_movement/");
define("Project_Documents", "/other_files/Project_Documents/"); //used for project management
define("Project_Doc_Size", "5"); //in MB
/***************************************************************/

/** STATEID define for Get Distict From Perticular State (6 Is state_id of HARYANA State From master_states Table) **/
define("STATEID", 6); 

$yr = date('Y');
$m = date('m');
if($m >= 3){ $CurrentYr = $yr+1; }else{ $CurrentYr = $yr+1;}
$CurDate = date('d-m-Y');
$CurYr = date('Y', strtotime($CurDate));
$Curmonth = date('m', strtotime($CurDate));
if($Curmonth >= 3){ $yrss = $CurYr+1; }else{ $yrss = $CurYr-1; }
$fn ="";
for($i=$CurrentYr;$i>=$yrss;$i--){
    $ly = $i-1;	
    $fn= $ly."-".$i;
}
define("FTS_Ticket_Number", "CDAC(M)/eFile/$fn/");
define('efile_action_type', array('Information','Act & Revert','Discuss', 'Approval'));
define("efile_access_level", array(
	'0'=>array('name'=>'Read / Write', 'shortname'=>'RW', 'is_active'=>'Y'),
	'1'=>array('name'=>'Read Only', 'shortname'=>'R', 'is_active'=>'Y')
));
define("efile_check_yes_no", array(
	'0'=>array('name'=>'No', 'shortname'=>'N', 'is_active'=>'Y'),
	'1'=>array('name'=>'Yes', 'shortname'=>'Y', 'is_active'=>'Y')
	
));

define('efile_priority', array('Normal','Moderate','High'));


define("ORGANAZATION_NAME", "Emergency response centre (ERSS)");
define("ORGANAZATION_NAME_HINDI", "आपातकालीन प्रतिक्रिया केंद्र (ईआरएसएस)");
define("ORGANAZATION_ADD", "Sector 3, Panchkula, Haryana 134112");
define("ORGANAZATION_ADD_HINDI", "सेक्टर 3, पंचकुला, हरियाणा 134112");
define("SHORT_ORGANAZATION_NAME", "ERSS");
define("ORGANAZATION_CENTRE", "Panchkula");
define("RECRU_MODE", "Interviews");


//define('Encrypt_Key', 'CoUnsElLiNgEnRyp'); // should be in 16 Characters
define('Encrypt_Key', date('YmdH')."eMuLaZ"); // should be in 16 Characters
define('JourneyClassType', array('Bus','AC Chair Car Shatabdi','AC Bus', 'Second Class Sitting'));
define('JourneyTickets', array('Self','Office'));
define('StayType', array('Hotel','Guest House','Self'));
define('transportMode', array('Auto Rickshaw','Public Transport','Taxi'));
define('IpdClaimType', array('Entire From Office','Left over from insurance Company'));
define('MaritalStatus', array('Single','Married','Divorcee','Widow','Widower'));
define('BloodGroups', array('A+ve','B+ve','A-ve','B-ve','AB+ve', 'AB-ve','O+ve','O-ve', 'Don\'t Know'));
define('Super_Admin_Emp_Code', '101');
define('Default_Password', '12345');

define("Emp_Allowances", array(
	'0'=>array('name'=>'Children Education Allowance', 'shortname'=>'CEA', 'is_active'=>'Y'),
	'1'=>array('name'=>'Hostel Subsidy', 'shortname'=>'HS', 'is_active'=>'Y')
));
define("Stds_list", array('Nursery-I', 'Nursery-II', '1st Std', '2nd Std', '3rd Std', '4th Std', '5th Std', '6th Std', '7th Std', '8th Std', '9th Std', '10th Std', '11th Std', '12th Std'));

//Canteen Allowance per day
define("Canteen_Allowance_Per_day", "60"); //in Rs.
define("Current_Interest_Rate_In_PF", "8.50%"); 


// Mail Configuration
define("SMTP_AUTH",true);
define("SMTP_PROTOCAL",'tls');
define("MAIL_USERNAME",'eakadamik');
define("MAIL_PASSWORD",'e@k@D@m1k1');

define("MAIL_HOST",'smtp.cdac.in');
define("MAIL_PORT",'587');

define("MAIL_FROM",'eakadamik@cdac.in');

define("MAIL_FROM_LABEL",'eMulazim (C-DAC, Mohali)');
define("emulazim_link_cdac",'http://10.228.1.58/');
define("emulazim_link_outside",'http://emulazim.in');
define("emulazim_lable",'eMulazim (File Tracking & Inventory Management System)');
//define("MAIL_FORGET_PASSWORD_SUBJECT",'Recover Your Password');
//define("MAIL_FORGET_VERIFICATION_TIME",'20'); // In Minutes 
