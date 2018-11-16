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

$sql_emprights = select_query_json("select * from tlu_employee_rights where deleted = 'N' and empsrno = '".$_SESSION['tcs_empsrno']."'", "Centra", "TCS");
$_SESSION['tlu_emp_prhdcod'] = 1;
if(count($sql_emprights) > 0) {
    $_SESSION['tlu_emp_prhdcod'] = $sql_emprights[0]['PRSCODE'];
}
// echo "**".$_SESSION['tlu_emp_prhdcod']."**";
// echo "........".$_SESSION['tcs_tlumenu_prev_prscode'].".......".$_SESSION['tcs_tlumenu_prscode']."........";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Employee Monitor Board :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

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
    .content-frame-body{
      overflow-x : scroll;
      padding-right:50px;
    }
    .list_view{
      min-width:2300px;

    }
    .nonadv_list{
      width:300px;
      margin:5px 10px;
      float:left;
    }
    .tasks{
       height: 740px;
       overflow-y: scroll;
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
       padding-bottom:10px;
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
                <li class="active">Employee Monitor Board</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">



                <!-- START CONTENT FRAME BODY -->
                <div class="content-frame-body" style="margin-left:0px;">

                    <div class="list_view push-up-12" style="overflow-x: scroll;" id='id_monitor_board'>

                      <?  $sql_ord_confirm = select_query_json("select * from tlu_process_master where deleted = 'N' order by PRSSRNO", "Centra", "TCS");
                          $tme = 0;
                          foreach ($sql_ord_confirm as $key => $ord_confirm_value) { $tme++; ?>
                            <div class="nonadv_list">
                                <h3 class="text-center"><?=$ord_confirm_value['PRSTITL']?></h3>

                                <div class="tasks" id="tasks_<?=$ord_confirm_value['PRSCODE']?>">

                                  <? 
                                  if($_SESSION['tlu_emp_prhdcod'] == 4 or $_SESSION['tlu_emp_prhdcod'] == 5) {
                                    $sql_timer = select_query_json("select * from tlu_stitching_detail pop, tlu_order_timer tim, userid usr
                                                                            where usr.usrcode = tim.adduser and pop.BRNCODE = tim.BRNCODE and pop.ENTYEAR = tim.ENTYEAR and pop.ENTNUMB = tim.ENTNUMB and 
                                                                                pop.ENTSRNO = tim.ENTSRNO and pop.SUBSRNO = tim.SUBSRNO and tim.deleted = 'N' and pop.deleted = 'N' and 
                                                                                tim.PRSCODE = '".$ord_confirm_value['PRSCODE']."' and tim.adduser = '".$_SESSION['tcs_usrcode']."'
                                                                            order by pop.PRIORIT, pop.BRNCODE, pop.ENTYEAR, pop.ENTNUMB, pop.ENTSRNO, pop.SUBSRNO, tim.PRSCODE", "Centra", "TCS");
                                  } else {
                                    $sql_timer = select_query_json("select * from tlu_stitching_detail pop, tlu_order_timer tim, userid usr
                                                                            where usr.usrcode = tim.adduser and pop.BRNCODE = tim.BRNCODE and pop.ENTYEAR = tim.ENTYEAR and pop.ENTNUMB = tim.ENTNUMB and 
                                                                                pop.ENTSRNO = tim.ENTSRNO and pop.SUBSRNO = tim.SUBSRNO and tim.deleted = 'N' and pop.deleted = 'N' and 
                                                                                tim.PRSCODE = '".$ord_confirm_value['PRSCODE']."'
                                                                            order by pop.PRIORIT, pop.BRNCODE, pop.ENTYEAR, pop.ENTNUMB, pop.ENTSRNO, pop.SUBSRNO, tim.PRSCODE", "Centra", "TCS");
                                  }

                                    if(count($sql_timer) > 0) {
                                      foreach($sql_timer as $key => $timer_value) {
                                         switch ($timer_value['PRIORIT']) {
                                            case 1:
                                              $taskcls_name = "task-danger";
                                              break;
                                            case 2:
                                              $taskcls_name = "task-warning";
                                              break;
                                            case 3:
                                              $taskcls_name = "task-success";
                                              break;

                                            default:
                                              $taskcls_name = "task-primary";
                                              break;
                                          } ?>
                                        <div class="task-item <?=$taskcls_name?>">
                                            <div class="task-text" onclick="popup_order_details('<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')">
                                              <B>ORDER NUMBER : <?=$timer_value['BRNCODE']."-".$timer_value['ENTYEAR']."-".$timer_value['ENTNUMB']."-".$timer_value['ENTSRNO']."-".$timer_value['SUBSRNO']?><br>
                                                <? echo $timer_value['PRDNAME']?>
                                                <? /* <br> User : <?=$timer_value['USRCODE']." - ".$timer_value['USRNAME']?> */ ?>
                                              </B></div>
                                            <input type="hidden" id="task_id" value="<? echo $timer_value['BRNCODE']."-".$timer_value['ENTYEAR']."-".$timer_value['ENTNUMB']."-".$timer_value['ENTSRNO']."-".$timer_value['SUBSRNO'];?>"/>
                                            <input type="hidden" id="task_flow" value="<? echo $timer_value['PRSCODE']?>"/>
                                            <div class="task-footer">
                                              <? // if ($timer_value['STRTIME'] == '') { ?>
                                                <div class="pull-left">
                                                  <input type="text" id="timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>" value="<?=$timer_value['EXPTIME']?>" style="background:#f5f5f5;border:none"/>
                                                </div>
                                                <div class="pull-right">
                                                  <span class="fa fa-user" onclick="assign_user('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')" style="color:red;margin-right:5px"></span>
                                                  <? /*
                                                    if($tme != 1) { ?><span onclick="startTime('<?=$ord_confirm_value['PRSCODE']?>' , '<?echo $timer_value['ENTNUMB'];?>');" class="fa fa-clock-o" style="color:green"></span><? } else { echo "&nbsp;"; } ?></div>
                                                  <?
                                              }else {
                                                $tim = explode(" " , $timer_value['PRSTIME']);
                                                ?>
                                                <div class="pull-left">
                                                  <input type="text" id="timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>" value="<?=$timer_value['EXPTIME']?>" style="background:#f5f5f5;border:none"/>
                                                    <script type="text/javascript">

                                                      function pretty_time_string(num) {
                                                        return ( num < 10 ? "0" : "" ) + num;
                                                        }

                                                        //var getdate = $('#starttime_3_3').val();
                                                        var start = new Date("<?echo $timer_value['STRTIME'];?>");

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

                                                          if(currentTimeString >= '00:00:05'){
                                                             $('#timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>').addClass( "blink_me" );
                                                             $('#timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>').css('color' , 'red');
                                                           }
                                                          $('#timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>').val(currentTimeString);
                                                        }, 1000);
                                                    </script>

                                                </div>
                                                <div class="pull-right">
                                                  <? if($tme != 1) { ?>
                                                  <span class="fa fa-play" id="play_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>" style="color:blue;margin-right:5px;display:none"></span>
                                                  <span class="fa fa-pause" id="pause_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>" style="color:blue;margin-right:5px"></span>
                                                  <span class="fa fa-clock-o" style="color:red"></span><? } else { echo "&nbsp;"; } ?>
                                                </div>
                                                <?
                                                } */
                                                ?>
                                              </div>
                                            </div>
                                            <script>
                                                function startTime(row , col){
                                                  $.ajax({
                                                     url : 'ajax/ajax_timer.php?action=track_update',
                                                     type: 'post',
                                                     dataType: "json",
                                                     data: {
                                                       step : row,
                                                       step_end : col,
                                                       id: 0
                                                     },
                                                     complete: function(){
                                                        //$('#load_page').show();
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
                                                             $('#timer_'+row+'_'+col).addClass( "blink_me" );
                                                             $('#timer_'+row+'_'+col).css('color' , 'red');
                                                           }
                                                          $('#timer_'+row+'_'+col).val(currentTimeString);
                                                       }, 1000);

                                                     }
                                                   });
                                                 }
                                              </script>
                                        </div>
                                    <? } } ?>
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

    <? include "lib/app_footer.php"; ?>

    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>

    <!-- Show Modal Window -->
    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Order Details</div>
                <div class="modal-body" id="modal-body1" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>
    <!-- Show Modal Window -->

    <!-- Show Modal Window -->
    <div id="myModal2" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Assign User</div>
                <div class="modal-body" id="modal-body2" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>
    <!-- Show Modal Window -->

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
    <script type="text/javascript">
        $(document).ready(function ($) {
            $('#txt_empcode').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
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
        });

        $('.ui-sortable').sortable({
            items: '> :not(.nodragorsort)'
        });

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
            var sendurl = "ajax_assign_user.php?current_step="+current_step+"&brncode="+brncode+"&entry_year="+entry_year+"&entry_no="+entry_no+"&entry_srno="+entry_srno+"&entry_sub_srno="+entry_sub_srno;
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

        function save_monitor_board(step_end, brncode, entry_year, entry_no, entry_srno, entry_sub_srno) {
            $('#load_page').show();
            var txt_empcode = $('#txt_empcode').val();
            var store_id = brncode+"-"+entry_year+"-"+entry_no+"-"+entry_srno+"-"+entry_sub_srno; // 121-2018-19-2-1-1
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
    </script>
    <!-- END TEMPLATE -->
<!-- END SCRIPTS -->
</body>
</html>
