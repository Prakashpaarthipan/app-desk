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

<link rel="icon" href="favicon.ico" type="image/x-icon" />

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

<style type="text/css">

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
    width: 40%;
    height :250px;
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
    .tab-content {
        border-left: 2px solid #ddd;
        border-right: 2px solid #ddd;
        padding: 10px;
    } 
    .panel .nav-tabs > .active > a{
        background-color: #034f84 !important;
        color:#fff !important;
    }
    
    .nav-tabs, .nav-tabs.nav {
        padding: 0px 0px;
        padding-top: 10px;
    }
    .nav-tabs > li > a {
    background: #c3d7e4 !important;
    line-height: 1.2 !important;
    font-size: 10px !important;
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
<form class="form-horizontal" role="form" id="frm_process_entry" name="frm_process_entry" action="#nsubmit()" method="post" enctype="multipart/form-data">
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> process_requirement_view.php*/ ?>
    <!-- START PAGE CONTAINER -->
    <input type="hidden" name="action" id="action" value="process"/>
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
                <li class="active">|Supplier Entry</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Supplier Entry</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>

                        <!-- ///////////////// -->

                        <div class="col-md-12">                        
                            <!-- START JUSTIFIED TABS -->
                            <div class="panel panel-default tabs">
                                <ul class="nav nav-tabs">
                                    <li class=""><a href="#tab4" data-toggle="tab" aria-expanded="false"><b>NEW PROCESS</b></a></li>
                                    <li class=""><a href="#tab5" data-toggle="tab" aria-expanded="false"><b>VIEW PROCESS</b></a></li>
                                   <!--  <li class=""><a href="#tab6" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li> -->
                                    <li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="false"><b>Entry</b></a></li>
                                    <li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false"><b>New Title</b></a></li>
                                    <li class=""><a href="#tab3" data-toggle="tab" aria-expanded="false"><b>New Language</b></a></li>
                                </ul>
                                <div class="panel-body tab-content">
                                    <div class="tab-pane" id="tab1">
                                       <div class="panel-body">

                                       <!-- /////////////////// for entry page ////////////////// -->
                                       <!-- <p>Entry Tab</p> -->
                                        <div class="panel-body">
                       
                        <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                        <table  class="table datatable table-striped"">
                            <thead>
                                <tr>
                                    <th class="center" style='text-align:center'>S.NO</th>
                                    <th class="center" style='text-align:center'>ENTRY NO</th>
                                    <th class="center" style='text-align:center'>TITLE</th>
                                    <th class="center" style='text-align:center'>LANGUAGE</th>
                                    <th class="center" style='text-align:center'>FIELDS</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?  $sql_search = select_query_json("select sp.prcsyr,sp.prcsno,sp.prcdsc,langnam from supmail_process sp,supmail_process_language spl where sp.prcsyr=spl.prcsyr and sp.prcsno=spl.prcsno", "Centra", 'TEST');
                            /*echo("select ent.entstat, ent.entryyr, ent.entryno, ent.entsrno, atc.atcname, pri.priname, pri.pridesc, count(atch.atcname) acount, 
                                                                            ent.dspfile, eof.empname, eof.empcode,ent.reqtitl
                                                                        from process_requirement_entry ent, trandata.approval_topcore atc, trandata.APPROVAL_Priority pri, 
                                                                            process_requirement_attachment atch, employee_office eof,userid usid
                                                                        where atc.atccode=ent.atccode and pri.pricode=ent.pricode and atch.entryyr=ent.entryyr and atch.entryno=ent.entryno and 
                                                                            usid.usrcode=ent.adduser and eof.empsrno=usid.empsrno 
                                                                        group by ent.entryyr, ent.entryno, ent.entsrno, atc.atcname, ent.dspfile, pri.priname, eof.empname, eof.empcode, pri.pridesc, ent.entstat,ent.reqtitl
                                                                        order by ent.entryyr, ent.entryno desc");*/
                            
                            $ki = 0;
                            for($k=0;$k<sizeof($sql_search);$k++){//echo $sql_search[$k]['ENTSTAT'];
                                 $ki++; 
                                 $sql_fld = select_query_json("select spf.fieldnm from supmail_process_field spf where spf.prcsyr='".$sql_search[$k]['PRCSYR']."' and spf.prcsno='".$sql_search[$k]['PRCSNO']."'", "Centra", 'TEST');
                                 ?>
                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                    <td class="center" style='text-align:center;'>
                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                    </td>
                                    <td class="center" style='text-align:center'><!-- for entryno-->
                                        <? echo $sql_search[$k]['PRCSYR'].'-'.$sql_search[$k]['PRCSNO']; ?>
                                    </td>
                                    <td class="center" style='text-align:center'><!-- for top core-->
                                        <? echo $sql_search[$k]['PRCDSC']; ?> 
                                    </td>
                                    <td class="center" style='text-align:center'><!-- for priority-->
                                        <? echo $sql_search[$k]['LANGNAM']; ?>
                                    </td>
                                    <td class="center" style='text-align:center'><!-- for  editor details-->
                                        <? for($j=0;$j<sizeof($sql_fld);$j++)
                                        {   echo $sql_fld[$j]['FIELDNM'].'<br>';
                                        }?>
                                    </td>
                                </tr>
                                <?
                            } ?>
                            </tbody>
                        </table>
                    </div>


                                     </div>
                                    </div>
                                    <div class="tab-pane active" id="tab2">
                                      <!-- ///////////////////////// -->
                                               <div class="panel panel-default">
                    
                        <div class="panel-body">
                            <!-- viki////////////////////////// -->
                               
                            <!-- ///////////////////////////////////// -->
                          
                          <!-- action='prakash/insert_project.php' -->
                          
                               <!-- Top Core -->
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="row form-group " >
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>PROCESS NAME<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">

                                                    <input type='text' name='process_name' id='process_name' placeholder='PROCESS TITLE' title='enter the project title' data-toggle="tooltip" data-placement="top" required  class='form-control' maxlength="100" style='text-transform:uppercase;'>
                                                </div>
                                            </div>
                                                <!-- //////////////////// -->

                                            <div class="row form-group">

                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>FIELDS<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                    <div id="add_filed">
                                                        <div style="width:100%;">
                                                            <div style="width:60%;float:left;padding:10px 0px;">
                                                                <input type="text" name = 'txt_value[]' class="form-control" id='txt_value1' placeholder= "NAME" style="text-transform: uppercase;" data-toggle ="tooltip" title ="values" required/>
                                                            </div>
                                                            <span style="width: 10%;float:right;padding:10px 0px;" class="input-group-btn"><button id="add_ledger_button" type="button" onclick="add_feild()" class="btn btn-success btn-add" title ="Add More">+</button>
                                                          </span>
                                                            <div style="width:30%;float:right;padding:10px 0px;">
                                                                <select class="form-control" autofocus required name='txt_mode_type[]' id='txt_mode_type' data-toggle="tooltip" data-placement="top" title="select mode type" >
                                                                     <option value="string">STRING</option>
                                                                     <option value="date" >DATE</option>
                                                                     <option value="number" >NUMBER</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ////////////////// -->
                                            <div class="row form-group">

                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>LANGUAGES<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                    <div id="add_language" >
                                                        <div style="width:100%;">
                                                            <div style="width:90%;float:left;">
                                                                 <input  type='text' name='txt_language[]' id='txt_project_name1' placeholder='Language' title='enter the project title' data-toggle="tooltip" data-placement="top" required value='' class='form-control' maxlength="100" style='text-transform:uppercase;width: 100%;float: left;'/>
                                                            </div>
                                                            <span style="width: 10%;float:right;" class="input-group-btn"><button id="add_ledger_button" type="button" onclick="add_lang()" class="btn btn-success btn-add" title ="Add More">+</button>
                                                             </span>
                                                           <a><input type="file" data-filename-placement="inside" name="file_upload[]" id="file_upload1" style="padding-bottom:5px"/></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <div class='clear clear_both'>&nbsp;</div>
                        <div class="form-group trbg" style='min-height:40px; padding-top:10px'>
                            <div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
                               
                              <input type="button" class="btn btn-success" name="btn_submit" id="btn_submit" onclick="nsubmit()" value="Submit" />
                              <!-- <input type ="submit" class ="btn btn-warning" name="submit" id="submit" value ="Submit" data-toggle="tooltip" title ="submit" /> -->
                                <button id="reset" type="reset" tabindex='3' class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="reset">     <i class="fa fa-times"></i> Reset</button>
                            </div>
                            <div class='clear clear_both'>&nbsp;</div>
                        </div>

                    </div>
                                            
                                            
                                   
                                    </div> </form></div>
                                    <div class="tab-pane" id="tab3">
                                    <form id="lang_entry"  method="post" enctype="multipart/form-data" >
                                        <input type="hidden" name="action" id="action" value="language">
                                            <div class="panel-body">
                                              <div class="form-group">
                                                    <label class="col-md-3 control-label"  style="text-align: left;">PROCESS<span style='color:red'>*</span></label>
                                                    <div class="col-lg-8 col-md-8" >
                                                        <select style="float: left;" class="form-control custom-select chosn" autofocus tabindex='1' required name='txtprocess' id='txtprocess' data-toggle="tooltip" data-placement="top" data-original-title="" >
                                                        <option value="" selected>CHOOSE PROCESS</option>
                                                        <?  $sql_project = select_query_json("select PRCDSC,PRCSYR,PRCSNO from supmail_process where deleted = 'N' order by prcsno", "Centra", 'TEST');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                            <option value='<?echo $sql_project[$project_i]['PRCSYR'].'_'.$sql_project[$project_i]['PRCSNO'];?>'><?=$sql_project[$project_i]['PRCDSC']?></option>
                                                        <? } ?>
                                                        </select>
                                                        <!-- <span class="help-block">Select Top Core</span> -->
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="col-md-3 control-label"  style="text-align: left;">LANGUAGES<span style='color:red'>*</span></label>
                                                    <div class="col-lg-8 col-md-8">
                                                        <div id="only_language" >
                                                            <div style="width:100%;">
                                                                <div style="width:90%;float:left;">
                                                                     <input  type='text' name='txt_lang[]' id='txt_lang1' placeholder='Language' title='enter the project title' data-toggle="tooltip" data-placement="top" required value='' class='form-control' maxlength="100" style='text-transform:uppercase;width: 100%;float: left;'/>
                                                                </div>
                                                                <span style="width: 10%;float:right;" class="input-group-btn"><button id="add_ledger_button" type="button" onclick="only_lang()" class="btn btn-success btn-add" title ="Add More">+</button>
                                                                 </span>
                                                               <a><input type="file" data-filename-placement="inside" name="file_uploadl[]" id="file_uploadl1" style="padding-bottom:5px"/></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group trbg" style='min-height:40px; padding-top:10px'>
                                                    <div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
                                                       
                                                      <input type="button" class="btn btn-success" name="btn_submit" id="btn_submit" onclick="lsubmit()" value="Submit" />
                                                      <!-- <input type ="submit" class ="btn btn-warning" name="submit" id="submit" value ="Submit" data-toggle="tooltip" title ="submit" /> -->
                                                        <button id="reset" type="reset" tabindex='3' class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="reset">     <i class="fa fa-times"></i> Reset</button>
                                                    </div>
                                                    <div class='clear clear_both'>&nbsp;</div>
                                                </div>

                                            </div>
                                        </form>
                                      
                                    </div> 
                                    <div class="tab-pane" id="tab4">
                                       <div class="panel-body">
                                        <div class="col-md-12">
                                    <div class="col-md-9"> 
                                    <form  id="process_entry" method="post" enctype="multipart/form-data">                                                                    
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">PROCESS</label>
                                        <div class="col-md-9 col-xs-12">                                                                                            
                                            <select class="form-control" required="required" id='processname' name='processname' 
                                            onchange="getfields(this.value);getlang(this.value);">
                                                <?php
                                                 $sql_reqid = select_query_json("select * from SUPMAIL_PROCESS where DELETED='N'", "Centra", 'TEST');
                                              
                                                 ?> 
                                                <option value="">SELECT PROCESS</option>
                                               <?for($i=0;$i<count($sql_reqid);$i++){ ?>
                                               
                                                    <option value="<?=$sql_reqid[$i]['PRCSNO'].':'.$sql_reqid[$i]['PRCSYR']?>"><?=strtoupper($sql_reqid[$i]['PRCDSC'])?></option><?}?>
                                            </select>
                                          
                                        </div>
                                    </div>
                                    <span id="fields"></span>
                                                       
                                
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">LANGUAGE</label>
                                        <div class="col-md-9 col-xs-12" id="language1">                                                   
                                            <select class="form-control" required="required" name='language' onchange="getimage1(this.value)">
                                                <option>SELECT LANGUAGE</option>
                                                <option value="1">TAMIL</option>
                                                <option value="2">ENGLISH</option>
                                                <option value="3">HINDI</option>
                                                <option value="4">TELUGU</option>
                                                <option value="5">MALAYALAM</option>
                                            </select>
                                        </div>
                                    </div>
                                    <span id="image"></span>
                                    <div class="form-group" style="padding:10px">
                                        <label class="col-md-3 col-xs-12 control-label">COMMENTS</label>
                                        <div class="col-md-9 col-xs-12">                                            
                                            <textarea class="form-control" name='comments' id="comments" rows="5" required="required" onkeyup='getcomments()'></textarea>
                                           
                                        </div>
                                    </div>
                                    <div class="form-group" style="padding:10px">
                                        
                                        <div class="col-md-12 col-xs-12">                                            
                                            <center><button class="btn btn-success" type="button" name="submit" value="submit" onclick='processsubmit()'>SUBMIT</button></center>
                                           
                                        </div>
                                    </div>
                                </form>
                                    </div>
                                                          </div>
                                    </div>
                                      </div>
                                      <!-- ///////////////////////                     -->
                                        <div class="tab-pane" id="tab5">
                                      <!-- ///////////////////////// -->
                                         
                                       <div class="panel-body">

                                        <table  class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.NO</th>
                                                    <th class="center" style='text-align:center'>PROCESS NO.</th>
                                                    <th class="center" style='text-align:center'>PROCESS NAME</th>
                                                    <th class="center" style='text-align:center'>LANGUAGE</th>
                                                    <th class="center" style='text-align:center'>COMMENT</th>
                                                    <!-- <th class="center" style='text-align:center'>AUTHOURIZED BY</th>
                                                    <th class="center" style='text-align:center'>REPLY</th>
                                                    <th class="center" style='text-align:center'>REPLY DATE</th>
                                                    <th class="center" style='text-align:center'>ACTION</th> -->
                                                    <th class="center" style='text-align:center'>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  $sql_search = select_query_json("select TEMPYR,TEMPNO,PRCSNO,PRCSYR,LANGCOD,TEMPCMNT from SUPMAIL_PROCESS_ENTRY where DELETED='N' order by TEMPNO desc", "Centra", 'TEST');
                                            if($sql_search){
                                            $ki = 0;
                                            for($k=0;$k<sizeof($sql_search);$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:center;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['TEMPYR'].' _ '.$sql_search[$k]['TEMPNO']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for top core-->
                                                        <? 
                                                        $pro_name = select_query_json("select PRCDSC from SUPMAIL_PROCESS where PRCSNO='".$sql_search[$k]['PRCSNO']."' and PRCSYR='".$sql_search[$k]['PRCSYR']."' and DELETED='N'", "Centra", 'TEST');

                                                        echo $pro_name[0]['PRCDSC']; ?> 
                                                    </td>
                                                    <td class="center" style='text-align:center; '><!-- for priority-->
                                                        <?
                                                            $lang_name = select_query_json("select LANGNAM from SUPMAIL_PROCESS_LANGUAGE where PRCSNO='".$sql_search[$k]['PRCSNO']."' and LANGCOD='".$sql_search[$k]['LANGCOD']."' and DELETED='N'", "Centra", 'TEST'); 
                                                        echo $lang_name[0]['LANGNAM']; ?>
                                                    </td>
                                                    
                                                    <td class="center" style='text-align:center'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['TEMPCMNT']; ?>
                                                    </td>
                                                   
                                                    <td class="center" style='text-align:center'><!-- for USER DETAIL-->
                                                        <a onclick="printpage('<? echo $sql_search[$k]['TEMPNO'];?>','<?echo $sql_search[$k]['TEMPYR'];?>');" data-toggle="tooltip" title="print" class="btn btn-warning btn-sm"><span class="fa fa-print"></span></a>
                                                        <a onclick="delprocess('<? echo $sql_search[$k]['TEMPNO'];?>','<?echo $sql_search[$k]['TEMPYR'];?>');" data-toggle="tooltip" title="delete" class="btn btn-danger btn-sm"><span class="fa fa-trash-o"></span></a>
                                                       
                                                    </td>
                                                    
                                                </tr>
                                                <? 
                                            }}else{?><td colspan="6" align="center"><label class="label label-danger">NO RECORDS FOUND</label></td><?} ?>
                                            </tbody>
                                        </table>
                                    
                                    </div>
                                      <!-- /////////////////////////////////// -->
                                    </div>
                                    <!-- /////////////////////////555555555555555555 -->

                                     <div class="tab-pane" id="tab6">
                                      <!-- ///////////////////////// -->
                                            <div class="panel-body">

                                                <table  class="table datatable table-striped"">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center'>NOTICE NO.</th>
                                                    <th class="center" style='text-align:center'>EMPLOYEE</th>
                                                    <th class="center" style='text-align:center'>REMARKS</th>
                                                    <th class="center" style='text-align:center'>NOTICE</th>
                                                    <th class="center" style='text-align:center'>AUTHOURIZED BY</th>
                                                    <th class="center" style='text-align:center'>REPLY</th>
                                                    <th class="center" style='text-align:center'>REPLY DATE</th>
                                                    <th class="center" style='text-align:center'>ACTION</th>
                                                    <th class="center" style='text-align:center'>PRINT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  $sql_search = select_query_json("Select UI.USRCODE,to_char(emnt.edtdate,'dd/MM/yyyy HH:mi:ss AM') edtdate1,(select empname from employee_office where empsrno=emnt.autsrno) assignuser, emnt.*, emp.empname,emp.empcode From employee_notice_detail emnt, employee_office emp,USERID UI where emnt.EMPSRNO = emp.empsrno AND UI.USRCODE=EMNT.ADDUSER AND EMNT.ADDUSER='".$_SESSION['tcs_usrcode']."' ORDER BY NOTNUMB DESC", "Centra", 'TCS');
                                            $ki = 0;
                                            for($k=0;$k<sizeof($sql_search);$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:center;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['NOTYEAR'].'-'.$sql_search[$k]['NOTNUMB']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for top core-->
                                                        <? echo $sql_search[$k]['EMPCODE'].' - '.$sql_search[$k]['EMPNAME']; ?> 
                                                    </td>
                                                    <td class="center" style='text-align:left; '><!-- for priority-->
                                                        <? echo $sql_search[$k]['REMARKS']; ?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for  editor details-->
                                                         <?  $num=explode('-',$sql_search[$k]['NOTNAME']);
                                                            $num=intval($num[1]);
                                                                if($num>10){  $bg_clr_class='label-danger';}
                                                                if($num>5 && $num<=10){  $bg_clr_class='label-warning'; }
                                                                if($num>0 && $num<=5){  $bg_clr_class='label-success'; }
                                                            
                                                        ?>
                                                        <div   style=" text-align:center;line-height: 35px; padding-left: 10px;"><span class="label <?=$bg_clr_class;?> label-form"><b> <?  echo $sql_search[$k]['NOTNAME'];?></b></span></div>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['ASSIGNUSER']; ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['EMP_REMARKS']; ?>
                                                    <td class="center" style='text-align:left'><!-- for attachment count-->
                                                        <? echo $sql_search[$k]['EDTDATE1']; ?>
                                                    </td>

                                                    <td class="center" style='text-align:center' id="<? echo $sql_search[$k]['NOTYEAR'];?>-<?echo $sql_search[$k]['NOTNUMB'];?>"><!-- for USER DETAIL-->
                                                        <?if($sql_search[$k]['EMP_STATUS']=='Y'){if($sql_search[$k]['NOTSTAT']!=''){?>
                                                               
                                                                <?  if($sql_search[$k]['NOTSTAT']=='A'){?>
                                                                    <div   style=" text-align:center;line-height: 35px; padding-left: 10px;"><span class="label label-success label-form"><b>APPROVED</b></span></div>
                                                                    <?}if($sql_search[$k]['NOTSTAT']=='R'){?>
                                                                     <div   style=" text-align:center;line-height: 35px; padding-left: 10px;"><span class="label label-danger label-form"><b>DENIED</b></span></div>
                                                                    <?}?>
                                                            <?}else{?>
                                                                <a onclick="approve('<? echo $sql_search[$k]['NOTYEAR'];?>','<?echo $sql_search[$k]['NOTNUMB'];?>','1');" class="btn btn-success btn-sm"><span class="fa fa-thumbs-up"></span></a>
                                                                <a onclick="approve('<? echo $sql_search[$k]['NOTYEAR'];?>','<?echo $sql_search[$k]['NOTNUMB'];?>','2');" class="btn btn-danger btn-sm"><span class="fa fa-thumbs-down"></span></a>
                                                            <?}}?>
                                                    </td>
                                                    <td class="center" style='text-align:center'><!-- for USER DETAIL-->
                                                        <a onclick="printpage('<? echo $sql_search[$k]['NOTYEAR'];?>','<?echo $sql_search[$k]['NOTNUMB'];?>');" class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>
                                                       
                                                    </td>
                                                    
                                                </tr>
                                                <? 
                                            } ?>
                                            </tbody>
                                        </table>
                                     </div>
                                      <!-- /////////////////////////////////// -->
                                    </div>   
                                    <!-- ////////666666666666666666666 -->




                                </div>
                            </div>                                         
                            <!-- END JUSTIFIED TABS -->
                        </div>
                        <!-- //////////////reply modal -->

                        <div class="page-title">
                        <div id="myModal" class="modal">
                          <!-- Modal content -->
                          <span class="close">&times;</span>
                          <div id="modal_data" class="modal-content">
                                    <textarea id="message" maxlength=250 name="message" malength=250 type="text" tabindex="3" class="form-control" style="text-transform:uppercase; height:100px; padding-right: 5px;" multiple placeholder="TCS Messages.."></textarea>
                                    <center><input type="button" class="btn btn-success" value="SEND" onclick="reply()"/></center>
                          </div>
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
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
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

        var modal = document.getElementById('myModal');
         var span = document.getElementsByClassName("close")[0];
        var gnotyear=0;
        var gnotnumb=0;
        var num=1;
        var llan=1;
        var lan=1;
        //////////
                function printpage(tempno,tempyr){
             var dataurl="report_print_page.php?tempno="+tempno+"&tempyr="+tempyr;
        //alert(dataurl);
             window.open(dataurl);
        }
        function getcomments(){
            var val=$('#comments').val();
            $('#processcomments').html(val);
        }
        
        function delprocess(tempno,tempyr){
            
            $.ajax({
                    type: "POST",
                    url: 'delprocess.php',
                    data:{
                        tempno:tempno,
                        tempyr:tempyr
                    },  
                                    
                    success: function(response) {   
                            location.reload();

                    },
                    error: function(response, status, error)
                    {       alert(error);
                            //alert(response);
                            //alert(status);
                    }
                });




        }
        function getfields(id){

            $.ajax({
                    type: "POST",
                    url: 'getfields.php',
                    data:{
                        'id':id
                    },  
                    dataType:'html',                
                    success: function(response) {   
                                
                       $('#fields').html(response);
                       var val=$('#processname').val();                    
                       getfields_row(id);
                       getprocessname(id);

                    },
                    error: function(response, status, error)
                    {       alert(error);
                            //alert(response);
                            //alert(status);
                    }
                });




        }
        function getprocessname(id){

            $.ajax({
                    type: "POST",
                    url: 'getprocessname.php',
                    data:{
                        'id':id
                    },  
                    dataType:'html',                
                    success: function(response) {   
                    console.log(response)                   
                       $('#process_title').html(response);            

                    },
                    error: function(response, status, error)
                    {       alert(error);
                            //alert(response);
                            //alert(status);
                    }
                });




        }
        function getfields_row(id){

            $.ajax({
                    type: "POST",
                    url: 'getfieldsrow.php',
                    data:{
                        'id':id
                    },  
                    dataType:'html',                
                    success: function(response) {                       
                       $('#fields_row').html(response);           

                    },
                    error: function(response, status, error)
                    {       alert(error);
                            //alert(response);
                            //alert(status);
                    }
                });




        }
        function getlang(id){

            $.ajax({
                    type: "POST",
                    url: 'getlanguage.php',
                    data:{
                        'id':id
                    },  
                    dataType:'html',                
                    success: function(response) {
                        
                       $('#language').html(response);
                    },
                    error: function(response, status, error)
                    {       alert(error);
                            //alert(response);
                            //alert(status);
                    }
                });
        }
        ////////////////

        function nsubmit()
        {   var pnm=$('#process_name').val();
            var vflag =0;
            var lflag=0;
            var fflag=0;
            $('#action').val("process");
            for(var i=1;i<=num;i++)
            {
                if($('#txt_value'+i).length!=0)
                {   if($('#txt_value'+i).val().trim()=='')
                    {vflag=1;}
                }
            }
            for(var i=1;i<=lan;i++)
            {
                if($('#txt_project_name'+i).length!=0)
                {   if($('#txt_project_name'+i).val().trim()=='')
                    {lflag=1;}
                }
            }
            for(var i=1;i<=lan;i++)
            {
                if($('#file_upload'+i).length!=0)
                {   if($('#file_upload'+i).val().trim()=='')
                    {fflag=1;
                    }
                }
            }
            if(pnm.trim()!='' && vflag==0 && lflag==0 && fflag==0)
            {
                //console.log("came in");
              var form_data = new FormData(document.getElementById("frm_process_entry"));
              $.ajax({
              url: "viki/insert_template.php",
              type: "POST",
              data: form_data,
              processData: false,
              contentType: false
              }).done(function(data)
              {
                  //console.log(data);
                  //return false;
              });
              alert("PROCESS CREATED");
              location.reload();

            }
            else
            {   if(pnm.trim()=='')
                    alert("Process Name Required");
                if(vflag==1)
                    alert("Feild Name Required");
                if(lflag==1)
                    alert("Language Name Required");
                if(fflag==1)
                    alert("Language File Required");
            }
        }

        function lsubmit()
        {   var pnm=$('#txtprocess').val();
            var lflag=0;
            var fflag=0;
            $('#action').val("language");
            for(var i=1;i<=llan;i++)
            {
                if($('#txt_lang'+i).length!=0)
                {   if($('#txt_lang'+i).val().trim()=='')
                    {lflag=1;}
                }
            }
            for(var i=1;i<=llan;i++)
            {
                if($('#file_uploadl'+i).length!=0)
                {   if($('#file_uploadl'+i).val().trim()=='')
                    {fflag=1;
                    }
                }
            }
            if(pnm.trim()!='' && lflag==0 && fflag==0)
            {
                //console.log("came in");
              var form_data = new FormData(document.getElementById("lang_entry"));
              $.ajax({
              url: "viki/insert_template.php",
              type: "POST",
              data: form_data,
              processData: false,
              contentType: false
              }).done(function(data){

              });
              alert("PROCESS CREATED");
              location.reload();

            }
            else
            {   if(pnm.trim()=='')
                    alert("Please choose the Process");
                if(lflag==1)
                    alert("Language Name Required");
                if(fflag==1)
                    alert("Language File Required");
            }
        }
        function processsubmit(){
            //alert('enter')
              var form_data = new FormData(document.getElementById("process_entry"));
              $.ajax({
              url: "saveprocessentry.php",
              type: "POST",
              data: form_data,
              processData: false,
              contentType: false,
              success:function(data){
                //location.reload();
               //console.log(data)
              }
              });
             // alert("PROCESS CREATED");
             // location.reload();
             location.reload();
           
              
              


        }

        function add_feild() {
        num++;

         
        $('#add_filed').prepend(
              '<div id="'+num+'">'+
                '<div style="width:100%;">'+
                    '<div style="width:60%;float:left;padding:10px 0px;">'+
                        '<input type="text" name = "txt_value[]"" class="form-control" id="txt_value'+num+'" placeholder= "NAME" style="text-transform: uppercase;" data-toggle ="tooltip" title ="values" required>'+
                    '</div>'+
                     '<span style="width: 10%;float:right;padding:10px 0px;" class="input-group-btn"><button id="add_ledger_button" type="button" class="btn btn-danger btn-remove"  onclick="remove('+num+')" title ="remove">-</button></span>'+
                    '<div style="width:30%;float:right;padding:10px 0px;">'+
                        '<select class="form-control" autofocus required name="txt_mode_type[]" id="txt_mode_type" data-toggle="tooltip" data-placement="top" title="select feild type" ><option value="string">STRING</option><option value="date" >DATE</option><option value="number" >NUMBER</option></select>'+
                    '</div>'+
                '</div>'+
              '</div>');
        }

        function add_lang()
        {lan++;
            $('#add_language').prepend('<div style="width:100%;" id="l'+lan+'">'+
                                            '<div style="width:90%;float:left;" >'+
                                                 '<input  type="text" name="txt_language[]" id="txt_lang'+lan+'" placeholder="Language" title="enter the project title" data-toggle="tooltip" data-placement="top" required value="" class="form-control" maxlength="100" style="text-transform:uppercase;width: 100%;float: left;"/>'+
                                           ' </div>'+
                                            '<span style="width: 10%;float:right;" class="input-group-btn"><button id="add_ledger_button" type="button" onclick="lremove('+lan+')" class="btn btn-danger btn-add" title ="Add More">-</button></span>'+
                                           '<a><input type="file" data-filename-placement="inside" name="file_upload[]" id="file_uploadl'+lan+'" style="padding-bottom:5px"/></a></div>');
                                        

        }
        function only_lang()
        {llan++;
            $('#only_language').prepend('<div style="width:100%;" id="ll'+llan+'">'+
                                            '<div style="width:90%;float:left;" >'+
                                                 '<input  type="text" name="txt_lang[]" id="txt_lang'+llan+'" placeholder="Language" title="enter the project title" data-toggle="tooltip" data-placement="top" required value="" class="form-control" maxlength="100" style="text-transform:uppercase;width: 100%;float: left;"/>'+
                                           ' </div>'+
                                            '<span style="width: 10%;float:right;" class="input-group-btn"><button id="add_ledger_button" type="button" onclick="loremove('+llan+')" class="btn btn-danger btn-add" title ="Add More">-</button></span>'+
                                           '<a><input type="file" data-filename-placement="inside" name="file_uploadl[]" id="file_uploadl'+llan+'" style="padding-bottom:5px"/></a></div>');
                                        

        }


        function remove(i)
        {       console.log(i);
             $('#'+i).remove();
        }
        function loremove(i)
        {       console.log(i);
             $('#ll'+i).remove();
        }

        function showreply(notyear,notnumb){
            modal.style.display = "block";
            gnotyear=notyear;
            gnotnumb=notnumb;
        }
        function aadd_filed()
        {
            $('#fileds').append("");
        }
        function reply()
        {   var vurl = "viki/notice_entry.php";
        //alert(gnotyear);
        //alert(gnotnumb);
            var remarks=$('#message').val();
            if(remarks.trim()!='')
            {
                $.ajax({
                    type: "POST",
                    url: vurl,
                    data:{
                        'notyear':gnotyear,
                        'notnumb':gnotnumb,
                        'action':'reply',
                        'remarks':remarks
                    },
                    dataType:'html',
                    success: function(data1) {
                       //$('#notice').val(data1);
                       alert("UPDATED SUCCESSFULLY");
                       modal.style.display = "none";
                        location.reload();

                    },
                    error: function(response, status, error)
                    {       alert(error);
                            //alert(response);
                            //alert(status);
                    }
                });
            }
            else{
                alert("Response required");
                
            }
        }

        span.onclick = function() {
            modal.style.display = "none";
              
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
               
            }
        }


    $('#datepicker-example').Zebra_DatePicker({
     direction:true,
      format: 'd-M-Y'
     
    });



    $('#datepicker-example3').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example4')
    });

    

    

    </script>
<!-- END SCRIPTS -->
</body>
</html>
