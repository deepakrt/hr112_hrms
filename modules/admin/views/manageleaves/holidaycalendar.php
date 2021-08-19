<?php
$this->title= 'Holiday Calendar';
$url =Yii::$app->homeUrl."admin/manageleaves/createholidaycalendar?securekey=$menuid";
date_default_timezone_set('Asia/Kolkata');
?>

<div class="row">
    <div class="col-6">
        <div class="row">
        <div class="col-10">
        <label>Select Year</label>
    <select onchange="callHolidays(this.value)" class="form-control form-control-sm" id="holidaytype" name="HolidayChart[holiday_type][]" required="">
            <option value=''>Select Year</option>
            <option value='2021' selected="selected">2021</option>
        </select>
    </div>
    <div class="col-2">
        <!-- <button class="btn btn-success btn-sm mybtn mt-4 px-3">View</button> -->
    </div>
</div>
    </div>
    <div class="col-6 text-right">
    <a href="<?=$url?>" class="btn btn-success btn-sm mybtn">Add Holidays Calendar</a>
</div>
</div>
<hr>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            
            <th>Date</th> 
            <th>Day</th>
            <th>Month</th>             
            <th>Leave Type</th>  
            <th>Name</th>
            <th>Is Active</th>              
                      <input type="hidden" id="menuid" value="<?=$menuid?>" name="menuid">
        </tr>
    </thead>
    <tbody>
    <?php
    /*echo '<pre>';
    print_r($HolidaysList);
    die;*/
    if(!empty($HolidaysList)){
        $i=1;
        foreach($HolidaysList as $holiday){
//            $lc_id = Yii::$app->utility->encryptString($leave['lc_id']);
//            $viewUrl = Yii::$app->HomeUrl."admin/manageservicedetail/view?securekey=$menuid&securecode=$code";
             $year = $holiday['H_year'];
           
           
          // print_r($holiday);
            $notact = "";
            $is_active = "Yes";
           if($holiday['is_active'] == 'N'){
                $is_active = "<span >No</span>";
                $notact = "style='background-color:#f7e2dd;'";
            }
          
        ?>
        <tr <?=$notact?> >
            <td><?=$i?></td>
           
            
           
            <td><?=$holiday['H_Date']?></td>
            <td>
                
                <?=date("l", strtotime($holiday['H_Date']))?>

            </td>
            <td>
                
                <?=date("F", strtotime($holiday['H_Date']))?>

            </td>
            <td><?=$holiday['description'];?></td>
            <td><?=$holiday['H_name'];?></td>
            <td><?=$is_active?></td>
        </tr>
        <?php $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Year</th>
            <th>Date</th> 
            <th>Month</th>             
            <th>Leave Type</th>
            <th>Name</th>
            <th>Is Active</th>               
                      
        </tr>
    </tfoot>
</table>

<script>
    function callHolidays(values){
      
      
var menuid = $('#menuid').val();

        var url = BASEURL+"admin/manageleaves/holidaycalendar?securekey="+menuid+"&key="+values;

                $.ajax({
                    type: "GET",
                    url: url,
                    
                    
                    success: function(data){

                        //0 : record already there
                        //1:  records are clear for insertion
                        if(data==1){

               
                               
                            return false;
                           
                        }else{
                            showError('Error: Something went wrong!'); 
                           
                            return false;
                        }
                    },
                    error:function(err){

                        console.log(err.responseText);
                    }
                });
           
    }
    </script>