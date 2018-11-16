<?php
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');?>
 <table style="width:100%" class="contact" style="padding:10px">
<? if(isset($_REQUEST['id'])){
$sql_fields=select_query_json("select * from SUPMAIL_PROCESS_FIELD a,SUPMAIL_PROCESS_VALUE b where a.PRCSNO='".$_REQUEST['id']."' and b.PRCSNO='".$_REQUEST['id']."' and a.FIELDNM=b.FIELDNM","Centra",'TEST');
for($i=0;$i<count($sql_fields);$i++){

?>

                      
                      <tr>
                        <td align="left" style="padding:10px" width='30%'><?=$sql_fields[$i]['FIELDNM']?><span class="pull-right">:</span></td>
                        <td align="left" style="padding:10px" width='70%'><?=$sql_fields[$i]['FIELDVAL']?></td>                     
                      </tr>
                       
                      
               

                                    <?php } }?> </table>

