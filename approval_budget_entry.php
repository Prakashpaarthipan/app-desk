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
<title>Budget Entry</title>
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
    height :650px;
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
    .disabledbutton {
        pointer-events: none;
        opacity: 0.4;
    }
    .content-frame-body { padding: 10px !important; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
    .blinking{
    animation: blinkingText 1s infinite;
    }
    @keyframes blinkingText{
        0%{     color: rgba(224, 75, 74, 1);    }
        30%{    color: transparent; }
        30%{    color: transparent; }
        30%{    color:transparent;  }
        100%{   color:rgba(224, 75, 74, 1);    }
    }
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
                <li class="active">Budget Planner Enrty</li>
            </ul>
            <!-- END BREADCRUMB -->
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="panel-heading" style="margin-bottom: 20px;">
                        <h3 class="panel-title"><strong>Budget Planner Enrty</strong></h3>
                        <center><span class="label label-primary" style="font-size: 20px">Month : <?$month = date('F',strtotime('+1 month'));echo($month);?> - <?echo date('y');?></span></center>
                        <ul class="panel-controls">
                        </ul>
                    </div>
                    <input type="hidden" name="txt_last_full" id="txt_last_full" value="" />

                     <div class="form-group trbg non-printable" style="display:block;">
                          

                      <?
                      $sql_brn = select_query_json("select distinct b.brncode,b.brnname from branch b,approval_branch_head ab where b.brncode=ab.brncode order by brncode", "Centra", "TCS");
                      ?>
                      <!-- <center> -->
                        <div class='row'>
                          <center>
                            <input type="hidden" name="month" id="month" value="<?$month = date('m')+1;echo($month);?>"/>
                            <input  type="hidden" id="class_current" name="class_current" value="1" />
                            <input type="hidden" name="txt_cur_exp_1" id="txt_cur_exp_1" value="0"/>
                            <div class="col-md-5">
                              
                                <div class="input-group " style="margin:10px;width: 75%;">                           
                                      <select class="form-control custom-select chosn" autofocus tabindex='1' required id="branchname" name="branchname" onchange="overall_load();">
                                          <option data-selected="" value="">Choose branch</option>
                                          <?for($k=0;$k<sizeof($sql_brn);$k++){?>
                                          <option data-selected="<?=$sql_brn[$k]['BRNNAME']?>" value="<?=$sql_brn[$k]['BRNCODE']?>"><?=$sql_brn[$k]['BRNNAME']?></option>
                                          <?}?>
                                       </select>
                                      <span class="input-group-btn" style="background-color: black">
                                          <button class="btn btn-info" type="button" onclick="reloadalter();"><span>Branch Load</span></button>
                                      </span>                                
                                  </div>

                                </div>
                                <div class="col-md-2">
                                  <div class="input-group " style="margin: 10px;vertical-align: middle;float: left;">
                                         <button class="btn btn-danger" type="button" style="margin-left: 10px; float: right;" onclick="nview();"><span class="fa fa-file-text"></span> View</button>
                                  </div>
                               </div>
                               <div class="col-md-5">
                                <div id="overall_table">
                                  <table class="table table-bordered">
                                      <tbody> 
                                          <tr>
                                              <td style="background: #f1f5f9;width: 50%;">LAST YEAR SALES <?$month = date('M',strtotime('+1 month'));echo($month);?> - <?echo date('y')-1;?> (in Lakhs) </td>
                                              <td style="min-width: 50%;"><span id="txt_last_year_sale" name="txt_last_year_sale"> - </span></td>
                                          </tr>
                                          <tr>
                                              <td style="background: #f1f5f9">LAST  MONTH IMPROVEMENT %</td>
                                              <td><span name="txt_lst_mnt_imp" id="txt_lst_mnt_imp"> - </span><span> %</span></td>
                                          </tr>
                                          <tr>
                                              <td style="background: #f1f5f9">TARGETED SALES (%)<span id="tar_val" style="float: right;"></span></td>
                                              <td ><input type="number" class="Number" name="txt_tar_sal" id="txt_tar_sal" value="" style="width: 100%;box-sizing: border-box;" onblur="calculate('a');" />
                                                <!-- <input type="hidden" name="txt_tar_sal" id="txt_tar_sal"/> -->
                                              </td>
                                          </tr>
                                          <tr>
                                              <td style="background: #f1f5f9">ESTIMATED EXP % : <span class="blinking" ><span id="txt_estimated_val" name="txt_estimated_val"> 0 </span><span> %</span></span></td>
                                              <td style="background: #f1f5f9"><span>DEPARTMENT EXP % : </span><span class="blinking" ><span id="txt_dep_exp" name="txt_dep_exp"> 0 </span><span> %</span></span></td>
                                          </tr>
                                      </tbody>
                                  </table>
                                </div>
                               </div>
                            </center>
                      </div>
                      <!-- /////////////////////////tabtest -->
                      <div id="data" style=" border-top:1px solid #ADADAD;">
                        <center style="margin-top: 30px;">
                          <h3>Choose the Branch to begin</h3>                        
                        </center>
                      </div>
                    </div>
                    <div  style='clear:both; border-top:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'>
                      
                        <div id="maintable" style="margin-top: 10px;" >
                        </div>
                     
                    </div>
                  </div>
                </div>
                </div>
                <div id="myModal" class="modal">
                          <!-- Modal content -->
                  <span class="close">&times;</span>
                  <div id="modal_data" class="modal-content">
                    
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
      ////////////////////////
      function delete_entry(taryear,brncode,tarmont,expsrno,tarnumb,depcode,depexpp,curexpp,reqval,lastsal,entnumb){
        console.log(taryear+" - "+brncode+" - "+tarmont+" - "+expsrno+" - "+tarnumb+" - "+depcode);
        //console.log("hi");
        $.ajax({
          url:"viki/budget_entry.php",
          data:{
            taryear,
            brncode,
            tarmont,
            expsrno,
            tarnumb,
            depcode,
            depexpp,
            curexpp,
            reqval,
            lastsal,
            entnumb,
            action:'delete'
          },
          complete:function(data){
            nview();
          }
        });
      }
      function reloadalter()
      {
        if(confirm("Are you sure to reaload ?"))
        {
          alternativeload();
        }
      }
      function depcalculate(clsname)
      { //console.log(clsname);
        var sum=0;
        //var target_sale=Number($('#txt_tar_sal').val())*100000;
        var target_sale_per=Number($('#txt_tar_sal').val());
        var lst_sale=Number($('#txt_last_full').val());
        console.log(lst_sale);
        var target_sale=(target_sale_per/100)*lst_sale;
        target_sale=target_sale+lst_sale;
        var show=(target_sale/100000).toFixed(2);
              console.log(show);
               target_sale=lst_sale;
        $('#tar_val').html(show);
        //console.log(target_sale);
        var tag_val=document.getElementsByClassName(clsname);
        //var dep_per=Number($('#txt_dep_exp').html());
        for(var i=0;i<tag_val.length;i++)
        {
            sum=sum+Number(tag_val[i].value);
        }
        var old_dep_exp=Number($('#txt_cur_exp_1').val());
        var percent=((sum)/(target_sale))*100;
        console.log("------------------- hi");
        console.log(sum);
        console.log(target_sale);
        console.log(old_dep_exp);
        console.log("------------------- bye");
        old_dep_exp=old_dep_exp+Number(percent.toFixed(2));
        //console.log(old_dep_exp);
        $('#txt_dep_exp').html(old_dep_exp.toFixed(2));
      }
      /////////////////////////
      $('.Number').keypress(function (event) {
          var keycode = event.which;
          if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
              event.preventDefault();
          }
      });
      function view_submit()
      {
        //document.getElementById("frm_budget_entry"+tab).elements['action'].value='commit';
        if(confirm("Are Sure to Commit !"))
        {
          $('#load_page').fadeIn('slow');
          var form_data = new FormData(document.getElementById("frm_view"));
          $.ajax({
            url:"viki/budget_entry.php",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            async:true,
            dataType:"html",
            success:function(data){

              nview();
              console.log(data);
              alert("Approved Successfully !");
            }
          });
        }
      }
      function clearForm(clsname)
      { var tag_val=document.getElementsByClassName(clsname);
        var str='.'+clsname;
        $(str).each(function(e){
            $(this).val('');
        });
        var exp_cur=$('#txt_cur_exp').val();
          $('#txt_estimated_val').html(exp_cur);
          var cur_exp=$('#txt_cur_exp_1').val();
          $('#txt_dep_exp').html(cur_exp);
       $('.show_chk').each(function(e){
            $('.nav-tabs').each(function(e){
              $(this).css('opacity','1');
            });
            $(this).css('pointer-events','auto');
        });
         //alternativeload();
      }

      var ntab=0;
       function calculate(clsname)
      { 
        var tar_cls=$('#class_current').val();
        if(tar_cls!='')
        {     if(clsname=='a')
              {
                clsname=$('#class_current').val();
              }
              else
              {
                $('#class_current').val(clsname);
              }
              //var target_sale=Number($('#txt_tar_sal').val())*100000;

              var target_sale_per=Number($('#txt_tar_sal').val());
              var lst_sale=Number($('#txt_last_full').val());
              console.log(lst_sale);
              var target_sale=(target_sale_per/100)*lst_sale;
              target_sale=target_sale+lst_sale;
              var show=(target_sale/100000).toFixed(2);
              console.log(show);
              target_sale=lst_sale;
             
              $('#tar_val').html(show);

              console.log(target_sale);
              if(target_sale_per=='' || target_sale_per<10 || target_sale_per>100)
              {
                if(target_sale_per>100)
                {
                  $('#txt_tar_sal').val(100);
                  // return false;

                }
                if(target_sale_per<10)
                {
                  $('#txt_tar_sal').val(10);
                  // return false;
                }
                
                //alert("Target Sale must be > 10");
                var str='.'+clsname;
                $(str).each(function(e){
                    $(this).val('');
                });
                var exp_cur=$('#txt_cur_exp').val();
                $('#txt_estimated_val').html(exp_cur);
                 var cur_exp=$('#txt_cur_exp_1').val();
                $('#txt_dep_exp').html(cur_exp);//viki
                // return false;
              }
              var tag_val=document.getElementsByClassName(clsname);
              var flag=0;
              for(var i=0;i<tag_val.length;i++)
              {
                 if(tag_val[i].value!='')
                 {
                  flag=1;
                 }
              }
              if(flag==0)
              {
                $('.show_chk').each(function(e){
                    $('.nav-tabs').each(function(e){
                      $(this).css('opacity','1');
                    });
                    $(this).css('pointer-events','auto');
                });
                var exp_cur=$('#txt_cur_exp').val();
                $('#txt_estimated_val').html(exp_cur);
                var cur_exp=$('#txt_cur_exp_1').val();
                $('#txt_dep_exp').html(cur_exp);
                // return false;
              }

              $('.show_chk').each(function(e){
                  $('.nav-tabs').each(function(e){
                    $(this).css('opacity','0.4');
                  });

                  
                  $(this).css('pointer-events','none');
              });
              //console.log(clsname);
              var sum=0;
              var tag_val=document.getElementsByClassName(clsname);

             var target_sale_per=Number($('#txt_tar_sal').val());
              var lst_sale=Number($('#txt_last_full').val());
              console.log(lst_sale);
              var target_sale=(target_sale_per/100)*lst_sale;
              target_sale=target_sale+lst_sale;
              var show=(target_sale/100000).toFixed(2);
              console.log(show);
              if(target_sale_per>10 && target_sale_per<100)
              {
                $('#tar_val').html(show);
              }
              
              console.log(lst_sale);
               target_sale=lst_sale;

              for(var i=0;i<tag_val.length;i++)
              {
                  sum=sum+Number(tag_val[i].value);
              }
              //console.log(sum);
              var last_year=Number($('#cal_last_year_val').val());
              var req_val=Number($('#cal_req_val').val());
              
              var sum_appr=$('#cal_sum_appr_budgt').val();
              if(target_sale=='')
              {
                
              }
              //console.log(last_year+target_sale+sum);
              var percent=((req_val+sum)/(target_sale))*100;
              console.log('------------------');
              console.log(req_val);
              console.log(sum);
              console.log(target_sale);
              console.log('------------------');

              //console.log(percent);
              $('#txt_estimated_val').html(percent.toFixed(2));
              depcalculate(clsname);
        }
        
      } 

      var modal = document.getElementById('myModal');

      // Get the button that opens the modal
      var btn = document.getElementById("view_msg");

      // Get the <span> element that closes the modal
      var span = document.getElementsByClassName("close")[0];
      span.onclick = function() {
          modal.style.display = "none";
          $("#modal_data").html(' ');
      }

      $(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (event) {
          //console.log(event);
           var ele=event.delegateTarget.activeElement.innerText;
           $('#expensetitle').html(ele);
           var exp_cur=$('#txt_cur_exp').val();
          $('#txt_estimated_val').html(exp_cur);

          ele=ele.split("|");
          console.log(ele);
          if(typeof ele[1] != 'undefined')
          { console.log(ele[1]);
             ele=ele[1].split("%)");
            console.log(ele[0]);
            if(ele[0]!='')
            {
              $('#txt_dep_exp').html(ele[0]);
              $('#txt_cur_exp_1').val(ele[0]);
            }
          }
          else{
            $('#txt_dep_exp').html('0');
            $('#txt_cur_exp_1').val('0');
          }
           
           window.scrollTo(0, 0);
        });


      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
          if (event.target == modal) {
              modal.style.display = "none";
              $("#modal_data").html(' ');
          }
      }



      $('#load_page').fadeOut('slow');
      function nload()
      { $('#load_page').fadeIn('fast');
        ntab++;
        $.ajax({
          url:"viki/budget_entry.php",
          data:{
            branch:$('#branchname').val(),
            expsrno:$('#expsrno').val(),
            tabid:ntab,
            action:'load'
          },
          dataType:'html',
          success:function(data1)
          {
              //alert("success");
              var name=$('#expsrno option:selected').text();
              $('#tab_header').append("<li><a aria-expanded='false' href='#tab"+ntab+"' data-toggle='tab'>"+name+"</a></li>")
              $('#tab_content').append("<div class='tab-pane' id='tab"+ntab+"'>"+data1+"</div>");
              if(ntab==1)
              {
                $('#tab'+ntab).addClass('active');
              }
              //$('#maintable').html(data1);
              $('#ntable'+ntab).dataTable({
                    "bSort": false
                  });
              $('#load_page').fadeOut('fast');
          },
          error:function(err){
            console.log(err);
          }
        });
      }
      function nsubmit(tab)
      { $('#load_page').fadeIn('fast');
        var brncode1=document.getElementById('branchname').value;
        var month1=document.getElementById('month').value;
        //alert(month1);
        var lakval=Number($('#txt_last_year_sale').html());
        $('#form_last_sales'+tab).val(lakval);
        $('#form_lst_mnt_imp'+tab).val($('#txt_lst_mnt_imp').html());
        $('#form_tarsales'+tab).val($('#txt_tar_sal').val());
        $('#form_cur_est'+tab).val($('#txt_estimated_val').html());
        $('#form_dep_exp'+tab).val($('#txt_dep_exp').html());
        var result=0;
        //document.getElementById('brncode'+tab).value=brncode1;
        $('#brncode'+tab).val(brncode1);
        document.getElementById('month'+tab).value=month1;
        document.getElementById("frm_budget_entry"+tab).elements['action'].value='validate';
        var form_data = new FormData(document.getElementById("frm_budget_entry"+tab));
        $.ajax({
         url:"viki/budget_entry.php",
          //url:"viki/post_test.php",
          type: "POST",
          data: form_data,
          processData: false,
          contentType: false,
          async:true,
          dataType:"html",
          success:function(data){
            result=parseInt(data);
            //alert(result);
            if(result==0)
            {
              document.getElementById("frm_budget_entry"+tab).elements['action'].value='commit';
              var form_data = new FormData(document.getElementById("frm_budget_entry"+tab));
              $.ajax({
                url:"viki/budget_entry.php",
                //url:"viki/post_test.php",
                type: "POST",
                data: form_data,
                processData: false,
                contentType: false,
                async:true,
                dataType:"html",
                success:function(data){
                  //console.log(data);
                  alert("Request Submitted");
                  alternativeload();
                  $("#load_page").fadeOut("slow");
                }
              });
            }
            else
            { $("#load_page").fadeOut("slow");
              alert(" Request value cannot be empty or < 0");
            }
          }
          });
        $("#load_page").fadeOut("slow");
        //$('#load_page').fadeIn('fast');
        
      }
      function nview()
      {   $('#load_page').fadeIn('slow');
          if(document.getElementById('month').value!='' && $('#branchname').val()!='')
          {
            $.ajax({
              url:"viki/budget_entry.php",
              data:{
                action:'view',
                month:document.getElementById('month').value,
                brncode:$('#branchname').val()
              },
              dataType:'html',
              success:function(data1)
              {
                  //alert("success");
                  $('#load_page').fadeOut('fast');
                  $('#modal_data').html(data1);
                  $('#viewtable').dataTable({
                        "bSort": false
                      });
                  modal.style.display = "block";
              },
              error:function(err){
                console.log(err);
              }
            });
          }
          else
          {
            alert("Month | Branch is required !");
            $('#load_page').fadeOut('fast');
          }
          
      }
      function alternativeload()
      {   
         // alert("****alternativeload");
          var month1=document.getElementById('month').value;
          var exp_cur=$('#txt_cur_exp').val();
          $('#txt_estimated_val').html(exp_cur);
          var cur_exp=$('#txt_cur_exp_1').val();
          $('#txt_dep_exp').html(cur_exp);
          if(month1!='')
          {
            $('#load_page').fadeIn('slow');
            // overall_load(document.getElementById('branchname').value,document.getElementById('month').value,'2018');
          
            $.ajax({
            url:"viki/budget_entry.php",
            data:{
              branch:document.getElementById('branchname').value,
              month:document.getElementById('month').value,
              action:'alternativeload'
            },
            dataType:'html',
            async: false,
            success:function(data1)
            {
                //alert("success");

                
                $('#data').html(data1);
                $('.Number').keypress(function (event) {
                    var keycode = event.which;
                    if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
                        event.preventDefault();
                    }
                });
                initTable();
                $('#load_page').fadeOut('fast');
                var val=$('#cal_last_year_val').val();
                console.log("va2l = "+val+" => "+(val/100000).toFixed(2));
                $('#txt_last_year_sale').html((val/100000).toFixed(2));
                var exp_str=document.getElementById("ntab1").innerText;
                $('#expensetitle').html(exp_str);
                exp_str=exp_str.split("|");
                console.log(exp_str);

                var target_sale_per=Number($('#txt_tar_sal').val());
                var lst_sale=Number($('#txt_last_full').val());
                console.log(lst_sale);
                var target_sale=(target_sale_per/100)*lst_sale;
                target_sale=target_sale+lst_sale;
                var show=(target_sale/100000).toFixed(2);
                      console.log(show);
                       target_sale=lst_sale;
                if(target_sale_per>10 && target_sale_per<100)
              {
                $('#tar_val').html(show);
              }
                //$('#tar_val').html(show);

                if(typeof exp_str[1] != 'undefined')
                { console.log(exp_str[1]);
                   exp_str=exp_str[1].split("%)");
                  console.log(exp_str[0]);
                  if(exp_str[0]!='')
                  {
                    $('#txt_dep_exp').html(exp_str[0]);
                    $('#txt_cur_exp_1').val(exp_str[0]);
                  }
                }
                else{
                  $('#txt_dep_exp').html('0');
                  $('#txt_cur_exp_1').val('0');
                }

                //$('#modal_data').html(data1);
                //$('#viewtable').dataTable();
                //modal.style.display = "block";
            },
            error:function(err){
              console.log(err);
            }
          });
        }
        else{
          alert("Please Select month");
        } 
      }
      function overall_load()
      {
        //alert("****overall_load");
        var branch = document.getElementById('branchname').value;
        var month = document.getElementById('month').value;
        var year = '2018';

        $.ajax({
          url:"viki/budget_entry.php",
          data:{
            branch:branch,
            month:month,
            year:year,
            action:'overall'
          },
          dataType:'html',
          async: false,
          success:function(data1)
          {
              alternativeload();
              //alert("success");
              if(data1!='')
              {
                $('#overall_table').html(data1);
                $('.Number').keypress(function (event) {
                    var keycode = event.which;
                    if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
                        event.preventDefault();
                    }
                });
                var val=$('#cal_last_year_val').val();
                console.log("va1l = "+val+" => "+(val/100000).toFixed(2));
                $('#txt_last_full').val(val);
                $('#txt_last_year_sale').html((val/100000).toFixed(2));
              }
          },
          error:function(err){
            console.log(err);
          }
      });
    }
    </script>
<!-- END SCRIPTS -->
</body>
</html>
<? } else { ?>
    <script>window.location="home.php";</script>
<?php exit();
}