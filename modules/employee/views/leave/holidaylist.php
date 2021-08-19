<?php
$this->title= 'Holiday Calendar';
$url =Yii::$app->homeUrl."admin/manageleaves/createholidaycalendar?securekey=$menuid";
date_default_timezone_set('Asia/Kolkata');
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-6">
        <div class="row">
        <div class="col-10">
        <label>Select Year</label>
    <select onchange="callHolidays(this.value)" class="form-control form-control-sm" id="holidaytype" name="HolidayChart[holiday_type][]" required="">
            <option value='<?=$years;?>' selected="selected"><?php echo $years;?></option>
            <option value='2020' >2020</option>
            <option value='2019'>2019</option>
            <option value='2018'>2018</option>
            
        </select>
    </div>
   
</div>
    </div>
    <div class="col-6 text-right">
    
</div>
<hr>

<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            
            <th>Date</th> 
            <th>Day</th>
                     
            <th>Leave Type</th>  
            <th>Name</th>
                      
                      
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
            
            <td><?=$holiday['description'];?></td>
            <td><?=$holiday['H_name'];?></td>
            
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
                     
            <th>Leave Type</th>
            <th>Name</th>
                        
                      
        </tr>
    </tfoot>
</table>
<input type="hidden" id="menuid" value="<?=$menuid?>" name="menuid">
<?php 


ActiveForm::end(); ?>
<script>
    function callHolidays(values){
      
        var menuid = $('#menuid').val();
        var url = BASEURL+"employee/leave/holidaylist?securekey="+menuid+"&key="+values;
        window.location = url;
        exit();
               
    }
    </script>