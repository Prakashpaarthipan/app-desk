<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

$menu_name = 'SERVICE REQUEST';
$inner_submenu = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc", "Centra", 'TCS');
if($_SESSION['tcs_empsrno'] != '') {
    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' order by MNUCODE Asc", "Centra", 'TCS');
} else {
    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and SUPCODE = '".$_SESSION['tcs_userid']."' order by MNUCODE Asc", "Centra", 'TCS');
}
if($inner_menuaccess[0]['VEWVALU'] == 'N' or $inner_menuaccess[0]['MNUCODE'] == 'VEWVALU') { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
}

if($inner_menuaccess[0]['VEWVALU'] == 'Y') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Purchase Order Track Fixing :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
<link rel="icon" href="favicon.ico" type="image/x-icon" />

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

<style type="text/css">
.messages .item .text {
  background: #FFF;
  padding: 10px;
  margin: 5px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  border: 1px solid #D5D5D5;
  }
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
textarea {
    color: #333;
    font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
    line-height: 18px;
    font-weight: 400;
}
.label {
white-space: normal !important;
/* white-space: nowrap; */
}
.highlight_redtitle { color: #FF0000; font-size: 20px; }
.highlight_blacktitle { color: #000; font-size: 20px; }

.cls_left { background-color: #eaeaf3 !important; }
.cls_right { background-color: #ebeaea !important; }
.badge-info { background-color: #1caf9a !important; color: #FFFFFF !important; }
.date { font-size: 10px !important; color: #000 !important; font-weight: normal !important; }
#load_page {
 position: fixed;
 left: 0px;
 top: 0px;
 width: 100%;
 height: 100%;
 z-index: 10;
 opacity: 0.4;
 background: url('images/loading.gif') 50% 50% no-repeat rgb(249,249,249);
}
.list-group-status { margin-right: 5px !important; }
.list-group-contacts .list-group-item img { margin-right: 5px !important; }
.modal {
    display: none; /* Hidden by default */
    position:FIXED; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: auto; /* Full width */
    height: auto; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal {
    display: none; /* Hidden by default */
    position:FIXED; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: auto; /* Full width */
    height: auto; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    height :500px;
    overflow-x: scroll;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}


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
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }
    .tabs {
    margin-top: 10px !important;

    }
    .nav-tabs-vertical .nav-tabs > li.active > a, .nav-tabs-vertical .nav-tabs > li.active > a:hover, .nav-tabs-vertical .nav-tabs > li.active > a:focus, .nav-tabs-vertical .nav-tabs > .dropdown.active.open > a:hover {
         background-color: rgba(112, 167, 215, 0.89) !important;
         color: white;
    }
    /* width */
::-webkit-scrollbar {
    width: 6px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
    
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555; 
}
.content-frame-body { padding: 10px !important; }
</style>
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<style>
.badge-info { background-color: #1caf9a !important; color: #FFFFFF !important; }
</style>
<!-- EOF CSS INCLUDE -->
</head>
<body>
    <div id="load_page" style='display:block;padding:12% 40%;'></div>
    <!-- START PAGE CONTAINER -->
        <div class="page-container page-navigation-toggled">

        <!-- START PAGE SIDEBAR -->
        <div class="page-sidebar">
            <!-- START X-NAVIGATION -->
            <? include 'lib/app_left_panel.php'; ?>
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
                <li><a href="home.php">Dashboard</a></li>
                <li class="active">Purchase Order Process Stage Fixing</li>
            </ul>
            <!-- END BREADCRUMB -->
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="panel-heading" style="margin-bottom: 20px;">
                        <h3 class="panel-title"><strong>Purchase Order Process Stage Fixing</strong></h3>
                        <ul class="panel-controls">
                        </ul>
                    </div>
                    

                     <div class="form-group trbg non-printable" style="display:block;">
                      <?
                           $sql_sec = select_query_json("select sec.seccode,sec.secname,count(distinct tdet.znestat) ss from order_tracking_detail tdet,section sec where tdet.seccode=sec.seccode and tdet.deleted='N' group by sec.seccode,sec.secname,sec.secsrno,tdet.poryear||' - '||tdet.pornumb having count(distinct tdet.znestat)=1 order by sec.secsrno,sec.seccode,sec.secname", "Centra", "TEST");
                           $arr_sec=array();
                           foreach ($sql_sec as $key => $value) {
                             $temp=count($arr_sec[$value['SECCODE']]);
                             if($temp==0)
                             {
                              $arr_sec[$value['SECCODE']]=$value;
                             }
                           }
                           //echo('<pre>');
                           //print_r($arr_sec);
                           //echo('</pre>');
                           $sql_sec=$arr_sec;
                           $sql_po = select_query_json("select poryear||' - '||pornumb PONUMB,pordate,porqty,porval,supcode,count(distinct znestat) ss from order_tracking_detail where deleted='N' group by poryear||' - '||pornumb,poryear,pornumb,pordate,porqty,porval,supcode having count(distinct znestat)=1 order by poryear,pornumb", "Centra", "TEST");

                      ?>
                      <input type="hidden" name="po_numb" id="po_numb" value=''/>
                       <div class='row'>
                            <div class="col-md-4">
                              <div class="input-group hover-box" style="margin:10px;">    
                                  <span class="input-group-btn">
                                      <button class="btn btn-info" type="button" ><span >Section</span></button>
                                  </span>                        
                                  <select class="form-control custom-select chosn" autofocus tabindex='1' required id="txt_section" name="txt_section" onchange="sec_load();" onmouseover="call_box()" onmouseleave="call_out()">
                                      <option value="">Choose Section</option>
									   <option value="all">All Section</option>
                                      <?foreach($sql_sec as $key => $value){?>
                                        <option value="<?=$value['SECCODE']?>"><?=$value['SECNAME']?></option>
                                      <?}?>
                                   </select>   
								   
                              </div><br><br>
							  
							  
                            </div>
							
                      </div> 
                      <div class="row">
                        <div class="col-md-2">
                          <h3 style="text-align: center;"> Po. Number </h3>
						  
                        </div>
						                     
                        <div class="col-md-10">
                          <h3 style="text-align: center;"> Stage </h3><br>
                        </div>
						
                          
                  
                                 <b style="font-size:17px;margin-left:85px;">Search:</b> <input type="text" name="ord_search" id="ord_search" placeholder="Search Order No."/>
            
                                               
                      
                      <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-3" id="po_list">
                          <div class="panel panel-default tabs nav-tabs-vertical hover-box-def" style="height:600px;overflow-y: scroll;overflow-x: hidden;box-shadow: 0 0 5px black; border-radius: 10px;" id="check_list1">   
						   <ul class="nav nav-tabs " style="width: 100%;border-radius: 10px;">
						   <div class="row" style="margin-bottom: 10px;border-bottom: 1px solid black;">		   
 		
 		<label class="control-label text-left" style="font-size:17px;margin-right:10px;">All</label>
		<label class="switch switch-small">
		      <input class="switch all_check1" type="checkbox" name="all_check1" id="all_check1" value="N" onclick="load_process('<?=$sql_po[0]['PONUMB']?>');"/>
		      <span></span>
		</label>
 	</div>
 
                                 
                                  <?for($k=0;$k<sizeof($sql_po);$k++){  
         $supname=select_query_json("select sup.SUPNAME,cty.CTYNAME from supplier sup, city cty where supcode='".$sql_po[$k]['SUPCODE']."' and sup.CTYCODE=cty.CTYCODE","Centra", "TEST");         
?>

 						<li>
								<div class="row" style="border:1px solid #ccc;margin:0;font-size:12px !important;max-width:100%;cursor:pointer">
                                    <div class="col-md-12"  style="padding-top:1px;padding-left:0;padding-right:0;height:auto;max-width:100%;>
                                        <div class="form-group" onclick="load_process('<?=$sql_po[$k]['PONUMB']?>');" style="border-radius: 10px;">
   <label class="switch switch-small" class="col-md-4" style="float: right;padding-top: 10px;">
				<input class="switch toggle1 po_check" type="checkbox" id="check_<?=$k?>" name="<?=$sql_po[$k]['PONUMB']?>" value="N" />
				<span></span>
			</label>
                                          <div class="col-md-12" style="font-size: 14px; padding-right: 2px;font-weight:bold;margin-right:5px;color: #002eff;margin-top:0px;">
										Order No :<?=$sql_po[$k]['PORYEAR']?> <?=$sql_po[$k]['PONUMB']?> [ <?=$sql_po[$k]['PORDATE']?>]
								
                                          </div>
                                          <div style="clear: both;"></div>
                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              Supplier : <?=$sql_po[$k]['SUPCODE']." - ".$supname[0]['SUPNAME'].", ".$supname[0]['CTYNAME']?>
                                          </div><!-- rate -->
                                          <div style="clear: both;"></div>                                         
                                         
                                          <div class="col-md-6" style="padding: 5px 10px; padding-left: 10px;font-weight:bold;color: #FF0000;">
                                              Qty : <?=$sql_po[$k]['PORQTY']?>
                                          </div><!-- CGST -->
										  <div class="col-md-6" style="padding:0; text-align: right; padding-right: 10px; padding: 5px 10px; padding-left: 2px;font-weight:bold;color: #FF0000;">
                                              Val : <?=number_format(($sql_po[$k]['PORVAL']/100000),2);?> 
                                          </div><!-- SGST -->
              
                                          <div style="clear: both;"></div>
                                       </div>
                                      
                                    </div>

									</li>
							<?}?>
                                </ul>
								
                          </div>
						
                        </div>
                        <div class="col-md-8">
                           <div class="col-md-8 hover-box-def" style='clear:both;box-shadow: 0 0 5px black; margin-top: 10px;height: 600px;width: 100%; border-radius: 10px; padding:2% 10%;overflow-y: auto;overflow-x: hidden;'>
                              <div class="panel-body">
                                   <form class="form-horizontal" role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                                      <div id="check_list" >

                                      </div>
                                  </form>
                              </div>
                          </div>
                        </div>

                    <!-- viki -->

                  </div>
                </div>
				
                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>

    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>

    <!-- Collect Document -->
    
    <!-- Collect Document -->
    <div class='clear'></div>


<!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>

    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>

    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <!-- Select2 -->
    <script src="../dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
	
$('#ord_search').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                url:"vinothini/ajax_purchase_order_entry_1.php",
                type: "POST",
                data: {selectval:request.term,
                      action:'order_search'},
                
                dataType:"html",
                success:function(data){
                 console.log(data);
                  $('#po_list').html(data);
                  $('#load_page').hide();
				
                   // location.reload();
                   //window.location.reload();
                }
            });
                },
                autoFocus: true,
                minLength: 4
            });
	
	
$('#load_page').fadeOut('fast');
     function hover_box(){
       $('.hover-box').mouseover(function (){
        $(this).css('box-shadow','0 0 7px black');
       });
       $('.hover-box').mouseout(function (){
        $(this).css('box-shadow','none');
       });
       $('.hover-box-def').mouseover(function (){
        $(this).css('box-shadow','0 0 10px black');
       });
       $('.hover-box-def').mouseout(function (){
        $(this).css('box-shadow','0 0 7px black');
       });
     }
     hover_box();
     $(document).ready(function(){
      hover_box();
     });
function sec_load(){
      var sec=$('#txt_section').val();
      console.log(sec);
      $.ajax({
        url:"vinothini/ajax_purchase_order_entry_1.php",
        data:{
          sec:sec,
          action:'sec_load'
        },
        type:'POST',
        dataType:'html',
        success:function(data){
          $('#check_list1').html('');
          console.log(data);
          $('#po_list').html(data);
           $('.all_check1').click(function(){
            var temp=$(this).val();
            if(temp=='A')
            {
              $(this).val('N');
            }
            else{
              $(this).val('A');
            }
            if($(this).val()=='A')
            {
              $('.po_check').prop("checked",true);
              $('.po_check').val('A');
            }
            else
            {
              $('.po_check').prop("checked",false);
              $('.po_check').val('N');
            }
          });
        }
      });
     }
      $('.all_check1').click(function(){
            var temp=$(this).val();
            if(temp=='A')
            {
              $(this).val('N');
            }
            else{
              $(this).val('A');
            }
            if($(this).val()=='A')
            {
              $('.po_check').prop("checked",true);
              $('.po_check').val('A');
            }
            else
            {
              $('.po_check').prop("checked",false);
              $('.po_check').val('N');
            }
          });
      $('.toggle1').click(function(){
            var temp=$(this).val();
            console.log(temp);
            if(temp=='A')
            {
              $(this).val('N');
              console.log($(this).val());
            }
            else{
              $(this).val('A');
            }
          });
function load_process(po_numb){
      console.log("loading staqrted");
      $('#po_numb').val(po_numb);
      $.ajax({
        url:"vinothini/ajax_purchase_order_entry_1.php",
        data:{
          po_numb:po_numb,
          action:'load'
        },
        dataType:'html',
        success:function(data){
         // console.log(data);

          $('#check_list').html(data);
          hover_box();
          $('.all_check').prop("checked",false);
          $('.toggle').click(function(){
            var temp=$(this).val();
            console.log(temp);
            if(temp=='A')
            {
              $(this).val('N');
              console.log($(this).val());
            }
            else{
              $(this).val('A');
            }
          });
          $('.all_check').click(function(){
            var temp=$(this).val();
            if(temp=='A')
            {
              $(this).val('N');
            }
            else{
              $(this).val('A');
            }
            if($(this).val()=='A')
            {
              $('.entry_check').prop("checked",true);
              $('.entry_check').val('A');
            }
            else
            {
              $('.entry_check').prop("checked",false);
              $('.entry_check').val('N');
            }
          });
          auto_complete();
        }
      });
     }
      // $('.all_check').click(function(){
      //       var temp=$(this).val();
      //       if(temp=='A')
      //       {
      //         $(this).val('N');
      //       }
      //       else{
      //         $(this).val('A');
      //       }
      //       if($(this).val()=='A')
      //       {
      //         $('.entry_check').prop("checked",true);
      //         $('.entry_checkentry_check').val('A');
      //       }
      //       else
      //       {
      //         $('.entry_check').prop("checked",false);
      //         $('.entry_check').val('N');
      //       }
      //     });
		  
 
		  
function sec_load1(po_numb){
      console.log("loading staqrted");
      $('#po_numb').val(po_numb);
      $.ajax({
        url:"vinothini/ajax_purchase_order_entry_1.php",
        data:{
          po_numb:po_numb,
          action:'check'
        },
        dataType:'html',
        success:function(data){
         // console.log(data);

          $('#check_list1').html(data);
          hover_box();
          $('.all_check1').prop("checked",false);
          $('.toggle1').click(function(){
            var temp=$(this).val();
            console.log(temp);
            if(temp=='A')
            {
              $(this).val('N');
              console.log($(this).val());
            }
            else{
              $(this).val('A');
            }
          });
          $('.all_check1').click(function(){
            var temp=$(this).val();
            if(temp=='A')
            {
              $(this).val('N');
            }
            else{
              $(this).val('A');
            }
            if($(this).val()=='A')
            {
              $('.po_check').prop("checked",true);
              $('.po_check').val('A');
            }
            else
            {
              $('.po_check').prop("checked",false);
              $('.po_check').val('N');
            }
          });
          auto_complete();
        }
      });
     }

function nsubmit()
     {   var po_numb= $('#po_numb').val();
      $('#load_page').fadeIn('fast');
      var check = jQuery("#frm_request_entry").serializeArray();
      check = check.concat(
        jQuery('#frm_request_entry input[type=checkbox]:not(:checked)').map(
                function() {
                    return {"name": this.name, "value": this.value}
                }).get()
      );
      console.log(check);
	   var analyse = jQuery("#po_list").serializeArray();
	  analyse = analyse.concat(
        jQuery('#po_list input[type=checkbox]:checked').map(
                function() {
                    return {"name": this.name, "value": this.value}
                }).get()
      );
    console.log(analyse);
      var assign=[];
      $('.auto_complete').each(function(){
        var name=$(this).attr('name');
        name=name.split('_');
        name=name[2];
        var value=$(this).val();
        value=value.split(' - ');
        value=value[0];
        console.log(name+" = "+value);
        //var temp=Object.keys(assign1.name).length;
        assign[name]=value;
      });
      console.log(assign);
      //console.log(check);
      $.ajax({
        data:{
          check:check,
          analyse:analyse,
          action:"submit",
          assign:assign,
          po_numb:po_numb
        },
        url:"vinothini/ajax_purchase_order_entry_1.php",
        type:'POST',
        dataType:'html',
        success:function(data)
        {$('#load_page').fadeOut('fast');
          console.log(data);
          alert("Submitted Successfully");
          window.location.reload();
        }
      });
     }
	 
	
function auto_complete(){
      $('.auto_complete').each(function(){
        $('#load_page').fadeIn('fast');
            $(this).autocomplete({
              source: function( request, response ) {
                $.ajax({
                  url : 'ajax/ajax_employee_details.php',
                  dataType: "json",
                  
                  data: {
                     name_startsWith: request.term,
                     // topcr: $('#slt_topocore').val(),
                     // subcr: $('#slt_subcore').val(),
                     type: 'employee'
                  },
                  success: function( data ) {
                    $('#load_page').fadeOut('fast');
                    if(data == 'No User Available in this Top core and Sub Core') {
                      $('#txt_workintiator').val('');
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
              minLength: 3
            });
          });
      $('#load_page').fadeOut('fast');
     }
    </script>
<!-- END SCRIPTS -->

</body>
</html>
<? } else { ?>
    <script>window.location="home.php";</script>
<?php exit();
}





