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
            <? include '../lib/app_left_panel.php';?>
            <!-- END X-NAVIGATION -->
        </div>
        <!-- END PAGE SIDEBAR -->

        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
            <? include "../lib/app_header.php"; ?>
            <!-- END X-NAVIGATION VERTICAL -->

            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                <li><a href="home.php">Home</a></li>
                <li class="active">Employee Monitor Board </li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">


              <? /* <div id="gallery" style="display: block;">
                <?  $filename = "121-2018-19-2-1.jpg";
                    $folder_path = "STITCHING_IMAGE_DETAIL/STITCH_IMG/121-2018-19-2/"; ?>
                        <a href="ftp_image_views.php?pic=<?=$filename?>&path=<?=$folder_path?>"><img style='width:100px; height:100px;' alt="<?=$filename?>" src="ftp_image_views.php?pic=<?=$filename?>&path=<?=$folder_path?>"/></a>
              </div> */ ?>

                <!-- START CONTENT FRAME BODY -->
                <div class="content-frame-body" style="margin-left:0px;">

                    <div class="list_view push-up-12" style="overflow-x: scroll;" id='id_monitor_board'>

                      <?  $sql_ord_confirm = select_query_json("select * from tlu_process_master where deleted = 'N' order by PRSSRNO", "Centra", "TEST");
                          $tme = 0; $ttl_prscnt = '';
                          foreach ($sql_ord_confirm as $key => $ord_confirm_value) { $tme++; 
                            switch ($ord_confirm_value['PRSCODE']) {
                              case 1:
                                $prs_time = "30 Mins";
                                break;
                              case 2:
                                $prs_time = "30 Mins";
                                break;
                              case 3:
                                $prs_time = "20 Mins";
                                break;
                              case 4:
                                $prs_time = "25 Mins";
                                break;
                              case 5:
                                $prs_time = "1.3 Hrs";
                                break;

                              case 6:
                                $prs_time = "15 Mins";
                                break;
                              case 7:
                                $prs_time = "20 Mins";
                                break;
                              case 8:
                                $prs_time = "30 Mins";
                                break;
                              case 9:
                                $prs_time = "";
                                break;
                              case 10:
                                $prs_time = "30 Mins";
                                break;
                              
                              default:
                                $prs_time = "30 Mins";
                                break;
                            }

                            if($_SESSION['tlu_emp_prhdcod'] == 4 or $_SESSION['tlu_emp_prhdcod'] == 5) {
                               $sql_timer = select_query_json("select pop.*, tim.*, usr.*, usr.usrcode empcd, to_char(tim.adddate,'dd-MON-yyyy hh:mi:ss AM') tlutim_orddate 
                                                                      from tlu_stitching_detail pop, tlu_order_timer tim, userid usr
                                                                      where usr.usrcode = tim.adduser and pop.BRNCODE = tim.BRNCODE and pop.ENTYEAR = tim.ENTYEAR and pop.ENTNUMB = tim.ENTNUMB and 
                                                                          pop.ENTSRNO = tim.ENTSRNO and pop.SUBSRNO = tim.SUBSRNO and tim.deleted = 'N' and pop.deleted = 'N' and 
                                                                          tim.PRSCODE = '".$ord_confirm_value['PRSCODE']."' and tim.adduser = '".$_SESSION['tcs_usrcode']."'
                                                                      order by pop.PRIORIT, pop.BRNCODE, pop.ENTYEAR, pop.ENTNUMB, pop.ENTSRNO, pop.SUBSRNO, tim.PRSCODE", "Centra", "TEST");
                            } else {
                                $sql_timer = select_query_json("select pop.*, tim.*, usr.*, usr.usrcode empcd, to_char(tim.adddate,'dd-MON-yyyy hh:mi:ss AM') tlutim_orddate 
                                                                      from tlu_stitching_detail pop, tlu_order_timer tim, userid usr
                                                                      where usr.usrcode = tim.adduser and pop.BRNCODE = tim.BRNCODE and pop.ENTYEAR = tim.ENTYEAR and pop.ENTNUMB = tim.ENTNUMB and 
                                                                          pop.ENTSRNO = tim.ENTSRNO and pop.SUBSRNO = tim.SUBSRNO and tim.deleted = 'N' and pop.deleted = 'N' and 
                                                                          tim.PRSCODE = '".$ord_confirm_value['PRSCODE']."'
                                                                      order by pop.PRIORIT, pop.BRNCODE, pop.ENTYEAR, pop.ENTNUMB, pop.ENTSRNO, pop.SUBSRNO, tim.PRSCODE", "Centra", "TEST");
                            }
							
                            $ttl_prscnt = 0;
                            if(count($sql_timer) > 0) {
                                $ttl_prscnt = count($sql_timer);
                            }
                            ?>
                            <div class="nonadv_list">
                                <h3 class="text-center"><?=$ord_confirm_value['PRSTITL']?><br>
                                    <span class="pull-left blue_clr" style="font-size: 12px; padding-top: 8px; padding-left: 2px;"><? if($prs_time != '') { ?>Process Time : <?=$prs_time?><? } ?></span>
                                    <span class="pull-right blue_clr" style="font-size: 12px; padding-top: 8px; padding-right: 2px;"><? if($ttl_prscnt > 0) { ?>Count : <?=$ttl_prscnt?><? } ?></span>
								                </h3>

                                <div class="tasks" id="tasks_<?=$ord_confirm_value['PRSCODE']?>">

                                <? if(count($sql_timer) > 0) {
                                      foreach($sql_timer as $key => $timer_value) {
                                        $id_time = $timer_value['ENTYEAR']."-".$timer_value['BRNCODE']."-".$timer_value['ENTNUMB']."-".$timer_value['ENTSRNO']."-".$timer_value['SUBSRNO'];
                                        $sql_emp_user = select_query_json("select USRCODE, USRNAME from userid where usrcode = '".$timer_value['EMPCD']."'", "Centra", "TEST");
                                        $sql_stich_sm = select_query_json("select to_char(DUEDATE,'dd-MON-yyyy') DUEDATE, CUSCODE, BRNCODE from STITCHING_SUMMARY 
                                                                                  where BRNCODE = '".$timer_value['BRNCODE']."' and ENTYEAR = '".$timer_value['ENTYEAR']."' and 
                                                                                      ENTNUMB = '".$timer_value['ENTNUMB']."'", $timer_value['BRNCODE'], "TCS");
                                        $customer_code = $sql_stich_sm[0]['CUSCODE'];
                                        $sql_cus = select_query_json("select CUSNAME, CUSMOBL from customers_tailyou 
                                                                              where BRNCODE = '".$timer_value['BRNCODE']."' and CUSCODE = ".$customer_code."", "Centra", "TEST");
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
                                              $taskcls_name = "task-warning";
                                              break;
                                          } ?>
                                        <div class="task-item <?=$taskcls_name?>">
                                            <div class="task-text"> <? /* onclick="javascript:void(0)" // onclick="popup_order_details('<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')" */ ?>
                                                <B><span class="red_clr">ORDER NO</span> : <span class="blue_clr"><?=$timer_value['ENTYEAR']."-".$timer_value['BRNCODE']."-".$timer_value['ENTNUMB']."-".$timer_value['ENTSRNO']."-".$timer_value['SUBSRNO']?></span><br>
                                                  <? echo $timer_value['PRDNAME']?>
                                                  <br><span title="Change Employee" onclick="changeEmployee('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>', '<?=$sql_emp_user[0]['USRCODE']?>')">User : <?=$sql_emp_user[0]['USRCODE']." - ".$sql_emp_user[0]['USRNAME']?></span>
                                                  <br> Customer : <?=$sql_cus[0]['CUSNAME']." - ".$sql_cus[0]['CUSMOBL']?>

                                                  <br><span title="Change Due Date" onclick="changeDueDate('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>', '<?=$sql_stich_sm[0]['DUEDATE']?>')">Due Date : <?=$sql_stich_sm[0]['DUEDATE']?></span>
                                                </B>
                                            </div>
                                            <input type="hidden" id="task_id" value="<? echo $timer_value['ENTYEAR']."-".$timer_value['BRNCODE']."-".$timer_value['ENTNUMB']."-".$timer_value['ENTSRNO']."-".$timer_value['SUBSRNO'];?>"/>
                                            <input type="hidden" id="task_flow" value="<? echo $timer_value['PRSCODE']?>"/>
                                            <div class="task-footer">

                                                <div class="pull-left">
													<span class="fa fa-arrows" onclick="getOrderImages('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')" style="color:red;margin-right:5px"></span>
													<span class="fa fa-paperclip" onclick="getOrderImages('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')" style="color:red;margin-right:5px"></span>
                                                  <? /* $sql_duedate = select_query_json("select * from tlu_ord_duedate_change 
                                                                                              where BRNCODE = '".$timer_value['BRNCODE']."' and ENTYEAR = '".$timer_value['ENTYEAR']."' and 
                                                                                                  ENTNUMB = '".$timer_value['ENTNUMB']."' and ENTSRNO = '".$timer_value['ENTSRNO']."'", "Centra", "TEST");
                                                    if(count($sql_duedate) > 0) { ?>
                                                        <span class="fa fa-comments blink_me" onclick="changeDueDate('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')" style="color:red;margin-right:5px"></span>
                                                    <? } */

                                                  if($ord_confirm_value['PRSCODE'] == 10) { ?>
                                                      <span class="fa fa-scissors" onclick="assignStyleMaster('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')" style="color:red;margin-right:5px"></span>
                                                  <? } ?>

                                                  <input type="hidden" id="timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['BRNCODE'];?>_<?echo $timer_value['ENTYEAR'];?>_<?echo $timer_value['ENTNUMB'];?>_<?echo $timer_value['ENTSRNO'];?>" value="<?=$timer_value['SUBSRNO']?>" style="background:#f5f5f5;border:none"/>
                                                  <script type="text/javascript">
                                                    function pretty_time_string(num) {
                                                      return ( num < 10 ? "0" : "" ) + num;
                                                    }

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
                                                         // $('#timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['BRNCODE'];?>_<?echo $timer_value['ENTYEAR'];?>_<?echo $timer_value['ENTNUMB'];?>_<?echo $timer_value['ENTSRNO'];?>" value="<?=$timer_value['SUBSRNO']?>').addClass( "blink_me" );
                                                         // $('#timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['BRNCODE'];?>_<?echo $timer_value['ENTYEAR'];?>_<?echo $timer_value['ENTNUMB'];?>_<?echo $timer_value['ENTSRNO'];?>" value="<?=$timer_value['SUBSRNO']?>').css('color' , 'red');
                                                       }
                                                      // $('#timer_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['BRNCODE'];?>_<?echo $timer_value['ENTYEAR'];?>_<?echo $timer_value['ENTNUMB'];?>_<?echo $timer_value['ENTSRNO'];?>" value="<?=$timer_value['SUBSRNO']?>').val(currentTimeString);
                                                    }, 1000);
                                                  </script>
                                                </div>

                                                <div class="pull-right">
											
                                                    <span class="fa fa-user" onclick="assign_user('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')" style="color:red;margin-right:5px"></span>
                                                    <span class="fa fa-history" onclick="showHistory('<?=$ord_confirm_value['PRSCODE']?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>')" style="color:red;margin-right:5px"></span>
                                                    <? /* if ($timer_value['STRTIME'] == '') { ?>
                                                          <? if($tme != 1) { ?>
                                                                <span onclick="startTime('<?=$ord_confirm_value['PRSCODE']?>' , '<?echo $timer_value['ENTNUMB'];?>', '<?=$timer_value['BRNCODE']?>', '<?=$timer_value['ENTYEAR']?>', '<?=$timer_value['ENTNUMB']?>', '<?=$timer_value['ENTSRNO']?>', '<?=$timer_value['SUBSRNO']?>');" class="fa fa-clock-o" style="color:green"></span>
                                                          <? } else { echo "&nbsp;"; } ?>
                                                    <? } else { 
                                                          $tim = explode(" " , $timer_value['PRSTIME']); ?>
                                                          <? if($tme != 1) { ?>
                                                              <span class="fa fa-play" id="play_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>" style="color:blue;margin-right:5px;display:none"></span>
                                                              <span class="fa fa-pause" onclick="stopTime('<?=$ord_confirm_value['PRSCODE']?>' , '<?echo $timer_value['ENTNUMB'];?>' , '<? echo $id_time; ?>');" id="pause_<?=$ord_confirm_value['PRSCODE']?>_<?echo $timer_value['ENTNUMB'];?>" style="color:blue;margin-right:5px"></span>
                                                        <? } else { echo "&nbsp;"; } ?>
                                                    <? } */ ?>
                                                </div>
                                            </div>
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
