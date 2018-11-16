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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Request List :: Approval Desk :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
<link rel="icon" href="favicon.ico" type="image/x-icon" />

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

<style type="text/css">
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
</style>
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<!-- EOF CSS INCLUDE -->
</head>
<body>

    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> process_requirement_view.php*/ ?>
    <!-- START PAGE CONTAINER -->
    <div class="page-container page-navigation-top-fixed">

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
                <li class="active">Requirement List</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Report</strong></h3>
                        <ul class="panel-controls">

                        </ul>
                    </div>
                    <div class="form-group trbg non-printable">
                                <form class="form-horizontal" role="form" id="frm_supplier_list" name="frm_supplier_list" action="" method="post" enctype="multipart/form-data">
                                    

                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px 5px 0 5px;'>
                                        <input type='text' class="form-control" tabindex='1' name='search_sprno' id='search_sprno' value='<?=$_REQUEST['search_sprno']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='SUPPLIER NAME/CODE/MOBILE NO' title="SUPPLIER NAME/CODE/MOBILE NO" style='text-transform: uppercase;'>
                                    </div>

                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px;'>
                                        <input type='text' class="form-control" tabindex='2' name='search_value' id='search_value' value='<?=$_REQUEST['search_value']?>' data-toggle="tooltip" data-placement="top" maxlength="20" placeholder='REQUEST ID' title="REQUEST ID" style='text-transform: uppercase;'>
                                    </div>
                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px;'>
                                        <input type='text' class="form-control" tabindex='3' style="text-transform: uppercase;"  name='search_user' id='search_user' value='<?=$_REQUEST['search_user']?>' data-toggle="tooltip" data-placement="top" maxlength="10" placeholder='ASSIGNED USER' title="ASSIGNED USER" style='text-transform: uppercase;' autocomplete='off'> 
                                       
                                    </div>
                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px;'>
                                        <select class="form-control" tabindex='4'  name='status_type' id='status_type' data-toggle="tooltip" data-placement="top" title="STATUS TYPE" >
                                                 <option value='<?=$_REQUEST['status_type']?>'selected>CHOOSE THE MODE </option>
                                                 <option value="A" >ASSIGNED</option>
                                                 <option value="C" >CLOSED</option>
                                            </select>
                                    </div>

                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px 5px 0 6px;'>
                                        <input type='hidden' name='search_add_findate' id='search_add_findate' value='ADDDATE' >
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                    </div>

                                    <div class="col-lg-2 col-sm-2" style='text-align:center; padding:5px;'>
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
                                    </div>

                                    <div class="col-lg-2 col-sm-2 pull-right" style='text-align:right; padding:5px;'>
                                        <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
                                    </div>
                                </form>
                    </div>
                    
                     
                      <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>
                        
                      <table  class="table datatable table-striped  no-footer">
                          <thead>
                              <tr>
                                <th class="center" style='text-align:center'>S.No</th>
                                <th class="center" style='text-align:center'>Request Id</th>
                                <th class="center" style='text-align:center'>Date</th>
                                <th class="center" style='text-align:center'>Supplier Name</th>
                                <th class="center" style='text-align:left'>Supplier Contact</th>
                                <th class="center" style='text-align:center'>Assigned Member</th>
                                <th class="center" style='text-align:center'>Desk No.</th>
                                <th class="center" style='text-align:center'>Status</th>
                                <th class="center" style='text-align:center'>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                           
                           <? $and = "";
                            if($search_sprno != '') {
                                        $and1 = "  and sup.SUPNAME like '%".strtoupper($search_sprno)."%' or sup.supmobi1 like '%".strtoupper($search_sprno)."%' or sup.supcode like '%".strtoupper($search_sprno)."%' ";
                                    }
                                   
                            if($search_value !=''){
                                $and .= " and SRR.REQNUMB =".strtoupper($search_value)." ";
                            } /*
                            if($search_user!=''){
                                 $and .= " And RESTUSR like '%".strtoupper($search_user)."%' ";
                            }
                            if($status_type!=''){
                                 $and .= " And REQSTAT like '%".strtoupper($search_user)."%' ";
                            }*/
                            if($and !=''){
                                        if($search_fromdate != '' or $search_todate != '') {
                                                    if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                                    $exp1 = explode("-", $search_fromdate);
                                                    $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                                    if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                                    $exp2 = explode("-", $search_todate);
                                                    $to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));

                                                    if($search_add_findate == 'ADDDATE') {
                                                        $and .= " and  trunc(SRR.REQDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                                    } elseif($search_add_findate == 'FINDATE') {
                                                        $and .= " And trunc(ar.FINDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                                    }
                                                }
                                }
                                else{
                                    if($search_fromdate != '' or $search_todate != '') {
                                        if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                        $exp1 = explode("-", $search_fromdate);
                                        $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                        if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                        $exp2 = explode("-", $search_todate);
                                        $to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));

                                        if($search_add_findate == 'ADDDATE') {
                                            $and .= " and  trunc(SRR.REQDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                        } elseif($search_add_findate == 'FINDATE') {
                                            $and .= " And trunc(ar.FINDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                        }
                                    }

                                }
                                    /*
                                }
                            if($and != '') {
                                        
                                        $sql_brn = select_query_json("select BRNCODE, esecode from employee_office where empsrno = '".$_SESSION['tcs_userid']."'", "Centra", 'TCS');
                                        if($sql_brn[0]['BRNCODE'] == 888 or $sql_brn[0]['BRNCODE'] == 100) {
                                            // $rqusr = " and ar.REQESEC=".$sql_brn[0]['ESECODE']." ";
                                            $rqusr = " and ar.REQESEC=".$_SESSION['tcs_esecode']." ";
                                        }

                                        $sql_search = select_query_json("select ", "Centra", 'TCS');
                                    } else {
                                       
                                        $sql_search = select_query_json("select ", "Centra", 'TCS');
                                    }*/
                                    
                                    //$and .= "SRR.REQNUMB =".$search_value;
                         $sql_search1 = select_query_json("SELECT SRE.REQCONT,SRE.REQMAIL,SRE.REQDESKNO,SRE.REQALTCONT,TO_CHAR(SRE.REQNUMB) REQNUMB,SRE.REQSTAT,SRE.REQDESKNO FROM service_request SRE WHERE ".$req."", "Centra", "TEST");

                         $sql_search = select_query_json("select srr.REQNUMB,srr.REQSRNO,srr.REQCONT,srr.REQDATE,srr.REQSTAT,srr.REQDESKNO,srr.REQUSER from service_request srr where 1=1 ".$and."  order by srr.REQDATE ","Centra","TCS");
                         
                         
                            $sno= 1;
                            for($list=0;$list<count($sql_search);$list++)
                            {?>
                          
                            <tr class="gradeX odd" style="background-color:; color:;" role="row">
                                      <td class="center sorting_1" style="text-align:center;">
                                       <? echo $sno; // SERIAL NUMBER OF THE RECORD ?>                            </td>
                                      <td class="center" style="text-align:center"><!-- for entryno-->
                                        <?echo $sql_search[$list]['REQNUMB']; ?>                                     </td>
                                      <td class="center" style="text-align:center"><!-- for entryno-->
                                        <? echo $sql_search[$list]['REQDATE'];
                                       
                                        ?>                                   </td>
                                      <td class="center" style="text-align:center"><!-- for top core-->
                                     <?$supname = select_query_json("select sup.supname,sup.supcode,sup.supmobi1 from supplier sup where sup.supcode=".$sql_search[$list]['REQUSER'],"Centra","TCS");?>
                                       <? echo $supname[0]['SUPCODE']; ?>-<? echo $supname[0]['SUPNAME']; ?><br>

                                      </td>
                                      <td class="center" style="text-align:left"><!-- for  editor details-->
                                       Contact :<?echo ' '.$sql_search[$list]['REQCONT'];?><br>
                                        <?if($sql_search[$list]['REQALTCONT']!='-'){echo 'Alternate Contact : '.$sql_search[$list]['REQALTCONT'].'<br>';}?>
                                        Mail :<?echo ' '.$sql_search[$list]['REQMAIL'];?>                       </td>
                                      <td class="center" style="text-align:center"><!-- for attachment count-->
                                     -               </td>
                                      <td class="center" style="text-align:center"><!-- for attachment count-->
                                      <? echo $sql_search[$list]['REQDESKNO']?>      </td>
                                      <td class="center" style="text-align:center"><!-- for USER DETAIL-->
                                       <?if($sql_search[$list]['REQSTAT']=='A'){echo("ASSIGNED");}if($sql_search[$list]['REQSTAT']=='N'){echo("NOT ASSIGNED");}
                                        if($sql_search[$list]['REQSTAT']=='C'){echo("CLOSED");}?>         </td>
                                      <td class="center" style="text-align:center"><!-- for USER DETAIL-->
                                        <btn id="view_msg" class="btn btn-warning btn-sm" onclick="getmsg(<? echo $sql_search[$list]['REQNUMB'] ?>)"><span class="fa fa-eye"></span></btn>
                                      </td>
                                    </tr>
                                    <?$sno++;}?>
                          </tbody>
                      </table>
                  </div>
                </div>


                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
</form>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>

    <!-- Collect Document -->
    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>APPROVAL FINAL FINISH</div>
                <div class="modal-body" id="modal-body1"></div>
            </div>
        </div>
    </div>

    <div id="myModal2" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-body" id="modal-body2"></div>
            </div>
        </div>
    </div>
    <!-- Collect Document -->
    <div class='clear'></div>

<!-- START SCRIPTS -->
    <!-- START PLUGINS -->

     <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- END PLUGINS -->
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
    <!-- START THIS PAGE PLUGINS-->  
     <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
 <script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
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
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
<!-- START SCRIPTS -->
    <!-- START PLUGINS -->
   
   
  
    
   
    <!-- END PLUGINS -->
  
   
  
    <script type="text/javascript">
       
      // var a ='<?//echo "select srr.REQNUMB,srr.REQSRNO,srr.REQCONT,srr.REQDATE from service_request srr where 1=1 ".$and." order by srr.REQDATE";?>';
       //var date = '<?//echo $frm_date;?>';
       //console.log(a);

         //auto complete
      
            //alert("sd");
           $('#search_user').autocomplete({

          source: function( request, response ) {
            $.ajax({
              url : 'ajax/ajax_employee_details.php',
              dataType: "json",
              data: {
                 name_startsWith: request.term,
                 type: 'employee'
              },
              success: function( data ) {
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
          minLength: 0
        });


    function PrintDiv(dataurl) {
        var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
    }

    $(function() {
        var showTotalChar = 200, showChar = "Show (+)", hideChar = "Hide (-)";
        $('.show_moreless').each(function() {
            var content = $(this).text();
            if (content.length > showTotalChar) {
                var con = content.substr(0, showTotalChar);
                var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                var txt= '<b>'+con +  '</b><span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="javascript:void(0)" class="showmoretxt">' + showChar + '</a></span>';
                $(this).html(txt);
            }
        });

        $(".showmoretxt").click(function() {
            if ($(this).hasClass("sample")) {
                $(this).removeClass("sample");
                $(this).text(showChar);
            } else {
                $(this).addClass("sample");
                $(this).text(hideChar);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });

    $(document).ready(function() {
        $("#load_page").fadeOut("slow");
        $(".finish_confirm").click( function() {
        });
    });

    $(document).keypress(function(e) {
        if (e.keyCode == 27) {
            $("#myModal1").fadeOut(500);
        }
    });

    $('#datepicker-example3').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example4')
    });

    $('#datepicker-example4').Zebra_DatePicker({
      direction: [1, '<?=date("d-M-Y")?>'], // ['<?=date("d-M-Y")?>', 0], // 0,
      format: 'd-M-Y'
    });

    function call_confirm(ivalue, reqid, year, rsrid, creid, typeid, aprnumb)
    {
        $('#load_page').show();
        var send_url = "final_finish.php?aprnumb="+aprnumb+"&reqid="+reqid+"&year="+year+"&rsrid="+rsrid+"&creid="+creid+"&typeid="+typeid;
        $.ajax({
        url:send_url,
        type: "POST",
        success:function(data){
                $("#myModal1").modal('show');
                $('#load_page').hide();
                document.getElementById('modal-body1').innerHTML=data;
                $('#load_page').hide();
            }
        });
    }

    function cmnt_mail(aprnumb)
    {
        var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
        $.ajax({
        url:sendurl,
        success:function(data){
            $("#myModal2").modal('show');
            $('#modal-body2').html(data);
            $('#txtmailcnt').val("");
            }
        });
    }

    function cmt_usr()
    {
        $('#cmtusr').css("display", "block");
        $('.select2').select2();
        $('#mailusr').focus();
        //$("#mailusr").select2("open");
        $('#mailusr').select2({
        placeholder: 'Enter EC No/Name to Select an mail user',
        allowClear: true,
        dropdownAutoWidth: true,
        minimumInputLength: 3,
        maximumSelectionLength: 3,
        ajax: {
          url: 'ajax/ajax_general_temp.php?action=MAILUSER',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }
    </script>
<!-- END SCRIPTS -->
</body>
</html>
