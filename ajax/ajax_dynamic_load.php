<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}
if($_REQUEST['rsrid'] == '') {
	$rqsrno = 1;
} else {
	$rqsrno = $_REQUEST['rsrid'];
}

$sql_approval = select_query_json("select * from approval_request 
											where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ATCCODE = '".$_REQUEST['creid']."' 
												and ATYCODE = '".$_REQUEST['typeid']."' and arqsrno=".$rqsrno." and deleted='N'", "Centra", 'TEST');

$apmcode = $sql_approval[0]['APMCODE'];
$apprno  = $sql_approval[0]['APRNUMB'];
if($apmcode == 669 or $apmcode == 6 or $apmcode == 829 or $apmcode == 668 or $apmcode == 725 or $apmcode == 712 or $apmcode ==710 or $apmcode == 711 or $apmcode == 1274 or $apmcode == 1275 or $apmcode == 1275 or $apmcode == 1276 or $apmcode == 1280 or $apmcode == 1281 or $apmcode == 829 or $apmcode == 843 ){
	$apmcode =6;
}

if($apmcode == 8 or $apmcode == 671 or $apmcode == 715 or $apmcode == 714 or $apmcode == 670 or $apmcode == 716 or $apmcode == 958 or $apmcode == 960){
	$apmcode =8;
}
//JEWELLERY - STAFF BRANCH TRANSFER
if($apmcode == 842){ $apmcode=623; }
//JEWELLERY - STAFF DESIGNATION CHANGE
if($apmcode == 843){ $apmcode=6; }
//JEWELLERY - STAFF BASIC INCREMENT
if($apmcode == 844){ $apmcode=5; }

switch ($apmcode) {
	case 909:
	case 1368: ?>
		<!-- staff night duty Start-->
		<? $sql_night = select_query_json("select * from approval_night_duty 
													where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_approval[0]['APRNUMB']."%' 
													order by entsrno", "Centra", 'TEST');
		if(count($sql_night)>0){?>
		<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
		<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
			 &nbsp;#
		</div>
		<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
        <div class="col-sm-2 colheight"  style="padding: 0px;">PHOTO</div>
        <div class="col-sm-2 colheight"  style="padding: 0px;overflow-wrap: break-word;">DEPARTMENT</div>
		<div class="col-sm-2 colheight"  style="padding: 0px;overflow-wrap: break-word;">DESIGNATION</div>
		<div class="col-sm-2 colheight"  style="padding: 0px;">NATURE OF WORK </div>
		<div class="col-sm-1 colheight"  style="padding: 0px;">WORKING HOURS</div>
        </div>
        <? $g=0;
        foreach ($sql_night as $gift) {$g++;?>
     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
			<div class="col-sm-1 colheight" style="padding: 1px 0px;">
				<div class="fg-line">&nbsp;<?=$g?></div>
			</div>

			<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
				<div>
					<input type="text" name="empname[]" id="txt_staffcode_1" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top"  style=" text-transform: uppercase; padding: 2px; height: 25px;" readonly="readonly" value="<?=$gift['EMPCODE']."-".$gift['EMPNAME']?>">
				</div>
				<div style="clear: both;"></div>
			</div>

			<div class="col-sm-2 colheight" style="padding: 1px 0px;">
				<div id="photo_1">
					<? if($gift['EMPCODE'] != 0){?>
				<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$gift['EMPCODE']?>"  title="<? echo $gift['EMPNAME']; ?>"> 	
				<?}?>
				</div>	
			</div>
			<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
    			 <input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;"  value="<?=$gift['DESNAME']?>">
    		</div>
    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
    			 <input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" value="<?=$gift['ESENAME']?>">
    		</div>
    		 
    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
    			  <input type="text"  name="CURWORK[]" id="curwork_1" placeholder="NATURE OF WORK" data-toggle="tooltip" data-placement="top" title="NATURE OF WORK" class="form-control" readonly="readonly"   style=" text-transform: uppercase;height: 25px;"  value="<?=$gift['WRKDESC']?>" >
    		</div>
    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
    			  <input type="text"  name="TOTWORK[]" id="totwork_1" placeholder="WORKING HOURS" data-toggle="tooltip" data-placement="top" title="WORKING HOURS" class="form-control"  readonly="readonly"  style=" text-transform: uppercase;height: 25px;" onkeypress="return validateFloatKeyPress(this,event,1);" maxlength="5" value="<?=$gift['WRKHURS']?>" >
    		</div>
        </div>
		<?}}?>
		<!-- staff night duty end -->
		<?
		break;
	case 659:
		?>
		<!-- staff marriage gift Start-->
					<?
					$sql_gift = select_query_json("select * from approval_staff_marriage where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_approval[0]['APRNUMB']."%' order by entsrno", "Centra", 'TCS');
					if(count($sql_gift)>0){?>
						
						<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						 &nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">PHOTO</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">EXP</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">DOJ</div>
	            	<div class="col-sm-1 colheight"  style="padding: 0px;overflow-wrap: break-word;">BRANCH</div>
	            	<div class="col-sm-1 colheight"  style="padding: 0px;overflow-wrap: break-word;">DEPARTMENT</div>
            		<div class="col-sm-2 colheight"  style="padding: 0px;overflow-wrap: break-word;">DESIGNATION</div>
            		<div class="col-sm-1 colheight"  style="padding: 0px;">OWN GIFT <br> GRAM </div>
            		<div class="col-sm-1 colheight"  style="padding: 0px;">TRUST <br> AMOUNT</div>
		            
		        </div>
		        <? $g=0;
		        foreach ($sql_gift as $gift) {$g++;?>
		     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;<?=$g?></div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
						<div>
							<input type="text" name="empname[]" id="txt_staffcode_1" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top"  style=" text-transform: uppercase; padding: 2px; height: 25px;" readonly="readonly" value="<?=$gift['EMPCODE']."-".$gift['EMPNAME']?>">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">
						<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$gift['EMPCODE']?>"  title="<? echo $gift['EMPNAME']; ?>"> 	
						</div>	
					</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="CUREXP[]" id="curexp_1" value="<?=$gift['CUREXP']?>" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" value="<?=strtoupper(date("d-M-Y", strtotime($gift['DATEJOIN'])))?>" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="CURBRN[]" id="curbrn_1" value="<?=$gift['CURBRN']?>" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	
		    			 <input type="text"  name="CURDEP[]" id="curdep_1" value="<?=$gift['CURDEP']?>" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	
		    			 <input type="text"  name="CURDES[]" id="curdes_1" value="<?=$gift['CURDES']?>" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>

		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	
		    			 <input type="text"  name="OWNGIFT[]" id="owngift_1" value="<?=$gift['OWNGIFT']?>" data-toggle="tooltip" data-placement="top" title="GIFT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	
		    			 <input type="text"  name="TRUSTAMT[]" id="trustamt_1" value="<?=$gift['TRUSTAMT']?>" data-toggle="tooltip"  data-placement="top" title="TRUST AMOUNT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
		    		</div>
		        </div>
				<?}}?>
				<!-- staff marriage gift end -->
				<?
		break;
	case 623 :
		$sql_branch = select_query_json("select * from approval_staff_branch_change where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_approval[0]['APRNUMB']."%'", "Centra", 'TCS');
		if(count($sql_branch)>0){?>
		<div id='' style="padding-left: 10px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						&nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">PHOTO</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">EXP</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">DOJ</div>
		            <div class="col-sm-2 colheight"  style="padding: 0px;">CURRENT</div>
		            <div class="col-sm-4 colheight"  style="padding: 0px;">
		            	<div class="col-sm-12 colheight"  style="padding: 0px;">CHANGE</div>
            			<div class="col-sm-3 colheight"  style="padding: 0px;">BRANCH </div>
        	    		<div class="col-sm-3 colheight"  style="padding: 0px;">DEPARTMENT</div>
	            		<div class="col-sm-3 colheight"  style="padding: 0px;">DESIGNATION</div>
	            		<div class="col-sm-3 colheight"  style="padding: 0px;">REPORTING TO</div>
		            </div>
		        </div>
		        
		     	<?foreach ($sql_branch as $res) 
     			{?>
		        <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;1</div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100"  value="<?=$res['EMPCODE']."-".$res['EMPNAME']?>" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" readonly="true" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">
							<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$res['EMPCODE']?>"  title="<? echo $res['EMPNAME']; ?>"> 	
						</div>
						
		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="CUREXP[]" id="curexp_1" value="<?=$res['CUREXP']?>" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" value="<?=strtoupper(date("d-M-Y", strtotime($res['DATEJOIN'])))?>" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 
		    			<input type="text"  name="CURBRN[]" id="curbrn_1" value="<?=$res['CURBRN']?>" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: blue;font-weight: bold;" >
		    			<input type="text"  name="CURDEP[]" id="curdep_1" value="<?=$res['CURDEP']?>" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: blue;font-weight: bold;" >
		    			<input type="text"  name="CURDES[]" id="curdes_1" value="<?=$res['CURDES']?>" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: blue;font-weight: bold;" >

		    		</div>
		    		
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
						<input type="text"  name="NEWBRN[]" id="newbrn_1" value="<?=$res['NEWBRN']?>" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
		    			
					</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			<input type="text"  name="NEWDEP[]" id="newdep_1" value="<?=$res['NEWDEP']?>" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
		    			
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			<input type="text"  name="NEWDES[]" id="newdes_1" value="<?=$res['NEWDES']?>" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >	
	    			</div>
	    			<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			<input type="text"  name="REPORTTO[]" id="reportto_1" value="<?=$res['REPORTTO']?>" data-toggle="tooltip" data-placement="top" title="REPORTING TO" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >	
	    			</div>
				</div>
				<?}?>
				</div>
			</div>
			<?}

			break;

		case 6 :
			$sql_des = select_query_json("select * from approval_staff_designation where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_approval[0]['APRNUMB']."%'", "Centra", 'TEST');
			if(count($sql_des)>0){
			?>
			<div id='' style="padding-left: 10px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						&nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">PHOTO</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">EXP</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">DOJ</div>
		            <div class="col-sm-3 colheight"  style="padding: 0px;">
		            	<div class="col-sm-12 colheight"  style="padding: 0px;">CURRENT</div>
            			<div class="col-sm-4 colheight"  style="padding: 0px;">BRANCH </div>
        	    		<div class="col-sm-4 colheight"  style="padding: 0px;overflow-wrap: break-word;">DEPARTMENT</div>
	            		<div class="col-sm-4 colheight"  style="padding: 0px;overflow-wrap: break-word;">DESIGNATION</div>
		            </div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">CHANGE <br> DESIGNATION</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">CHANGE <br> DEPARTMENT</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">REPORTING TO</div>
		        </div>
		        
		     	<?php 
					$r=0;
					
					foreach ($sql_des as $key => $des) {
					$r++;
					?>
		     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;<?=$r?></div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control" readonly="readonly" data-toggle="tooltip" value="<?=$des['EMPCODE']."-".$des['EMPNAME']?>"   data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">
						<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$des['EMPCODE']?>"  title="<? echo $des['EMPNAME']; ?>">	
						</div>	
						<input type="hidden"  name="EMPSRNO[]" id="empsrno_1" value="">	
		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <!-- <label id="exp_1"></label> -->
		    			 <input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" value="<?=$des['CUREXP']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <!-- <label id="doj_1"></label> -->
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" value="<?=strtoupper(date("d-M-Y", strtotime($des['DATEJOIN'])))?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <!-- <label id="cbrn_1"></label> <br> -->
		    			  <input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" value="<?=$des['CURBRN']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	
		    			 <!-- <label id="cdep_1"></label> -->
		    			 <input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" value="<?=$des['CURDEP']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	
		    			 <!-- <label id="cdes_1"></label> -->
		    			 <input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" value="<?=$des['CURDES']?>" style=" text-transform: uppercase;height: 25px;color: blue;font-weight: bold;" >
		    		</div>
		             
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    		<input type="text"  name="NEWDES[]" id="newdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" value="<?=$des['NEWDES']?>" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
		    		</div>
		             
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    		<input type="text"  name="NEWDEPT[]" id="newdept_1" placeholder="NEW DESIGNATION" data-toggle="tooltip" data-placement="top" title="NEW DESIGNATION" class="form-control" readonly="readonly" value="<?=$des['NEWDEPT']?>" style="text-transform: uppercase; height: 25px; color: green; font-weight: bold;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    		<input type="text"  name="REPORTTO[]" id="reportto_1" placeholder="REPORTING TO" data-toggle="tooltip" data-placement="top" title="REPORTING TO" class="form-control" readonly="readonly" value="<?=$des['REPORTTO']?>" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
		    		</div>
		    	</div>
				<? } ?>
				</div>
			</div>
			<?}
			break;

	case 967 : // ESI Multiple Branch format 
		$sql_des = select_query_json("SELECT * from approval_branch_detail bd, approval_branch_list bl where bd.BRNCODE = bl.BRNCODE and bd.APRNUMB = '".$apprno."'", "Centra", 'TEST');
		if(count($sql_des)>0){ ?>
			<div id='' style="padding-left: 10px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-2 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						&nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">BRANCH CODE</div>
		            <div class="col-sm-4 colheight"  style="padding: 0px;">BRANCH NAME</div>
		            <div class="col-sm-2 colheight"  style="padding: 0px;">NUMBER OF EMPLOYEE</div>
		            <div class="col-sm-2 colheight"  style="padding: 0px;">VALUE</div>
		        </div>
		        
		     	<?php 
		     	$r=0; $ttlamt = 0;
				foreach ($sql_des as $key => $dep) { $r++; $ttlamt += $dep['APRAMNT']; ?>
		     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-2 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;<?=$r;?></div>
					</div>
					<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
						<?=$dep['BRNCODE']?>
					</div>
					<div class="col-sm-4 colheight" style="padding: 1px 0px;">
						<?=$dep['BRNNAME']?>
		    		</div>
		            <div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			<?=$dep['NOFEMPL']?>
		    		</div>
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <?=$dep['APRAMNT']?>
		    		</div>
		    	</div>
		    	<? } 

		    	if($ttlamt > 0) { ?>
			     	<div class="row" style="margin-right: -5px; font-weight: bold; display: flex; text-transform: uppercase;">
						<div class="col-sm-10 colheight" style="padding: 1px 0px; text-align: center; padding-right: 2px; text-align: right;">	
			    			Total
			    		</div>
			    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
			    			 <?=$ttlamt?>
			    		</div>
			    	</div>
			    <? } ?>
				</div>
			</div>
			<? } 

			break;

	case 8 :
			$sql_des = select_query_json("select * from approval_staff_department where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_approval[0]['APRNUMB']."%'", "Centra", 'TCS');
			if(count($sql_des)>0){
			?>
			<div id='' style="padding-left: 10px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						&nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">PHOTO</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">EXP</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">DOJ</div>
		            <div class="col-sm-3 colheight"  style="padding: 0px;">
		            	<div class="col-sm-12 colheight"  style="padding: 0px;">CURRENT</div>
            			<div class="col-sm-4 colheight"  style="padding: 0px;">BRANCH </div>
        	    		<div class="col-sm-4 colheight"  style="padding: 0px;overflow-wrap: break-word;">DEPARTMENT</div>
	            		<div class="col-sm-4 colheight"  style="padding: 0px;overflow-wrap: break-word;">DESIGNATION</div>
		            </div>
		            <div class="col-sm-3 colheight"  style="padding: 0px;">CHANGE <br> DEPARTMENT</div>
		        </div>
		        
		     	<?php 
		     	$r=0;
					
					foreach ($sql_des as $key => $dep) {
				$r++;
				?>
		     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;<?=$r;?></div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control" data-toggle="tooltip" value="<?=$dep['EMPCODE']."-".$dep['EMPNAME']?>"  data-placement="top" 
							style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">
						<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$dep['EMPCODE']?>"  title="<? echo $dep['EMPNAME']; ?>">	
						</div>	
						<input type="hidden"  name="EMPSRNO[]" id="empsrno_1" value="">
		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <!-- <label id="exp_1"></label> -->
		    			 <input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" value="<?=$dep['CUREXP']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <!-- <label id="doj_1"></label> -->
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" value="<?=strtoupper(date("d-M-Y", strtotime($dep['DATEJOIN'])))?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <!-- <label id="cbrn_1"></label> <br> -->
		    			 <input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" value="<?=$dep['CURBRN']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	
		    			 <!-- <label id="cdep_1"></label> -->
		    			 <input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" value="<?=$dep['CURDEP']?>" style=" text-transform: uppercase;height: 25px;color: blue;font-weight: bold;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">	
		    			 <!-- <label id="cdes_1"></label> -->
		    			 <input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" value="<?=$dep['CURDES']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		             

		            <? if($hid_aprnumb == 'S-TEAM / ATTENDANCE 1000068 / 26-04-2018 / 0068 / 02:23 PM') { 
		            		$sql_des = select_testquery("select * from approval_staff_department where apryear='".$_REQUEST['year']."' and aprnumb like '%".$sql_approval[0]['APRNUMB']."%'"); ?>
		            	<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
			    		<input type="text"  name="NEWDEP[]" id="curdes_1" placeholder="NEW DEPARTMENT" data-toggle="tooltip" data-placement="top" title="NEW DEPARTMENT" class="form-control" readonly="readonly" value="<?=$dep['NEWDEP']?>" style="text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
			    		</div>

			    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
			    		<input type="text" name="NEWDES[]" id="curdesi_1" placeholder="NEW DESIGNATION" data-toggle="tooltip" data-placement="top" title="NEW DESIGNATION" class="form-control" readonly="readonly" value="<?=$sql_des[0]['NEWDES']?>" style="text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
		            <? } else { ?>
			    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
			    		<input type="text"  name="NEWDEP[]" id="curdes_1" placeholder="NEW DEPARTMENT" data-toggle="tooltip" data-placement="top" title="NEW DEPARTMENT" class="form-control" readonly="readonly" value="<?=$dep['NEWDEP']?>" style="text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
			    		</div>
			    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
			    		<input type="text"  name="REPORTTO[]" id="reportto_1" placeholder="REPORTING TO" data-toggle="tooltip" data-placement="top" title="REPORTING TO" class="form-control" readonly="readonly" value="<?=$dep['REPORTTO']?>" style="text-transform: uppercase;height: 25px;color: green;font-weight: bold;" >
			    		</div>
			    	<? } ?>
		    	</div>
		    	<? } ?>	
				</div>
			</div>
			<? } 
			break;

	case 5:
		$sql_salary = select_query_json("select app.* from approval_staff_salary_change app,employee_office emp  where emp.empsrno= app.empsrno and app.apryear='".$_REQUEST['year']."' and app.aprnumb like  '%".$sql_approval[0]['APRNUMB']."%'", "Centra", 'TCS');
		if(count($sql_salary)>0){
			?>
			<div id='' style="padding-left: 10px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						&nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">PHOTO</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">EXP</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">DOJ</div>
		            <div class="col-sm-4 colheight"  style="padding: 0px;">
		            	<div class="col-sm-12 colheight"  style="padding: 0px;">CURRENT</div>
            			<div class="col-sm-3 colheight"  style="padding: 0px;">BRANCH </div>
        	    		<div class="col-sm-3 colheight"  style="padding: 0px;overflow-wrap: break-word;">DEPARTMENT</div>
	            		<div class="col-sm-3 colheight"  style="padding: 0px;overflow-wrap: break-word;">DESIGNATION</div>
	            		<div class="col-sm-3 colheight"  style="padding: 0px;">BASIC</div>
		            </div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">	INCREMENT <br> AMOUNT</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">NEW BASIC</div>
		        </div>
		        <?php
		        $r=0; 
					
					foreach ($sql_salary as $key => $basic) {
				$r++;
				?>
		        <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;<?=$r;?></div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control" data-toggle="tooltip" value="<?=$basic['EMPCODE']."-".$basic['EMPNAME']?>" readonly="readonly" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">
						<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$basic['EMPCODE']?>"  title="<? echo $basic['EMPNAME']; ?>">	
						</div>	
							
		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			<input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" value="<?=$basic['CUREXP']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" value="<?=strtoupper(date("d-M-Y", strtotime($basic['DATEJOIN'])))?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" value="<?=$basic['CURBRN']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">	
		    			<input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" value="<?=$basic['CURDEP']?>" style=" text-transform: uppercase;height: 25px;" >
	    			</div>
	    			<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">	
	    				<input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" value="<?=$basic['CURDES']?>" style=" text-transform: uppercase;height: 25px;" >
	    			</div>
	    			<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
	    				<input type="text"  name="CURBAS[]" id="curbas_1" placeholder="BASIC" data-toggle="tooltip" data-placement="top" title="BASIC" class="form-control" readonly="readonly" value="<?=$basic['CURBAS']?>" style=" text-transform: uppercase;height: 25px;color: blue;font-weight: bold;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
	    				<input type="text"  name="INCAMT[]" id="incamt_1" placeholder="INCREMENT AMOUNT" data-toggle="tooltip" data-placement="top" title="INCREMENT AMOUNT" class="form-control" readonly="readonly" value="<?=$basic['INCAMT']?>" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;">
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			<input type="text" readonly="readonly" name="NEWBAS[]" id="newbas_1" placeholder="NEW BASIC" data-toggle="tooltip" data-placement="top" title="NEW BASIC" class="form-control" value="<?=$basic['NEWBAS']?>" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;">
		    		</div>
				</div>
				<? } ?>
				</div>
			</div>
			<? 
			}
		break;
		/*staff sale commission*/

	/* case 6:
		$sql_comm = select_query_json("select * from approval_staff_sale_commission where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_approval[0]['APRNUMB']."%'", "Centra", 'TCS');
		if(count($sql_comm)>0){
			?>
			<div id='' style="padding-left: 10px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						&nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">PHOTO</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">EXP</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">DOJ</div>
		            <div class="col-sm-4 colheight"  style="padding: 0px;">
		            	<div class="col-sm-12 colheight"  style="padding: 0px;">CURRENT</div>
            			<div class="col-sm-3 colheight"  style="padding: 0px;">BRANCH </div>
        	    		<div class="col-sm-3 colheight"  style="padding: 0px;overflow-wrap: break-word;">DEPARTMENT</div>
	            		<div class="col-sm-3 colheight"  style="padding: 0px;overflow-wrap: break-word;">DESIGNATION</div>
	            		<div class="col-sm-3 colheight"  style="padding: 0px;">BASIC</div>
		            </div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">COMMISSION</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">NEW COMMISSION</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">APROXIMATE AMOUNT</div>
		        </div>
		        <?php
		        $r=0; 
					
					foreach ($sql_comm as $key => $comm) {
				$r++;
				?>
		        <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;<?=$r;?></div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control" data-toggle="tooltip" value="<?=$comm['EMPCODE']."-".$comm['EMPNAME']?>" readonly="readonly" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">
						<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$comm['EMPCODE']?>"  title="<? echo $comm['EMPNAME']; ?>">	
						</div>	
							
		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			<input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" value="<?=$comm['CUREXP']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" value="<?=strtoupper(date("d-M-Y", strtotime($comm['DATEJOIN'])))?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" value="<?=$comm['CURBRN']?>" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">	
		    			<input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" value="<?=$comm['CURDEP']?>" style=" text-transform: uppercase;height: 25px;" >
	    			</div>
	    			<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">	
	    				<input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" value="<?=$comm['CURDES']?>" style=" text-transform: uppercase;height: 25px;" >
	    			</div>
	    			<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
	    				<input type="text"  name="CURBAS[]" id="curbas_1" placeholder="BASIC" data-toggle="tooltip" data-placement="top" title="BASIC" class="form-control" readonly="readonly" value="<?=$comm['CURBAS']?>" style=" text-transform: uppercase;height: 25px;color: blue;font-weight: bold;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
	    				<input type="text"  name="CURCOMM[]" id="curcomm_1" placeholder="CURRENT COMMISSION" data-toggle="tooltip" data-placement="top" title="CURRENT COMMISSION" class="form-control" readonly="readonly" value="<?=$comm['CURCOMM']?>" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;">
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			<input type="text" readonly="readonly" name="NEWCOMM[]" id="newcomm_1" placeholder="NEW COMMISSION" data-toggle="tooltip" data-placement="top" title="NEW COMMISSION" class="form-control" value="<?=$comm['NEWCOMM']?>" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;">
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			<input type="text" readonly="readonly" name="APRXAMT[]" id="aprxamt_1" placeholder="APROXIMATE AMOUNT" data-toggle="tooltip" data-placement="top" title="APROXIMATE AMOUNT" class="form-control" value="<?=$comm['APRXAMT']?>" style=" text-transform: uppercase;height: 25px;color: green;font-weight: bold;">
		    		</div>
				</div>
				<? } ?>
				</div>
			</div>
			<? }
		break; */

	default:
		
		break;

}

$sql_gen_det = select_query_json("select * from approval_general_detail where aprnumb='".$apprno."' order by rowsrno,colsrno", "Centra", 'TEST');
$sql_gen_master = select_query_json("select * from approval_general_master where tempid=".$sql_gen_det[0]['TEMPID']." order by colsrno", "Centra", 'TEST'); ?>
<table class="table table-bordered table-striped table-hover">
 	<thead>
 		<tr style="background-color:#1D5B69; color:#FFFFFF; text-transform: uppercase;">
	 		<? foreach ($sql_gen_master as $col) { ?>
	 			<th><?=$col['COLDET']?></th>	
	 		<? } ?>
	 	</tr>
 	</thead>
 	<tbody>
	<?
	$row = "";
	// $col['COLDET'];
	$row1 = 0;
	foreach($sql_gen_det as $col)
	{
		if($row != $col['ROWSRNO'])
		{
			$row1++;
			?><tr><? } ?>
			<td>
				<? if($col['COLSRNO'] == 1) { echo $row1; }
				else { 
					// echo "**".$col['APMCODE']."**".$col['COLSRNO']."**".$col['COLDET']."**";
					if($col['APMCODE'] == 856 and $col['COLSRNO'] == 2) { 
						$expl = explode(",", $col['COLDET']);
						for($ij = 0; $ij < count($expl); $ij++) {
							$sql_tablemast = select_query_json("select * from master_table_detail where deleted = 'N' and MASTERID = '".$expl[$ij]."' order by TABNAME asc", "Centra", 'TCS'); 
							if(count($sql_tablemast) > 0) { // echo "**".$sql_tablemast[0]['TABNAME']."**".$sql_tablemast[0]['MASTERID']."**"; ?>
								<a href="javascript:void(0)" title="<?=$sql_tablemast[0]['TABNAME']?> TABLE" data-title="<?=$sql_tablemast[0]['TABNAME']?> TABLE" onclick="find_tablemaster('<?=$sql_tablemast[0]['TABNAME']?>')"><?=$sql_tablemast[0]['TABNAME']?> (<?=$sql_tablemast[0]['MASTERID']?>) </a>;&nbsp;&nbsp;
							<?
							} else {
								echo $col['COLDET'];
							}
						} 
					} else { ?>
					<input readonly="readonly" type="text" style="text-transform: uppercase;" name="a[<?=$col['COLSRNO']?>][]" id="a_<?=$col['COLSRNO']?>_1" class="form-control" value="<?=$col['COLDET']?>">
				<? }
				} ?>
			</td>
	<? $row = $col['ROWSRNO'];
	} ?>
 	</tbody>
</table>