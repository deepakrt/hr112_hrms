<?php
$this->title= 'Technology Master';
$url =Yii::$app->homeUrl."admin/managetrainings/addnewtechnology?securekey=$menuid";

?>
<div class="text-right">
    <a href="<?=$url?>" class="btn btn-success btn-sm mybtn">Add New Entry</a>
</div>
<hr>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th style="width:5%">Sr.</th>
            <th style="width:25%">Tehnology Name</th>         
            <th style="width:25%">Technology Code</th>            
            <th style="width:15%">Added On</th> 
            <th style="width:10%">Is Active</th>  
            <th style="width:20%">Action</th>                  
        </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($technologies)){

        $i=1;
        foreach($technologies as $technology){
            
            $technology_id = $technology['technology_id'];

           $delete_url =Yii::$app->homeUrl."admin/managetrainings/removetechnology?securekey=$menuid&technology_id=$technology_id&check=delete";
           $activate_url =Yii::$app->homeUrl."admin/managetrainings/activatechnology?securekey=$menuid&technology_id=$technology_id";
           $edit_url =Yii::$app->homeUrl."admin/managetrainings/updatetechnology?securekey=$menuid&technology_id=$technology_id";
            $updtd ="-";
            if(!empty($technology['created_date'])){
                $updtd = date('d-m-Y H:i:s', strtotime($technology['created_date']));
            }
            $notact = "";
            $is_active = "Yes";
            if($technology['active'] == '0'){
                $is_active = "<span>No</span>";
                $notact = "style='background-color:#f7e2dd;'";
            }
        ?>
        <tr <?php echo $notact;?> >
            <td><?php echo $i;?></td>
            <td <?php echo $notact;?>><?php echo $technology['technology_name'];?></td>
            <td><?php echo $technology['technology_code'];?></td>
            <td><?php echo date('d-m-Y H:i:s', strtotime($technology['created_date']));?></td>
            <td><?=$is_active?></td>
                <td>
                    <?php  if($technology['active'] == '0'){?>

                        <a title="Activate this record" class="linkcolor" href="<?php echo $activate_url;?>">Activate</a>

                    <?php }else{?>
                    <a title="Delete this record" class="linkcolor deleteallow1" href="<?php echo $delete_url;?>">DeActivate</a>
                <?php }?>
                <a title="Delete this record" class="linkcolor" href="<?php echo $edit_url;?>">Edit</a>
                </td>
        </tr>
        <?php $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Tehnology Name</th>         
            <th>Technology Code</th>            
            <th>Added On</th> 
            <th>Is Active</th>          
        </tr>
    </tfoot>
</table>