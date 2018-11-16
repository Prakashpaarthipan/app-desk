<?php
header('Cache-Control: no cache'); //no cache // This is for avoid failure in submit form  pagination form details page
session_cache_limiter('private_no_expire, must-revalidate'); // works // This is for avoid failure in submit form  pagination form details page

try {
error_reporting(E_ALL);
include('lib/config.php');
include("db_connect/public_functions.php");
include('lib/pagination.class.php');
extract($_REQUEST);
if($_SESSION['tcs_userid'] == '') { ?>
	<script>window.location='logout.php?msg=session';</script>
<?php
exit();
}
$menu_name = 'SUPPLIER SERVICE REQUEST';
$inner_submenu = select_query("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc");
if($_SESSION['tcs_empsrno'] != '') {
	$inner_menuaccess = select_query("select * from srm_menu_access where MNUCODE = ".$inner_submenu[0][0]." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' order by MNUCODE Asc");
} else {
	$inner_menuaccess = select_query("select * from srm_menu_access where MNUCODE = ".$inner_submenu[0][0]." and SUPCODE = '".$_SESSION['tcs_userid']."' order by MNUCODE Asc");
}
if($inner_menuaccess[0][6] == 'N' or $inner_menuaccess[0][6] == '') { ?>
<script>alert("You dont have access to view this"); window.location='home.php';</script>
<?
 exit();
}

?>
<!DOCTYPE html>
<html>
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Service Request ::  <?php echo $site_title; ?> </title>
	 <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="dist/newlte/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="dist/newlte/bower_components/Ionicons/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="dist/newlte/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <!-- <link rel="stylesheet" href="dist/newlte/bower_components/select2/dist/css/select2.min.css"> -->
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="dist/newlte/iCheck/all.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/newlte/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link href="dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css" />
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- Select2 -->
	<link href="bootstrap/css/select2.css" rel="stylesheet"/>
	
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
	</style>
	
	 
  </head>
<body oncopy="return false" oncut="return false" onpaste="return false"	ondragstart="return false" onselectstart="return false" oncontextmenu="return false" class="skin-black side sidebar-collapse">
   <div id='pageloader' class="loader"></div>
      <? include("includes/header.php"); ?>
      <? include("includes/left_panel.php"); ?>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="col-md-12">
			<div class="col-md-3"></div>
			<div class="col-md-6">
		<section class="content-header">
		  <h1>
			SERVICE REQUEST
			<?/* <small>Supplier Design Entry</small> */?>
		  </h1>
		  <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Purchase</a></li>
			<li class="active">TCS Service Register</li>
		  </ol>
		</section>

		<!-- Main content -->
		<section class="content">
		
      <div class="box box-primary" style="max-height: 744px;">
        <div class="box-header with-border">
          <h3 class="box-title">Request Entry</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <form name="entryForm" id="entryForm" method="post" action="" class='my-form' enctype="multipart/form-data">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
             <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li  onclick='view_tab(1);' class="active" id="tab1"><a href="#tab_1" data-toggle="tab">Entry</a></li>
              <!-- <li onclick='view_tab(2);' id="tab2"><a href="#tab_2" data-toggle="tab">Report</a></li> -->
              <!--<li class="pull-right"><a href="#" class="text-muted" data-toggle="modal" data-target="#modal-default"><i class="fa fa-info"></i></a></li>-->
              <input type="hidden" name="reopen" id="reopen" value="">
              <input type="hidden" name="reqnumb" id="reqnumb" value="">
              <input type="hidden" name="reqsrno" id="reqsrno" value="">
            </ul>
            <div class="tab-content col-md-12" style="max-height: 650px;">
              <div class="tab-pane active" id="tab_1">
				<?php
				//if($_SESSION['tcs_empsrno'] == ""){
				$client = new SoapClient("http://mobile.thechennaisilks.com/TCSservice.asmx?Wsdl");
					$code = 1402;
					$type = "E";
					$get_parameter->CODE=$code;  // empsrno/supcode
					$get_parameter->TYPE=$type;  //User Mode(sup/emp)
					try{
						$get_result=$client->RR_GETSUPPLIER($get_parameter)->RR_GETSUPPLIERResult;
						}
					catch(SoapFault $fault){
							echo "Fault code:{$fault->faultcode}".NEWLINE;
							echo "Fault string:{$fault->faultstring}".NEWLINE;
							if ($client != null)
							{
								$client=null;
							}
							exit();
					}
					$soapClient = null;
					$supplier = json_decode($get_result,true);
				
				?>
				<div id="reopen_div" style="display: block;">
				<div class="form-group col-md-12">
                <label> Supplier : <span class="colr_red">*</span> </label>
				<div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </div>
                  <select class="form-control" data-placeholder="Select a Supplier" name="supplier" id="supplier" onchange="get_sup_details(this.value);" required style="width: 100%;">
				<?
					foreach ($supplier as $key => $supp) { ?>
						<option value="">Select Supplier</option>
						<option value="<?=$supp['SUPCODE']?>"><?=$supp['SUPCODE']?> - <?=$supp['SUPNAME']?></option>		
				<?	}
				?>	
				</select>
                </div>
                <!-- /.input group -->
				</div>
				<? //} ?>

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
                 <input type="text" class="form-control" placeholder="Enter Desk Telephone No(optional)" name="desk_no" id="desk_no" maxlength="12" onkeypress="return isTeleNumber(event)"; <?if($_SESSION['tcs_empsrno'] !=""){?> required <?}?> >
				</div>
                <!-- /.input group -->
				</div>

				</div>

				<div class="form-group col-md-12">
                <label> Request Type : <span class="colr_red">*</span> </label>
				<div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-address-card"></i>
                  </div>
               
                <select class="form-control select2" data-placeholder="Select a Complaint Type" name="comp_name" id="comp_name" required style="width: 100%;">
					<option value=""> Choose Any Complaint </option>
					<? $complaintMaster = select_query("Select comcode,comname from APP_COMPLAINT_MASTER where deleted='N' order by COMCODE ASC");
						foreach($complaintMaster as $res) { ?>
							<option value="<?=$res['COMCODE']?>"><?=$res['COMNAME']?></option>
					<? } ?>
				</select>
                
				</div>
                <!-- /.input group -->
				</div>
                </div>
				<div class="form-group col-md-12">
                <label> Message : <span class="colr_red">*&nbsp;(Note:Maximum 250 Characters Allowed)</span></label>
				<div class="input-group">
                  <div class="input-group-addon" id="div_sec">
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
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
				<div id="chat_content" class="col-md-12">
                	
                </div>
				</div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
			</div>
              <!-- /.nav tab-group -->
            </div>
            <!-- /.col 12 -->
          </div>
          <!-- /.row -->
        </div>
    </form>
        <!-- /.box-body -->
        <!--<div class="box-footer" style="font-size: initial;">
          <code style="background-color: #f0c6d2;">Before Clicking Submit, Enter The Message fill details upto duedays</code>
        </div> -->
      </div>
      <!-- /.box --> 



	<!-- /.row -->
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
        <!-- /.modal -->
		</section>
		</div>
	<div class="col-md-3"></div>
		<!-- /.content -->
		</div>
	</div>
	
		<!-- /.content-wrapper -->
	
	

	<? include("includes/footer.php"); ?>
    <!-- jQuery 2.1.3 -->
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
	<script src="dist/newlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap datepicker -->
	<script src="dist/newlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<!-- FastClick -->
	<script src="dist/newlte/bower_components/fastclick/lib/fastclick.js"></script>
	<!-- Select2 -->
	<!-- <script src="dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script> -->
    <!-- AdminLTE App -->
    <script src="dist/newlte/app.min.js" type="text/javascript"></script>
	<!-- iCheck 1.0.1 -->
	<script src="dist/newlte/iCheck/icheck.min.js"></script>
	
	<script type="text/javascript" src="bootstrap/js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="bootstrap/js/core.js"></script>
	
	<script src="bootstrap/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/fresco.js"></script>
	<script src="bootstrap/js/drag.js"> </script>
	<script src="bootstrap/js/click.js"> </script>

	<script src="bootstrap/js/select2.js"></script>
	<script  type="text/javascript">
	$(window).load(function() {
		$(".loader").fadeOut("slow");
		$('#comp_name').select2();
   		$('#supplier').select2();
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
			url:"ajax_complaint.php?mode=chat",
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
		$.ajax({
		url:"ajax_complaint.php?mode=supp_detail&supcode="+supp,
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
		$('.loader').show();
		$.ajax({
		url:"ajax_complaint.php?mode=SAVE",
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
<? 
} 
catch(Exception $e) {
	echo 'Unknown Error. Try again.';
}
?>
