<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('/lib/config.php');
include_once('/lib/function_connect.php');
include_once('/general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

//$sql_emprights = select_query_json("select * from tlu_employee_rights where deleted = 'N' and empsrno = '".$_SESSION['tcs_empsrno']."'", "Centra", "TCS");
$_SESSION['tlu_emp_prhdcod'] = 1;
if(count($sql_emprights) > 0) {
    $_SESSION['tlu_emp_prhdcod'] = $sql_emprights[0]['PRSCODE'];
}
// echo "**".$_SESSION['tlu_emp_prhdcod']."**";
// echo "........".$_SESSION['tcs_tlumenu_prev_prscode'].".......".$_SESSION['tcs_tlumenu_prscode']."........";

if(isset($_REQUEST['updatetrack'])){
  if($_FILES['upload']['name'] != '')
      { 
        
        ///----------updating the index to attachment to local
       echo $q=$_FILES['upload']['name'];
        
        $tmp_name = $_FILES["upload"]["tmp_name"];       
        
      
        // echo "\n".$name."\n";
        $a1local_file = "../uploads/purchase_order/".$q;
        move_uploaded_file($tmp_name, $a1local_file);

       
      }
    $currentdate = strtoupper(date('d-M-Y h:i:s A'));
    $count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
    
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNECODE']=$_REQUEST['znecode'];
    $g_fld['ZNEPCDE']=$_REQUEST['znepcode'];
    $g_fld['ZNEDAYS']=$_REQUEST['znecode']+1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_SESSION['tcs_empsrno'];
    $g_fld['REMARKS']=$_REQUEST['remarks'];
    $g_fld['ADDUSER']=$_SESSION['tcs_empsrno'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    
    $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
    
    $up_table="ORDER_TRACKING_DETAIL";
    $up_fld=array();
    $up_fld['EDTUSER']=$_SESSION['tcs_empsrno'];
    $up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $up_fld['REMARKS']=$_REQUEST['remarks'];;
    $up_fld['ZNESTAT']='N';
    $where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".$_REQUEST['znecode']."' AND ZNEPCDE='".$_REQUEST['znepcode']."'";
     
     $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Purchase Order Track Board :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">
<style type="text/css">
  .red_clr { font-size: 16px; }
  .blue_clr { font-size: 16px; }

    .comment-frame {
        background-color: #f0eeee;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.23);
        box-shadow: 0 1px 2px rgba(0,0,0,.23);
        margin: 4px 4px 12px 0;
    }
    .comment-box {
        position: relative;
    }
    .comment-box {
        position: relative;
    }
    .comment-box-input {
        background: none;
        height: 75px;
        margin: 0;
        min-height: 75px;
        padding: 9px 11px;
        padding-bottom: 0;
        width: 100%;
        resize: none;
    }
    .comment-box-options {
        position: absolute;
        bottom: 20px;
        right: 6px;
    }
    .comment-box-options-item {
        border-radius: 3px;
        float: left;
        height: 18px;
        margin-left: 4px;
        padding: 6px;
    }
    .fa-at:before {
        content: "\f1fa";
    }
    .content-frame-body{
      overflow-x : scroll;
      padding-right:50px;
    }
    .list_view{
      min-width:3300px;
    }
    .nonadv_list{
      width:500px;
      margin:5px 10px;
      float:left;
    }
    .tasks{
       height: 740px;
      /* overflow-y: scroll;*/
       padding: 2px 5px;

     }
     .nonadv_list{
       border:solid 1px #3e4095;
       border-radius:2px;
       padding-top:5px;
       box-shadow: 2px 5px 20px #8788a5;
     }
     .nonadv_list h3{
       border-bottom:solid 1px #3e4095;
       padding-bottom:25px;
       color:#ed3237
     }
     .nonadv_list .task-item{
       box-shadow: 0px 2px 10px #8788a5;
       margin-right: 10px;
     }
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }

    item.task-primary {
        border-left-color: #1b1e24;
    }

    .page-container .page-content .content-frame {
        background: #f5f5f5 url(../../images/3/bg1.jfif) center center no-repeat !important;
    }

    #gallery {
      margin-left: auto;
      margin-right: auto;
    }
    table{
      font-size:12px !important;
    }
</style>
<!-- END META SECTION -->
<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<link rel="stylesheet" type="text/css" href="css/social-buttons.css"/>
<style>

#main_wrapper{
    background: url('images/ap1.png') #FFFFFF;
}

#main_content{
    background-color: #000000;
    height: 5em;
    width: 5em;
}
.view_grid{
  margin: 0 13%;
}
@media only screen and (max-width: 980px) {
  .view_grid{
    margin: 0;
  }
}

</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- EOF CSS INCLUDE -->
</head>
<body>
    <div id="load_page" style='display:block;padding:12% 40%;'></div>
    <!-- START PAGE CONTAINER -->
    <div class="page-container page-navigation-toggled page-container-wide page-navigation-top-fixed">

        <!-- START PAGE SIDEBAR -->
        <div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide mCS_no_scrollbar mCS_disabled">
            <!-- START X-NAVIGATION -->
            <? include 'lib/app_left_panel.php';?>
            <!-- END X-NAVIGATION -->
        </div>
        <!-- END PAGE SIDEBAR -->

        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
            <? include "lib/app_header.php"; ?>
            <!-- END X-NAVIGATION VERTICAL -->

            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                <li><a href="home.php">Home</a></li>
                <li class="active">Purchase Order Track</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">


                <!-- START CONTENT FRAME BODY -->
                <div class="content-frame-body" style="margin-left:0px;">

                    <div class="list_view push-up-12" id='id_monitor_board' style="overflow-x:scroll">

                      <? $sql_ord_confirm = select_query_json("select distinct(sec.ZNECODE),sec.ZNEDAYS,mas.ZNENAME from order_tracking_section sec, order_tracking_master mas where sec.ZNECODE=mas.ZNECODE and (sec.EMPSRNO='".$_SESSION['tcs_empsrno']."' or sec.ALTSRNO='".$_SESSION['tcs_empsrno']."') and mas.ZNEMODE='R' and sec.DELETED='N'  order by sec.ZNECODE asc", "Centra", "TEST");
                          $tme = 0; $ttl_prscnt = '';
                          foreach ($sql_ord_confirm as $key => $ord_confirm_value) { $tme++; 
                            

                            ?>
                            <div class="nonadv_list">
                                <h3 class="text-center" style="color:#006622"><?=$ord_confirm_value['ZNENAME']?><br>
                                   <!--  <span class="pull-left blue_clr" style="font-size: 12px; padding-top: 8px; padding-left: 2px;"><? if($prs_time != '') { ?>Process Time : <?=$prs_time?><? } ?></span>
                                    <span class="pull-right blue_clr" style="font-size: 12px; padding-top: 8px; padding-right: 2px;"><? if($ttl_prscnt > 0) { ?>Count : <?=$ttl_prscnt?><? } ?></span> -->
								                </h3>
                                 <div id="list_head" style="background-color:#29a329;margin:0">
                                      <div class="row">
                                        <div class="col-md-12" style="padding:5px 0px;text-align:center">                                       
                                         
                                         <div class="col-md-1">
                                             <!--  <label >Sec</label> -->
                                          </div><!-- duration -->
                                          <div class="col-md-1">
                                              <label >Order No</label>
                                          </div><!-- duration -->
                                          <div class="col-md-2">
                                              <label>Supplier code</label>
                                          </div><!-- rate -->
                                          <div class="col-md-3">
                                              <label >Supplier</label>
                                          </div><!-- discount -->
                                          <div class="col-md-2">
                                              <label >City</label>
                                          </div><!-- amount -->
                                          <div class="col-md-1">
                                              <label >Qty</label>
                                          </div><!-- CGST -->
                                          <div class="col-md-2">
                                              <label >Val</label>
                                          </div><!-- SGST -->
                                       </div>
                                       
                                      </div> 
                                    </div> 
								<div class='tasks table' style="overflow-y:auto">
                   <?php 
                        $sql=select_query_json("select SUPCODE,PORNUMB,PORQTY,PORYEAR,ZNECODE,ZNEPCDE,REMARKS, to_char(PORDATE,'dd/MM/yyyy HH:mi:ss AM') PORDATE, to_char(POREDDT,'dd/MM/yyyy HH:mi:ss AM') POREDDT,PORVAL from order_tracking_detail where ZNECODE='".$ord_confirm_value['ZNECODE']."' and ZNESTAT='N' and DELETED='N'", "Centra", "TEST");
                        $t=0;foreach($sql as $key1 => $sql_val) {
                          $t++;
                          $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$sql_val['SUPCODE']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
                        ?>
                     <form name='purchase_form<?=$tme.$t?>'  id= 'purchase_form<?=$tme.$t?>' method='post' action=''> 
                     <div class='task-item' id='task_item<?=$tme.$t?>'>
                          <div class="row" style="background-color:#ffebcc;border:1px solid #ccc;margin:0px -10px;">
                                    <div class="col-md-12"  style="padding:5px 0px">
                                        <div class="form-group">
                                         <div class="col-md-1">
                                             <span style="font=size:12px;font-weight:bold;color:#ffebcc;cursor:pointer;border:1px solid #000;padding:2px 5px;background-color:#29a329"  onclick="selecttoggle('<?=$tme.$t?>')"> + </span>
                                          </div><!-- duration -->
                                          
                                          <div class="col-md-1">
                                              <label style="float:left !important"><?=$sql_val['PORNUMB']?></label>
                                          </div><!-- duration -->
                                          <div class="col-md-2 ">
                                              <label style="text-align:center"><?=$sql_val['SUPCODE']?></label>
                                          </div><!-- rate -->
                                          <div class="col-md-3">
                                              <label ><?=$supname[0]['SUPNAME']?></label>
                                          </div><!-- discount -->
                                          <div class="col-md-2">
                                              <label ><?=$supname[0]['CTYNAME']?></label>
                                          </div><!-- amount -->
                                          <div class="col-md-1">
                                              <label ><?=$sql_val['PORQTY']?></label>
                                          </div><!-- CGST -->
                                          <div class="col-md-2">
                                              <label ><?=number_format(($sql_val['PORVAL']/100000),2).' Lacs';?></label>
                                          </div><!-- SGST -->
                                       </div>
                                    </div>
                        </div> 
                        <?if($ord_confirm_value['ZNECODE']=='1'){?>
                         <div class="row" id='dropsec<?=$tme.$t?>' style='display:none'>
                              <div class="col-md-12">
                                        <table class='table table-hovered table-bordered tabledrop' style='border:1px solid #000;width:99%'>
                                        <thead>
                                                                   <tr class="darkgrey" style="background-color:#000">                           
                                                                    <th style='text-align:center'>PARTICULARS</th>
                                                                    <th style='text-align:center'>ACTUAL DUE DATE</th>
                                                                    <th style='text-align:center'>ACTUAL DAYS</th>
                                                                    <th style='text-align:center'>REQUIRED DAYS</th>
                                                                    <th style='text-align:center'>ACTUAL DATE</th>                                                      
                                                                 </tr>
                                         </thead>
                                         <tr>
                                        
                                        <td><?=$ord_confirm_value['ZNENAME']?></td>
                                        <td><?=$sql_val['PORDATE'];?></td>
                                        <td>45</td>
                                        <td><input type='text' name='znedays' style='width:50px'/> </td>
                                        <td><?=$sql_val['POREDDT'];?> </td>                       
                                        </tr>
                                              
                                   </table>

                                   <center><button class='btn btn-warning' type='button' onclick='submitform("purchase_form<?=$tme.$t?>")'>Finish</button></center><br>
                              </div>
                        </div> 

                        <input type='hidden' name='poryear' value='<?=$sql_val['PORYEAR']?>'/>
                         <input type='hidden' name='pornumb' value='<?=$sql_val['PORNUMB']?>'/>
                          <input type='hidden' name='znecode' value='<?=$sql_val['ZNECODE']?>'/>
                           <input type='hidden' name='znepcode' value='<?=$sql_val['ZNEPCDE']?>'/>
                           <input type='hidden' name='remarks' value='<?=$sql_val['REMARKS']?>'/>
                           <input type='hidden' name='supcode' value='<?=$sql_val['SUPCODE']?>'/>
                           <?}else{?>
                           	 <div class="row" id='dropsec<?=$tme.$t?>' style='display:none'>
                              <div class="col-md-12" >
                                        <table class='table table-hovered table-bordered tabledrop' style='border:1px solid #000;width:99%'>
                                        <thead>
                                                                   <tr class="darkgrey" style="background-color:#000">                           
                                                                    <th style='text-align:center'>PARTICULARS</th>
                                                                    <th style='text-align:center'>STATUS</th>
                                                                    <th style='text-align:center'>REMARKS</th>
                                                                    <th style='text-align:center'>UPLOAD</th>
                                                                                                                         
                                                                 </tr>
                                         </thead>
                                         <tr>
                                        
                                        <td style="width:25%"><?=$ord_confirm_value['ZNENAME']?></td>
                                        <td style="width:25%"><select class='form-group' name='status<?=$tme.$t?>' id='status<?=$tme.$t?>'>
                                        	<? 
                                          // $selopt=select_query_json("select ord.ZNEPNME,ord.ZNEPCDE from order_tracking_master ord,order_tracking_detail tra where ord.ZNECODE='".$ord_confirm_value['ZNECODE']."' and ord.ZNEMODE='R' and ord.DELETED='N' and tra.ZNECODE=ord.ZNECODE and tra.ZNESTAT='N' order by tra.ZNEPCDE asc", "Centra", "TEST");
                                          $selopt=select_query_json("select ord.ZNECODE,ord.ZNEPCDE,ord.ZNEPNME,tra.ZNECODE from order_tracking_master ord,order_tracking_detail tra where tra.ZNECODE='".$ord_confirm_value['ZNECODE']."' and tra.PORNUMB='".$sql_val['PORNUMB']."' and tra.PORYEAR='".$sql_val['PORYEAR']."' and tra.SUPCODE='".$sql_val['SUPCODE']."' and tra.ZNESTAT='N' and ord.ZNECODE=tra.ZNECODE and tra.ZNEPCDE=ord.ZNEPCDE and ord.ZNEMODE='R' and ord.DELETED='N' and tra.ZNECODE=ord.ZNECODE and (tra.ZNESTAT='N' or tra.ZNESTAT='T') order by tra.ZNEPCDE asc", "Centra", "TEST");
                                        	foreach($selopt as $key=>$seloptval){
                                        	?>
                                        	<option value='<?=$seloptval["ZNEPCDE"]?>' <?if($seloptval["ZNEPCDE"]==$sql_val['ZNEPCDE']){?>selected='selected'<?}?>><?=$seloptval["ZNEPNME"]?></option>
                                        	<?}?>

                                        </select> </td>
                                       
                                        <td style="width:25%"><input type='text' name='remarks<?=$tme.$t?>'  class='form-group' style="width:100%" onblur="setremarks(this.value,'<?=$tme.$t?>')"/> </td>
                                        <td style="width:25%"><input type='file' name='upload' accept="image/jpg,image/jpeg,image/png,.pdf" id='upload<?=$tme.$t?>' onchange="selectedfile(event,'<?=$tme.$t?>')" class='form-group' style="width:50px"/>
                                        <span id="filename<?=$tme.$t?>"></span> </td>                       
                                        </tr>
                                              
                                   </table>

                                   <center><button class='btn btn-primary' type='submit' name='updatetrack'>Submit</button>&nbsp;&nbsp;&nbsp; <button class='btn btn-warning' type='button' onclick='submitform("purchase_form<?=$tme.$t?>")'>Finish</button></center><br>
                              </div>
                        </div> 
                        
                        <input type='hidden' name='poryear' value='<?=$sql_val['PORYEAR']?>'/>
                         <input type='hidden' name='pornumb' value='<?=$sql_val['PORNUMB']?>'/>
                          <input type='hidden' name='znecode' value='<?=$sql_val['ZNECODE']?>'/>
                           <input type='hidden' name='znepcode' value='<?=$sql_val['ZNEPCDE']?>'/>
                           <input type='hidden' name='remarks' id='remarks<?=$tme.$t?>' value='<?=$sql_val['REMARKS']?>'/>
                           <input type='hidden' name='supcode' value='<?=$sql_val['SUPCODE']?>'/>
                           <?}?>
                  </div></form><?}?>
								 	  
								</div>
								
								</div>
                               
                           
                      <? } ?>
                  </div>
              </div>

            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->
<script>
function selecttoggle(v){
	
	
		
		$('#dropsec'+v).toggle();
	
	
}
function selectedfile(e,id){  
  
   $('#filename'+id).html(e.target.files[0].name);
    console.log(id);
    console.log(e.target.files[0].name);   
}
function setremarks(val,id){

		$('#remarks'+id).val(val);
	
	
}
function submitform(frm_name){
	$('#load_page').show();
var form_data = new FormData(document.getElementById(frm_name));
            $.ajax({
                url:"ajax/ajax_purchase_order.php",
                type: "POST",
                data: form_data,
                processData: false,
                contentType: false,
                async:true,
                dataType:"html",
                success:function(data){
                
            
                	$('#load_page').hide();
                    console.log(data);
                    location.reload();
                   //window.location.reload();
                }
            });

}

</script>
    <? include "lib/app_footer.php"; ?>

    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>

    <!-- Show Modal Windows -->
    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Order Details</div>
                <div class="modal-body" id="modal-body1" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>

    <div id="myModal2" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Assign User</div>
                <div class="modal-body" id="modal-body2" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>

    <!-- Show History -->
    <div id="myModal_showHistory" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Show History</div>
                <div class="modal-body" id="modal-bodyshowHistory" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>
    <!-- Show History -->

    <!-- Show History -->
    <div id="myModal_changeDueDate" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Change Order Due Date</div>
                <div class="modal-body" id="modal-body_changeDueDate" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>
    <!-- Show History -->

    <!-- Show History -->
    <div id="myModal_assignStyleMaster" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Assign Style Master</div>
                <div class="modal-body" id="modal-body_assignStyleMaster" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>
    <!-- Show History -->

    <!-- Show History -->
    <div id="myModal_getOrderImages" class="modal fade" style="z-index: 1">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Order Images</div>
                <div class="modal-body" id="modal-body_getOrderImages" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>
    <!-- Show History -->

    <!-- Show History -->
    <div id="myModal_changeEmployee" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Change Employee</div>
                <div class="modal-body" id="modal-body_changeEmployee" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>
    <!-- Show History -->
    <!-- Show Modal Windows -->

    <!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
    <script type="text/javascript" src="js/plugins/moment.min.js"></script>
    <script type="text/javascript" src="js/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>
    <script type="text/javascript" src="js/task.js"></script>

    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>

    <link href="css/ekko-lightbox.css" rel="stylesheet">
    <!-- yea, yea, not a cdn, i know -->
    <script src="js/ekko-lightbox-min.js"></script>
    <link href="css/lightgallery.css" rel="stylesheet">
    <script src="js/picturefill.min.js"></script>
    <script src="js/lightgallery.js"></script>
    <script src="js/lg-fullscreen.js"></script>
    <script src="js/lg-thumbnail.js"></script>
    <script src="js/lg-video.js"></script>
    <script src="js/lg-autoplay.js"></script>
    <script src="js/lg-zoom.js"></script>
    <script src="js/lg-hash.js"></script>
    <script src="js/lg-pager.js"></script>

    <link rel="stylesheet" href="css/flavor-lightbox.css">
    <script src="js/jquery.flavor.js"></script>
    <script src="js/script.js"></script>
    <script type="text/javascript">
        $(document).ready(function ($) {
            // $('#gallery').gallerie();

            $('#txt_empcode').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        type: 'post',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == '0') {
                                $('#txt_empcode').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });
            $("#load_page").fadeOut("slow");

            // Search the Box
            $('#txt_search').keyup(function(){
                $.each($('#id_monitor_board').find('div'), function(){
                    //alert('ga');
                    if($(this).text().toLowerCase().indexOf($('#txt_search').val()) == -1){
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
            // Search the Box
        });

        $('.ui-sortable').sortable({
            items: '> :not(.nodragorsort)'
        });

        $('#datepicker-alterdate').Zebra_DatePicker({
            format: 'd-m-Y',
            direction: true
        });

        function startTime(row, col, brncode, entry_year, entry_no, entry_srno, entry_sub_srno){
          $.ajax({
             url : 'ajax/ajax_timer.php?action=start_timer_update',
             type: 'post',
             dataType: "json",
             data: {
               step : row,
               step_end : col,
               id: entry_year+"-"+brncode+"-"+entry_no+"-"+entry_srno+"-"+entry_sub_srno
             },
             complete: function(){
                refresh_monitor_board();
                function pretty_time_string(num) {
                    return ( num < 10 ? "0" : "" ) + num;
                }

                var start = new Date;
                setInterval(function() {
                  var total_seconds = (new Date - start) / 1000;
                  var hours = Math.floor(total_seconds / 3600);
                  total_seconds = total_seconds % 3600;
                  var minutes = Math.floor(total_seconds / 60);
                  total_seconds = total_seconds % 60;
                  var seconds = Math.floor(total_seconds);
                  hours = pretty_time_string(hours);
                  minutes = pretty_time_string(minutes);
                  seconds = pretty_time_string(seconds);
                  var currentTimeString = hours + ":" + minutes + ":" + seconds;
                  if(currentTimeString >= '00:00:5'){
                      // $('#timer_'+row+'_'+col).addClass( "blink_me" );
                      // $('#timer_'+row+'_'+col).css('color' , 'red');
                   }
                  // $('#timer_'+row+'_'+col).val(currentTimeString);
               }, 1000);
             }
           });
         }

        function stopTime(row, col, id_time){
            $.ajax({
               url : 'ajax/ajax_timer.php?action=stop_timer_update',
               type: 'post',
               dataType: "json",
               data: {
                  id: id_time
               },
               complete: function(){
                  //$('#load_page').show();
                  refresh_monitor_board();
                  // $('#timer_'+row+'_'+col).val("currentTimeString");

                  function pretty_time_string(num) {
                      return ( num < 10 ? "0" : "" ) + num;
                  }

                  var start = new Date;
                  setInterval(function() {
                    var total_seconds = (new Date - start) / 1000;
                    var hours = Math.floor(total_seconds / 3600);
                    total_seconds = total_seconds % 3600;
                    var minutes = Math.floor(total_seconds / 60);
                    total_seconds = total_seconds % 60;
                    var seconds = Math.floor(total_seconds);
                    hours = pretty_time_string(hours);
                    minutes = pretty_time_string(minutes);
                    seconds = pretty_time_string(seconds);
                    var currentTimeString = hours + ":" + minutes + ":" + seconds;
                    if(currentTimeString >= '00:00:5'){
                        // $('#timer_'+row+'_'+col).addClass( "blink_me" );
                        // $('#timer_'+row+'_'+col).css('color' , 'red');
                     }
                    // $('#timer_'+row+'_'+col).val(currentTimeString);
                 }, 1000);
               }
            });
        }

        function popup_order_details(brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_show_order_details.php?brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal1").modal('show');
                    $('#load_page').hide();
                    document.getElementById('modal-body1').innerHTML = data;
                    $('#load_page').hide();
                    // $('.lightgallery').lightGallery();
                }
            });
        }

        function assign_user(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=assign_user&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal2").modal('show');
                    $('#load_page').hide();
                    document.getElementById('modal-body2').innerHTML = data;
                    $('#load_page').hide();
                    // $('.lightgallery').lightGallery();

                    $('#txt_empcode').autocomplete({
                        source: function( request, response ) {
                            $.ajax({
                                url : 'ajax/ajax_employee_details.php',
                                type: 'post',
                                dataType: "json",
                                data: {
                                   name_startsWith: request.term,
                                   type: 'employee'
                                },
                                success: function( data ) {
                                    if(data == '0') {
                                        $('#txt_empcode').val('');
                                        var ALERT_TITLE = "Message";
                                        var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                                    } else {
                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                }
                            });
                        },
                        autoFocus: true,
                        minLength: 0
                    });
                }
            });
        }

        function showHistory(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=showHistory&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_showHistory").modal('show');
                    $('#load_page').hide();
                    document.getElementById('modal-bodyshowHistory').innerHTML = data;
                    $('#load_page').hide();
                    // $('.lightgallery').lightGallery();

                    $('#txt_empcode').autocomplete({
                        source: function( request, response ) {
                            $.ajax({
                                url : 'ajax/ajax_employee_details.php',
                                type: 'post',
                                dataType: "json",
                                data: {
                                   name_startsWith: request.term,
                                   type: 'employee'
                                },
                                success: function( data ) {
                                    if(data == '0') {
                                        $('#txt_empcode').val('');
                                        var ALERT_TITLE = "Message";
                                        var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                                    } else {
                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                }
                            });
                        },
                        autoFocus: true,
                        minLength: 0
                    });
                }
            });
        }

        function refresh_monitor_board() { 
            $('#load_page').show();
            var strURL="ajax/ajax_monitor_board.php";
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    $.getScript( "js/task.js" );
                    $("#id_monitor_board").html(data);
                    $("#myModal2").modal('hide');
                    $('#load_page').hide();
                }
            });
        }

        function save_monitor_board(step_ends, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var txt_empcode = $('#txt_empcode').val();
            var step_end = $('#txt_nextstep').val();
            var store_id = entry_year+"-"+brncode+"-"+entry_no+"-"+entry_srno+"-"+entry_sub_srno; // 121-2018-19-2-1-1
            var strURL="ajax/ajax_drag_embc.php?action=track_update&id="+store_id+"&txt_empcode="+txt_empcode+"&step_end="+step_end;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    $.getScript( "js/task.js" );
                    refresh_monitor_board();
                    $("#myModal2").modal('hide');
                    $('#load_page').hide();
                }
            });
        }

        function changeDueDate(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno, ex_duedate) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=changeDueDate&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno+"&ex_duedate="+ex_duedate;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $.getScript("js/zebra_datepicker.js");
                    $.getScript("js/core.js");
                    $("#myModal_changeDueDate").modal('show');
                    document.getElementById('modal-body_changeDueDate').innerHTML = data;
                    $('#load_page').hide();
                }
            });
        }

        function savechangedDueDate(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno, ex_duedate) {
            $('#load_page').show();
            var nw_duedate = $('#datepicker-alterdate').val();
            var sendurl = "ajax/ajax_drag_embc.php?action=savechangedDueDate&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno+"&ex_duedate="+ex_duedate+"&nw_duedate="+nw_duedate;
            $.ajax({
                url:sendurl,
                success:function(data){
                    refresh_monitor_board();
                    $("#myModal_changeDueDate").modal('hide');
                    $('#load_page').hide();
                }
            });
        }

        function assignStyleMaster(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=assignStyleMaster&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_assignStyleMaster").modal('show');
                    document.getElementById('modal-body_assignStyleMaster').innerHTML = data;
                    $('#load_page').hide();
                }
            });
        }

        function saveStyleMaster(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            $("#frm_assignStyleMaster_"+brncode+"_"+entry_year+"_"+entry_no+"_"+entry_srno+"_"+entry_sub_srno).on('submit',(function(e) {
              e.preventDefault();
                $.ajax({
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    url: "ajax/ajax_drag_embc.php?action=saveStyleMaster&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno,
                    success: function(data){
                        $('#load_page').hide();
                    },
                    error: function(){}
                });
            }));
            $('#load_page').hide();
        }

        function getOrderImages(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=getOrderImages&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_getOrderImages").modal('show');
                    /* $.getScript("js/ekko-lightbox-min.js");
                    $.getScript("js/picturefill.min.js");
                    $.getScript("js/lightgallery.js");
                    $.getScript("js/lg-fullscreen.js");
                    $.getScript("js/lg-thumbnail.js");
                    $.getScript("js/lg-video.js");
                    $.getScript("js/lg-autoplay.js");
                    $.getScript("js/lg-zoom.js");
                    $.getScript("js/lg-hash.js");
                    $.getScript("js/lg-pager.js");
                    $('.lightgallery').lightGallery(); */

                    /* $.getScript("js/jquery.gallerie.js");
                    $('#gallery').gallerie(); */

                    /* $.getScript("js/lightbox-plus-jquery.js"); */ 

                    $.getScript("js/jquery.flavor.js");
                    $.getScript("js/script.js");
                    document.getElementById('modal-body_getOrderImages').innerHTML = data;
                    $('#load_page').hide();
                }
            });
        }

        
        function changeEmployee(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno, ex_employee) {
            $('#load_page').show();
            var sendurl = "ajax_assign_user.php?action=changeEmployee&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno+"&ex_employee="+ex_employee;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_changeEmployee").modal('show');
                    document.getElementById('modal-body_changeEmployee').innerHTML = data;
                    $('#load_page').hide();
                }
            });
        }

        function savechangeEmployee(current_step, brncode, entry_year, entry_no, entry_srno, entry_sub_srno, ex_employee) {
            $('#load_page').show();
            var nw_employee = $('#nw_employee').val();
            var sendurl = "ajax/ajax_drag_embc.php?action=savechangeEmployee&current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno+"&ex_employee="+ex_employee+"&nw_employee="+nw_employee;
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal_changeEmployee").modal('hide');
                    refresh_monitor_board();
                    $('#load_page').hide();
                }
            });
        }
    </script>
    <!-- END TEMPLATE -->
<!-- END SCRIPTS -->
</body>
</html>
