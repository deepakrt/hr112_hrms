<?php
$this->title= 'Add Holidays of Full Calendar Year';

use yii\widgets\ActiveForm;

$masterHolidayTypes = Yii::$app->hr_utility->hr_get_master_holiday_type(NULL);
date_default_timezone_set('Asia/Kolkata');
               
//echo "<pre>"; print_r($masterHolidayTypes); die;
?>
<style>
    .col-sm-4{margin-bottom: 15px;}
</style>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-4">
    
        <label>Year</label>

        <select class="form-control form-control-sm col-12" id="yearofcalendar" name="Holidays[year][]" required="">
            <option value=''>Select Year</option>
            <?php 
            $cYr = date('Y');
            $Y = Yii::$app->utility->encryptString($cYr);
            echo "<option value='$Y'>$cYr</option>";
//            $curYr = date('Y', strtotime('+1 year'));            
//            $yrss = $curYr-3;
//            for($i=$curYr;$i>=$yrss;$i--){
//                $id = Yii::$app->utility->encryptString($i);
//                echo "<option value='$id'>$i</option>";
//            }
            ?>
        </select>
    
   </div>
    <div class="col-6">
        <label>Holiday Type</label>
        <select class="form-control form-control-sm col-8" id="holidaytype" name="HolidayChart[holiday_type][]" required="">
            <option value=''>Select Holiday Type</option>
            <?php 
            if(!empty($masterHolidayTypes)){
                foreach($masterHolidayTypes as $type){
                    if($type['is_active'] == 'Y'){
                        $Hl_id = Yii::$app->utility->encryptString($type['Hl_id']);
                        $name = $type['description']." (".$type['Holiday_type'].")";
                        echo "<option value='$Hl_id|$name' >$name</option>";
                    }
                }
            }
            ?>
        </select>
    </div>
    <div class="col-4">
        <label>Date</label>
        
       
           <input type="date" data-date-inline-picker="true" id="date" class="form-control form-control-sm"  
           name="date[]" /> 
        
    </div>
    <div class="col-4">
        <label>Holiday Name</label>
        
       
           <input type="text" id="name" class="form-control form-control-sm"  
           name="name[]" placeholder="Name" /> 
        
    </div>
   
    <div class="col-4">
       <input type="button" class="btn btn-info btn-sm lftlink mt-4 p-1 px-3" id="addHolidayRow" value="Add" />
    </div>
    <div class="col-12 mt-3">
        <table class="table">
            <tr><th>Holiday Type</th><th>Holiday Date</th><th>Holiday Name</th><th>Action</th></tr>
            <tbody id="addedleaveContainer">
                
            </tbody>






        </table>
    </div>
   
    <div class="col-sm-12 text-center">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit" />
        <a href='<?=Yii::$app->homeUrl?>admin/manageleaves?securekey=<?=$menuid?>' class="btn btn-danger btn-sm">Back</a>
        <input type="hidden" id="menuid" value="<?=$menuid?>" name="menuid">
        
    </div>
</div>

<?php 


ActiveForm::end(); ?>


<script>

    
    $(document).ready(function(){
        var i=0;
        
var holidayArray = [];
      
        $(document).on('click','.btn_remov',function(){

            var button_id = $(this).attr("id");
            
            $("#row"+button_id+"").remove();
        });
        $('#addHolidayRow').click(function(){
           // $("#addedleave").html('');
          var dateval = $('#date').val();
            var holidaytype = $('#holidaytype').val();


var wresult = holidaytype.split('|');


holidaytype = wresult[0];
var holidaytypename = wresult[1];


            var menuid = $('#menuid').val();
               var name = $('#name').val();
              var year =   $('#yearofcalendar').val();
              
              var al = holidayArray.length;
              
              for(var j=0;j<al;j++){
                
                if(holidayArray[j] == dateval){
                    showError('Error: '+dateval+' is already exist!'); 
                           
                            return false;
                }
              }
              holidayArray.push(dateval);    
              

            if(dateval && holidaytype){
                var url = BASEURL+"admin/manageleaves/checkholiday?securekey="+menuid+"&key="+holidaytype+"&dateval="+dateval+"&year="+year+"&name="+name;

                $.ajax({
                    type: "GET",
                    url: url,
                    
                    
                    success: function(data){

                        //0 : record already there
                        //1:  records are clear for insertion
                        if(data==1){

                            //$('#display_error').html('');
                            $('#addedleaveContainer').append('<tr id="row'+i+'"><td><input type="hidden" name="Holidays[sholidaytype][]" id="typefieldactual'+i+'" value="holidaytype"><input type="text" class="form-control" id="typefield'+i+'"  readonly="readonly"></td><td><input  class="form-control" type="text" id="datafield'+i+'" name="Holidays[sdate][]" readonly="readonly"></td><td><input type="text" name="Holidays[sname][]" id="sname'+i+'" value="name" class="form-control" readonly="readonly"></td><td><button id="'+i+'" class="btn_remov btn btn-danger">X</button></td></tr>');
          
                                $("#datafield"+i+"").attr("value", dateval).append();
                                
                                $("#typefield"+i+"").attr("value", holidaytypename).append();
                                $("#typefieldactual"+i+"").attr("value", holidaytype).append();
                                 $("#sname"+i+"").attr("value", name).append();

                                i++;  
                                
                                                

                               
                            return false;
                           
                        }else{
                            showError('Error: '+dateval+' is already exist!'); 
                           
                            return false;
                        }
                    },
                    error:function(err){

                        console.log(err.responseText);
                    }
                });
            }
        });
    });
</script>