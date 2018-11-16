<?
session_start();
error_reporting(0);
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
<title>Search Result :: Approval Desk :: <?php echo $site_title; ?></title>             
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- CSS INCLUDE -->        
<link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
<!-- EOF CSS INCLUDE -->                                    
</head>
<body>
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> */ ?>
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
                <li class="active">Search Result</li>
            </ul>
            <!-- END BREADCRUMB -->                       
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    
                    <!-- START SEARCH -->                            
                    <div class="panel panel-default">
                        <div class="panel-body">                                    
                            <div class="row stacked">
                                <div class="col-md-9">
                                    <div class="input-group push-down-10">
                                        <span class="input-group-addon"><span class="fa fa-search"></span></span>
                                        <input type="text" class="form-control" placeholder="Keywords..." value="Management"/>
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary">Search</button>
                                        </div>
                                    </div>                                                                
                                    
                                    <span class="line-height-30">Search Results for <strong>Management</strong> (53 results)</span>
                                    <? /* <div class="pull-right">
                                        <div class="btn-group">
                                            <button class="btn btn-default active"><span class="fa fa-user"></span></button>
                                            <button class="btn btn-default"><span class="fa fa-globe"></span></button>                                        
                                        </div>
                                        <button class="btn btn-default"><span class="fa fa-cog"></span></button>
                                    </div> */ ?>
                                </div>
                                <div class="col-md-2">
                                    <? /*     
                                    <div class="pull-right push-down-10">
                                        <button class="btn btn-default active"><span class="fa fa-search"></span> Search</button>
                                        <button class="btn btn-default"><span class="fa fa-image"></span> Images</button>
                                        <button class="btn btn-default"><span class="fa fa-map-marker"></span> Maps</button>
                                        <button class="btn btn-default"><span class="fa fa-camera"></span> Video</button>
                                        <button class="btn btn-default"><span class="fa fa-file"></span> News</button>
                                    </div>
                                    
                                    <div class="line-height-30 pull-right text-right" style="width: 100%;">
                                        <a href="#">Support</a> - <a href="#">Contacts</a> - <a href="#">Terms of usage</a>
                                    </div>    
                                    */ ?>                                        
                                </div>
                            </div>
                        </div>                                                                
                    </div>
                    <!-- END SEARCH -->
                    
                    <!-- START SEARCH RESULT -->
                    <div class="search-results">
                        <div class="sr-item">
                            <a href="#" class="sr-item-title">ADMIN / INFO TECH 4008114 / 12-03-2018 / 8114 / 04:53 PM</a>
                            <a href="#" style="text-decoration: none !important;"><div class="sr-item-link">ADMIN</div>
                            <p>Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval Admin – Approval</p></a>
                            <p class="sr-item-links pull-right"><a href="#" class="red_clr">Read More..</a></p>
                        </div> 
                        
                        <div class="sr-item">
                            <a href="#" class="sr-item-title">ADMIN / INFO TECH 4008112 / 12-03-2018 / 8112 / 04:23 PM</a>
                            <a href="#" style="text-decoration: none !important;"><div class="sr-item-link">OPERATION</div>
                            <p>Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval Operation – Approval</p></a>
                            <p class="sr-item-links pull-right"><a href="#" class="red_clr">Read More..</a></p>
                        </div>                               
                        
                        <div class="sr-item">
                            <a href="#" class="sr-item-title">ADMIN / INFO TECH 4008113 / 12-03-2018 / 8113 / 04:41 PM</a>
                            <a href="#" style="text-decoration: none !important;"><div class="sr-item-link">MANAGEMENT</div>
                            <p>Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval </p></a>
                            <p class="sr-item-links pull-right"><a href="#" class="red_clr">Read More..</a></p>
                        </div>

                        <div class="sr-item">
                            <a href="#" class="sr-item-title">ADMIN / INFO TECH 4008114 / 12-03-2018 / 8114 / 04:53 PM</a>
                            <a href="#" style="text-decoration: none !important;"><div class="sr-item-link">S-TEAM</div>
                            <p>S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval</p>
                            <p>S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval S-Team – Approval</p></a>
                            <p class="sr-item-links pull-right"><a href="#" class="red_clr">Read More..</a></p>
                        </div>                                
                        
                        <div class="sr-item">
                            <a href="#" class="sr-item-title">ADMIN / INFO TECH 4008113 / 12-03-2018 / 8113 / 04:41 PM</a>
                            <a href="#" style="text-decoration: none !important;"><div class="sr-item-link">MANAGEMENT</div>
                            <p>Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval</p>
                            <p>Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval Management – Approval</p></a>
                            <p class="sr-item-links pull-right"><a href="#" class="red_clr">Read More..</a></p>
                        </div>
                    </div>
                    <!-- END SEARCH RESULT -->
                    
                    <ul class="pagination pagination-sm pull-right push-down-20">
                        <li class="disabled"><a href="#">«</a></li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>                                    
                        <li><a href="#">»</a></li>
                    </ul>                            
                    
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

    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>
    
<!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>        
    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->        
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
    <!-- END THIS PAGE PLUGINS-->        

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    
    <script type="text/javascript" src="js/plugins.js"></script>        
    <script type="text/javascript" src="js/actions.js"></script>
    
    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
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
    </script>
<!-- END SCRIPTS -->         
</body>
</html>