<?php
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');?>
<select class="form-control select" required="required"  name='language' onchange="getimage1(this.value)">
	<option value="">Select Language</option>
<? if(isset($_REQUEST['id'])){
$sql_fields=select_query_json("select * from SUPMAIL_PROCESS_LANGUAGE a where a.PRCSNO='".$_REQUEST['id']."'","Centra",'TEST');
for($i=0;$i<count($sql_fields);$i++){

?>

                                               
                                      <option value='<?=$sql_fields[$i]['LANGCOD']?>'><?=$sql_fields[$i]['LANGNAM']?></option>         
                                           
									   

                                    <?php } }?> </select>
