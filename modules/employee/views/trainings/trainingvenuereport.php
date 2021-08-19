<style type="text/css">
    #table_detail
    {
        width: 100%;
        /*width:500px;*/
        text-align:left;
        border-collapse: collapse;
        color:#2E2E2E;
        border:#A4A4A4;
    }
    #table_detail tr:hover
    {
        background-color:#F2F2F2;
    }
    #table_detail .hidden_row
    {
        display:none;
    }
    #table_detail .hidden_row2
    {
        display:none;
    }

    #table_detail .hidden_innerrow
    {
        display:none;
    }

    .modal-lg {
      width: 100% !important;
      margin: auto;
      margin-top:20px;
    }

    .trngdata table {
      text-align: left;
      position: relative;
      border-collapse: collapse; 
    }
    
     .btn-primary {
        color: #fff;
        background-color: #1b8aac !important;
        border-color: #0062cc;
    }

    .btn-primary:hover {
        background: #000;
        font-weight: bold;
        color: #fff !important;
    }    

    .trngdata th {
      background: white;
      position: sticky;
      top: 0; /* Don't forget this, required for the stickiness */
      box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    }

    .modal-header {
        background: #ab0501;
        padding: 6px 4px 6px 20px;
        color:#fff;
    }
    #modalLongTitle {
        font-weight: bold;
    }

    .modal-lg {
        max-width: 1024px;
    }
</style>

<?php
    $this->title= 'Training Venue Report';
?>

<div id="wrapper">

    <table border=1 id="table_detail" align=center cellpadding=10>

        <tr>
            <th>Sr.</th>
            <th>Training Center</th>
            <th>Trainees</th>
            <th colspan="2">Attended</th>
        </tr>


        <?php
            // recursion  
            function munishSearch($array, $key, $value)
            {
                $results = array();

                if (is_array($array)) {
                    if (isset($array[$key]) && $array[$key] == $value) {
                        $results[] = $array;
                    }

                    foreach ($array as $subarray) {
                        $results = array_merge($results, munishSearch($subarray, $key, $value));
                    }
                }

                return $results;
            }
/*            echo "<pre>"; 
                print_r($get_repo_data);
            echo "<pre>"; 
*/
            // die();

            if(!empty($get_repo_data))
            {

                $i=1;
                $prv=1;
                foreach($get_repo_data as $ky=>$reportData)
                {
                    $reportData = (object)$reportData;
                    
                    /*echo "<pre>"; 
                    print_r($reportData);
                    echo "</pre>";*/ 

                     // die();

                    if(isset($reportData->venueID))
                    {
                        $venueID = $reportData->venueID;

                        $total_emp_venue_wise = $reportData->total_emp_venue_wise;
                    ?>
                        <tr id="master_row_ID_<?=$venueID;?>" onclick="show_hide_row('hidden_row<?=$venueID?>');" style="    cursor: pointer;">
                            <td><?=$prv;?></td>
                            <td style="color:blue; text-decoration: underline; " id="venue_name<?=$venueID?>"><?=$reportData->Venue;?></td>
                            <td><?=$total_emp_venue_wise;?></td>
                            <td><?=$total_emp_venue_wise;?></td>
                        </tr>

                        <?php
                            // $tpms
                            // $getRc = munishSearch($tpms, 'parent_id', $venueID);


                            $getAllCode = $reportData->course_id;

                             /*echo "<pre>"; 
                                print_r($getAllCode);
                            echo "<pre>-----"; 
                             die();*/
                        ?>

                        <tr id="hidden_row<?=$venueID?>" class="hidden_row">    
                            <td colspan=4>
                                <table border=1 id="table_detail" align=center cellpadding=5>
                                    <thead>                            
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Districty Name</th>
                                            <th>Total Trainees</th>
                                            <th>Attended</th>
                                            <th>Pending</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                           /* echo "<pre>"; 
                                                print_r($getAllCode);
                                            echo "<pre>"; 
                                             die();*/
                                            if(!empty($getAllCode))
                                            {
                                                $rpv=1;
                                                foreach($getAllCode as $kycd=>$courseID)
                                                {
                                                    $course_wiseNM = 0; // distwiseNM
                                                    $course_wiseTTLEMP = 0; // distwiseTTLEMP
                                                    $course_wiseTTLATTN = 0; // distwiseTTLATTN
                                                    $course_wiseTTLPEN = 0; // distwiseTTLPEN
                                                    $courseID = $courseID;

                                                    if(isset($reportData->$kycd))
                                                     {
                                                        $coursedta = (object)$reportData->$kycd;
                                                        
                                                        $course_wiseNM = $coursedta->course_name; // distwiseNM
                                                        $course_wiseTTLEMP = $coursedta->cntemployee_code; // distwiseTTLEMP
                                                        $course_wiseTTLATTN = $course_wiseTTLEMP;  // distwiseTTLATTN
                                                        $course_wiseTTLPEN = 0; //  distwiseTTLPEN          
                                                    }   
                                            ?>
                                                <tr id="row_ID_<?=$kycd;?>" style="cursor: pointer;">
                                                    <td>
                                                        <?php echo $rpv;?>
                                                    </td>
                                                    <td id="course_name<?=$venueID?><?=$courseID?>"><?=$course_wiseNM;?></td>            
                                                    <td><?=$course_wiseTTLATTN;?></td>            
                                                    <td id="total_rc<?=$venueID?><?=$courseID?>"><?=$course_wiseTTLATTN;?></td>
                                                    <td><?=$course_wiseTTLPEN;?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".exampleModalCenter" onclick="show_data_inmodel('<?=$venueID?>','<?=$courseID?>',1);">View</button>
                                                        
                                                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".exampleModalCenter" onclick="show_hide_innerrow('hidden_innerrow<?=$venueID?><?=$kycd?>');">View</button> -->
                                                    </td>
                                                </tr>

                                                <?php $rpv++;
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    <?php
                     $prv++;
                    }
                    elseif(isset($get_repo_data['total_venues']))
                    {
                        ?>
                            <tr id="master_row_ID_total_venues" onclick="show_hide_row('hidden_rowtotal_venues');" style="    cursor: pointer;">
                                <td colspan="2" style="text-align:center;">All Total</td>
                                <td><?=$get_repo_data['total_venues'];?></td>
                                <td><?=$get_repo_data['total_venues'];?></td>
                            </tr>
                        <?php
                    }

                }
            }
        ?>

    </table>
    <hr>
    <div col-md-12><h4>2nd Phase</h4></div>

  <table border=1 id="table_detail" align=center cellpadding=10>

        <tr>
            <th>Sr.</th>
            <th>Training Center</th>
            <th>Trainees</th>
            <th colspan="2">Attended</th>
        </tr>


        <?php
            

            if(!empty($get_repo_data2))
            {

                $i=1;
                $prv=1;
                $totalrec=0;
                foreach($get_repo_data2 as $ky=>$reportData)
                {
                    $reportData = (object)$reportData;
                    
                    // echo "<pre>"; 
                    // print_r($reportData);
                    // echo "</pre>"; 

                     // die();

                    if(isset($reportData->venueID))
                    {
                        $venueID = $reportData->venueID;

                         $total_emp_venue_wise = $reportData->total_emp_venue_wise;
                         $totalrec=$totalrec+$reportData->total_emp_venue_wise;

                    ?>
                        <tr id="master_row_ID2_<?=$venueID;?>" onclick="show_hide_row2('hidden_row2<?=$venueID?>');" style="    cursor: pointer;">
                            <td><?=$prv;?></td>
                            <td style="color:blue; text-decoration: underline; " id="venue_name<?=$venueID?>"><?=$reportData->Venue;?></td>
                            <td><?=$total_emp_venue_wise;?></td>
                            <td><?=$total_emp_venue_wise;?></td>
                        </tr>

                        <?php
                            // $tpms
                            // $getRc = munishSearch($tpms, 'parent_id', $venueID);


                            $getAllCode = $reportData->course_id;

                             /*echo "<pre>"; 
                                print_r($getAllCode);
                            echo "<pre>-----"; 
                             die();*/
                        ?>

                        <tr id="hidden_row2<?=$venueID?>" class="hidden_row2">    
                            <td colspan=4>
                                <table border=1 id="table_detail" align=center cellpadding=5>
                                    <thead>                            
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Districty Name</th>
                                            <th>Total Trainees</th>
                                            <th>Attended</th>
                                            <th>Pending</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                           /* echo "<pre>"; 
                                                print_r($getAllCode);
                                            echo "<pre>"; 
                                             die();*/
                                            if(!empty($getAllCode))
                                            {
                                                $rpv=1;
                                                foreach($getAllCode as $kycd=>$courseID)
                                                {
                                                    $course_wiseNM = 0; // distwiseNM
                                                    $course_wiseTTLEMP = 0; // distwiseTTLEMP
                                                    $course_wiseTTLATTN = 0; // distwiseTTLATTN
                                                    $course_wiseTTLPEN = 0; // distwiseTTLPEN
                                                    $courseID = $courseID;

                                                    if(isset($reportData->$kycd))
                                                     {
                                                        $coursedta = (object)$reportData->$kycd;
                                                        
                                                        $course_wiseNM = $coursedta->course_name; // distwiseNM
                                                        $course_wiseTTLEMP = $coursedta->cntemployee_code; // distwiseTTLEMP
                                                        $course_wiseTTLATTN = $course_wiseTTLEMP;  // distwiseTTLATTN
                                                        $course_wiseTTLPEN = 0; //  distwiseTTLPEN          
                                                    }   
                                            ?>
                                                <tr id="row_ID_<?=$kycd;?>" style="cursor: pointer;">
                                                    <td>
                                                        <?php echo $rpv;?>
                                                    </td>
                                                    <td id="course_name<?=$venueID?><?=$courseID?>"><?=$course_wiseNM;?></td>            
                                                    <td><?=$course_wiseTTLATTN;?></td>            
                                                    <td id="total_rc<?=$venueID?><?=$courseID?>"><?=$course_wiseTTLATTN;?></td>
                                                    <td><?=$course_wiseTTLPEN;?></td>

                                                    <td>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".exampleModalCenter" onclick="show_data_inmodel('<?=$venueID?>','<?=$courseID?>',2);">View</button>
                                                        
                                                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".exampleModalCenter" onclick="show_hide_innerrow('hidden_innerrow<?=$venueID?><?=$kycd?>');">View</button> -->
                                                    </td>
                                                </tr>

                                                <?php $rpv++;
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    <?php
                     $prv++;
                    }
                    elseif(isset($get_repo_data2['total_venues']))
                    { 

                        ?>
                            <tr id="master_row_ID_total_venues" onclick="show_hide_row2('hidden_rowtotal_venues');" style="    cursor: pointer;">
                                <td colspan="2" style="text-align:center;">All Total</td>
                                <td><?=$totalrec;?></td>
                                <td><?=$totalrec;?></td>
                            </tr>
                        <?php
                    }

                }
            }
        ?>

    </table>


</div>


<!-- Modal -->
<div class="modal fade exampleModalCenter" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalDataDivDisp">       
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">



    function show_hide_row(row)
    {
        $(".hidden_row").hide();
        $(".hidden_innerrow").hide();
        $("#"+row).toggle();
    }
     function show_hide_row2(row)
    { 
        $(".hidden_row2").hide();
        $(".hidden_innerrow2").hide();
        $("#"+row).toggle();
    }

    function show_hide_innerrow(row)
    {
        $(".hidden_innerrow").hide();
        $("#"+row).toggle();
    }  

    function show_data_inmodel(venueID,crs_code,tech_id,dist_id=null) 
    {
        //alert(tech_id);
        $('#modalDataDivDisp').html('');
        if(venueID != '' && crs_code != '')
        {

            if(dist_id == '')
            {
                dist_id = null;
            }

            var venue_name = $('#venue_name'+venueID).html();
            var course_name = $('#course_name'+venueID+''+crs_code).html();
            var total_rc = $('#total_rc'+venueID+''+crs_code).html();
            var technog_id=tech_id;

            $('#modalLongTitle').html('');



            $('#modalLongTitle').html('Vanue:'+venue_name+' || Course Name:'+course_name+' || Total Records:'+total_rc);

            startLoader();
             $.ajax({
                url: "<?php echo Yii::$app->homeUrl."employee/trainings/gettraningdatavenuwise?securekey=$menuid";?>",
                type: 'POST',
                data: { venueID:venueID,crs_code:crs_code,dist_id:dist_id,tech_id:technog_id},
                dataType: 'JSON',
                success: function (data) 
                {
                    // alert(data);
                    // $('#datashow').html(data);
                    
                    $('#modalDataDivDisp').html(data.traning_data);
                    
                    stopLoader();
                    
                }
            });
        }
    }    

    function startLoader()
    {
       $("#loading").show();
    }
     
    function stopLoader()
    {
        $("#loading").hide();
    }
</script>