<?php
require_once './mpdf/mpdf.php';
//$mpdf = new mPDF(['mode'=>'utf-8','format'=>'utf-8',[500,600]]);
$mpdf = new mPDF();

$mpdf->WriteHTML("First Time Noting Data");
$mpdf->AddPage();
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetImportUse();
$path = getcwd() . '/9.pdf';
$pagecount = $mpdf->SetSourceFile($path);
//echo $pagecount; die;
for ($i=1;$i<=$pagecount;$i++)
{
$import_page = $mpdf->ImportPage($i);
$mpdf->UseTemplate($import_page);

        if ($i < $pagecount)
            $mpdf->AddPage();
}

/* Step -II Add Noting Here */
$mpdf->AddPage();
$mpdf->WriteHTML("Second Time Noting Data");
$path1 = getcwd() . '/SanctionLetter.pdf';
/* Step -III Upload Other Docs Here */
$mpdf->AddPage();
$pagecount = $mpdf->SetSourceFile($path1);

for ($i=1;$i<=$pagecount;$i++)
{
$import_page = $mpdf->ImportPage($i);
        $mpdf->UseTemplate($import_page);

        if ($i < $pagecount)
            $mpdf->AddPage();
}


$mpdf->Output();


?>
