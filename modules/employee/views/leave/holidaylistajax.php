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