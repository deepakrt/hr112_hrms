<?php 
$this->title = "Add New Proposal";
?>
<script>
    $(document).ready(function(){
        $(".projectlist-start_date").prop("disabled", true);
        $(".projectlist-end_date").prop("disabled", true);
    });
</script>
<?= $this->render('_form', [
        'model' => $model,        
        'menuid' => $menuid,
    ]) ?>

