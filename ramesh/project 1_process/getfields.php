<?php
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');?>
<div class="form-group" style="padding:10px" id="fields">
<? 
if(isset($_REQUEST['id'])){
$process=explode(':',$_REQUEST['id']);
$processno=$process[0];
$processyear=$process[1];	
$sql_fields=select_query_json("select * from SUPMAIL_PROCESS_FIELD a where a.PRCSNO='".$processno."' and a.PRCSYR='".$processyear."' and a.DELETED='N' order by a.FIELDNM asc","Centra",'TEST');
for($i=0;$i<count($sql_fields);$i++){

?>

									   <label class="col-md-3 col-xs-12 control-label"><?=strtoupper($sql_fields[$i]['FIELDNM'])?></label>
									   <input type='hidden' name='field_name[]' value='<?=strtoupper($sql_fields[$i]['FIELDNM'])?>'/>
									    <input type='hidden' name='field_no[]' value='<?=strtoupper($sql_fields[$i]['FIELDNO'])?>'/>
                                        <div class="col-md-9 col-xs-12">  
                                        <? if(strtoupper($sql_fields[$i]['FIELDTY'])=='DATE'){?>  
                                        	<input type='text' id='datepicker-example' name='field_val[]'  value='' class='form-control' style='text-transform:uppercase;'>
                                        	<?}else{?>                                                                                    
                                            <input type='text' name='field_val[]'  value='' class='form-control' style='text-transform:uppercase;'><?}?>
                                          
                                        </div><br><br><br><br>

                                    <?php } }?></div>    

 <script>
    $('#datepicker-example').Zebra_DatePicker({
     
      format: 'd-M-Y'
     
    });

    

   </script>