<?
session_start();
error_reporting(0);
header('X-UA-Compatible: IE=edge');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

$menu_name = 'ADMIN DASHBOARD';
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

if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_request'] != '')
{
   /* switch($txt_process_type) {
        case 1 : // ORIGINAL APPROVAL NEED
            // Update into approval_request Table for Original Print Need
            $tbl_approval_request = "approval_request";
            $field_approval_request = array();
            $field_approval_request['APPRMRK']  = "";
            $where_approval_request = " arqsrno = 1 and aprnumb like '".$txt_aprnumb."' ";
            // print_r($field_approval_request);
            $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
            // Update into approval_request Table for Original Print Need
            break;

        case 2 : // APPROVAL AUTO FORWARD
            /* Update into approval_request Table for APPROVAL AUTO FORWARD
            $tbl_approval_request = "approval_request";
            $field_approval_request = array();
            $field_approval_request['APPRMRK']  = "";
            $where_approval_request = " arqsrno = 1 and aprnumb like '".$txt_aprnumb."' ";
            // print_r($field_approval_request);
            // $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
            // Update into approval_request Table for APPROVAL AUTO FORWARD */
          /*  break;

        case 3 : // PROJECT ID CHANGE, AFTER APPROVAL
            $sql_apst = select_query_json("select * from approval_request where appstat = 'A' and aprnumb like '".$txt_aprnumb."'", "Centra", 'TCS');
            if(count($sql_apst) > 0) {
                // Update into approval_request Table for PROJECT ID CHANGE, AFTER APPROVAL
                $tbl_approval_request = "approval_request";
                $field_approval_request = array();
                $field_approval_request['APRCODE']  = $slt_project;
                $where_approval_request = " appstat = 'A' and aprnumb like '".$txt_aprnumb."' ";
                // print_r($field_approval_request);
                $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
                // Update into approval_request Table for PROJECT ID CHANGE, AFTER APPROVAL
            } else { ?>
                <script>window.location='admin_process.php?action=add&msg=This approval is not yet approved.';</script>
                <?php exit();
            }
            break;
        default:
            break;
    }

    // exit;
    if($update_approval_request == 1) { ?>
        <script>window.location='admin_process.php?status=success';</script>
        <?php exit();
    } else { ?>
        <script>window.location='admin_process.php?action=add&status=failure';</script>
        <?php exit();
    }
    */


}

if($inner_menuaccess[0]['VEWVALU'] == 'Y') { // Menu Permission is allowed ?>

<!DOCTYPE html>
    <html lang="en">
    <head>
    <!-- META SECTION -->
    <title>Service Entry :: Approval Desk :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <!-- Select2 -->
    <link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">
    <link href="css/jquery-customselect.css" rel="stylesheet" />
        <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
        <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
        <!-- multiple file upload -->
        <link href="css/jquery.filer.css" rel="stylesheet">

    <style type="text/css">
    <style type="text/css"> 
    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        opacity: 0.4;
        background: url('images/l2.gif') 50% 50% no-repeat rgb(249,249,249);
    }
    .loadinggif {
    background:url('images/l_spin_n.gif') no-repeat left;
    }
    .mine{font-size: 20px;color: red;}
    .colr_red{color:red;}

    .select2-container--default .select2-selection--single{
    border: 1px solid #d2d6de !important;
    border-radius: 0px !important;
    } 
    .select2-container .select2-selection--single {
        height: 34px !important;
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
        .load_page {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                opacity: 0.4;
                // background: url('images/cassette.jpg') 50% 50% no-repeat rgb(249,249,249);
                background: url('../images/preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                }

        .panel .panel-body {
            padding: 15px 15px 15px 5px !important;
            position: relative;
        }

        .input-group .input-group-addon {
            border-color: #1caf9a !important;
            background-color: #1caf9a !important;
        }
        .input-group-btn .btn-primary {
            background-color: #1caf9a !important;
            border-color: #1caf9a !important;
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
        <div  class = "loader" id="load_page" style='display:block;padding:12% 40%;'></div> 
        <!-- START PAGE CONTAINER -->
        <div class="page-container page-navigation-toggled">

            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <? include 'lib/app_left_panel.php'; ?>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->

            <!-- PAGE CONTENT style="overflow: hidden;"-->
            <div class="page-content" >
            
                <!-- START X-NAVIGATION VERTICAL -->
                <? include "lib/app_header.php"; ?>
                <!-- END X-NAVIGATION VERTICAL -->

                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="home.php">Dashboard</a></li>
                    <li class="active">Request Entry</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE CONTENT WRAPPER -->
                
               <?
              //$supplierCount = select_query_json("select count(*) as count from supplier where deleted = 'N' and SUPCODE>=7000","Centra","TCS");
              
               ?>                                
                
                <!-- PAGE TITLE -->
                <div class="page-title">                    
                   <h2><span class="fa fa-users"></span> Supplier Service Request Entry <small></small></h2>
                </div>
                <!-- END PAGE TITLE -->                
                
                <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default tabs" >                            
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="active"><a href="#tab-first" role="tab" data-toggle="tab">Request Entry</a></li>
                                        
                                    </ul>                            
                                <div class="panel-body tab-content">
                                        <div class="tab-pane active" id="tab-first">
                                 <form name="entryForm" id="entryForm" method="post" action="" class='my-form' enctype="multipart/form-data">            
                                   <div id="reopen_div" style="display: block;">
                                        <div class="form-group col-md-12">
                                        <label> Supplier : <span class="colr_red">*</span> </label>
                                        <div class="input-group">
                                         <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                         </div>
                                         <input type="text" class="form-control" placeholder="Choose the Supplier" name="supplier" id="supplier"  onchange="get_sup_details(this.value)" required style="width: 100%;" value="">
                                         </div>
                                <!-- /.input group    " -->
                                        </div>


                                        <div class="col-md-12" style="padding: 0px;">
                                        <div class="form-group col-md-6">   
                                        <label> Contact No : <span class="colr_red">*</span> </label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                          </div>
                                         <input type="text" class="form-control" placeholder="Enter Contact No" name="contact_no" id="contact_no" maxlength="15"  onkeypress="return isTeleNumber(event)"; required>
                                        </div>
                                        <!-- /.input group -->
                                        </div>

                                        <div class="form-group col-md-6">
                                        <label> Alternate No : </label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                          </div>
                                         <input type="text" class="form-control" placeholder="Enter Desk Telephone No" name="alternate_no" id="alternate_no" maxlength="15" onkeypress="return isTeleNumber(event)";>
                                        </div>
                                        <!-- /.input group -->
                                        </div>

                                        <div class="col-md-12" style="padding: 0px;">
                                        <div class="form-group col-md-6">   
                                        <label> Email-ID : <span class="colr_red">*</span> </label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-envelope"></i>
                                          </div>
                                         <input type="email" class="form-control" placeholder="Enter Email-ID" name="email" id="email" required maxlength="40">
                                        </div>
                                        </div>
                                        <!-- /.input group -->

                                        <div class="form-group col-md-6">
                                        <label> Desk Telephone No : <?if($_SESSION['tcs_empsrno'] !=""){?> <span class="colr_red">*</span> <?}?></label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-phone-square"></i>
                                          </div>
                                         <input type="text" class="form-control" placeholder="Enter Desk Telephone No" name="desk_no" id="desk_no" maxlength="12" onkeypress="return isTeleNumber(event)"; <?if($_SESSION['tcs_empsrno'] !=""){?> required <?}?> >
                                        </div>
                                        <!-- /.input group -->
                                        </div>

                                        </div>

                                        <div class="form-group col-md-12">
                                        <label>  Request Type : <span class="colr_red">*</span> </label>
                                        <div class="input-group">
                                         <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                         </div>
                                         <select class="form-control" data-placeholder="Select a Complaint Type" name="comp_name" id="comp_name" required style="width: 100%;"> 
                                            <option value=""> Choose Any Complaint </option>
                                            <? $complaintMaster = select_query_json("Select comcode,comname from APP_COMPLAINT_MASTER where deleted='N' order by COMCODE ASC","Centra","TCS");
                                                foreach($complaintMaster as $res) { ?>
                                                    <option value="<?=$res['COMCODE']?>"> <?=$res['COMNAME']?> </option>
                                            <? } ?>
                                        </select>
                                       
                                        </div>
                                <!-- /.input group -->
                                        </div>

                                        <div class="form-group col-md-12">
                                        <label> Message : <span class="colr_red">*&nbsp;(Note:Maximum 250 Characters Allowed)</span></label>
                                        <div class="input-group">
                                          <div class="input-group-addon " id="div_sec" aria-hidden="true"  style="vertical-align: middle;" >
                                            <i class="fa fa-edit" id="i_sec"></i>
                                          </div>
                                        <textarea rows=10 class="form-control" name="tcsComment" id="tcsComment" required maxlength="250" style="border-radius:10px;text-transform: uppercase;"></textarea>
                                        </div>
                                        <!-- /.input group -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="col-md-8">
                                              <div class="input-group margin">
                                            
                                                <div class="input-group-btn">
                                                <span class="colr_red">*</span>
                                                <button type="button" class="btn btn-danger">Attach file</button>
                                                </div>
                                                <!-- /btn-group onchange="upload_image('desfile')"-->
                                                <input type="file" class="form-control" id="desfile" name="desfile[]" multiple="multiple"  accept="image/*,audio/*,video/*,.pdf"> 
                                            </div>
                                            </div>
                                            

                                            <div class="col-md-4">
                                            <div class="input-group margin">
                                            
                                            <button type="button" name='newsave' id='newsave' class="btn btn-success"><i class="fa fa-save"></i>&nbsp;Submit</button>
                                            <!--<input type="button" name="newsave" id="newsave" value="SAVE" class="form-control btn-info" style="width: 85px;border-radius: 5px;"> -->
                                            
                                            </div>
                                            </div>
                                        </div>
                                    </div>



                                  
                
                                </div>  <!-- main div  -->  
                            </form>
                            </div>  <!-- tab active end -->  
                        </div>  <!-- panel body end -->  
                       
                      </div>
                 <!-- Box end -->      
                </div>
                <!-- ROW END --> 
                    <!-------------------------------------------------------->
                     <div class="modal fade" id="reopen_model">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Message</h4>
                          </div>
                          <div class="modal-body" id="reopen_body" style="color: red;font-size: x-large;font-weight: bolder;text-align: -webkit-center;">
                            <p id="div_p">&hellip;</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>

                    <!-------------------------------------------------------->

              </div>
                <!-- END PAGE CONTENT WRAPPER -->                                                 
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

               
        
    <!-- START SCRIPTS -->
        <script type="text/javascript" src="js/jquery.js"></script>
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
        <script src="js/jquery-ui-1.10.3.custom.min.js"></script>        
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
         <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
        <!-- END THIS PAGE PLUGINS-->        
        <!-- Prakash custom -->

        <!-- START TEMPLATE -->

        <script type="text/javascript" src="js/settings.js"></script>
        
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>        
        <!-- END TEMPLATE -->

     <script type="text/javascript">
         
         
    $(window).load(function() {
        $("#load_page").fadeOut("slow");
        //$('#comp_name').select2();
        //$('.loader').show();
    });

   

     $('#supplier').autocomplete({

          source: function( request, response ) {
            $.ajax({
              url : 'ajax_complaint_prakash.php?action=supplier',
              dataType: "json",
              data: {
                 name_startsWith: request.term,
                 
              },
              success: function( data ) {
               if(data == '0') {
                                            $('#supplier').val('');
                                            alert("No Data!");
                                        } else {
                                            response( $.map( data, function( item ) {
                                                return {
                                                    label: item,
                                                    value: item
                                                }
                                            }));
                                        }
 
                    
                },
              
              
            change: function(event,ui)
                { 
                    if (ui.item == '') 
                    {
                        $("#supplier").val('');
                        //$("#supplier").blur(); 
                    }
              
                }

            });
          },
          autoFocus: true,
          minLength: 0
        });




    function view_tab(tabid){
            if(tabid == '1'){
                document.getElementById('tab_1').style.display='block';
                document.getElementById('tab_2').style.display='none';
                $("#tab1").addClass("active");
                $("#tab2").removeClass("active");
                 $('#comp_name').select2();
                 $('#supplier').select2();
            } else {
                document.getElementById('tab_2').style.display='block';
                document.getElementById('tab_1').style.display='none';
                $("#tab2").addClass("active");
                $("#tab1").removeClass("active");
                chat_view();
            }
        }

        function isTeleNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(evt+"****"+charCode);0
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43 && charCode != 45) {
            return false;
        }
        return true;
        }

        function chat_view(){
            $('.loader').show();
            $.ajax({
            url:"ajax_complaint_entry.php?mode=chat",
            success:function(data)
            {
                $('.loader').hide();
                $('#chat_content').html(data);
                $('.tab-content').css('overflow-y','scroll');
                if($('#login_user').val()==0){
                    for (var i = 0; i <= $('#total_chat').val(); i++) {
                    
                    var chat1 = $("#chat1_left"+i).html();
                    var chat2 = $("#chat2_right"+i).html();
                    $("#chat1_left"+i).html("");
                    $("#chat2_right"+i).html("");
                    $("#chat1_right"+i).html(chat1);
                    $("#chat2_left"+i).html(chat2);
                    $("#chat1_right"+i).css('float','right');
                    $("#chat2_left"+i).css('float','left');
                    $("#chat1_left"+i).css('float','');
                    $("#chat2_right"+i).css('float','');
                    $("#chat_name1"+i).html("You");
                    $("#chat_name2"+i).html("Supplier");
                    $("#content_box2"+i).css({'background-color':'#017701','color':'#fff'});
                    $("#content_box1"+i).css({'background-color':'#c1c3c1','color':'black'});
                   }
                }else{ }
            }
        });
    }    

    function upload_image(id)
    {
        var x = document.getElementById(id);
        var chk_file = x.value;
        Extension = chk_file.substring(chk_file.lastIndexOf('.') + 1).toLowerCase();
        if (Extension == "png" || Extension == "jpeg" || Extension == "jpg" || Extension == "pdf") {

        for (var i = 0; i < x.files.length; i++) 
        {
            var file = x.files[i];
            if ('size' in file) {
                if(file.size > 2097152)
                {
                    $("#modal-default").modal('show');
                    document.getElementById('div_p').innerHTML="File Size Must Be With in 2 MB";
                    document.getElementById(id).value = '';
                    document.getElementById(id).focus;
                }
            }
        }
        }else{
            $("#modal-default").modal('show');
            document.getElementById('div_p').innerHTML="File Must .jpeg or .png or PDF";
            document.getElementById(id).value = '';
            document.getElementById(id).focus;
        }
         
    }
    
$('#newsave').click(function(){
    var error = 0;
    var msg = 'Please enter all the required fields \n';
    $(':input[required]', '#entryForm').each(function(){
        $(this).css('border','1px solid #00000040');
          if($(this).val() == ''){
            msg; //+= '\n' + $(this).attr('id') + ' Is A Required Field..';
            $(this).css('border','2px solid red');
            if(error == 0){ $(this).focus(); }
            error = 1;
        }
    });

    if(error == 0){
            $(this).focus();
            var tab = $(this).closest('.tab-pane').attr('id');
            $('#myTab a[href="#' + tab + '"]').tab('show');
    }
    if(error == 1) {
        alert(msg);
        return false;
    } else {
        saveComplaint();
        return true;
    }
    });

    function get_sup_details(supp){
        
           
            var suppID = supp.split(" - ");
            var code = suppID[0];
        $.ajax({

        url:"ajax_complaint_prakash.php?mode=supp_detail&supcode="+code,
        }).done(function(data) 
        {   
            data1 = data.split('~');
            phn = data1[0].split(',');
            $('#contact_no').val(phn[0]);
            $('#alternate_no').val(phn[1]);
            $('#email').val(data1[1]);  
        });
    }

function saveComplaint()
    {
        if(confirm('Are you sure to save this complaint?'))
        {
        var form_data = new FormData(document.getElementById("entryForm"));
        $('#load_page').show();
        $.ajax({
                        //url:"viki/post_test.php",
        url:"ajax_complaint_prakash.php?mode=SAVE",
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false 
        }).done(function(data) 
        {   
            if(data == "size"){
                alert("Your Upload Files Exceed 20 MB.Reduce Your File Size...!");
                $('.loader').hide();
            }else{
            var data =  jQuery.parseJSON(data);
            if(data['Success'] == "1")
            {
                alert('Request Saved successfully...!');
                $(':input','#entryForm')
                 .not(':button, :submit, :reset, :hidden')
                 .val('')
                 .removeAttr('checked')
                 .removeAttr('selected');
                 $('.loader').hide();
            }else {
                alert('Request Saving Field...!Error-'+data['Msg']);
                $('.loader').hide();
            }
            } //else    
        });
            return false;
    } else {
        return false;
            }
        }


    function reopen_cmd(reqnum,reqsrno)
        {
            $('.loader').show();
            $('#tab_1').css('display','block');
            $('#tab_2').css('display','none');
            $("#tab1").addClass("active");
            $("#tab2").removeClass("active");
            $('#reopen_div').css('display','none');
            $('#reopen').val('YES');
            $('#reqnumb').val(reqnumb);
            $('#reqsrno').val(reqsrno);
            $('.loader').hide();
            
        }   
 

   
     </script>

       
   </body>

    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>





