<?php
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');?>
<div class="form-group" style="padding:10px" id="fields">
<? if(isset($_REQUEST['id'])){
$sql_fields=select_query_json("select * from SUPMAIL_PROCESS_FIELD a where a.PRCSNO='".$_REQUEST['id']."'","Centra",'TEST');
for($i=0;$i<count($sql_fields);$i++){

?>

									   <label class="col-md-3 col-xs-12 control-label"><?=$sql_fields[$i]['FIELDNM']?></label>
									   <input type='hidden' name='field_name[]' value='<?=$sql_fields[$i]['FIELDNM']?>'/>
                                        <div class="col-md-9 col-xs-12">    
                                        	<input type='text' name='field_val[]'  value='' class='form-control' style='text-transform:uppercase;'>                                                                                    
                                            
                                          
                                        </div><br><br><br><br>

                                    <?php } }?></div>    

