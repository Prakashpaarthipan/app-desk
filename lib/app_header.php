<ul class="x-navigation x-navigation-horizontal x-navigation-panel">
    <!-- TOGGLE NAVIGATION -->
    <li class="xn-icon-button">
        <a href="javascript:void(0)" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
    </li>
    <!-- END TOGGLE NAVIGATION -->
    <!-- SEARCH -->
   <li class="xn-search" style="display:none">
        <form role="form" name="frm_search" id="frm_search" action="" method="post">
            <input type="text" name="txt_search" id="txt_search" class="form-control" placeholder="Search Order No.."/>
        </form>
    </li>
    <!-- END SEARCH -->
    <!-- SIGN OUT -->
    <li class="xn-icon-button pull-right">
        <a href="javascript:void(0)" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>
    </li> 
    <!-- END SIGN OUT -->
    <!-- MESSAGES -->
    <? 
        
    $sql_mdul = select_query_json("select * from employee_office 
                                            where empsrno = ".$_SESSION['tcs_empsrno']." 
                                            order by DESCODE, empcode, empsrno", "Centra", 'TEST');
        $sql_noti = select_query_json("select * from srm_menu_notify where entsrno='".$_SESSION['tcs_empsrno']."'", "Centra", 'TEST');
        $apnot=0;
        $reqnot=0;
        $tcsnot=0;
        //print_r($sql_noti);
        foreach($sql_noti as $key => $value)
        {
            if($value['MNUCODE']=='5')
            {
                $apnot=$value['MSGCOUNT'];
            }
            if($value['MNUCODE']=='192')
            {
                $reqnot=$value['MSGCOUNT'];
            }
            if($value['MNUCODE']=='202')
            {
                $tcsnot=$value['MSGCOUNT'];
            }
        }
         $all=1;
        if($_SESSION['tcs_empsrno'] == $sql_mdul[0]['EMPSRNO']) { // Only Top Category User can switch Module 
             $rights_purchase = find_user_rights('PURCHASE', $sql_mdul[0]['EMPSRNO']);
             $rights_system   = find_user_rights('SYSTEM', $sql_mdul[0]['EMPSRNO']);
             $rights_admin    = find_user_rights('ADMIN', $sql_mdul[0]['EMPSRNO']);
             $rights_crm      = find_user_rights('CRM', $sql_mdul[0]['EMPSRNO']);
             $rights_jewellery= find_user_rights('JEWELLERY', $sql_mdul[0]['EMPSRNO']);
             $rights_app_desk = find_user_rights('APPROVAL DESK', $sql_mdul[0]['EMPSRNO']);

            // $rights_off_reprt= find_user_rights('OFFLINE REPORT', $sql_mdul[0]['EMPSRNO']);
            $rights_service_request= find_user_sub_rights('APPROVAL DESK','SERVICE REQUEST', $sql_mdul[0]['EMPSRNO']);
            $rights_tcs_connect= find_user_sub_rights('APPROVAL DESK','TCS CONNECT', $sql_mdul[0]['EMPSRNO']);
           //  $rights_purchase = find_user_sub_rights('APPROVAL DESK','PURCHASE', $sql_mdul[0]['EMPSRNO']);
           // $rights_system   = find_user_sub_rights('APPROVAL DESK','SYSTEM', $sql_mdul[0]['EMPSRNO']);
           //  $rights_admin    = find_user_sub_rights('APPROVAL DESK','ADMIN', $sql_mdul[0]['EMPSRNO']);
           //  $rights_crm      = find_user_sub_rights('APPROVAL DESK','CRM', $sql_mdul[0]['EMPSRNO']);
           //   $rights_jewellery= find_user_sub_rights('APPROVAL DESK','JEWELLERY', $sql_mdul[0]['EMPSRNO']);
           //  $rights_app_desk = find_user_sub_rights('APPROVAL DESK','APPROVAL DESK', $sql_mdul[0]['EMPSRNO']);
            // $rights_purchase = 0;
            // $rights_system   = 0;
            // $rights_admin    = 0;
            // $rights_crm      = 0;          
            // $rights_jewellery= 0;
            // $rights_app_desk = 0;
            // $rights_off_reprt= 0; 

        } ?>
    <li class="xn-icon-button pull-right">
        <a href="javascript:void(0)"><span class="fa fa-th fa-fw"></span></a>
        <? /* <div class="informer informer-danger">4</div> */ ?>
        <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="fa fa-th fa-fw"></span> CHOOSE MODULE</h3>                                
                <div class="pull-right">
                    <? /* <span class="label label-danger">4 new</span> */ ?>
                </div>
            </div>
            <div class="panel-body list-group list-group-contacts scroll" style="height: 250px;">
                <? /* <a href="#" class="list-group-item">
                    <div class="list-group-status status-online"></div>
                    <img src="assets/images/users/user2.jpg" class="pull-left" alt="John Doe"/>
                    <span class="contacts-title">John Doe</span>
                    <p>Praesent placerat tellus id augue condimentum</p>
                </a>
                <a href="#" class="list-group-item">
                    <div class="list-group-status status-away"></div>
                    <img src="assets/images/users/user.jpg" class="pull-left" alt="Dmitry Ivaniuk"/>
                    <span class="contacts-title">Dmitry Ivaniuk</span>
                    <p>Donec risus sapien, sagittis et magna quis</p>
                </a>
                <a href="#" class="list-group-item">
                    <div class="list-group-status status-away"></div>
                    <img src="assets/images/users/user3.jpg" class="pull-left" alt="Nadia Ali"/>
                    <span class="contacts-title">Nadia Ali</span>
                    <p>Mauris vel eros ut nunc rhoncus cursus sed</p>
                </a>
                <a href="#" class="list-group-item">
                    <div class="list-group-status status-offline"></div>
                    <img src="assets/images/users/user6.jpg" class="pull-left" alt="Darth Vader"/>
                    <span class="contacts-title">Darth Vader</span>
                    <p>I want my money back!</p>
                </a> */ ?>
                <?  if($rights_service_request == 1) { ?>
                    <div class="col-lg-4 text-center" style="padding:15px 5px;"><a class="text-center" href="http://www.tcsportal.com/approval-desk-test/service_request_demo.php" target="_blank"><i class="fa fa-shopping-cart fa-fw"></i></a><div style='font-size:10px;'><a class="text-center" style="padding: 0px !important;" href="http://www.tcsportal.com/approval-desk-test/service_request.php" target="_blank">SERVICE REQUEST</a><span style="border:1px solid;background-color: #333;border-radius: 3px;padding: 2px 5px;color: white;"><?echo $reqnot;?></span></div></div>
                <? } ?>
                
                <? if($rights_tcs_connect == 1 || $all== 1) { ?>
                    <div class="col-lg-4 text-center" style="padding:15px 5px;"><a class="text-center" href="http://www.thechennaisilks.co/tcs_connect/" target="_blank"><i class="fa fa-tasks fa-fw"></i></a><div style='font-size:10px;'><a class="text-center" style="padding: 0px !important;" href="http://www.thechennaisilks.co/tcs_connect/?userec=<?=$_SESSION['tcs_usrcode']?>" target="_blank">TCS CONNECT</a><span style="border:1px solid;background-color: #333;border-radius: 3px;padding: 2px 5px;color: white;"><?echo $tcsnot;?></span></div></div>
                <? } ?>

                <? if($rights_app_desk == 1) { ?>
                    <div class="col-lg-4 text-center module_active" style="padding:15px 5px;"><a class="text-center" href="http://www.tcsportal.com/approval-desk-test/home.php"><i class="fa fa-check-square fa-fw"></i></a><div style='font-size:10px;'><a class="text-center" href="http://www.tcsportal.com/approval-desk-test/home.php" style="padding: 0px !important;">APPROVAL DESK</a>
                        <span style="border:1px solid;background-color: #333;border-radius: 3px;padding: 2px 5px;color: white;"><?echo $apnot;?></span></div></div>
                <? } ?>
                 <?  if($rights_purchase == 1) { ?>
                    <div class="col-lg-4 text-center" style="padding:15px 5px;"><a class="text-center" href="<?=$_SESSION['websiteurl']?>/home.php?sltmdul=PURCHASE" target="_blank"><i class="fa fa-shopping-cart fa-fw"></i></a><div style='font-size:10px;'><a class="text-center" href="<?=$_SESSION['websiteurl']?>/home.php?sltmdul=PURCHASE" style="padding: 0px !important;" target="_blank">PURCHASE</a></div></div>
                <? } ?>

                <? if($rights_system == 1) { ?>
                    <div class="col-lg-4 text-center" style="padding:15px 5px;"><a class="text-center" href="<?=$_SESSION['websiteurl']?>/home.php?sltmdul=SYSTEM" target="_blank"><i class="fa fa-tasks fa-fw"></i></a><div style='font-size:10px;'><a class="text-center" href="<?=$_SESSION['websiteurl']?>/home.php?sltmdul=SYSTEM" style="padding: 0px !important;" target="_blank">SYSTEM</a></div></div>
                <? } ?>

                <? if($rights_admin == 1) { ?>
                    <div class="col-lg-4 text-center" style="padding:15px 5px;"><a class="text-center" href="<?=$_SESSION['websiteurl']?>/home.php?sltmdul=ADMIN" target="_blank"><i class="fa fa-dashboard fa-fw"></i></a><div style='font-size:10px;'><a class="text-center" href="<?=$_SESSION['websiteurl']?>/home.php?sltmdul=ADMIN" style="padding: 0px !important;" target="_blank">ADMIN</a></div></div>
                <? } ?>
                
                <? if($rights_crm == 1) { ?>
                    <div class="col-lg-4 text-center" style="padding:15px 5px;"><a class="text-center" href="<?=$_SESSION['websiteurl']?>/home.php?sltmdul=CRM" target="_blank"><i class="fa fa-users fa-fw"></i></a><div style='font-size:10px;'><a class="text-center" href="<?=$_SESSION['websiteurl']?>/home.php?sltmdul=CRM" style="padding: 0px !important;" target="_blank">CRM</a></div></div>
                <? } ?>
                
                <? if($rights_jewellery == 1) { ?>
                    <div class="col-lg-4 text-center" style="padding:15px 5px;"><a class="text-center" href="<?=$_SESSION['websiteurl']?>/ktmportal/index.php" target="_blank"><i class="fa fa-money"></i></a><div style='font-size:10px;'><a class="text-center" href="<?=$_SESSION['websiteurl']?>/ktmportal/index.php" style="padding: 0px !important;" target="_blank">JEWELLARY</a></div></div>
                <? } ?>
                
                
                
                <? if($rights_off_reprt == 1) { ?>
                    <div class="col-lg-4 text-center" style="padding:15px 5px;"><a class="text-center" href="<?=$_SESSION['websiteurl']?>/offline_report/index.php" target="_blank"><i class="fa fa-bar-chart-o fa-fw"></i></a><div style='font-size:10px;'><a class="text-center" href="<?=$_SESSION['websiteurl']?>/offline_report/index.php" style="padding: 0px !important;" target="_blank">OFFLINE REPORT</a></div></div>
                <? } ?>
            </div>
            <? /* <div class="panel-footer text-center">
                <a href="pages-messages.html">Show all messages</a>
            </div> */ ?>
        </div>                        
    </li>
    <!-- END MESSAGES -->


    <li class="xn-icon-button pull-right">
        <a href="javascript:void(0)" class="blink_me"><span class="fa fa-bullhorn fa-fw"></span></a>
        <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="fa fa-bullhorn fa-fw"></span> CHOOSE REQUIREMENT</h3>                                
                <div class="pull-right">
                </div>
            </div>
            <div class="panel-body list-group list-group-contacts scroll" style="height: 250px;">
                <div class="col-lg-6 text-center" style="padding:15px 5px;"><a class="text-center" href="process_requirement_entry.php" target="_blank"><i class="fa fa-edit fa-fw"></i></a><div style='font-size:12px;'><a class="text-center" href="process_requirement_entry.php" style="padding: 0px !important;" target="_blank">Requirement Entry</a></div></div>
                <div class="col-lg-6 text-center" style="padding:15px 5px;"><a class="text-center" href="process_requirement_list.php" target="_blank"><i class="fa fa-check-square-o fa-fw"></i></a><div style='font-size:12px;'><a class="text-center" href="process_requirement_list.php" style="padding: 0px !important;" target="_blank">Requirement List</a></div></div>
            </div>
        </div>                        
    </li>

    <!-- HELP -->
    <li class="xn-icon-button pull-right">
        <? /* <a href="javascript:void(0)" onclick="call_user_manual()"><span class="fa fa-chain"></span></a> */ ?>
        <a href="user_manual.php" title="Click here to View User Manual"><span class="fa fa-chain" title="Click here to View User Manual"></span></a>
    </li> 
    <!-- END HELP -->

    <? if($_SESSION['tcs_empsrno'] == '43878') { 
        $sql_pendmail = select_query_json("select count(MAILNUMB) CNTMAILNUMB from mail_send_summary where status = 'N' and DEPTID = 1", "Centra", 'TCS'); 
        if($sql_pendmail[0]['CNTMAILNUMB'] >= 5) { ?>
            <li><span style='color:#00ff50; font-weight:bold;' class='blink_me'><?=$sql_pendmail[0]['CNTMAILNUMB']?> Mail waiting to send. Verify Table / EXE.</span></li>
        <? } 
    } ?>
    <!-- TASKS -->
    <? /* <li class="xn-icon-button pull-right">
        <a href="#"><span class="fa fa-tasks"></span></a>
        <div class="informer informer-warning">3</div>
        <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="fa fa-tasks"></span> Tasks</h3>                                
                <div class="pull-right">
                    <span class="label label-warning">3 active</span>
                </div>
            </div>
            <div class="panel-body list-group scroll" style="height: 200px;">                                
                <a class="list-group-item" href="#">
                    <strong>Phasellus augue arcu, elementum</strong>
                    <div class="progress progress-small progress-striped active">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">50%</div>
                    </div>
                    <small class="text-muted">John Doe, 25 Sep 2014 / 50%</small>
                </a>
                <a class="list-group-item" href="#">
                    <strong>Aenean ac cursus</strong>
                    <div class="progress progress-small progress-striped active">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">80%</div>
                    </div>
                    <small class="text-muted">Dmitry Ivaniuk, 24 Sep 2014 / 80%</small>
                </a>
                <a class="list-group-item" href="#">
                    <strong>Lorem ipsum dolor</strong>
                    <div class="progress progress-small progress-striped active">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%;">95%</div>
                    </div>
                    <small class="text-muted">John Doe, 23 Sep 2014 / 95%</small>
                </a>
                <a class="list-group-item" href="#">
                    <strong>Cras suscipit ac quam at tincidunt.</strong>
                    <div class="progress progress-small">
                        <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                    </div>
                    <small class="text-muted">John Doe, 21 Sep 2014 /</small><small class="text-success"> Done</small>
                </a>                                
            </div>     
            <div class="panel-footer text-center">
                <a href="pages-tasks.html">Show all tasks</a>
            </div>                            
        </div>                        
    </li>
    <!-- END TASKS --> */ ?>
</ul>

<script type="text/javascript">
    function blinker() {
        $('.blink_me').fadeOut(100);
        $('.blink_me').fadeIn(800);
    }
    setInterval(blinker, 1000);

    /* function call_user_manual() {
        window.location = "user_manual.php";
    } */

    /* function loadiframe(htmlHref) //load iframe
    {
        document.getElementById('targetiframe').src = htmlHref;
    }

    function unloadiframe() //just for the kicks of it
    {
        var frame = document.getElementById("targetiframe"),
        frameHTML = frame.contentDocument || frame.contentWindow.document;
        frameHTML.removeChild(frameDoc.documentElement);    
    } */
</script>