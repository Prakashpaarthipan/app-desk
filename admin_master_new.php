<?php

session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

if(isset($_REQUEST['id'])){
    $sqledit= select_query_json("select * from tlu_master where TLMASID='".$_REQUEST['id']."' and TLMSSTS = 'A'", "Centra", 'TCS'); 
}

// $up_table="TLU_MASTER";
//       $up_fld=array();
//       $up_fld['TLMSSTS']='A';
      
//       print_r($up_fld);
//      echo $where_appplan="TLMASID='40' AND TLMSSTS='D'";
       
//       echo $insert_appplan1 = update_dbquery($up_fld, $up_table, $where_appplan);     
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Admin Master List :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">
<!-- START PLUGINS -->
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/tock.js"></script>
    <link rel="stylesheet" href="js/jquery-confirm.min.css">
    <script src="js/jquery-confirm.min.js"></script>
    <!-- END PLUGINS -->

<link href="css/admin_master.css" rel="stylesheet" type="text/css">
<!-- END META SECTION -->
<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<link rel="stylesheet" type="text/css" href="css/social-buttons.css"/>
<link href="css/facebook_alert.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery_facebook.alert.js"></script>
<link href="css/admin_master1.css" rel="stylesheet" type="text/css">
<!-- EOF CSS INCLUDE -->
</head>
<body style="background-color: #FFFFFF;">
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
                <li class="active">Admin Master</li>
            </ul>
            <!-- END BREADCRUMB -->
            <? 
            $value=array();$cateid=array();$catename=array();$cateimage=array();$parentid=array();$type=array();$agefrom=array();$ageto=array();
            $sql= select_query_json("select * from tlu_master where TLMSSTS = 'A' order by TLAGFYR asc,TLMSTYP asc,TLMSSRN desc", "Centra", 'TCS');
            foreach($sql as $key=> $entry){
                $value[$entry['TLMSTYP']][]=$entry;
                $cateid[$entry['TLMASID']]=$entry['TLMASID'];
                $catename[$entry['TLMASID']]=$entry['TLMASNM'];
                $cateimage[$entry['TLMASID']]=$entry['TLMSIMG'];
                $parentid[$entry['TLMASID']]=$entry['TLMPRID'];
                $type[$entry['TLMASID']]=$entry['TLMSTYP'];
                $agefrom[$entry['TLMASID']]=$entry['TLAGFYR'];
                $ageto[$entry['TLMASID']]=$entry['TLAGTYR'];
            }
           // print_r($parentid);
            ?>
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">

                <div class="panel panel-default">
                    <div class="panel-body" style="width: 96%; margin: 1%;">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active"><a href="#id_category" data-toggle="tab">CATEGORY</a></li>
                                <li><a href="#id_section" data-toggle="tab">SECTION</a></li>
                                <li><a href="#id_style" data-toggle="tab">STYLE</a></li>
                                <li><a href="#id_parts" data-toggle="tab">PARTS</a></li>
                                <li><a href="#id_addons" data-toggle="tab">ADDONS</a></li> 
                            </ul>

                            <div class="tab-content">
                                <!-- id_category - Start -->
                                <div class="tab-pane active" id="id_category">
                                    <!-- CATEGORY -->
                                    <form name="category_frm" id="category_frm" action="" role="form" method="post" enctype="multipart/form-data">
                                        
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Category<span style='color:red;'> *</span> &nbsp;: </label>
                                            <div class="col-md-2 col-xs-12" style='margin-left:-20px'>
                                                <div class="">
                                                    <input type="text" class="form-control" id="txt_category" name="txt_category" style="text-transform: uppercase;" onkeyup="check('txt_category','category_cat')" maxlength="100" required data-toggle="tooltip" data-placement="top" data-original-title="Enter The Category" value="<?=$sqledit[0]['TLMASNM']?>"/>
                                                </div>
                                                <span class="help-block" id='category_cat' style="display:none" >Note : Enter The Category</span>
                                            </div>

                                            <label class="col-md-2 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Age From Year<span style='color:red;'> *</span>&nbsp; : </label>
                                            <div class="col-md-2 col-xs-12" style='margin-left:-20px'>
                                                <div class="">
                                                    <input type="text" class="form-control" id="txt_from" onkeyup="check('txt_from','category_agefrom')" name="txt_from" style="text-transform: uppercase;" maxlength="2" required data-toggle="tooltip" data-placement="top" data-original-title="Enter Age From" value="<?=$sqledit[0]['TLAGFYR']?>"/>
                                                </div>
                                                <span class="help-block" id='category_agefrom' style="display:none">Note : Age From Year</span>
                                            </div>

                                            <label class="col-md-2 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Age To Year<span style='color:red;'> *</span>&nbsp; : </label>
                                            <div class="col-md-2 col-xs-12" style='margin-left:-20px'>
                                                <div class="">
                                                    <input type="text" class="form-control" id="txt_to" name="txt_to" onkeyup="check('txt_to','category_ageto')" style="text-transform: uppercase;" maxlength="2" required data-toggle="tooltip" data-placement="top" data-original-title="Enter Age To" value="<?=$sqledit[0]['TLAGTYR']?>"/>
                                                </div>
                                                <span class="help-block" id='category_ageto' style="display:none">Note : Age To Year</span>
                                            </div>
                                        </div>

                                        <div style="clear: both;"></div>
                                        <div style='clear:both; text-align: center;'>
                                            <div class="input-group" style="margin: 30px 0px 10px 0px; width: 100%; vertical-align: middle; text-align: center;">
                                                
                                                    <input type="hidden" name="action" id="action" value="save_category"/>
                                                <button class="btn btn-info" type="button" id="cate_submit" style="text-align: center;" onclick="nsubmit_form('category_frm');"><span class="fa fa-thumbs-up"></span> Submit</button>
                                                    <input type="hidden" name="action" id="action" value="update_category"/>
                                                    <input type="hidden" name="tlmasid" id="tlmasid" value=""/>
                                                    <button class="btn btn-info" id="cate_update" type="button" style="text-align: center;display:none" onclick="nupdate_form('category_frm');"><span class="fa fa-thumbs-up"></span>Update</button>&nbsp;&nbsp;
                                                    <button class="btn btn-warning" id="cate_cancel" type="button" style="text-align: center;display:none" onclick="location.reload()"><span class="fa fa-thumbs-down"></span>Cancel</button>
                                            </div>
                                        </div>
                                        <div style="clear: both; min-height: 10px; border-bottom: 1px solid #a0a0a0;"></div>

                                        <div style="padding-top: 10px;">
                                            <h3 class="panel-title"><b>Category List</b></h3>
                                        </div>
                                        <div style="clear: both;"></div>
                                        <div style="maring:5px; padding-top: 10px;">
                                          <table id="category_tbl" class="table datatable" style="overflow-x: scroll !important;">
                                              <thead>
                                                  <tr>
                                                      <th style='text-align:center'>Sr. No</th>
                                                      <th style='text-align:center'>Category</th>
                                                      <th style='text-align:center'>Age From Year</th>
                                                      <th style='text-align:center'>Age To Year</th>
                                                      <th style='text-align:center'>Action</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              <?  
                                              $ij = 0; $priority_based = '';$value1prid=array();
                                              for($i=0;$i<count($value[1]);$i++){  
                                              $value1prid[$value[1][$i]['TLMASID']]= $value[1][$i]['TLMASNM'];                                               
                                                  $ij++; ?>
                                                      <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                          <td style='text-align:center'><?=$ij?></td>
                                                          <td style='text-align:left'><?=$value[1][$i]['TLMASNM']?> </td>
                                                          <td style='text-align:center'><?=$value[1][$i]['TLAGFYR']?></td>
                                                          <td style='text-align:center'><?=$value[1][$i]['TLAGTYR']?></td>
                                                          <td style='text-align:center'><button class="btn btn-sm btn-info" type="button" onclick="editcat('<?=$value[1][$i]['TLMASID']?>')"  data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></button> 

                                                            | <button class="btn btn-sm btn-danger" type="button"  onclick="delcat('<?=$value[1][$i]['TLMASID']?>')" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash-o"></i></button>

                                                        </td>
                                                      </tr>
                                                  <? } ?>
                                              </tbody>
                                          </table>
                                      </div>
                                      <div style="clear: both; min-height: 10px;"></div>
                                    </form>
                                </div>
                                <!-- id_category - End -->


                                <!-- id_section - Start -->
                                <div class="tab-pane" id="id_section">
                                    
                                    <!-- CATEGORY -->
                                    <form name="section_frm" id="section_frm" action="" role="form" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="action" id="action" value="save_section"/>
                                        <input type="hidden" name="insert_section" id="action" value="SECTION"/>
                                        <div class="form-group">
                                            <label class="col-md-2 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Category<span style='color:red;'> *</span> &nbsp;: </label>
                                            <div class="col-md-2 col-xs-12" style='margin-left:-20px'>
                                                <div class="">
                                                    <select class="form-control" tabindex='1' required name='slt_category' id='slt_category' onchange="check('slt_category','section_cat')" data-toggle="" data-placement="top" data-original-title="Choose The Category">
                                                        <option value="choose top core" selected>Choose The Category</option>
                                                        <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[1]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[1][$i]['TLMASID']?>' ><?=$value[1][$i]['TLMASNM']?> (<?=$value[1][$i]['TLAGFYR']?> to <?=$value[1][$i]['TLAGTYR']?>)</option>
                                                        <? } ?>
                                                    </select>
                                                </div>
                                                <span class="help-block" id='section_cat' style="display:none">Note : Choose The Category</span>
                                            </div>

                                            <label class="col-md-2 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Section<span style='color:red;'> *</span> &nbsp;: </label>
                                            <div class="col-md-2 col-xs-12" style='margin-left:-20px'>
                                                <div class="">
                                                    <input type="text" onkeyup="check('txt_section','section_sec')" class="form-control" tabindex="2" id="txt_section" name="txt_section" style="text-transform: uppercase;" maxlength="100" required data-toggle="tooltip" data-placement="top" data-original-title="Enter The Section"/>
                                                </div>
                                                <span class="help-block" id="section_sec" style="display:none">Note : Enter The Section</span>
                                            </div>
                                            <label class="col-md-2 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Image<span style='color:red;'> *</span>&nbsp; : </label>
                                            <div class="col-md-2 col-xs-12" style='margin-left:-20px'>
                                                <div class="">
                                                    <input type="file" tabindex="3" onchange="check('img_section','section_img')" class="form-control" id="img_section" name="img_section" style="text-transform: uppercase;" data-toggle="tooltip" data-placement="top" data-original-title="Select Section Image" accept="image/x-png,image/gif,image/jpeg"/>
                                                </div>
                                                <span class="help-block" id='section_img' style="display:none">Note : Select The Image</span>
                                                <div class="preview_section">
                                                    <img id="preview_section_img" style="max-height:50px;max-width:50px" src=""/>
                                                </div>  
                                            </div>
                                        </div>

                                        <div style="clear: both;"></div>
                                        <div style='clear:both; text-align: center;'>
                                            <div class="input-group" style="margin: 30px 0px 10px 0px; width: 100%; vertical-align: middle; text-align: center;">
                                                <button class="btn btn-info" type="button" id="section_submit" style="text-align: center;" onclick="nsubmit_form('section_frm');"><span class="fa fa-thumbs-up"></span> Submit</button>
                                                <input type="hidden" name="action" id="action" value="update_section"/>
                                                    <input type="hidden" name="secmasid" id="secmasid" value=""/>
                                                    <button class="btn btn-info" id="section_update" type="button" style="text-align: center;display:none" onclick="nupdate_form('section_frm');"><span class="fa fa-thumbs-up"></span>Update</button>&nbsp;&nbsp;
                                                    <button class="btn btn-warning" id="section_cancel" type="button" style="text-align: center;display:none" onclick="location.reload()"><span class="fa fa-thumbs-down"></span>Cancel</button>
                                            </div>
                                        </div>
                                        <div style="clear: both; min-height: 10px; border-bottom: 1px solid #a0a0a0;"></div>

                                        <div style="padding-top: 10px;">
                                            <h3 class="panel-title"><b>Section List</b></h3>
                                        </div>
                                        <div style="clear: both;"></div>
                                        <div style="margin:5px; padding-top: 10px;">
                                          <table id="section_tbl" class="table" style="overflow-x: scroll !important;">
                                              <thead>
                                                  <tr>
                                                      <th style='text-align:center'>Sr. No</th>
                                                      <th style='text-align:center'>Category</th>
                                                      <th style='text-align:center'>Section</th>
                                                      <th style='text-align:center'>Image</th>
                                                      <th style='text-align:center'>Action</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              <?  
                                              $ij = 0; $priority_based = ''; $value2prid=array(); $value2img=array(); $value2pid=array();
                                              for($i=0;$i<count($value[2]);$i++){
                                                $ij++;$catname=""; $agefrm="";$agto="";  
                                                if(in_array($value[2][$i]['TLMPRID'],$cateid)){
                                                    $catname=$catename[$value[2][$i]['TLMPRID']];
                                                    $agefrm=$agefrom[$value[2][$i]['TLMPRID']];
                                                    $agto=$ageto[$value[2][$i]['TLMPRID']];
                                                }
                                             
                                                $filename = $value[2][$i]['TLMSIMG'];
                                                $folder_path = "approval_desk/tailyou_admin_master/section/"; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                      <td style='text-align:center'><?=$ij?></td>
                                                      <td style='text-align:left'><?= $catname;?> ( <?=$agefrm?> to <?=$agto?> )</td>
                                                      <td style='text-align:left'><?=$value[2][$i]['TLMASNM']?></td>
                                                      <td style='text-align:center'><a target='_blank' href='ftp_image_view.php?pic=<?=$filename?>&path=<?=$folder_path?>'><img src='ftp_image_view.php?pic=<?=$filename?>&path=<?=$folder_path?>' style='max-height:40px;max-width:40px'/></a></td>
                                                       <td style='text-align:center'><button class="btn btn-sm btn-info" type="button" onclick="editsec('<?=$value[2][$i]['TLMASID']?>')"  data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></button> 

                                                           | <button class="btn btn-sm btn-danger" type="button"  onclick="delsec('<?=$value[2][$i]['TLMASID']?>')" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash-o"></i></button> </td>
                                                  </tr>
                                                <? } ?>
                                              </tbody>
                                          </table>
                                      </div>
                                      <div style="clear: both; min-height: 10px;"></div>
                                    </form>
                                </div>
                                <!-- id_section - End -->


                                <!-- id_style - Start -->
                                <div class="tab-pane" id="id_style">
                                    <!-- CATEGORY -->
                                      <form name="style_frm" id="style_frm" action="" role="form" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="action" id="action" value="save_style"/>
                                        <input type="hidden" name="insert_style" id="insert_style" value="STYLE"/>
                                         <div class="form-group">
                                        <div class='col-md-6' style="padding-bottom:10px;">
                                       

                                        <label class="col-md-3 control-label" style="text-align: right;line-height: 30px;">Category<span style='color:red'> *</span>&nbsp;:</label>
                                        <div class="col-md-9" style='margin-left:-20px'>
                                            <select class="form-control custom-select chosn"  tabindex='1' required name='slu_category1' id='slu_category1' onchange="check('slu_category1','style_cat');find_section(this.value);"  data-placement="top" data-toggle="" data-original-title="Choose The Category" >
                                           <option value="choose top core" selected>Choose The Category</option>
                                                        <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[1]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[1][$i]['TLMASID']?>' ><?=$value[1][$i]['TLMASNM']?> (<?=$value[1][$i]['TLAGFYR']?> to <?=$value[1][$i]['TLAGTYR']?>)</option>
                                                        <? } ?>
                                        </select>
                                            <span class="help-block" id="style_cat" style="display:none">Note : Select The Category</span>
                                        </div>
                                    </div>
                                    <!-- CATEGORY -->
                                    <!-- STYLE -->
                                   <div class='col-md-6' style="padding-bottom:10px;">
                                   
                                        <label class="col-md-3 control-label" id='attachment' style="text-align: right;line-height: 30px;">Style<span style='color:red;'> *</span>&nbsp;:</label>
                                        <div class="col-md-9 col-xs-12" style='margin-left:-20px'>
                                            <div class="">
                                                <input type="text" class="form-control" tabindex="3" onkeyup="check('txt_style','style_stl')" id="txt_style" name="txt_style" style="text-transform: uppercase;" maxlength="100" required data-toggle="tooltip" data-placement="top" data-original-title="Enter The Style"/>
                                            </div>
                                            <span class="help-block" id="style_stl" style="display:none">Note : Enter The Style</span>
                                        </div>
                                        <div class="tags_clear"></div>
                                   </div><br>
                                    <!-- STYLE -->
                                    <!-- SECTION -->
                                     <div class='col-md-6' style="padding-bottom:10px;">
                                   
                                        <label class="col-md-3 control-label" style="text-align: right;line-height: 30px;">Section<span style='color:red'> *</span>&nbsp;:</label>
                                        <div class="col-md-9" style='margin-left:-20px' >
                                          <span id='section_fields'>
                                            <select class="form-control custom-select chosn"  tabindex='2' required name='slu_section1' id='slu_section1'  data-placement="top" onChange="check('slu_section1','style_sec');find_style()"  data-original-title="Choose The Section" >
                                            <option value="choose top core" selected>Choose The Section</option>
                                             <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[2]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[2][$i]['TLMASID']?>' ><?=$value[2][$i]['TLMASNM']?></option>
                                                        <? } ?>
                                        </select></span>
                                            <span class="help-block" id="style_sec" style="display:none">Note: Select The Section</span>
                                        </div>
                                    </div>
                                    

                                    <!-- STYLE -->
                                    <div class='col-md-6' style="padding-bottom:10px;">
                                   
                                    <label class="col-md-3 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Image<span style='color:red;'> *</span>&nbsp; : </label>
                                            <div class="col-md-9 col-xs-12" style='margin-left:-20px'>
                                                <div class="">
                                                    <input type="file" tabindex="4" onchange="check('img_style','style_img')" class="form-control" id="img_style" name="img_style" style="text-transform: uppercase;" maxlength="100" required data-toggle="tooltip" data-placement="top" data-original-title="Select The Image" accept="image/x-png,image/gif,image/jpeg"/>
                                                </div>
                                                <span class="help-block" id="style_img" style="display:none">Note : Select The Image</span>
                                                <div class="preview_style">
                                                    <img id="preview_style_img" style="max-height:50px;max-width:50px" src=""/>
                                                </div>  
                                            </div></div></div>
                                        <div style="clear: both;"></div>
                                        <div style='clear:both; text-align: center;'>
                                            <div class="input-group" style="margin: 30px 0px 10px 0px; width: 100%; vertical-align: middle; text-align: center;">
                                                <button class="btn btn-info" id="style_submit" type="button" style="text-align: center;" onclick="nsubmit_form('style_frm');"><span class="fa fa-thumbs-up"></span> Submit</button>
                                                 <input type="hidden" name="action" id="action" value="update_style"/>
                                                    <input type="hidden" name="stylemasid" id="stylemasid" value=""/>
                                                    <button class="btn btn-info" id="style_update" type="button" style="text-align: center;display:none" onclick="nupdate_form('style_frm');"><span class="fa fa-thumbs-up"></span>Update</button>&nbsp;&nbsp;
                                                    <button class="btn btn-warning" id="style_cancel" type="button" style="text-align: center;display:none" onclick="location.reload()"><span class="fa fa-thumbs-down"></span>Cancel</button>
                                            </div>
                                        </div>
                                         <div style="clear: both; min-height: 10px; border-bottom: 1px solid #a0a0a0;"></div>

                                        <div style="padding-top: 10px;">
                                            <h3 class="panel-title"><b>Style List</b></h3>
                                        </div>
                                        <div style="clear: both;"></div>
                                        <div style="maring:5px; padding-top: 10px;">
                                          <table id="style_tbl" class="table" style="overflow-x: scroll !important;">
                                              <thead>
                                                  <tr>
                                                      <th style='text-align:center'>Sr. No</th>
                                                      <th style='text-align:center'>Category</th>
                                                      <th style='text-align:center'>Section</th>
                                                      <th style='text-align:center'>Section Img</th>
                                                      <th style='text-align:center'>Style</th>
                                                      <th style='text-align:center'>Style Img</th>
                                                      <th style='text-align:center'>Action</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              <?  
                                              $ij = 0; $priority_based = '';$value3prid=array();$value3img=array();$value3pid=array();
                                              for($i=0;$i<count($value[3]);$i++){$catname=""; $ij++;$agefrm="";$agto=""; 
                                                    if(in_array($value[3][$i]['TLMPRID'],$cateid)){;
                                                        $parid=$parentid[$value[3][$i]['TLMPRID']];
                                                        $secname=$catename[$value[3][$i]['TLMPRID']];
                                                        $secimg=$cateimage[$value[3][$i]['TLMPRID']];

                                                        $filename1 = $secimg;
                                                        $folder_path1 = "approval_desk/tailyou_admin_master/section/";  
                                                      }else{
                                                        $secname="";$secimg="";
                                                      } 
                                                      if(in_array($parid,$cateid)){
                                                        $catname=$catename[$parid];
                                                        $agefrm=$agefrom[$parid];
                                                        $agto=$ageto[$parid];
                                                      }    
                                                      
                                                      $filename2 = $value[3][$i]['TLMSIMG'];
                                                      $folder_path2 = "approval_desk/tailyou_admin_master/style/";                                               
                                                    ?>
                                                      <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                          <td style='text-align:center'><?=$ij?></td>
                                                          <td style='text-align:left'><?= $catname?> ( <?=$agefrm?> to <?=$agto?> )</td>
                                                          <td style='text-align:left'><?= $secname?></td>
                                                          <td style='text-align:center'><a target='_blank' href='ftp_image_view.php?pic=<?=$filename1?>&path=<?=$folder_path1?>'><img src='ftp_image_view.php?pic=<?=$filename1?>&path=<?=$folder_path1?>' style='max-height:40px;max-width:40px'/></a></td>
                                                          <td style='text-align:left'><?=$value[3][$i]['TLMASNM']?></td>
                                                          <td style='text-align:center'><a target='_blank' href='ftp_image_view.php?pic=<?=$filename2?>&path=<?=$folder_path2?>'><img src='ftp_image_view.php?pic=<?=$filename2?>&path=<?=$folder_path2?>' style='max-height:40px;max-width:40px'/></a></td>
                                                           <td style='text-align:center'><button class="btn btn-sm btn-info" type="button" onclick="editstyle('<?=$value[3][$i]['TLMASID']?>')"  data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></button> 

                                                           | <button class="btn btn-sm btn-danger" type="button"  onclick="delstyle('<?=$value[3][$i]['TLMASID']?>')" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash-o"></i></button> </td>
                                                      </tr>
                                                  <? } ?>
                                              </tbody>
                                          </table>
                                      </div>
                                      <div style="clear: both; min-height: 10px;"></div>
                                </form>
                                </div>
                                <!-- id_style - End -->


                                <!-- id_parts - Start -->
                                <div class="tab-pane" id="id_parts">
                                    <!-- CATEGORY -->
                                      <form name="parts_frm" id="parts_frm" action="" role="form" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="action" id="action" value="save_parts"/>
                                        <input type="hidden" name="insert_parts" id="insert_parts" value="PARTS"/>
                                         <div class="form-group">
                                        <div class='col-md-6' style="padding-bottom: 10px">
                                       

                                        <label class="col-md-3 control-label" style="text-align: right;">Category<span style='color:red;line-height: 30px;'> *</span> &nbsp;:</label>
                                        <div class="col-md-9"  style='margin-left:-20px'>
                                            <select class="form-control custom-select chosn"  tabindex='1' required name='slu_category2' id='slu_category2' onChange="check('slu_category2','parts_cat');find_section1(this.value)" data-toggle="" data-placement="top" data-original-title="Choose The Category" >
                                            <option value="choose top core" selected>Choose The Category</option>
                                               <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[1]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[1][$i]['TLMASID']?>' ><?=$value[1][$i]['TLMASNM']?> (<?=$value[1][$i]['TLAGFYR']?> to <?=$value[1][$i]['TLAGTYR']?>)</option>
                                                        <? } ?>
                                        </select>
                                            <span class="help-block" id="parts_cat" style="display:none">Note : Select The Category</span>
                                        </div>
                                    </div>
                                    <!-- CATEGORY -->
                                    <!--PARTS-->
                                    <div class='col-md-6' style="padding-bottom:10px">
                                   
                                        <label class="col-md-3 control-label" id='attachment' style="text-align: right;line-height: 30px;">Parts<span style='color:red;'> *</span> &nbsp;:</label>
                                        <div class="col-md-9 col-xs-12" style='margin-left:-20px'>
                                            <div class="">
                                                <input type="text" onkeyup="check('txt_parts','parts_prt')" class="form-control" id="txt_parts" name="txt_parts" tabindex="4" style="text-transform: uppercase;" maxlength="100" required data-toggle="tooltip" data-placement="top" data-original-title="Enter The Parts"/>
                                            </div>
                                            <span class="help-block" id="parts_prt" style="display:none">Note : Enter The Parts</span>
                                        </div>
                                        <div class="tags_clear"></div>
                                   </div>
                                   <!--PARTS-->
                                    <!-- SECTION -->
                                    <div class='col-md-6' style="padding-bottom: 10px">
                                   
                                        <label class="col-md-3 control-label" style="text-align: right;line-height: 30px;">Section&nbsp; :</label>
                                        <div class="col-md-9" style='margin-left:-20px'>
                                            <span id='section_fields1'>
                                            <select class="form-control custom-select chosn"  tabindex='2' required name='slu_section2' id='slu_section2' onChange="check('slu_section2','parts_sec');find_style(this.value)"  data-placement="top" data-toggle="" data-original-title="Choose The Section" >
                                            <option value="choose top core" selected>Choose The Section</option>
                                            <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[2]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[2][$i]['TLMASID']?>' ><?=$value[2][$i]['TLMASNM']?></option>
                                                        <? } ?>
                                        </select></span>
                                            <span class="help-block" id="parts_sec" style="display:none">Note : Select The Section</span>
                                        </div>
                                    </div>
                                    <!-- SECTION -->
                                    

                                    <!-- IMAGE -->
                                    <div class='col-md-6' style="padding-bottom: 10px">
                                   
                                    <label class="col-md-3 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Image<span style='color:red;'> *</span> &nbsp;: </label>
                                            <div class="col-md-9 col-xs-12" style='margin-left:-20px'>
                                                <div class="">
                                                    <input type="file" onchange="check('img_parts','parts_img')" class="form-control" id="img_parts" name="img_parts" tabindex="5" style="text-transform: uppercase;" maxlength="100" data-toggle="tooltip" data-placement="top" data-original-title="Select The Image" accept="image/x-png,image/gif,image/jpeg"/>
                                                </div>
                                                <span class="help-block" id="parts_img" style="display:none">Note : Select The Image</span>
                                               
                                            </div>
                                        </div>
                                    
                                    <!-- STYLE -->
                                    <div class='col-md-6'>
                                   
                                        <label class="col-md-3 control-label" style="text-align: right;line-height: 30px;">Style&nbsp;:</label>
                                        <div class="col-md-9" style='margin-left:-20px'>
                                          <span id='style_fields'>
                                            <select class="form-control custom-select chosn"  tabindex='3' required name='slu_style1' id='slu_style1' data-toggle="" data-placement="top" data-original-title="Choose The Style" >
                                            <option value="choose top core" selected>Choose The Style</option>
                                           <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[3]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[3][$i]['TLMASID']?>' ><?=$value[3][$i]['TLMASNM']?></option>
                                                        <? } ?>
                                        </select></span>
                                            <span class="help-block" id="parts_stl" style="display:none">Note : Select The Style</span>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                      <span class='col-md-3'></span>
                                       <div class="preview_parts">
                                          <img id="preview_parts_img" style="max-height:50px;max-width:50px" src=""/>
                                        </div>  

                                    </div>
                                        <!-- IMAGE -->
                                    </div>
                                        <div style="clear: both;"></div>
                                        <div style='clear:both; text-align: center;'>
                                            <div class="input-group" style="margin: 30px 0px 10px 0px; width: 100%; vertical-align: middle; text-align: center;">
                                                <button class="btn btn-info" id="parts_submit" type="button" style="text-align: center;" onclick="nsubmit_form('parts_frm');"><span class="fa fa-thumbs-up"></span> Submit</button>
                                                <input type="hidden" name="action" id="action" value="update_parts"/>
                                                    <input type="hidden" name="partsmasid" id="partsmasid" value=""/>
                                                    <button class="btn btn-info" id="parts_update" type="button" style="text-align: center;display:none" onclick="nupdate_form('parts_frm');"><span class="fa fa-thumbs-up"></span>Update</button>&nbsp;&nbsp;
                                                    <button class="btn btn-warning" id="parts_cancel" type="button" style="text-align: center;display:none" onclick="location.reload()"><span class="fa fa-thumbs-down"></span>Cancel</button>
                                            </div>
                                        </div>
                                         <div style="clear: both; min-height: 10px; border-bottom: 1px solid #a0a0a0;"></div>

                                        <div style="padding-top: 10px;">
                                            <h3 class="panel-title"><b>Parts List</b></h3>
                                        </div>
                                        <div style="clear: both;"></div>
                                        <div style="margin:5px; padding-top: 10px;">
                                          <table id="parts_tbl" class="table" style="overflow-x: scroll !important;">
                                              <thead>
                                                  <tr>
                                                      <th style='text-align:center'>Sr. No</th>
                                                      <th style='text-align:center'>Category</th>
                                                      <th style='text-align:center'>Section</th>
                                                      <th style='text-align:center'>Section Img</th>
                                                      <th style='text-align:center'>Style</th>
                                                      <th style='text-align:center'>Style Img</th>
                                                      <th style='text-align:center'>Parts</th>
                                                      <th style='text-align:center'>Parts Img</th>
                                                       <th style='text-align:center'>Action</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              <?  
                                              $ij = 0; $priority_based = '';$value4prid=array();$value4img=array();$value4pid=array();
                                              for($i=0;$i<count($value[4]);$i++){  
                                                   $catname="";$stylename="";$styleimg="";$ij++;$secname="";$secimg="";$parid="";$parid1="";$style=false;$agefrm="";$agto="";$section=false; 
                                                    if(in_array($value[4][$i]['TLMPRID'],$cateid) && $type[$value[4][$i]['TLMPRID']]==3){
                                                        $style=true;
                                                        $parid=$parentid[$value[4][$i]['TLMPRID']];
                                                        $stylename=$catename[$value[4][$i]['TLMPRID']];
                                                        $styleimg=$cateimage[$value[4][$i]['TLMPRID']];
                                                        $filename2 = $styleimg;
                                                        $folder_path2 = "approval_desk/tailyou_admin_master/style/"; 
                                                      }
                                                       else if(in_array($value[4][$i]['TLMPRID'],$cateid) && $type[$value[4][$i]['TLMPRID']]==2){
                                                        $section=true;
                                                        $parid=$parentid[$value[4][$i]['TLMPRID']];
                                                        $secname=$catename[$value[4][$i]['TLMPRID']];
                                                        $secimg=$cateimage[$value[4][$i]['TLMPRID']];
                                                        $filename1 = $secimg;
                                                        $folder_path2 = "approval_desk/tailyou_admin_master/section/"; 

                                                    }
                                                    else if(in_array($value[4][$i]['TLMPRID'],$cateid) && $type[$value[4][$i]['TLMPRID']]==1){
                                                           $catname=$catename[$value[4][$i]['TLMPRID']]; 
                                                           $agefrm=$agefrom[$value[4][$i]['TLMPRID']];
                                                           $agto=$ageto[$value[4][$i]['TLMPRID']];

                                                    }
                                                   if($style){
                                                        if(in_array($parid,$cateid)){
                                                            $parid1=$parentid[$parid];
                                                            $secname=$catename[$parid];
                                                            $secimg=$cateimage[$parid];
                                                            $filename1 = $secimg;
                                                            $folder_path1 = "approval_desk/tailyou_admin_master/section/";  
                                                        }
                                                        if(in_array($parid1,$cateid)){
                                                           $catname=$catename[$parid1]; 
                                                           $agefrm=$agefrom[$parid1];
                                                           $agto=$ageto[$parid1];
                                                       }
                                                    }
                                                   
                                                     if($section){
                                                       
                                                        if(in_array($parid,$cateid)){
                                                           $catname=$catename[$parid]; 
                                                           $agefrm=$agefrom[$parid];
                                                           $agto=$ageto[$parid];
                                                       }
                                                    }
                                                    
                                                   
                                                    $filename3 = $value[4][$i]['TLMSIMG'];
                                                    $folder_path3 = "approval_desk/tailyou_admin_master/parts/"; 
                                                    ?>
                                                      <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                           <td style='text-align:center'><?=$ij?></td>
                                                          <td style='text-align:left'><?=$catname?> ( <?=$agefrm?> to <?=$agto?> ) </td>
                                                          <td style='text-align:left'><? if($secname!="") echo $secname; else echo "-";?> </td>
                                                          <td style='text-align:center'><?if($secname!=""){?><a target='_blank' href='ftp_image_view.php?pic=<?=$filename1?>&path=<?=$folder_path1?>'><img src='ftp_image_view.php?pic=<?=$filename1?>&path=<?=$folder_path1?>' style='max-height:40px;max-width:40px'/></a><?}else{ echo "-";}?></td>
                                                          <td style='text-align:left'><?if($stylename!="") echo $stylename; else echo "-";?>  </td>
                                                          <td style='text-align:center'><?if($styleimg!=""){?> <a target='_blank' href='ftp_image_view.php?pic=<?=$filename2?>&path=<?=$folder_path2?>'><img src='ftp_image_view.php?pic=<?=$filename2?>&path=<?=$folder_path2?>' style='max-height:40px;max-width:40px'/></a><?}else{ echo "-";}?></td>
                                                          <td style='text-align:left'><?=$value[4][$i]['TLMASNM']?></td>
                                                          <td style='text-align:center'><a target='_blank' href='ftp_image_view.php?pic=<?=$filename3?>&path=<?=$folder_path3?>'><img src='ftp_image_view.php?pic=<?=$filename3?>&path=<?=$folder_path3?>' style='max-height:40px;max-width:40px'/></a></td>
                                                           <td style='text-align:center'><button class="btn btn-sm btn-info" type="button" onclick="editparts('<?=$value[4][$i]['TLMASID']?>')"  data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></button> 

                                                           | <button class="btn btn-sm btn-danger" type="button"  onclick="delparts('<?=$value[4][$i]['TLMASID']?>')" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash-o"></i></button> </td>
                                                           
                                                      </tr>
                                                  <? } ?>
                                              </tbody>
                                          </table>
                                      </div>
                                      <div style="clear: both; min-height: 10px;"></div>
                                </form>
                                </div>
                                <!-- id_parts - End -->


                                <!-- id_addons - Start -->
                                <div class="tab-pane" id="id_addons">
                                    <!-- CATEGORY -->
                                    <!-- CATEGORY -->
                                      <form name="addons_frm" id="addons_frm" action="" role="form" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="action" id="action" value="save_addons"/>
                                        <input type="hidden" name="insert_addons" id="insert_addons" value="ADD-ONS"/>
                                        <div class="form-group">
                                        
                                        <div class='row'>  
                                        <div class='col-md-6' style="padding-bottom: 10px">
                                       

                                        <label class="col-md-3 control-label" style="text-align: right;line-height: 30px">Category<span style='color:red'> *</span> &nbsp;:</label>
                                        <div class="col-md-9" style='margin-left:-10px'>
                                            <select class="form-control custom-select chosn"  tabindex='1' required name='slu_category3' id='slu_category3' onChange="check('slu_category3','addons_cat');find_section2(this.value)"  data-placement="top" data-original-title="Choose The Category" data-toggle="">
                                            <option value="choose top core" selected>Choose The Category</option>
                                             <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[1]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[1][$i]['TLMASID']?>' ><?=$value[1][$i]['TLMASNM']?> (<?=$value[1][$i]['TLAGFYR']?> to <?=$value[1][$i]['TLAGTYR']?>)</option>
                                                        <? } ?>
                                        </select>
                                            <span class="help-block" id="addons_cat" style="display:none">Note : Select The Category</span>
                                        </div>
                                    </div>
                                     <!-- ADDONS -->
                                   <div class='col-md-6' style="padding-bottom: 10px">
                                   
                                        <label class="col-md-3 control-label" id='attachment' style="text-align: right;line-height: 30px;">Add-Ons<span style='color:red;'> *</span>&nbsp;:</label>
                                        <div class="col-md-9 col-xs-12" style='margin-left:-10px'>
                                            <div class="">
                                                <input type="text" class="form-control" id="txt_addons" name="txt_addons" style="text-transform: uppercase;" maxlength="100" onkeyup="check('txt_addons','addons_add')" required data-toggle="tooltip" data-placement="top" data-original-title="Enter The Addons"/>
                                            </div>
                                            <span class="help-block" id="addons_add" style="display:none">Note : Enter The Add-Ons</span>
                                        </div>
                                        <div class="tags_clear" ></div>
                                   </div>
                                   </div> 
                                    <!-- SECTION -->
                                    <div class='row'>  
                                    <div class='col-md-6' style="padding-bottom: 10px">
                                   
                                        <label class="col-md-3 control-label" style="text-align: right;line-height: 30px;">Section&nbsp;:</label>
                                        <div class="col-md-9"  style='margin-left:-10px'>
                                            <span id='section_fields2'>
                                            <select class="form-control custom-select chosn"  tabindex='1' required name='slu_section3' id='slu_section3' onChange="check('slu_section3','addons_sec');find_style1(this.value)"  data-placement="top" data-original-title="Choose The Section" data-toggle="">
                                            <option value="choose top core" selected>Choose The Section</option>
                                             <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[2]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[2][$i]['TLMASID']?>' ><?=$value[2][$i]['TLMASNM']?></option>
                                                        <? } ?>
                                        </select></span>
                                            <span class="help-block" id="addons_sec" style="display:none">Note : Select The Section</span>
                                        </div>
                                    </div>
                                    <!-- SECTION -->
                                   <div class='col-md-6' style="padding-bottom: 10px">
                                   
                                    <label class="col-md-3 col-xs-12 control-label" style="text-align: right; line-height: 30px;">Image<span style='color:red;'> *</span> &nbsp;: </label>
                                            <div class="col-md-9 col-xs-12" style="margin-left:-10px">
                                                <div class="">
                                                    <input type="file" class="form-control" id="img_addons" name="img_addons" style="text-transform: uppercase;" onchange="check('img_addons','addons_img')" maxlength="100" required data-toggle="tooltip" data-placement="top" data-original-title="Select The Image" accept="image/x-png,image/gif,image/jpeg"/>
                                                </div>
                                                <span class="help-block" id="addons_img" style="display:none">Note : Select The Image</span>
                                                
                                            </div>
                                        </div>
                                       </div> 
                                        <!-- IMAGE -->
                                <!-- STYLE-->

                                    <div class='row'>
                                      <div class='col-md-6' style="padding-bottom: 10px">
                                   
                                        <label class="col-md-3 control-label" style="text-align: right;line-height: 30px;">Style &nbsp;:</label>
                                        <div class="col-md-9"  style='margin-left:-10px'>
                                            <span id='style_fields1'>
                                            <select class="form-control custom-select chosn"  tabindex='1' required name='slu_style2' id='slu_style2' onChange="find_parts(this.value)"  data-placement="top" data-original-title="Choose The Style" data-toggle="">
                                            <option value="choose top core" selected>Choose The Style</option>
                                             <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[3]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[3][$i]['TLMASID']?>' ><?=$value[3][$i]['TLMASNM']?></option>
                                                        <? } ?>
                                        </select></span>
                                            <span class="help-block" id="addons_stl" style="display:none">Note : Select The Style</span>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                      <span class='col-md-3'></span>
                                      <div class="preview_addons">
                                            <img id="preview_addons_img" style="max-height:50px;max-width:50px" src=""/>
                                        </div>  
                                    </div>
                                  </div>
                                    <!-- STYLE -->
                                    
                                 
                                    
                                        <!-- PARTS -->
                                    <div class='row'>                                 
                                     <div class='col-md-6' style="padding-bottom: 10px">
                                   
                                        <label class="col-md-3 control-label" style="text-align: right;line-height: 30px;">Parts &nbsp;:</label>
                                        <div class="col-md-9"  style='margin-left:-10px'>
                                          <span id='part_fields'>
                                            <select class="form-control custom-select chosn"  tabindex='1' required name='slu_parts' id='slu_parts'  data-placement="top" data-original-title="Choose The Parts" data-toggle="" >
                                            <option value="choose top core" selected>Choose The Parts</option>
                                            <?   
                                                      $ij = 0; $priority_based = '';
                                                      for($i=0;$i<count($value[4]);$i++){                                                  
                                                          $ij++; ?>
                                                                <option value='<?=$value[4][$i]['TLMASID']?>' ><?=$value[4][$i]['TLMASNM']?></option>
                                                        <? } ?>
                                        </select></span>
                                            <span class="help-block" id="addons_prt" style="display:none">Note : Select The Parts</span>
                                        </div>
                                    </div>
                                  </div>
                                    </div>
                                        <div style="clear: both;"></div>
                                        <div style='clear:both; text-align: center;'>
                                            <div class="input-group" style="margin: 30px 0px 10px 0px; width: 100%; vertical-align: middle; text-align: center;">
                                                <button class="btn btn-info" type="button" id="addons_submit" style="text-align: center;" onclick="nsubmit_form('addons_frm');"><span class="fa fa-thumbs-up"></span> Submit</button>
                                                 <input type="hidden" name="action" id="action" value="update_addons"/>
                                                    <input type="hidden" name="addonsmasid" id="addonsmasid" value=""/>
                                                    <button class="btn btn-info" id="addons_update" type="button" style="text-align: center;display:none" onclick="nupdate_form('addons_frm');"><span class="fa fa-thumbs-up"></span>Update</button>&nbsp;&nbsp;
                                                    <button class="btn btn-warning" id="addons_cancel" type="button" style="text-align: center;display:none" onclick="location.reload()"><span class="fa fa-thumbs-down"></span>Cancel</button>
                                            </div>
                                        </div>
                                         <div style="clear: both; min-height: 10px; border-bottom: 1px solid #a0a0a0;"></div>

                                        <div style="padding-top: 10px;">
                                            <h3 class="panel-title"><b>Add-ons List</b></h3>
                                        </div>
                                        <div style="clear: both;"></div>
                                        <div style="maring:5px; padding-top: 10px;">
                                          <table id="addons_tbl" class="table" style="overflow-x: scroll !important;">
                                              <thead>
                                                  <tr>
                                                      <th style='text-align:center'>Sr. No</th>
                                                      <th style='text-align:center'>Category</th>
                                                      <th style='text-align:center'>Section</th>
                                                      <th style='text-align:center'>Section Img</th>
                                                      <th style='text-align:center'>Style</th>
                                                      <th style='text-align:center'>Style Img</th>
                                                      <th style='text-align:center'>Parts</th>
                                                      <th style='text-align:center'>Parts Img</th>
                                                      <th style='text-align:center'>Addons</th>
                                                      <th style='text-align:center'>Addons Img</th>
                                                      <th style='text-align:center'>Action</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              <? 
                                               $ij = 0; 
                                              for($i=0;$i<count($value[5]);$i++){  
                                                   $catname="";$stylename="";$styleimg="";$ij++;$secname="";$secimg="";$partsname="";$partsimg="";$parid="";$parid1="";$style=false;$parts=false;$section=false;$agefrm="";$agto="";
                                                    if(in_array($value[5][$i]['TLMPRID'],$cateid) && $type[$value[5][$i]['TLMPRID']]==4)
                                                    {
                                                        $parts=true;
                                                        $parid=$parentid[$value[5][$i]['TLMPRID']];
                                                        $partsname=$catename[$value[5][$i]['TLMPRID']];
                                                        $partsimg=$cateimage[$value[5][$i]['TLMPRID']];
                                                        $filename3 = $partsimg;
                                                        $folder_path3 = "approval_desk/tailyou_admin_master/parts/"; 
                                                    
                                                        if(in_array($parid,$cateid) && $type[$parid]==3)
                                                        {
                                                            $style=true;    
                                                            $parid1=$parentid[$parid];
                                                            $stylename=$catename[$parid];
                                                            $styleimg=$cateimage[$parid];
                                                            $filename2 = $styleimg;
                                                            $folder_path2 = "approval_desk/tailyou_admin_master/style/"; 
                                                        
                                                                      if(in_array($parid1,$cateid) && $type[$parid1]==2)
                                                                      {
                                                                        $section=true;
                                                                        $parid2=$parentid[$parid1];
                                                                        $secname=$catename[$parid1];
                                                                        $secimg=$cateimage[$parid1];

                                                                        $filename1 = $secimg;
                                                                        $folder_path1 = "approval_desk/tailyou_admin_master/section/";

                                                                          $catname=$catename[$parid2]; 
                                                                          $agefrm=$agefrom[$parid2];
                                                                          $agto=$ageto[$parid2];
                                                                        }
                                                                        else{

                                                                          $catname=$catename[$parid1]; 
                                                                          $agefrm=$agefrom[$parid1];
                                                                          $agto=$ageto[$parid1];
                                                                        }

                                                       } 
                                                      else if(in_array($parid,$cateid) && $type[$parid]==2)
                                                        {
                                                                        $section=true;
                                                                        $parid1=$parentid[$parid];
                                                                        $secname=$catename[$parid];
                                                                        $secimg=$cateimage[$parid];

                                                                        $filename1 = $secimg;
                                                                        $folder_path1 = "approval_desk/tailyou_admin_master/section/";

                                                                        $catname=$catename[$parid1]; 
                                                                        $agefrm=$agefrom[$parid1];
                                                                        $agto=$ageto[$parid1];

                                                       } 
                                                       else
                                                       {
                                                                        $catname=$catename[$parid]; 
                                                                        $agefrm=$agefrom[$parid];
                                                                        $agto=$ageto[$parid];


                                                       }
                                                     }
                                                    else  if(in_array($value[5][$i]['TLMPRID'],$cateid) && $type[$value[5][$i]['TLMPRID']]==3)
                                                    {
                                                        $parts=false;$style=true;
                                                        $parid=$parentid[$value[5][$i]['TLMPRID']];
                                                        $stylename=$catename[$value[5][$i]['TLMPRID']];
                                                        $styleimg=$cateimage[$value[5][$i]['TLMPRID']];
                                                        $filename2 = $styleimg;
                                                        $folder_path2 = "approval_desk/tailyou_admin_master/style/"; 
                                                    
                                                        if(in_array($parid,$cateid) && $type[$parid]==2)
                                                        {
                                                           
                                                                        $section=true;
                                                                        $parid1=$parentid[$parid];
                                                                        $secname=$catename[$parid];
                                                                        $secimg=$cateimage[$parid];

                                                                        $filename1 = $secimg;
                                                                        $folder_path1 = "approval_desk/tailyou_admin_master/section/";

                                                                          $catname=$catename[$parid1]; 
                                                                          $agefrm=$agefrom[$parid1];
                                                                          $agto=$ageto[$parid1];
                                                          }
                                                          else
                                                       {
                                                                        $catname=$catename[$parid]; 
                                                                        $agefrm=$agefrom[$parid];
                                                                        $agto=$ageto[$parid];


                                                       }

                                                     }

                                                    else  if(in_array($value[5][$i]['TLMPRID'],$cateid) && $type[$value[5][$i]['TLMPRID']]==2)
                                                    {
                                                        $parts=false;$style=false;$section=true;
                                                        $parid=$parentid[$value[5][$i]['TLMPRID']];
                                                        $secname=$catename[$value[5][$i]['TLMPRID']];
                                                        $secimg=$cateimage[$value[5][$i]['TLMPRID']];
                                                        $filename1 = $secimg;
                                                        $folder_path1 = "approval_desk/tailyou_admin_master/section/"; 
                                                    
                                                        $catname=$catename[$parid]; 
                                                        $agefrm=$agefrom[$parid];
                                                        $agto=$ageto[$parid];
                                                     }
                                                          else
                                                       {
                                                                        $catname=$catename[$value[5][$i]['TLMPRID']]; 
                                                                        $agefrm=$agefrom[$value[5][$i]['TLMPRID']];
                                                                        $agto=$ageto[$value[5][$i]['TLMPRID']];


                                                       }

                                                    $filename4 = $value[5][$i]['TLMSIMG'];
                                                    $folder_path4 = "approval_desk/tailyou_admin_master/addons/";   
                                                    ?>
                                                      <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                          <td style='text-align:center'><?=$ij?></td>
                                                          <td style='text-align:left'><?=$catname?> ( <?=$agefrm?> to <?=$agto?> )</td>
                                                          <td style='text-align:left'><?if($secname!="") echo $secname; else echo "-";?></td>
                                                          <td style='text-align:center'><?if($secname!=""){?><a target='_blank' target='_blank' href='ftp_image_view.php?pic=<?=$filename1?>&path=<?=$folder_path1?>'><img src='ftp_image_view.php?pic=<?=$filename1?>&path=<?=$folder_path1?>' style='max-height:40px;max-width:40px'/></a><?}else{ echo "-";}?></td>
                                                          <td style='text-align:left'><? if($stylename!=""){ echo $stylename; } else echo "-";?></td>
                                                          <td style='text-align:center'><?if($styleimg!=""){?><a target='_blank' target='_blank' href='ftp_image_view.php?pic=<?=$filename2?>&path=<?=$folder_path2?>'><img src='ftp_image_view.php?pic=<?=$filename2?>&path=<?=$folder_path2?>' style='max-height:40px;max-width:40px'/></a><?}else echo "-";?></td>
                                                          <td style='text-align:left'><? if($partsname!=""){ echo $partsname;} else echo "-";?></td>
                                                          <td style='text-align:center'><?if($partsimg!=""){?><a target='_blank' target='_blank' href='ftp_image_view.php?pic=<?=$filename3?>&path=<?=$folder_path3?>'><img src='ftp_image_view.php?pic=<?=$filename3?>&path=<?=$folder_path3?>' style='max-height:40px;max-width:40px'/></a><?}else{ echo "-";}?></td>
                                                          <td style='text-align:left'><?=$value[5][$i]['TLMASNM']?></td>
                                                          <td style='text-align:center'><a target='_blank' target='_blank' href='ftp_image_view.php?pic=<?=$filename4?>&path=<?=$folder_path4?>'><img src='ftp_image_view.php?pic=<?=$filename4?>&path=<?=$folder_path4?>' style='max-height:40px;max-width:40px'/></a></td>
                                                          <td style='text-align:center'><button class="btn btn-sm btn-info" type="button" onclick="editaddons('<?=$value[5][$i]['TLMASID']?>')"  data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></button> 

                                                           | <button class="btn btn-sm btn-danger" type="button"  onclick="deladdons('<?=$value[5][$i]['TLMASID']?>')" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-trash-o"></i></button> </td>
                                                      </tr>
                                                  <? } ?>
                                              </tbody>
                                          </table>
                                      </div>
                                      <div style="clear: both; min-height: 10px;"></div>
                                </form>
                                </div>
                                <!-- id_addons - Start -->

                            </div>

                    </div>
                        
                </div>
                <!--body ended--->
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>

    <!-- Show Modal Windows -->
    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width: 80%; max-width: 600px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Order Details</div>
                <div class="modal-body" id="modal-body1" style="padding: 0px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>

    
    <!-- Revert this card -->
    <!-- Show Modal Windows -->

    <!-- START SCRIPTS -->
    

    <!-- START THIS PAGE PLUGINS-->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
    <script type="text/javascript" src="js/plugins/moment.min.js"></script>
    <script type="text/javascript" src="js/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- END THIS PAGE PLUGINS-->

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
    <script type="text/javascript" src="js/task.js"></script>

    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>

    <link rel="stylesheet" href="css/flavor-lightbox.css">
    <script src="js/jquery.flavor.js"></script>
    <script src="js/script.js"></script>
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="js/admin_master1.js"></script>

   <script>
     
     function editcat(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'edit_category'
                },                
                dataType:"json",
                success:function(data){
                   
                   console.log(data['catename']);
                   $('#txt_category').val(data['catename']);
                   $('#txt_from').val(data['agefrom']);
                   $('#txt_to').val(data['ageto']);
                   $('#tlmasid').val(obj);
                   $('#cate_submit').hide();
                   $('#cate_update').show();
                   $('#cate_cancel').show();
                   window.scrollTo(0, 0);
                   // $('#load_page').show();
                  //location.reload();
                }
               
            });

        }
        function delcat(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'delete_category'
                },                
                dataType:"html",
                success:function(data){
                   
                   console.log(data);
                  
                   // $('#load_page').show();
                  location.reload();
                }
               
            });

        }
          function editsec(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'edit_section'
                },                
                dataType:"json",
                success:function(data){
                  console.log(data); 
                   // console.log(data['catename']);
                    $('#slt_category').val(data['cateid']);
                     $('#txt_section').val(data['secname']);
                     // $('#img_section').val(data['image']);
                     $('#preview_section_img').attr('src','ftp_image_view.php?pic='+data['image']+'&path=<?=$folder_path?>');
                     $('#secmasid').val(obj);
                     $('#section_submit').hide();
                   $('#section_update').show();
                   $('#section_cancel').show();
                   window.scrollTo(0, 0);
                  
                }
               
            });

        }
        function delsec(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'delete_section'
                },                
                dataType:"html",
                success:function(data){
                   
                   console.log(data);
                  
                   // $('#load_page').show();
                  location.reload();
                }
               
            });

        }
        function editstyle(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'edit_style'
                },                
                dataType:"json",
                success:function(data){
                  console.log(data); 
                   // console.log(data['catename']);
                    $('#slu_category1').val(data['cateid']);
                     $('#slu_section1').val(data['secid']);
                     $('#txt_style').val(data['stylename']);
                     // $('#img_section').val(data['image']);
                     $('#preview_style_img').attr('src','ftp_image_view.php?pic='+data['image']+'&path=<?=$folder_path2?>');
                     $('#stylemasid').val(obj);
                     $('#style_submit').hide();
                   $('#style_update').show();
                   $('#style_cancel').show();
                   window.scrollTo(0, 0);
                  
                }
               
            });

        }
        function delstyle(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'delete_style'
                },                
                dataType:"html",
                success:function(data){
                   
                   console.log(data);
                  
                   // $('#load_page').show();
                  location.reload();
                }
               
            });

        }
         function editparts(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'edit_parts'
                },                
                dataType:"json",
                success:function(data){
                  console.log(data); 
                   // console.log(data['catename']);
                    $('#slu_category2').val(data['cateid']);
                     $('#slu_section2').val(data['secid']);
                     $('#slu_style1').val(data['styleid']);
                     $('#txt_parts').val(data['partsname']);
                     // $('#img_section').val(data['image']);
                     $('#preview_parts_img').attr('src','ftp_image_view.php?pic='+data['image']+'&path=<?=$folder_path3?>');
                     $('#partsmasid').val(obj);
                     $('#parts_submit').hide();
                   $('#parts_update').show();
                   $('#parts_cancel').show();
                   window.scrollTo(0, 0);
                  
                }
               
            });

        }
        function delparts(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'delete_parts'
                },                
                dataType:"html",
                success:function(data){
                   
                   console.log(data);
                  
                   // $('#load_page').show();
                  location.reload();
                }
               
            });

        }
         function editaddons(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'edit_addons'
                },                
                dataType:"json",
                success:function(data){
                  console.log(data); 
                   // console.log(data['catename']);
                    $('#slu_category3').val(data['cateid']);
                     $('#slu_section3').val(data['secid']);
                     $('#slu_style2').val(data['styleid']);
                     $('#slu_parts').val(data['partsid']);
                     $('#txt_addons').val(data['addonsname']);
                     // $('#img_section').val(data['image']);
                     $('#preview_addons_img').attr('src','ftp_image_view.php?pic='+data['image']+'&path=<?=$folder_path4?>');
                     $('#addonsmasid').val(obj);
                     $('#addons_submit').hide();
                     $('#addons_update').show();
                     $('#addons_cancel').show();
                     window.scrollTo(0, 0);
                  
                }
               
            });

        }
        function deladdons(obj){
           $.ajax({
                url:"ajax/ajax_admin_master_entry.php",
                type: "POST",
                data: {
                    id:obj,
                    action:'delete_addons'
                },                
                dataType:"html",
                success:function(data){
                   
                   console.log(data);
                  
                   // $('#load_page').show();
                  location.reload();
                }
               
            });

        }

  

   </script>
    
<!-- END TEMPLATE -->
<!-- END SCRIPTS -->
</body>
</html>