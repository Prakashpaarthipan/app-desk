<?
session_start();
error_reporting(0);
header( 'Content-Type: text/html; charset=utf-8' );
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

$menu_name = 'APPROVAL DESK';
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

if($inner_menuaccess[0]['VEWVALU'] == 'Y') {  // Menu Permission is allowed ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- META SECTION -->
        <title>Support Messages :: Call Centre :: <?php echo $site_title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
        <link href="css/jquery-ui-1.10.3.custom.min.css" rel="Stylesheet"></link>

        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->

        <!-- Select2 -->
        <link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

        <!-- CSS INCLUDE -->
        <?  $theme_view = "css/theme-default.css";
            if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
        <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
        <!-- EOF CSS INCLUDE -->
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
      .highlight_redtitle {
          color: #FF0000;
          font-size: 20px;
      }

      .cls_left { background-color: #eaeaf3 !important; }
      .cls_right { background-color: #ebeaea !important; }
      .badge-info { background-color: #1caf9a !important; color: #FFFFFF !important; }
      .date { font-size: 10px !important; color: #000 !important; font-weight: normal !important; }
    </style>

    </head>
    <body>
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
                <ul class="breadcrumb push-down-0">
                    <li><a href="home.php">Dashboard</a></li>
                    <li class="active">Chat History</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- START CONTENT FRAME -->
                <div class="content-frame">

                    <div class="content-frame-top" style="background-color: #FFFFFF;">
                        <div class="page-title">
                            <h2><span class="fa fa-comments"></span>CHAT HISTORY</h2>&nbsp&nbsp&nbsp
                            <div id="head_titile">

                            </div>
                        </div>
                        <div class="pull-right">
                            <a href="call_centre_supplier_contacts.php" target="_blank"><button class="btn btn-danger"><span class="fa fa-book"></span> Contacts</button>
                            <button class="btn btn-default content-frame-right-toggle"><span class="fa fa-bars"></span></button></a>
                        </div>

                    </div>
                    <!-- END CONTENT FRAME TOP -->

                    <!-- START CONTENT FRAME RIGHT -->
                    <div class="content-frame-right">

                         <div id="supplier-list" class="list-group list-group-contacts border-bottom push-down-10"> </div>
                    </div>

                    <!-- END CONTENT FRAME RIGHT -->

                    <!-- START CONTENT FRAME BODY xxxxxxxx-->

                      <div class="content-frame-body content-frame-body-left" style="background-color: #FFFFFF;">
                          <form id="mainform" enctype="multipart/form-data">
                          <div  class="messages messages-img" id="all-messages">
                            <div class="page-title">
                                <h3>Welcome to Supplier Chat</h3>
                            </div>
                          </div>
                          </form>
                      </div>

                    <!-- END CONTENT FRAME BODY -->
                </div>
                <!-- END PAGE CONTENT FRAME -->
            </div>
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

        <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Are you sure you want to log out?</p>
                        <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="pages-login.html" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- END MESSAGE BOX-->

        <!-- START PRELOADS -->
        <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
        <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
        <!-- END PRELOADS -->

    <!-- START SCRIPTS -->


        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
        <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
        <!-- END PLUGINS -->

        <!-- THIS PAGE PLUGINS -->
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <!-- END PAGE PLUGINS -->

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="js/settings.js"></script>

        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/actions.js"></script>
        <!-- END TEMPLATE -->
        <script>

          //Search Script Start
          $(document).ready(function()
        {
          $("#assigned_list").keyup(function(e){
            alert("keyup");
                  $.each($('#supplier-list').find('a'), function(){
                      //alert('ga');

                      if($(this).text().toLowerCase().indexOf($('#assigned_list').val()) == -1){
                          $(this).hide();
                          //calldb();
                          //var flag=1;
                      } else {

                          $(this).show();

                      }
                  });
                });
      });

                //Search Script End

              function assign_search(){

                $("#assigned_list").keyup(function(e){
                //alert("keyup");
                  $.each($('#assign').find('a'), function(){
                      //alert('ga');

                      if($(this).text().toLowerCase().indexOf($('#assigned_list').val()) == -1){
                          $(this).hide();
                          //calldb();
                          //var flag=1;
                      } else {

                          $(this).show();

                      }
                  });
                });

              }

              function notassign_search()
              {
                  $("#notassigned_list").keyup(function(e)
                  {
                    $.each($('#notassign').find('a'), function()
                    {
                        if($(this).text().toLowerCase().indexOf($('#notassigned_list').val()) == -1){
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    });
                  });
              }


        var greqnumb;
        var greqsrno;
        $(document).ready(function()
        {
            //$(".chosn").customselect();
    		//alert("hi");
            //$("#load_page").fadeOut("slow");
            //find_checklist();
    		commentupdator();
        //details(0,0);
        $('#txt_assign').autocomplete({

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
        //details(1,1);
    		// getcore();
    });
        function details(reqnumb,reqsrno)
        {
          // alert($('#reqnumb').val());
          var strURL="call_center_viki/request_details.php";
           //var strURL="test.php";
            $.ajax({
                  type: "POST",
                  url: strURL,
        			dataType:'html',
              success: function(data1) {
                $.getScript("js/plugins/jquery/jquery-ui.min.js");
                $.getScript("http://code.jquery.com/ui/1.10.2/jquery-ui.js");

                $("#all-messages").html(data1);
                greqnumb=reqnumb;
                greqsrno=reqsrno;
                $("#reqnumb").val(reqnumb);
                head_loader(greqnumb);
                //$("#reqsrno").val(reqsrno);
                // alert($('#reqnumb').val());

                $('#txt_assign').autocomplete({
                    source: function( request, response ) {
                      $.ajax({
                          url : 'ajax/ajax_employee_details.php',
                          dataType: "json",
                          data: {
                             name_startsWith: request.term,
                             type: 'employee'
                          },
                          success: function( data ) {
                              response( $.map( data, function( item ) {
                                  return {
                                      label: item,
                                      value: item
                                  }
                              }));
                          }
                      });
                  },
                  autoFocus: true,
                  minLength: 0
              });
            },
            data: {
    				   reqnumb: reqnumb,
               reqsrno: reqsrno
    				},
      			error: function(response, status, error)
      			{
                //alert(error);
      			}
      			});
        }
        function commentupdator()
        {
      		var strURL="call_center_viki/supplier_loader1.php";
              $.ajax({
                  type: "POST",
                  url: strURL,
      			dataType:'html',
                  success: function(data1) {
                      $("#supplier-list").html(data1);
                      //reqnumb=$('#reqnumb').val();
                  },
      			error: function(response, status, error)
      			{		alert(error);
      					//alert(response);
      					//alert(status);
      			}
      			});
      	}
        function head_loader(reqnumb)
        {
      		var strURL="call_center_viki/head_loader.php";
              $.ajax({
                  type: "POST",
                  url: strURL,
            data:{
              reqnumb:reqnumb
            },
      			dataType:'html',
                  success: function(data1) {
                      $("#head_titile").html(data1);
                      //reqnumb=$('#reqnumb').val();
                  },
      			error: function(response, status, error)
      			{		alert(error);
      					//alert(response);
      					//alert(status);
      			}
      			});
      	}

        function ValidateSingleInput(oInput, file_ext) {

            //$('#load_page').show();

            if(file_ext == 'pdf') {
                var _validFileExtensions = [".pdf",".PDF"];
    			//alert(oInput+" 1\n "+file_ext);
            } else {
                var _validFileExtensions = [".jpg",".jpeg",".png",".gif",".pdf",".JPG",".JPEG",".PNG",".GIF",".PDF"];
    			//	alert(oInput+" 2\n "+file_ext);
            }
            if (oInput.type == "file") {
                var sFileName = oInput.value;
    			//alert(sFileName+" 3\n "+file_ext);
                 if (sFileName.length > 0) {
                    var blnValid = false;
                    for (var j = 0; j < _validFileExtensions.length; j++) {
                        var sCurExtension = _validFileExtensions[j];
                        if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                            blnValid = true;
                            break;
                        }
                    }

                    if (!blnValid) {
                        // alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                        // alert("Sorry, Upload Only PDF file Format");
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Kindly Upload Only PDF file. Other Formats not allowed!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                        oInput.value = "";
                        return false;
                    }
                }
              //  $('#load_page').hide();
            }
            return true;
        }

        function close_call()
        {
          var strURL='call_center_viki/close_call.php'
            $.ajax({
                  type: "POST",
                  url: strURL,
            dataType:'html',
            success: function(data1) {
                    //$("#all-messages").html(data1);
                    //details($('#reqnumb').val());
                    //$('#message').val('');
                    commentupdator();
                    details(0,0);
                  },
            data: {
               reqnumb:greqnumb
               //message: $('#message').val(),
            },
            error: function(response, status, error)
            {		alert(error);

            }
          });
          alert("CALL CLOSED");
          details(0,0);

          return false;
        }


        function sendmessage()
        {
            var form_data = new FormData(document.getElementById("mainform"));
            $.ajax({
            url:"call_center_viki/send_message.php",
            //url:"viki/post_test.php",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false
            }).done(function(data)
            {
                console.log(data);
                return false;


            });
            alert("MESSAGE SUBMITED");
            details(greqnumb,greqsrno);
            return false;
        }

        function createCustomAlert(txt, title) {
            d = document;

            if(d.getElementById("modalContainer")) return;

            mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
            mObj.id = "modalContainer";
            mObj.style.height = d.documentElement.scrollHeight + "px";

            alertObj = mObj.appendChild(d.createElement("div"));
            alertObj.id = "alertBox";
            if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
            alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
            alertObj.style.visiblity="visible";

            h1 = alertObj.appendChild(d.createElement("h1"));
            h1.appendChild(d.createTextNode(title));

            msg = alertObj.appendChild(d.createElement("p"));
            //msg.appendChild(d.createTextNode(txt));
            msg.innerHTML = txt;

            btn = alertObj.appendChild(d.createElement("a"));
            btn.id = "closeBtn";
            btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
            btn.href = "#";
            btn.focus();
            btn.onclick = function() { removeCustomAlert();return false; }

            alertObj.style.display = "block";
        }

        function removeCustomAlert() {
            document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
        }
        setInterval(commentupdator,3000000);
        //setInterval(details,10000,greqnumb,0);

      $(document).ready(function()
        {
          $("#assigned_list").keyup(function(e){
            alert("keyup");
                  $.each($('#supplier-list').find('a'), function(){
                      //alert('ga');

                      if($(this).text().toLowerCase().indexOf($('#assigned_list').val()) == -1){
                          $(this).hide();
                          //calldb();
                          var flag=1;
                      } else {

                          $(this).show();

                      }
                  });
                });
      });
        </script>
    <!-- END SCRIPTS -->
    </body>
</html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>
