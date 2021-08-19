<div class="text-right">
    <button type="button" class="btn btn-secondary btn-sm btnxs" value="H" id="leavebalance">Leave Balance</button>
</div>
<span id="leavebalances" style="display: none;">
    <table class="table table-bordered">
        <tr>
            <th>Leave Type</th>
            <th>Balance</th>
            <th>Pending</th>
            <th>Available</th>
            <th>Leave Card</th>
        </tr>
        <?php 

        if(!empty($lcards)){
            foreach($lcards as $lcard){                
                $desc= $lcard['desc'];
                $pending= $lcard['pending_leaves'];
                $balance = $lcard['balance_leaves']+$pending;
                $avail = number_format($lcard['balance_leaves'],1);
                $balance = number_format($balance,1);
                echo "
                    <tr>
                        <td>$desc</td>
                        <td>$balance</td>
                        <td>$pending</td>
                        <td>$avail</td>
                        <td><a href='javascript:void(0)' onclick='leavecarddetail($lcard[leave_type]);'><img src=".Yii::$app->homeUrl.'images/view.png'." style='width: 23px;'/></a></td>
                    </tr>
                ";
            }
        }
        ?>
    </table>
</span>