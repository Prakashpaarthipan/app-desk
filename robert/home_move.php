<?php
error_reporting(0);
session_start();

if($_SESSION['tlu_cntlogn'] == 1) { ?>
	<script>window.location='change_password.php';</script>
<?php exit();
}

include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$usrprfl_img_path = "http://www.tailyou.com/images/tailyou-younew.png";
if($_SESSION['tlu_csimgpt'] != '') {
	$filename = $_SESSION['tlu_csimgpt'];
	$folder_path = "approval_desk/tailyou_customers/";
	$usrprfl_img_path = "ftp_image_view.php?pic=".$filename."&path=".$folder_path."";
}

// $_SESSION['tlu_user_mobile'] = '9994899999';
$sql_customer = select_query_json("select * from customers_tailyou where CUSMOBL = '".$_SESSION['tlu_user_mobile']."'", 'Centra', 'TEST');
$_SESSION['tlu_brncode'] = $sql_customer[0]['BRNCODE'];
$_SESSION['tlu_cuscode'] = $sql_customer[0]['CUSCODE'];
$_SESSION['tlu_user_mobile'] = $sql_customer[0]['CUSMOBL'];
$_SESSION['tlu_cusname'] = strtoupper($sql_customer[0]['CUSNAME']);
$_SESSION['tlu_cusemal'] = strtolower($sql_customer[0]['CUSEMAL']);
$_SESSION['tlu_csimgpt'] = $sql_customer[0]['CSIMGPT'];
$_SESSION['tlu_cntlogn'] = $sql_customer[0]['CNTLOGN'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="images/icon.png"  type="image/x-icon"/>
  <title>Customer Portal :: TAILYOU</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
  <? /* <link rel="stylesheet" href="css/font-awesome.css"> */ ?>
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.css">
  <!-- DataTables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap4.css">

  <link rel="stylesheet" href="css/bootstrap-imageupload.css">
	<style>

		@media (max-width: 767px) {
			.hidden-xs {
				display: none !important;
			}
			.visible-xs {
				display: block !important;
			}
		}
		@media (min-width: 768px) and (max-width: 991px) {
			.hidden-sm {
				display: none !important;
			}
			.visible-xs {
				display: block !important;
			}
		}
		@media (min-width: 992px) and (max-width: 1199px) {
			.hidden-md {
				display: none !important;
			}
			.visible-xs {
				display: block !important;
			}
		}
		@media (min-width: 1200px) {
			.hidden-lg {
				display: none !important;
			}
			.visible-xs {
				display: block !important;
			}
		}
	</style>
</head>
<body class="">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <!-- Profile Image -->
            <div class="card card-primary card-outline" style="background: url(http://www.tailyou.com/images/bg_new.jpg) repeat 0px 0px;">
              <div class="card-body box-profile">
                <div class="row">
					<div class="col-sm-4 col-xs-5 pull-left">
						<img class="img-fluid" src="http://www.tailyou.com/images/tailyou-younew.png" border="0" style="box-shadow:2px 5px 20px grey; border-radius:50%; width:100px;background:#fff">
					</div>
					<div class="col-sm-4 col-xs-6 text-right hidden-md hidden-sm hidden-lg pull-right" style="margin-top:-95px">
						<i class="fas fa-sign-out-alt" style='font-size:44px; cursor: pointer;color:#fcc837;background:#fff;padding:25px;border-radius:50%;' title="Signout from TAILYOU" onclick="location.href='logout.php';"></i>
					</div>
					<div class="col-sm-4 col-xs-12 text-center">

						<? /* <a href="#" data-toggle="modal" data-target="#profileIMG" style="border-radius:50%"> */ ?>
							<img class="profile-user-img img-fluid img-circle" src="<?=$usrprfl_img_path?>" alt="User profile picture" style="border: 1px solid #FFF !important;box-shadow:2px 5px 20px #000;background-image:url('images/Boards.png');background-size:cover;width:140px;padding:20px">
						<? /* </a> */ ?>
					</div>
					<div class="col-sm-4 text-right hidden-xs">
						<i class="fas fa-sign-out-alt" style='font-size:44px; cursor: pointer;color:#fcc837;background:#fff;padding:25px;border-radius:50%;' title="Signout from TAILYOU" onclick="location.href='logout.php';"></i>
					</div>

				</div>

                <h3 class="profile-username text-center" style="margin: 15px !important; "><span style="background-color:#FFF; color:#000; border-radius: 5px; box-shadow:2px 5px 20px #000; " id="customer_profile_name">&nbsp;&nbsp;<?=strtoupper($_SESSION['tlu_cusname'])?>&nbsp;&nbsp;</span></h3>

                <? /* <h3 class="profile-username text-center"><span style="background-color:#FFF; color:#000;">&nbsp;<?=$_SESSION['tlu_username']?>&nbsp;</span></h3>

                <p style='display:none;' class="text-muted text-center"><span style="background-color:#FFF; color:#000;">&nbsp;+91 98989 89898&nbsp;</span></p> */ ?>
								<!-- Change Profile Image using model -->
								  <div class="modal fade" id="profileIMG" role="dialog">
								    <div class="modal-dialog modal-md">

								      <!-- Modal content-->
								      <div class="modal-content">
								        <div class="modal-header">
													<h4 class="pull-left modal-title">Change Picture</h4>
								          <button type="button" class="close" data-dismiss="modal">&times;</button>
								        </div>
								        <div class="modal-body">
													<div class="row">
														<div class="col-sm-12">
															<div class="profile">
																<div class="imageupload panel panel-default">
																	<div class="text-center file-tab panel-body">
																	<br>
																	<br>
																	<label class="btn btn-default btn-file">
																			<img src="https://www.drupal.org/files/profile_default.png" style="height:250px;width:250px;border-radius:50%"/>
																			<span>Browse</span>
																			<!-- The file is stored here. -->
																			<input type="file" name="image-file">
																	</label>
																	<button type="button" class="btn btn-default" style="margin-top:-9px">Remove</button>
																	</div>
																</div>
													    </div>
														</div>
													</div>
													<div class="col-sm-12 text-center" style="padding:0">
														<hr>
														<button class="btn btn-primary" style="padding:6px 30px">Save</button>
													</div>
								        </div>

								      </div>

								    </div>
								  </div>
				<div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#current_orders" data-toggle="tab"><i class="fas fa-shopping-cart"></i> CURRENT ORDERS</a></li>
                  <li class="nav-item"><a class="nav-link" href="#order_history" data-toggle="tab"><i class="fas fa-history"></i> ORDER HISTORY</a></li>
                  <li class="nav-item"><a class="nav-link" href="#customer_profile" data-toggle="tab"><i class="fas fa-user"></i> PROFILE</a></li>
                  <? /* <li class="nav-item"><a class="nav-link" href="#customer_measurement" data-toggle="tab"><i class="fas fa-ruler"></i> MEASUREMENT</a></li> */ ?>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <!-- current orders -->
                  <div class="active tab-pane" id="current_orders" style="min-height: 500px;">
					<div class="card-body">
					  <table id="example1" class="table table-bordered table-striped">
					  	<? /* $sql_current_orders = select_query_json("SELECT to_char(SUMM.ENTDATE, 'dd-MM-yyyy HH:mi:ss AM') ENTDATE, to_char(SUMM.DUEDATE, 'dd-MM-yyyy') DUEDATE, SUMM.ENTAMNT,
					  														SUMM.ENTYEAR||' '||SUMM.ENTNUMB ORDER_NO, SUMM.CASPAID, SUMM.ITMISSU, to_char(summ.isudate, 'dd-MM-yyyy HH:mi:ss AM') isudate,
					  														CUS.CUSNAME, CUS.CUSMBLE, SUM(DET.PRDRATE*5/100) GST
																		FROM STITCHING_SUMMARY SUMM, STITCHING_DETAIL DET, CUSTOMER CUS, STITCHING_MASTER PRD
																		WHERE PRD.PRDCODE=DET.PRDCODE AND SUMM.BRNCODE = DET.BRNCODE AND SUMM.BRNCODE = CUS.BRNCODE AND DET.BRNCODE = CUS.BRNCODE AND
																			SUMM.CUSCODE = CUS.CUSCODE AND SUMM.ENTYEAR = DET.ENTYEAR AND summ.deleted='N' and SUMM.ENTNUMB = DET.ENTNUMB AND SUMM.BRNCODE = 114
																			AND CUS.CUSMBLE = '".$_SESSION['tlu_user_mobile']."' and SUMM.CASPAID = 'N' and SUMM.ITMISSU = 'N'
																		group by SUMM.ENTDATE, SUMM.DUEDATE, SUMM.ENTAMNT, SUMM.CASPAID, SUMM.ITMISSU, summ.isudate, CUS.CUSNAME, CUS.CUSMBLE, SUMM.ENTNUMB,
																			SUMM.ENTYEAR
																		Order by SUMM.ENTYEAR desc, SUMM.ENTNUMB desc", $_SESSION['tlu_brncode'], "TCS"); */
						$sql_current_orders = select_query_json("SELECT to_char(SUMM.ENTDATE, 'dd-MM-yyyy HH:mi:ss AM') ENTDATE, to_char(SUMM.DUEDATE, 'dd-MM-yyyy') DUEDATE, SUMM.ENTAMNT,
					  														SUMM.ENTYEAR||' '||SUMM.ENTNUMB ORDER_NO, SUMM.CASPAID, SUMM.ITMISSU, to_char(summ.isudate, 'dd-MM-yyyy HH:mi:ss AM') isudate,
					  														CUS.CUSNAME, CUS.CUSMBLE, SUM(DET.PRDRATE*5/100) GST
																		FROM STITCHING_SUMMARY SUMM, STITCHING_DETAIL DET, CUSTOMER CUS, STITCHING_MASTER PRD
																		WHERE PRD.PRDCODE=DET.PRDCODE AND SUMM.BRNCODE = DET.BRNCODE AND SUMM.BRNCODE = CUS.BRNCODE AND DET.BRNCODE = CUS.BRNCODE AND
																			SUMM.CUSCODE = CUS.CUSCODE AND SUMM.ENTYEAR = DET.ENTYEAR AND summ.deleted='N' and SUMM.ENTNUMB = DET.ENTNUMB AND 
																			SUMM.BRNCODE = '".$_SESSION['tlu_brncode']."' AND CUS.CUSMBLE = '".$_SESSION['tlu_user_mobile']."' and SUMM.CASPAID = 'N' 
																			and SUMM.ITMISSU = 'N'
																		group by SUMM.ENTDATE, SUMM.DUEDATE, SUMM.ENTAMNT, SUMM.CASPAID, SUMM.ITMISSU, summ.isudate, CUS.CUSNAME, CUS.CUSMBLE, SUMM.ENTNUMB,
																			SUMM.ENTYEAR
																		Order by SUMM.ENTYEAR desc, SUMM.ENTNUMB desc", $_SESSION['tlu_brncode'], "TCS");

						if(count($sql_current_orders) > 0) { ?>
						<thead>
							<tr style="text-align: center;">
							  <th>Sr. No</th>
							  <th>Order No</th>
							  <th>Entry Date</th>
							  <th>Amount &#8377</th>
							  <th>Delivery Date</th>
							  <? /* <th>Issue Date</th> */ ?>
							  <th>Status</th>
							</tr>
						</thead>
						<tbody>
							<? 	$coi = 0;
								foreach ($sql_current_orders as $co_key => $current_orders) { $coi++; ?>
								<tr style="text-align: center;">
								  <td><?=$coi?></td>
								  <td><?=$current_orders['ORDER_NO']?></td>
								  <td><?=$current_orders['ENTDATE']?></td>
								  <td><span class="badge bg-success" style="font-size: 100% !important;"><?=$current_orders['ENTAMNT']?></span></td>
								  <td><?=$current_orders['DUEDATE']?></td>
								  <? /* <td><?=$current_orders['ISUDATE']?></td> */ ?>
								  <td><? if($current_orders['CASPAID'] == 'Y') { ?> <span class="badge bg-success">Cash Paid</span> <? } elseif($current_orders['CASPAID'] == 'P') { ?> <span class="badge bg-warning">Cash Partially Paid</span> <? } else { ?> <span class="badge bg-danger">Cash Not Paid</span> <? } ?>&nbsp;/&nbsp;<? if($current_orders['ITMISSU'] == 'Y') { ?> <span class="badge bg-success">Item Issued</span> <? } else { ?> <span class="badge bg-danger">Item Not Issued</span> <? } ?></td>
								</tr>
							<? } ?>
						</tbody>
						<? } else { ?>
							<tr style="text-align: center;">
							  <td colspan="7">No Records Found..</td>
							</tr>
						<? } ?>
					  </table>
					</div>
					<!-- /.card-body -->
                  </div>
                  <!-- current orders -->

                  <!-- order history -->
                  <div class="tab-pane" id="order_history" style="min-height: 500px;">
					<table id="example2" class="table table-bordered table-striped">
					  	<? $sql_order_history = select_query_json("SELECT to_char(SUMM.ENTDATE, 'dd-MM-yyyy HH:mi:ss AM') ENTDATE, to_char(SUMM.DUEDATE, 'dd-MM-yyyy') DUEDATE, SUMM.ENTAMNT,
					  														SUMM.ENTYEAR||' '||SUMM.ENTNUMB ORDER_NO, SUMM.CASPAID, SUMM.ITMISSU, to_char(summ.isudate, 'dd-MM-yyyy HH:mi:ss AM') isudate,
					  														CUS.CUSNAME, CUS.CUSMBLE, SUM(DET.PRDRATE*5/100) GST
																		FROM STITCHING_SUMMARY SUMM, STITCHING_DETAIL DET, CUSTOMER CUS, STITCHING_MASTER PRD
																		WHERE PRD.PRDCODE=DET.PRDCODE AND SUMM.BRNCODE = DET.BRNCODE AND SUMM.BRNCODE = CUS.BRNCODE AND DET.BRNCODE = CUS.BRNCODE AND
																			SUMM.CUSCODE = CUS.CUSCODE AND SUMM.ENTYEAR = DET.ENTYEAR AND summ.deleted='N' and SUMM.ENTNUMB = DET.ENTNUMB AND 
																			SUMM.BRNCODE = '".$_SESSION['tlu_brncode']."' AND CUS.CUSMBLE = '".$_SESSION['tlu_user_mobile']."' and SUMM.CASPAID = 'Y' 
																			and SUMM.ITMISSU = 'Y'
																		group by SUMM.ENTDATE, SUMM.DUEDATE, SUMM.ENTAMNT, SUMM.CASPAID, SUMM.ITMISSU, summ.isudate, CUS.CUSNAME, CUS.CUSMBLE, SUMM.ENTNUMB,
																			SUMM.ENTYEAR
																		Order by SUMM.ENTYEAR desc, SUMM.ENTNUMB desc", $_SESSION['tlu_brncode'], "TCS");
						if(count($sql_order_history) > 0) { ?>
						<thead>
							<tr style="text-align: center;">
							  <th>Sr. No</th>
							  <th>Order No</th>
							  <th>Entry Date</th>
							  <th>Amount &#8377</th>
							  <th>Delivery Date</th>
							  <th>Issue Date</th>
							  <th>Status</th>
							  <th>Rating</th>
							</tr>
						</thead>
						<tbody>
							<? 	$ohi = 0;
								foreach ($sql_order_history as $co_key => $order_history) { $ohi++; ?>
								<tr style="text-align: center;">
								  <td><?=$ohi?></td>
								  <td><?=$order_history['ORDER_NO']?></td>
								  <td><?=$order_history['ENTDATE']?></td>
								  <td><span class="badge bg-success" style="font-size: 100% !important;"><?=$order_history['ENTAMNT']?></span></td>
								  <td><?=$order_history['DUEDATE']?></td>
								  <td><?=$order_history['ISUDATE']?></td>
								  <td><? if($order_history['CASPAID'] == 'Y') { ?> <span class="badge bg-success">Cash Paid</span> <? } elseif($order_history['CASPAID'] == 'P') { ?> <span class="badge bg-warning">Cash Partially Paid</span> <? } else { ?> <span class="badge bg-danger">Cash Not Paid</span> <? } ?>&nbsp;/&nbsp;<? if($order_history['ITMISSU'] == 'Y') { ?> <span class="badge bg-success">Item Issued</span> <? } else { ?> <span class="badge bg-danger">Item Not Issued</span> <? } ?></td>

								  <td>
								  		
								  </td>
								</tr>
							<? } ?>
						</tbody>
						<? } else { ?>
							<tr style="text-align: center;">
							  <td colspan="7">No Records Found..</td>
							</tr>
						<? } ?>
					  </table>
				  </div>
                  <!-- order history -->

                  <!-- customer profile -->
                  <div class="tab-pane" id="customer_profile" style="min-height: 500px;">

                  	<form role="form" id='frm_customer_profile' name='frm_customer_profile' action='' method='post' enctype="multipart/form-data">
                        <div class="row form-group trbg">
	                        <div class="col-md-2" style='text-align:right; padding:5px; float: left; line-height: 35px;'>Your Name : </div>
	                        <div class="col-md-3" style='text-align:center; padding:5px 5px 0 5px; float: left;'>
	                            <input type='text' class="form-control" tabindex='1' name='tlu_txt_yourname' id='tlu_txt_yourname' value='<?=$_SESSION['tlu_cusname']?>' maxlength="35" data-toggle="tooltip" data-placement="top" placeholder='Your Name' title="Your Name" onblur="store_profile();" style='text-transform: uppercase;'>
	                        </div>
	                        <div class="col-md-1" style='text-align:right; padding:5px; float: left; border-right: 1px solid #a0a0a0;'>&nbsp;</div>

	                        <div class="col-md-2" style='text-align:right; padding:5px; float: left; line-height: 35px;'>Your Email : </div>
	                        <div class="col-md-3" style='text-align:center; padding:5px 5px 0 5px; float: left;'>
	                            <input type='text' class="form-control" tabindex='2' name='tlu_txt_youremail' id='tlu_txt_youremail' value='<?=$sql_customer[0]['CUSEMAL']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Your Email' title="Your Email" onblur="store_profile();" style='text-transform: uppercase;'>
	                        </div>
	                        <div class="col-md-1" style='text-align:right; padding:5px; float: left;'>&nbsp;</div>
                    	</div>

                    	<div class="row form-group trbg">
	                        <div class="col-md-2" style='text-align:right; padding:5px; float: left; line-height: 35px;'>Primary Mobile : </div>
	                        <div class="col-md-3" style='text-align:left; padding:5px 5px 0 5px; float: left; line-height: 35px; font-weight: bold;'><?=$_SESSION['tlu_user_mobile']?></div>
	                        <div class="col-md-1" style='text-align:right; padding:5px; float: left; border-right: 1px solid #a0a0a0;'>&nbsp;</div>

	                        <div class="col-md-2" style='text-align:right; padding:5px; float: left; line-height: 35px;'>Secondary Mobile : </div>
	                        <div class="col-md-3" style='text-align:center; padding:5px 5px 0 5px; float: left;'>
	                            <input type='text' class="form-control" tabindex='2' name='tlu_txt_second_mobile' id='tlu_txt_second_mobile' value='<?=$sql_customer[0]['SECMOBL']?>' maxlength="10" data-toggle="tooltip" data-placement="top" placeholder='Your Secondary Mobile' onblur="store_profile();" title="Your Secondary Mobile" style='text-transform: uppercase;'>
	                        </div>
	                        <div class="col-md-1" style='text-align:right; padding:5px; float: left;'>&nbsp;</div>
                    	</div>
                    </form>

                    <form id="signupForm" method="post" class="form-horizontal" action="" novalidate style="display: none;">
                    	<div class="col-md-4">&nbsp;</div>
                    	<div class="col-md-4">
							<div class="form-group col-md-12">
								<label class="col-md-4 control-label" for="password">New Password</label>
								<div class="col-md-5">
									<input type="password" class="form-control" id="tlu_change_password" name="tlu_change_password" placeholder="Password" ng-model="newpass" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" for="confirm_password">Confirm password</label>
								<div class="col-md-5">
									<input type="password" class="form-control" id="tlu_change_confirm_password" name="tlu_change_confirm_password" placeholder="Confirm password" ng-model="conpass" />
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-9">
									<button type="submit" class="btn btn-success" name="signup" value="Sign up"> Change Password </button>
								</div>
							</div>
						</div>
						<div class="col-md-4">&nbsp;</div>
					</form>

				  </div>
                  <!-- customer profile -->

                  <!-- customer measurement -->
                  <div class="tab-pane" id="customer_measurement" style="min-height: 500px;">
					Welcome to Customer Measurement
				  </div>
                  <!-- customer measurement -->

                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>
<!-- Bootstrap 4 -->
<script src="js/bootstrap.bundle.js"></script>
<!-- DataTables -->
<script src="js/jquery.dataTables.js"></script>
<script src="js/dataTables.bootstrap4.js"></script>
<!-- SlimScroll -->
<script src="js/jquery.slimscroll.js"></script>
<!-- FastClick -->
<script src="js/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="css/bootstrap-imageupload.js"></script>
<script>
	var $imageupload = $('.imageupload');
	$imageupload.imageupload();

	$(document).ready(function(){
		$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
			localStorage.setItem('activeTab', $(e.target).attr('href'));
		});
		var activeTab = localStorage.getItem('activeTab');
		if(activeTab){
			
			$("#id_current_orders").removeClass("active");
			$("#id_order_history").removeClass("active");
			$("#id_customer_profile").removeClass("active");

			$('#id_'+activeTab).addClass("active");
			$('#myTab a[href="' + activeTab + '"]').tab('show');
		}
	});

	function setCookie(name, value, days) {
	    var expires = "";
	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime() + (days*24*60*60*1000));
	        expires = "; expires=" + date.toUTCString();
	    }
	    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
	}

	function getCookie(name) {
	    var nameEQ = name + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1,c.length);
	        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	    }
	    return null;
	}

	function eraseCookie(name) {   
	    document.cookie = name+'=; Max-Age=-99999999;';  
	}


	$(function () {
	    $("#example1").DataTable();
	    $("#example2").DataTable();
	    $('#example3').DataTable({
	      "paging": true,
	      "lengthChange": false,
	      "searching": false,
	      "ordering": true,
	      "info": true,
	      "autoWidth": false
	    });
	});

	// validate the sub product textbox
	function store_profile() {
	    var yourname = $("#tlu_txt_yourname").val();
	    var second_mobile = $("#tlu_txt_second_mobile").val();
	    var youremail = $("#tlu_txt_youremail").val();
	    var strURL="ajax/ajax_store_profile.php?action=store_profile&yourname="+yourname+"&second_mobile="+second_mobile+"&youremail="+youremail;
	    $.ajax({
	        type: "POST",
	        url: strURL,
	        success: function(data1) {
	            if(data1 == 0) {
	                var ALERT_TITLE = "Message";
	                var ALERTMSG = "Updation Failure. Kindly try again!!";
	                // createCustomAlert(ALERTMSG, ALERT_TITLE);
	                alert(ALERTMSG);
	            } else {
	            	$('#customer_profile_name').html(data1);
	            }
	        }
	    });
	}
	// validate the sub product textbox

	$.validator.setDefaults({
	  submitHandler: function () {
		// alert( "Submitted!" );
		var tlu_change_password = $("#tlu_change_password").val();
	    var strURL="ajax/ajax_store_profile.php?action=store_change_password&change_password="+tlu_change_password;
	    $.ajax({
	        type: "POST",
	        url: strURL,
	        success: function(data1) {
	        	// alert("++++++"+data1+"+++++++++");
	            if(data1 == 0) {
	                var ALERT_TITLE = "Message";
	                var ALERTMSG = "Updation Failure. Kindly try again!!";
	                // createCustomAlert(ALERTMSG, ALERT_TITLE);
	                alert(ALERTMSG);
	            } else {
	            	window.location="home.php";
	            }
	        }
	    });
	  }
	});

	$( document ).ready( function () {
	  $( "#signupForm" ).validate({
		rules: {

		  tlu_change_password: {
			required: true,
			minlength: 6
		  },
		  tlu_change_confirm_password: {
			required: true,
			minlength: 6,
			equalTo: "#tlu_change_password"
		  },
		  //
		  //agree: "required"
		},
		messages: {
		  // firstname: "Please enter your firstname",
		  // lastname: "Please enter your lastname",
		  // username: {
		  //   required: "Please enter a username",
		  //   minlength: "Your username must consist of at least 2 characters"
		  // },
		  tlu_change_password: {
			required: "Please provide a password",
			minlength: "Your password must be at least 6 characters long"
		  },
		  tlu_change_confirm_password: {
			required: "Please provide a password",
			minlength: "Your password must be at least 6 characters long",
			equalTo: "Please enter the same password as above"
		  },
		  // email: "Please enter a valid email address",
		  // agree: "Please accept our policy"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
		  // Add the `help-block` class to the error element
		  error.addClass( "help-block" );

		  //if ( element.prop( "type" ) === "checkbox" ) {
			error.insertAfter( element.parent( "label" ) );
		  //} else {
			error.insertAfter( element );
		 // }
		},
		highlight: function ( element, errorClass, validClass ) {
		  $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
		  $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		}
	  });
	});
</script>
</body>
</html>
