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
<?php 
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title>Employee Head Fix :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->
    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->
     <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/jquery-customselect.css" rel="stylesheet" />
    <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
    <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
    <!-- multiple file upload -->
    <link href="css/jquery.filer.css" rel="stylesheet">
    
<style>
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}

.col-lg-12 {
        padding-right:0px;
        }
    .panel-body {
        padding: 10px !important;
        margin: 4px 4px 12px 0;

    }
    
    .table {
        margin-bottom: 0px;
        }
    #page-wrapper { 
    position: relative;
    float: right;
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

    .dataTables_length {
            width:40%;
            float:left;
            padding-right: 5px;
            padding: 0px 0px 5px;
            border-bottom: none !important;
            font-size: 15px;
        }

    .dataTables_filter {
        width: 50%;
        float: right;
        padding-left: 5px;
        padding: 0px 0px 5px;
        border-bottom: none !important;
        font-size: 15px;
    }
</style>
</head>
<body>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
              $('#dataTables-example').dataTable();
        });
    function nsubmit(action){
        alert("uable to call");
            var vurl = "task_details.php";
        //var vurl="viki/post_test.php";

            $.ajax({
            type: "POST",
            url: vurl,
            data:{
                txt_task_details:$("#txt_task_details").val(),
                txt_task_from_time:$("#txt_task_from_time").val(),
                txt_task_to_time:$("#txt_task_to_time").val(),
                tassrno: $('#tassrno').val(),
                sbmt_update:action
                //'txt_employee_grade':$("#txt_employee_grade").val(),
            },
                
            dataType:'html',
            success: function(data1) {
               alert(data1);
            },
            error: function(response, status, error)
            {       alert(error);
                    //alert(response);
                    //alert(status);
            }
            });
    }

    function update_detail(){
        var vurl = "task_details.php?sbmt_update=update";
    
        $.ajax({
             type: "POST",
             url: vurl,
            data:{

                'txt_task_details':$("#txt_task_details").val(),
                'txt_task_from_time':$("#txt_task_from_time").val(),
                'txt_task_to_time':$("#txt_task_to_time").val(),
            },
            dataType:'html',
            success: function(data1) {
              alert("updated successfully");
            },
            error: function(response, status, error)
            {       
                alert(error);
            }
        });
    }   
</script>

    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-body" id="modal-body1"></div>
            </div>
        </div>
    </div>

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
                <li class="active">Employee Head Fix</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <? if( $_REQUEST['action'] == 'nedit' )  {
                $sql_reqid = select_query_json("select taskdet,enttime,outtime,empsrno from task_grade where tassrno = '".$_REQUEST['reqid']."'","Centra","TEST");
            } ?>
            <form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" method="post" enctype="multipart/form-data">
            <!--<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data">    -->
            <div class="page-content-wrap">
            <input type="hidden" id="tassrno" val=""/>

            <div class="row">
                <div class="col-md-12">

            <form class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Employee Head Fix</strong></h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>

                <!-- <form role="form" id='employee_grade' name='employee_grade' action='' method='post' enctype="multipart/form-data"> -->

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="col-md-6" style="text-align: right; padding-right: 30px;">EMPLOYEE HEAD<span style='color:red'> *</span> : </div>
                                <div class="col-md-6">
                                    <input type="text" name="txt_employee_head[]" id="txt_employee_head" placeholder="ENTER THE EMPLOYEE HEAD CODE" title="select the employee here" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" required maxlength="5" tabindex="5" onchange="javascript:return removeduperun(this);">
                                </div>
								
								
                            </div>
							
                            <!-- tilte text feild -->
                        <div class="tags_clear"></div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-6" style="text-align: right; padding-right: 30px;">TEAM EMPLOYEES<span style='color:red'> *</span> : </div>
                                <div class="col-md-6">
                                    <div id="add_emp" class="form-group">
                                        <input type="hidden" name="partint3" id="partint" value="1">
                                        <div class="form-group input-group" id="dynamicRemove" >

                                          <input type="text" name="txt_employee_code[]" id="txt_employee_code1" placeholder="ENTER THE EMPLOYEE CODE" title="select the employee here" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;"  required maxlength="5" tabindex="5" onchange="javascript:return removeduperun(this);">
                                          <span class="input-group-btn"><button id="add_emp_button" type="button" onclick="subject_addnew()" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More">+</button>
                                          </span>

                                        </div>
										<div class="showlist" name="list_emp" id="list_emp"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- tilte text feild -->
                        <div class="tags_clear"></div>
                    </div>
                </div>
            </div>
        <!-- </form> -->
            

            <div class="form-group trbg" style='min-height:40px; padding-top:10px'>
               <div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
                <input type='hidden' name='hid_reqid' id='hid_reqid' value='<?=$_REQUEST['reqid']?>'>
                <input type="button" id="update_btn" class="btn btn-success" name="update_btn" onclick="employee_submit()" style="display: none;" value="update">&nbsp;
                <input type="button" id="submit_btn" class="btn btn-success" name="submit_btn" value="SUBMIT" onclick="employee_submit();">&nbsp;
				
                <button  tabindex='3' class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Reset">RESET</button>
            </form><br><br>
                                
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Employee Head List</h3></div>
                         <div class="panel-body">
                        <table id="customers" class="table datatable" border="1">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">EMPLOYEE HEAD</th>
                                    <th style="text-align: center;">TEAM EMPLOYEES</th>
                                    <th style="text-align: center;">ACTION</th>
                                </tr>
                            </thead>
                           <tbody>
                            <? /* $join = select_query_json("select ef.EMPCODE, ef.EMPCODE||' - '||ef.empname head_user, ef.EMPSRNO, (select empcode||' - '||empname 
                                                                    from employee_office where empsrno = HD.empsrno) empname
                                                                from EMPLOYEE_HEAD_USER HD, employee_office ef 
                                                                where HD.emphdsr = ef.empsrno and deleted = 'N'
                                                                order by EMPCODE, empname", "Centra", "TEST"); */
                            $join = select_query_json("select distinct hd.emphdsr, ef.EMPCODE, ef.EMPCODE||' - '||ef.empname head_user, ef.EMPSRNO
                                                                from EMPLOYEE_HEAD_USER HD, employee_office ef 
                                                                where HD.emphdsr = ef.empsrno and deleted = 'N'
                                                                order by EMPCODE, head_user", "Centra", "TEST");
                            $search_ii = 0;
                            for($search_i = 0; $search_i < count($join); $search_i++) { $search_ii++; ?>
                                <tr class="even gradeX">
                                    <td class="center" style="text-align: center;"><?=$search_ii?></td>
                                    <td class="center" style="text-align: left;" <?=$clspan?>><?=$join[$search_i]['HEAD_USER']?></td>
                                    <td class="center" style="text-align: left;">
                                        <?  $sql_emplist = select_query_json("select distinct hd.emphdsr, ef.EMPCODE, ef.EMPCODE||' - '||ef.empname EMPLOYEE, ef.EMPSRNO
                                                                                from EMPLOYEE_HEAD_USER HD, employee_office ef 
                                                                                where HD.empsrno = ef.empsrno and deleted = 'N' and hd.emphdsr = ".$join[$search_i]['EMPHDSR']."
                                                                                order by EMPCODE, EMPLOYEE", "Centra", "TEST");
                                            foreach ($sql_emplist as $key => $emplist_value) {
                                                echo $emplist_value['EMPLOYEE']."<br>";
                                            }
                                        ?>
                                    </td>
                                    <td class="center" style="text-align: center;"><a href='javascript:void(0)' title='Edit' alt='Edit' onClick= "load_teamEmployee('<?=$join[$search_i]['EMPHDSR']?>')"><i class="fa fa-edit"></i> Edit</a> / <a href='employee_add.php?action=view&reqid=<? echo $join[$search_i]['EMLSRNO']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a> / <a href='javascript:void(0)' id="delete_confirm" onclick="call_confirm(<?=($emailmaster_i+1)?>, 'employee_add.php?action=deleted&reqid=<? echo $join[$search_i]['EMLSRNO']; ?>')" title='Delete' alt='Delete'><i class="fa fa-trash-o"></i> Delete</a></td>
                                </tr>
                            <? } ?> 
                           </tbody>
                        </table>
                    </div>
                </div>
            </div><br><br>
                    
                                       
    <? include "lib/app_footer.php"; ?>

    
    
    
    
     <div class='clear'></div>


    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>

    <!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <!-- <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script> -->
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

    <!-- Custom Scripts - Arun Rama Balan.G -->
    <script src="ajax/ajax_staff_change.js"></script>
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <? /* <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script> */ ?>
    <script src="js/monthpicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <!-- Select2 -->
    <script src="../dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
   
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
	
	//Enable Edit option
	function load_teamEmployee(headsrno){
		$("#dynamicRemove").css("display","none");
		$("#list_emp").html("");
		$.ajax({
                  url:"ajax/fix_grade_multi.php?action=edit_employee&emphead="+headsrno,
                  type: "POST",
                  
                  processData: false,
                  contentType: false,
                  async:true,
				success:function(data){
					$("#list_emp").append(data);
					$("#submit_btn").css("display","none");
					$.ajax({
							  url:"ajax/fix_grade_multi.php?action=load_head&emphead="+headsrno,
							  type: "POST",
							  
							  processData: false,
							  contentType: false,
							  async:true,
							success:function(data){
								$("#txt_employee_head").val(data);
								$("#txt_employee_head").attr("readonly","readonly");
								$("#txt_employee_head").addClass("newEmp");
								//$("#submit_btn").css("display","none");
								
								
							},
							error:function(error){
								console.log("Error");
							}
					});
					
				},
				error:function(error){
					console.log("Error");
				}
        });
		
	}
	
	function teamremove(head1,srno)
	{
								
				
				
				var headid = head1.split(" - ");
				var teamcode = headid[0];
				//alert(headcode);
				if(confirm("Are you sure want to remove :("+head1+") from list?"))
				{
				var p_id = 0;
				$.ajax({
					type:"post",
					url:"ajax/fix_grade_multi.php?action=remove_employee&empid="+teamcode+"&headid="+srno,
					
					dataType: 'text',
					success: function(data, textStatus, jqXHR){
						
						
						window.location.reload(true);
						
						},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('An error occurred... Try again!');
						window.location.reload(true);
						
						}
				});	
				}
				else{
				window.location.reload(true);
				}
	}
	
	
	
	
        function employee_submit(){
            var form_data = new FormData(document.getElementById("frm_requirement_entry"));
            $.ajax({
                  url:"ajax/fix_grade.php?action=add_employee",
                  type: "POST",
                  data: form_data,
                  processData: false,
                  contentType: false,
                  async:true,
            }).done(function(data)
            {
                  // console.log(data);
                  $("#load_page").fadeOut("slow");                  
            });  
        }
    function nedit(tassrno,taskdet,endtim,outtim)
    { 
        $('#submit_btn').hide();
        $('#update_btn').show();
        console.log(taskdet+" "+endtim+" "+outtim);
        $('#txt_task_details').val(taskdet);
        $('#tassrno').val(tassrno);
        $('#txt_task_from_time').val(endtim);
        $('#txt_task_to_time').val(outtim);
    }

     $('#txt_employee_head').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url : 'ajax/ajax_employee_details.php',
            dataType: "json",
            data: {
               slt_emp: request.term,
               brncode: 888,
               action: 'allemp'
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
        change: function(event,ui)
        { 
            if (ui.item == null) 
            {
                $("#txt_employee_head").val('');
                $("#txt_employee_head").focus(); 
            } 
        },
        autoFocus: true,
        minLength: 0
      });

     $('#txt_employee_code1').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url : 'ajax/ajax_employee_details.php',
            dataType: "json",
            data: {
               slt_emp: request.term,
               brncode: 888,
               action: 'allemp'
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
        change: function(event,ui)
            { if (ui.item == null) 
            {
                $("#txt_employee_code1").val('');
                $("#txt_employee_code1").focus(); 
            } 
            },
        autoFocus: true,
        minLength: 0
    });
    
    function subject_addnew() {

        $('[data-toggle="tooltip"]').tooltip();
        //var id1 = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        var value = $('#partint').val();
        var id = (parseInt(value) + 1).toString();
        $('#partint').val(id);
        $('#add_emp').append(
          '<div class="form-group input-group">'+
            '<input type="text" name="txt_employee_code[]" id="txt_employee_code'+id+'" required maxlength="5" placeholder="ENTER THE EMPLOYEE CODE" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" onchange="javascript:return removeduperun(this);">'+'<span class="input-group-btn"><button id="add_emp_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip" title ="remove">-</button></span>'+
          '</div>');
               $('#txt_employee_code'+id).autocomplete({
               source: function( request, response ) {
                 $.ajax({
                   url : 'ajax/ajax_employee_details.php',
                   dataType: "json",
                   data: {
                      slt_emp: request.term,
                      brncode: 888,
                      action: 'allemp'
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
               change: function(event,ui)
                { if (ui.item == null) 
                {
                    $("#txt_employee_code"+id).val('');
                    $("#txt_employee_code"+id).focus(); 
                } 
                },
               autoFocus: true,
               minLength: 0
             });
          $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });
    }


    function removeduperun(t) {
        var values = $('input[name="txt_employee_code[]"]').map(function() {
            return this.value;
        }).toArray();
        values = values.filter(function(e){return e}); 
        var hasDups = !values.every(function(v,i) {
            return values.indexOf(v) == i;
        });
        if(hasDups){
             // having duplicate values
             alert("Please do not repeat the same employee");
            t.value = '';
        }
    }
	
	function addNew(head2,srno){
		var newteam = head2.value;
		var headid = newteam.split(" - ");
		var teamcode = headid[0];
		$.ajax({
					type:"post",
					url:"ajax/fix_grade_multi.php?action=addNew_employee&empid="+teamcode+"&headid="+srno,
					
					dataType: 'html',
					success: function(data, textStatus, jqXHR){
						
							alert("Added successfully");
						
						window.location.reload(true);
						
						},
					error: function(jqXHR, textStatus, errorThrown) {
						//alert('An error occurred... Try again!');
						window.location.reload(true);
						
						}
				});	
		
	}
	function removeduperunnew(t) {
        var values = $('.newEmp').map(function() {
            return this.value;
        }).toArray();
        values = values.filter(function(e){return e}); 
        var hasDups = !values.every(function(v,i) {
            return values.indexOf(v) == i;
        });
        if(hasDups){
             // having duplicate values
             alert("Please do not repeat the same employee");
            t.value = '';
        }
    }

        $(document).ready(function() {
            $('#customers').dataTable();

            $('#customers2').dataTable({
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "order": [[ 0, 'asc' ]],
                "language": {
                    "zeroRecords": "No results available"
                },
                "displayLength": 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="10">'+group+'</td></tr>' // 05052017
                            );

                            last = group;
                        }
                    } );

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                            );

                            last = group;
                        }
                    } );
                }
            });

            // Order by the grouping
            $('#customers2 tbody').on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
                    table.order( [ 0, 'desc' ] ).draw();
                }
                else {
                    table.order( [ 0, 'asc' ] ).draw();
                }
            });
        });

    function cmnt_mail(aprnumb)
    {
        var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
        //var sendurl="viki/post_test.php";
        $.ajax({
        url:sendurl,
        success:function(data){
            $("#myModal2").modal('show');
            $('#modal-body2').html(data);
            $('#txtmailcnt').val("");
            }
        });
    }

    $(document).ready(function() {
        $('#customers2').dataTable({
            "columnDefs": [
                { "visible": false, "targets": 0 }
            ],
            "order": [[ 0, 'asc' ]],
            "language": {
                "zeroRecords": "No results available"
            },
            "displayLength": 25,
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="10">'+group+'</td></tr>' // 05052017
                        );

                        last = group;
                    }
                } );

                api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                        );

                        last = group;
                    }
                } );
            }
        });

        // Order by the grouping
        $('#customers2 tbody').on( 'click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
                table.order( [ 0, 'desc' ] ).draw();
            }
            else {
                table.order( [ 0, 'asc' ] ).draw();
            }
        });
    });
    </script>
<!-- END SCRIPTS -->
</body>
</html>

    <!-- Light Box - New -->
    <!-- Custom Scripts - Arun Rama Balan.G -->
<!-- END SCRIPTS -->
</body>
</html>