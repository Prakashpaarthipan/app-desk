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
//echo "select * from approval_npo_applist where apmcode = ".$slt_approval_listings."";
                                                                                                                                                                                                                                                                                                                  

$sql_dynamic_option = select_query_json("select * from approval_npo_applist where apmcode = ".$slt_approval_listings."", "Centra", 'TCS'); ?>
<div class='clear clear_both'></div>
<div style="border-top: 1px solid #d4d4d4; width: 100%; padding: 0% 2%; height: 5px;"></div>
<?
if($_REQUEST['slt_submission'] != 8 && $_REQUEST['slt_submission'] != 4 && $_REQUEST['slt_submission'] != 2  && $_REQUEST['slt_submission'] != 3){$pathid = 1;}
if(count($sql_dynamic_option) > 0) { $pathid = $sql_dynamic_option[0]['PATHID']; }
if($slt_approval_listings == 786 or $slt_approval_listings == 408 or $slt_approval_listings == 143 or $slt_approval_listings==97 or $slt_approval_listings==105 ){
	$pathid = 2;
}
if($slt_approval_listings == 955 or $slt_approval_listings == 954  ){
	$pathid = 13;
}
if($_REQUEST['view'] == "budget"){
	$pathid =1;
}

if(($_REQUEST['slt_submission'] != 4 && $_REQUEST['slt_submission'] != 2  && $_REQUEST['slt_submission'] != 3) or ($pathid != "")){
	$not_show_bdvlu = array("99", "12", "13"); // NOT SHOW THE BUDGET VALUE
	$dont_show = 0 ;
	if(in_array($pathid, $not_show_bdvlu)){
		$dont_show = 1;
	}
//echo "My Value is".$pathid;
	if($dont_show == 0){ ?>
		<div style="text-align: center; line-height: 25px; background-color: #666666; color: #FFFFFF; width: 100%; font-weight: bold; text-transform: uppercase;">Break Up for Approval Listing<span class="blink_me" id="budgt_vlu"> - Budget Value - </span>  <input type="button" name="prddet" id="prddet" value="View Product" onclick="get_prddet();" class="btn-info"> </div>
<?	}
} ?>
<div class='clear clear_both'></div>
<input type="hidden" name="hid_app_path" id="hid_app_path" value="<?=$pathid?>">
<?

switch ($pathid) {
	case 1: // PO Based - Start ?>
		<!-- Supplier Quotation / Po Based -->
		<div id='' style="margin: 1px 5px 1px 0px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight" style="padding: 0px; border-top-left-radius:5px;">
						<input type="hidden" name="partint3" id="partint3" value="1">
						<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="call_product_innergrid(1)"><span class="glyphicon glyphicon-plus"></span></button>
						<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="call_product_innergrid_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
					</div>
					<div class="col-sm-3 colheight" style="padding: 0px;">Product / Sub Product / Spec. / Image</div>
		            <div class="col-sm-3 colheight" style="padding: 0px;">Advt. Product Details</div>
		            <div class="col-sm-1 colheight" style="padding: 0px;">Qty</div>
		            <div class="col-sm-1 colheight" style="padding: 0px;">Rate</div>
		            <div class="col-sm-1 colheight" style="padding: 0px;">Tax</div>
		            <div class="col-sm-1 colheight" style="padding: 0px;">Discount %</div>
		            <div class="col-sm-1 colheight" style="padding: 0px;">Total Amount</div>
				</div>
                          
				<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;1</div>
					</div>

					<div class="col-sm-3 colheight" style="padding: 1px 0px;">
						<div style="width: 49%; float: left;">
							<input type="text" name="txt_prdcode[]" id="txt_prdcode_1" required="required" maxlength="100" placeholder="Product" title="Product" class="form-control supquot find_prdcode" data-toggle="tooltip" onKeyPress="enable_product();" data-placement="top" onBlur="validate_prdempty(1); find_tags();" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="width: 49%; float: left;margin-left: 2px;">
							<input type="text" name="txt_subprdcode[]" id="txt_subprdcode_1" maxlength="100" placeholder="Sub Product" data-toggle="tooltip" data-placement="top" title="Sub Product" class="form-control supquot find_subprdcode" onKeyPress="enable_product();" onBlur="validate_subprdempty(1); find_tags();" style=" text-transform: uppercase; padding: 2px; height: 25px;">

							<input type="hidden" readonly="readonly" name="txt_unitname[]" id="txt_unitname_1" required="required" maxlength="3" placeholder="Unit" data-toggle="tooltip" data-placement="top" title="Unit" onKeyPress="enable_product();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
		    				<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_unitcode[]" id="txt_unitcode_1" required="required" maxlength="3" placeholder="Unit Code" data-toggle="tooltip" data-placement="top" title="Unit Code" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
						</div>
						<div style="clear: both; height: 1px;"></div>

						<div>
							<input type="text" name="txt_prdspec[]" id="txt_prdspec_1" required="required" maxlength="100" placeholder="Product Specification" data-toggle="tooltip" data-placement="top" title="Product Specification" onKeyPress="enable_product();" class="form-control supquot find_prdspec" onBlur="validate_prdspcempty(1); find_tags();" style=" text-transform: uppercase; padding: 2px;height: 25px;">
						</div>
						<div style="clear: both;"></div>

						<div>
							<!-- Product Image -->
		    				<input type="file" name="fle_prdimage[]" id="fle_prdimage_1" data-toggle="tooltip" onchange="ValidateSingleInput(this);" accept="image/jpg,image/jpeg,image/png,image/jpg" class="form-control supquot" data-placement="left" data-toggle="tooltip" data-placement="top" title="Product Image" placeholder="Product Image" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : ONLY JPG, PNG IMAGES ALLOWED.</span>
		    				<!-- Product Image -->
						</div>
						<div style="clear: both;"></div>
		            </div>

		            <div class="col-sm-3 colheight" style="padding: 1px 0px;">
		            	<div style="width: 49%; float: left;">
		    				<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_ad_duration[]" id="txt_ad_duration_1" onblur="calculateqtyamount('1'); find_tags();" maxlength="3" placeholder="Ad. Duration" data-toggle="tooltip" data-placement="top" title="Ad. Duration" class="form-control supquot ad_category" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    			</div>
						<div style="width: 49%; float: left; margin-left: 2px;">
							<input type="text" name="txt_print_location[]" id="txt_print_location_1" maxlength="25" placeholder="Ad. Print Location" data-toggle="tooltip" data-placement="top" title="Ad. Print Location" onKeyPress="enable_product();" class="form-control supquot ad_category" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
						</div>
						<div style="clear: both;"></div>

						<div style="width: 49%; float: left;">
		    				<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_length[]" id="txt_size_length_1" onblur="calculateqtyamount('1'); find_tags();" maxlength="7" placeholder="Size Length" data-toggle="tooltip" data-placement="top" title="Size Length" class="form-control supquot ad_category" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    			</div>
						<div style="width: 49%; float: left; margin-left: 2px;">
		    				<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_width[]" id="txt_size_width_1" onblur="calculateqtyamount('1'); find_tags();" maxlength="7" placeholder="Size width" data-toggle="tooltip" data-placement="top" title="Size width" class="form-control supquot ad_category" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    			</div>
		    			<div style="clear: both;"></div>

		    			<input type="hidden" readonly="readonly" name="slt_usage_section[]" id="slt_usage_section_1" required="required" maxlength="3" placeholder="Usage Section" data-toggle="tooltip" data-placement="top" title="Usage Section" onKeyPress="enable_product();" class="form-control supquot custom-select chosn" style=" text-transform: uppercase;" >
		    			<? /* <select class="form-control supquot custom-select chosn" onKeyPress="enable_product();" required name='slt_usage_section[]' id='slt_usage_section_1' data-toggle="tooltip" data-placement="top" title="Usage Section" style="text-align: left !important;">
						<? 	$sql_project = select_query_json("select * from empsection where DELETED = 'N' order by ESENAME Asc");
							for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
								<option style="text-align: left !important;" value="<?=$sql_project[$project_i]['ESECODE']?>"><?=$sql_project[$project_i]['ESENAME']?></option>
						<? } ?>
						</select> */ ?>
		    		</div>


		            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
		    			<input type="text" onKeyPress="enable_product(); return numwodot(event)" name="txt_prdqty[]" id="txt_prdqty_1" onblur="calculateqtyamount('1'); find_tags();" required="required" maxlength="6" placeholder="Qty" data-toggle="tooltip" data-placement="top" title="Qty" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
		    		</div>

		            <div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px; text-align: center; padding-left: 2px;" id="id_sltrate_1">
		    			 -
		    		</div>
		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: left; padding-left: 2px;">
		    			<div style="float: left; width: 50%; text-align: right;">SGST : </div><div style="float: left; width: 50%;" id="id_sltsgst_1"> - </div>
						<div style="clear: both;"></div>
		    			<div style="float: left; width: 50%; text-align: right;">CGST : </div><div style="float: left; width: 50%;" id="id_sltcgst_1"> - </div>
						<div style="clear: both;"></div>
		    			<div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;IGST : </div><div style="float: left; width: 50%;" id="id_sltigst_1"> - </div>
						<div style="clear: both;"></div>
		    		</div>
		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: left; padding-left: 2px;">
						<div style="clear: both;"></div>
		    			<div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;DISC.% : </div><div style="float: left; width: 50%;" id="id_sltdisc_1"> - </div>
						<div style="clear: both;"></div>
		    		</div>
		            <div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px; text-align: center; padding-left: 2px;" id="id_sltamnt_1">
		    			 -
		    		</div>
				</div>

				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 0px; border-top-left-radius:5px;"></div>
					<!-- Quotation -->
					<div class="col-sm-10 colheight" style="padding: 0px; border-top-left-radius:5px;">
		    			<div class="fair_border" style="padding-left: 0px;">
							<div class="row" style="margin-right: -10px; background-color: #666666; color:#FFFFFF; display: flex; font-weight: bold;">
								<div class="col-sm-1 colheight" style="padding: 0px;">S.No</div>
								<div class="col-sm-3 colheight" style="padding: 0px;">Supplier Details</div>
								<div class="col-sm-1 colheight" style="padding: 0px;">Delivery Duration</div>
					            <div class="col-sm-1 colheight" style="padding: 0px;">Per Piece Rate / Adv. Amount</div>
					            <div class="col-sm-1 colheight" style="padding: 0px;">Tax Val.</div>
					            <div class="col-sm-1 colheight" style="padding: 0px;">Discount % </div>
					            <div class="col-sm-1 colheight" style="padding: 0px;">Total Amount</div>
					    		<div class="col-sm-1 colheight" style="padding: 0px;">Quotation PDF</div>
					    		<div class="col-sm-2 colheight" style="padding: 0px;">Remarks</div>
								<? /*<div class="col-sm-1 colheight" style="padding: 0px;">&nbsp;</div> */ ?>
							</div>
						</div>
						<!-- Quotation -->
					</div>
					<div class="col-sm-1 colheight" style="padding: 0px; border: 1px solid #FFFFFF !important; background-color: #FFFFFF; border-top-left-radius:5px;"></div>
				</div>

				<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>
		    		<div class="col-sm-10 colheight" style="padding-left: 0px;">
		    			<!-- Quotation -->
		    			<div class="parts3_1 fair_border">
							<div class="row" style="margin-right: -10px; display: flex;">
								<div class="col-sm-1 colheight" style="padding: 1px 0px;">
									<div class="fg-line">
										<input type="hidden" name="partint3_1" id="partint3_1" value="1"><input type="hidden" name="hid_nof_suppliers_1" id="hid_nof_suppliers_1" value="1"><input type="hidden" name="txt_prdsgst_per[1][]" id="txt_prdsgst_per_1_1" value=""><input type="hidden" name="txt_prdcgst_per[1][]" id="txt_prdcgst_per_1_1" value=""><input type="hidden" name="txt_prdigst_per[1][]" id="txt_prdigst_per_1_1" value="">
										<button class="btn btn-success btn-add3" id="addbtn_1" type="button" title="Add Suppliers" style="padding: 2px 5px; margin-right: 0px !important;" onclick="call_innergrid(1)"><span class="glyphicon glyphicon-plus"></span></button>
										<button id="removebtn_1" class="btn btn-remove btn-danger" type="button" title="Delete Suppliers" style="padding: 2px 5px; margin-right: 0px !important;" onclick="call_innergrid_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;<br><input type="radio" onKeyPress="enable_product();" onclick="getrequestvalue(1, 1)" checked="checked" name="txt_sltsupplier[1][]" id='txt_sltsupplier_1_1' value='1' data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;1<br>

										<input type="checkbox" name="chk_apply_supplier[1][]" id="chk_apply_supplier_1_1" onclick="apply_supplier(1)">
									</div>
								</div>

								<div class="col-sm-3 colheight" style="padding: 1px 0px;">
									<input type="text" name="txt_sltsupcode[1][]" id="txt_sltsupcode_1_1" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Supplier" class="form-control supquot find_supcode" onBlur="validate_supprdempty(1, 1); find_tags();" style=" text-transform: uppercase;height: 25px;">
									<input type="hidden" name="state[1][]" id="state1_1" value=""><span id="txt_taxpercentage_1_1"></span>
								</div>

								<div class="col-sm-1 colheight" style="padding: 1px 0px;">
									<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_delivery_duration[1][]" id="txt_delivery_duration_1_1" required="required" maxlength="4" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
								</div>

					            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
					            	<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdrate[1][]" id="txt_prdrate_1_1" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" onblur="calculatenetamount('1','1'); find_tags();" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">Adv.Amount Val.:
									<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount[1][]" id="txt_advance_amount_1_1" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
					            </div>

					            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
					    			<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdsgst[1][]" id="txt_prdsgst_1_1" required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" onblur="calculatenetamount('1','1'); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
					    			<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdcgst[1][]" id="txt_prdcgst_1_1" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" onblur="calculatenetamount('1','1'); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
					    			<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdigst[1][]" id="txt_prdigst_1_1" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" onblur="calculatenetamount('1','1'); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
					    		</div>


					            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
					    			<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_spldisc[1][]" id="txt_spldisc_1_1" required="required" maxlength="5" placeholder="Spl. Discount Value" data-toggle="tooltip" data-placement="top" title="Spl. Discount Value" onblur="calculatenetamount('1','1'); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
					    			<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_pieceless[1][]" id="txt_pieceless_1_1" required="required" maxlength="5" placeholder="Piece Less Value" data-toggle="tooltip" data-placement="top" title="Piece Less Value" onblur="calculatenetamount('1','1'); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
					    			<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prddisc[1][]" id="txt_prddisc_1_1" required="required" maxlength="10" placeholder="Discount %" data-toggle="tooltip" data-placement="top" title="Discount %" onblur="calculatenetamount('1','1'); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
					    			<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="hid_prdnetamount[1][]" id="hid_prdnetamount_1_1" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
					    		</div>

					            <div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_1_1">0</div>

					    		<div class="col-sm-1 colheight" style="padding: 1px 0px;">
									<input type="file" onKeyPress="enable_product();" name="fle_supquot[1][]" id="fle_supquot_1_1" data-toggle="tooltip" onchange="ValidateSingleInput(this);" accept=".pdf" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>
								</div>

					            <div class="col-sm-2 colheight" style="padding: 1px 0px;">
					            	<textarea onKeyPress="enable_product();" name="txt_suprmrk[1][]" id="txt_suprmrk_1_1" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('1','1'); find_tags();" class="form-control supquot" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>
					            </div>
							</div>
						</div>
		    			<!-- Quotation -->

					</div>
					<div class="col-sm-1 colheight" style=" border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>
				</div>
			</div>
			<div class='clear clear_both'></div>
		</div>
		<div class='clear clear_both'></div>
		<!-- Supplier Quotation / Po Based -->
<? 	// PO Based - End
	break;

	case 2: // Monthwise Budget - Start ?>

		<? 	if($view == 1) { ?>
		<div style="padding: 5px; margin: 5px; border: 1px solid #a0a0a0; background-color: #effff2; border-radius: 5px;">
		<!-- Supplier Name -->
		<div class="form-group trbg">
			<div class="col-lg-3 col-xs-3">
				<label style='height:27px;'>Supplier Name <span style='color:red'>*</span></label>
			</div>
			<div class="col-lg-9 col-xs-9">
				<?	if($_REQUEST['action'] == 'view') {
						echo ": ".$sql_reqid[0]['SUPCODE']." - ".$sql_reqid[0]['SUPNAME'];
				    } else { ?>
						<input type='text' class="form-control" tabindex='11' maxlength="100" name='txt_suppliercode' id='txt_suppliercode' data-toggle="tooltip" data-placement="top" title="Supplier Name" placeholder="Supplier Name" style="text-transform: uppercase;" value='<? if($sql_reqid[0]['SUPCODE'] != '') { echo $sql_reqid[0]['SUPCODE']." - "; } echo $sql_reqid[0]['SUPNAME']; ?>'>
				<?  } ?>
			</div>
		</div>
		<div class='clear clear_both' style='padding-top:10px;'></div>
		<!-- Supplier Name -->

		<!-- Supplier Contact No -->
		<div class="form-group trbg">
			<div class="col-lg-3 col-xs-3">
				<label style='height:27px;'>Supplier Contact No <span style='color:red'>*</span></label>
			</div>
			<div class="col-lg-9 col-xs-9">
				<?	if($_REQUEST['action'] == 'view') {
						echo ": ".$sql_reqid[0]['SUPCONT'];
				    } else { ?>
						<input type='text' class="form-control" tabindex='11' maxlength="25" name='txt_supplier_contactno' id='txt_supplier_contactno' onkeypress='return isNumber(event)' data-toggle="tooltip" data-placement="top" title="Supplier Contact No" placeholder="Supplier Contact No" style="text-transform: uppercase;" value='<?=$sql_reqid[0]['SUPCONT']?>'>
				<?  } ?>
			</div>
		</div>
		<div class='clear clear_both'><span style='color:#FF0000; font-size:10px;'><? /* NOTE : IF THIS IS QUOTATION BASED APPROVAL MEANS MUST FILL OUT THE SELECTED SUPPLIER DETAILS HERE.. */ ?>NOTE : MUST FILL OUT THE SELECTED SUPPLIER DETAILS HERE..</span></div>
		<div class='clear clear_both' style='padding-top:10px;'></div>
		<!-- Supplier Contact No -->
		</div>
		<div class='clear clear_both' style='padding-top:10px;'></div>

		<div class="col-lg-3 col-xs-3">Budget Planner &#8377;</div>
		<div class="col-lg-9 col-xs-9"> :
			<div id='id_budplanner'></div>
			<div>
				<table style='clear:both; float:left; width:100%;'>
				<tr><td>
					<table style='width:100%;'> <? /* class="monthyr_wrap" */ ?>
					<?
						$brnc = $slt_branch;
						$depc = $deptid;
						$core_dep = $core_deptid;
						$tarno = $target_no;
						$ttl_lock = 0;

						$explode_yr = explode("-", $current_year[0]['PORYEAR']);
						$explode_cryr = substr($explode_yr[0], 2);
						$date_quarter = date_quarter();

						$frdt = date("01-M-y");
						// $todt = "31-MAR-".$explode_yr[1];
						switch ($date_quarter) {
							case 1:
								$todt = "30-JUN-".$explode_cryr;
								break;

							case 2:
								$todt = "30-SEP-".$explode_cryr;
								break;

							case 3:
								$todt = "31-DEC-".$explode_cryr;
								break;

							case 4:
								$todt = "31-MAR-".$explode_yr[1];
								break;

							default:
								$todt = "31-MAR-".$explode_yr[1];
								break;
						}

						$nxtyr = $current_year[0]['PORYEAR'];
						$minvl = date('m/Y', strtotime($frdt));
						$maxvl = date('m/Y', strtotime($todt));
						$minvl1 = date('m,Y', strtotime($frdt));
						$maxvl1 = date('m,Y', strtotime($todt));

						$budyr = date('Y', strtotime($frdt));
						$budmn = date('m', strtotime($frdt));

						$prev_month_ts = strtotime(date('01-m-Y').' -1 month');

						$sql_paymntyr = select_query_json("select * from auto_update_process where AUPCODE = 34", "Centra", 'TCS');
						if($slt_approval_listings == 807 or $slt_approval_listings == 777) {
							$crnyr = $sql_paymntyr[0]['PAYYEAR'];
							$crnmn = $sql_paymntyr[0]['PAYMONT'];
							// $minvl = date('m/Y', strtotime('01-'.$sql_paymntyr[0]['PAYMONT'].'-'.$sql_paymntyr[0]['PAYYEAR']));
							// $maxvl = date('m/Y', strtotime('31-'.$sql_paymntyr[0]['PAYMONT'].'-'.$sql_paymntyr[0]['PAYYEAR']));
						} else {
							$crnyr = date('Y', $prev_month_ts);
							$crnmn = date('m', $prev_month_ts);
						}

						/* $minvl = '4/2017';
						$maxvl = '3/2018'; */
						$fdt = explode("/", $minvl);
						$tdt = explode("/", $maxvl);
						// echo "**".$fdt[0]."**".$fdt[1]."**";
						$ivl = 0; $ii = ''; $fstmnth = ''; $lstmnth = '';
						$can_edit = 1; $add_month = '';
						if($slt_approval_listings == '807' || $slt_approval_listings == '777') { // 807 - STAFF MONTHLY SALARY & 777 - BANK SALARY CREDIT
							if($slt_approval_listings == '807') { // 807 - STAFF MONTHLY SALARY
								$notin = " and status not in ('C') ";
							} elseif($slt_approval_listings == '777') { // 777 - BANK SALARY CREDIT
								$notin = " and status not in ('B') ";
							}

							$sql_mntsal = select_query_json("select * from attn_monthly_salary_detail
																where brncode = ".$brnc." and payyear = ".$crnyr." and paymont = ".$crnmn."
																	and status in ('N', 'C', 'B') ".$notin."", "Centra", 'TCS');

							/* echo ("select * from attn_monthly_salary_detail
																where brncode = ".$brnc." and payyear = ".$crnyr." and paymont = ".$crnmn."
																	and status in ('N', 'C', 'B') ".$notin.""); */
							if(count($sql_mntsal) > 0) {
								$add_month = $sql_mntsal[0]['PAYMONT'].", ".$sql_mntsal[0]['PAYYEAR'];
								$can_edit = 0;
							} else {
								$can_edit = 1;
							}
							$add_month_name = findmonth($sql_mntsal[0]['PAYMONT']).", ".$sql_mntsal[0]['PAYYEAR'];
							if($slt_approval_listings == '807') { // 807 - STAFF MONTHLY SALARY
								$bank_cash = $sql_mntsal[0]['CASHPART'];
							} elseif($slt_approval_listings == '777') { // 777 - BANK SALARY CREDIT
								$bank_cash = $sql_mntsal[0]['BANKPART'];
							}
						}

						$sql_tarno = select_query_json("select * from budget_planner_yearly
															where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep."", "Centra", 'TCS');
						if(count($sql_tarno) > 0) { ?>
							<div>
							<table style='clear:both; float:left; width:90%;'>
							<tr><td><table class="monthyr_wrap" style='width:100%;'>
							<?
							$sql_extr = select_query_json("select sum(nvl(APPRVAL, 0)) aprvlu from approval_budget_planner_temp
																	where BRNCODE=".$brnc." and APRYEAR = '".$nxtyr."' and EXPSRNO = ".$core_dep." and deleted = 'N' and USEDVAL = '".$slt_submission."'", "Centra", 'TCS'); //  ATYCODE = USEDVAL
							if(count($sql_extr) > 0) {
								$sql_yrlyttl = select_query_json("select sum(distinct nvl(sm.BUDVALUE, 0)) BUDVALUE, (sum(distinct nvl(sm.APPVALUE, 0)) + sum(distinct nvl(tm.APPRVAL, 0))) APPVALUE,
																			(sum(distinct nvl(sm.BUDVALUE, 0)) - sum(distinct nvl(sm.APPVALUE, 0)) - sum(distinct nvl(tm.APPRVAL, 0))) pendingvalue
																		from budget_planner_head_sum sm, approval_budget_planner_temp tm
																		where sm.BUDYEAR=tm.APRYEAR AND sm.BRNCODE=tm.BRNCODE AND sm.EXPSRNO=tm.EXPSRNO and tm.deleted = 'N' and sm.BRNCODE=".$brnc." and
																			sm.BUDYEAR = '".$nxtyr."' and sm.EXPSRNO = ".$core_dep." and USEDVAL = '".$slt_submission."'", "Centra", 'TCS'); //  ATYCODE = USEDVAL
							} else {
								$sql_yrlyttl = select_query_json("select sum(nvl(BUDVALUE, 0)) BUDVALUE, sum(nvl(APPVALUE, 0)) APPVALUE, (sum(nvl(BUDVALUE, 0)) - sum(nvl(APPVALUE, 0))) pendingvalue
																		from budget_planner_head_sum where BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and EXPSRNO = ".$core_dep."", "Centra", 'TCS');
							}

							if($slt_submission == 7) {
								$ttl_lock = 10000000000000;
							} else {
								if($sql_yrlyttl[0]['PENDINGVALUE'] == '' or $sql_yrlyttl[0]['PENDINGVALUE'] <= 0) {
									$ttl_lock = 0;
								} else {
									$ttl_lock = $sql_yrlyttl[0]['PENDINGVALUE'];
								}
							}
							// $ttl_lock = 1;

							if($slt_submission != 7) { ?>
								<tr>
									<td colspan="3" style='text-align: center; font-weight:bold;'>
										Budget Value : <? if($sql_yrlyttl[0]['PENDINGVALUE'] == '' or $sql_yrlyttl[0]['PENDINGVALUE'] <= 0) { echo "0"; } else { echo moneyFormatIndia($sql_yrlyttl[0]['PENDINGVALUE']); } ?>
									</td>
								</tr>
							<? }

							if($action != 'show_budgetvalue') {
								if($add_month != '') { ?>
									<tr>
										<td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$sql_mntsal[0]['PAYMONT']?>' class='form-control' value='<?=$sql_mntsal[0]['PAYMONT']?>,<?=$fdt[1]?>'><span><?=$add_month_name?></span> : </td>
										<td style='width:5%;'></td>
										<td style='width:40%;'><input type='text' tabindex='11' readonly="readonly" required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$sql_mntsal[0]['PAYMONT']?>' class='form-control ttlsum ttlsumrequired' value="<?=$bank_cash?>" onkeypress='enable_month(); return isNumber(event)' onKeyup='calculate_sum()' onblur="calculate_sum(); allow_zero(<?=$sql_mntsal[0]['PAYMONT']?>, this.value, '<?=$bank_cash?>');" maxlength='10' style='margin: 2px 0px;'></td>
										<td style='width:30%; text-align:center; font-weight:bold;'><input type='hidden' id='ttl_lock_<?=$sql_mntsal[0]['PAYMONT']?>' name='ttl_locks[]' value='<? echo $bank_cash; ?>'></td>
									</tr>
							<? 	}

							if($fdt[1] == $tdt[1]) {
								for($i = $fdt[0]; $i <= $tdt[0]; $i++) { $ivl++;
									if($i < 10 && strlen($i) == 2) {
										$i = ltrim($i, '0');
									}
									$ii = findmonth($i);
									if($ivl == 1) {
										$fstmnth = $i.",".$fdt[1];
									}

									$sql_yrly = select_query_json("select * from budget_planner_yearly
																			where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and BUDMONTH in (".$i.") and TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep."
																			order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS');
                                                     
									$sql_yr = select_query_json("select * from budget_planner_branch where taryear+1=".substr($fdt[1], 2)." and tarmont=".$i." and tarnumb=".$target_no." and BRNCODE = ".$slt_branch." and DEPCODE = ".$deptid."", "Centra", 'TCS');
									if(count($sql_yr) == 0) {
										// Insert budget_planner_branch
										$tbl_docs = "budget_planner_branch";  
										$field_docs['BRNCODE'] = $slt_branch;
										$field_docs['TARYEAR'] = substr(($fdt[1] - 1), 2);
										$field_docs['TARMONT'] = $i;
										$field_docs['DEPCODE'] = $deptid;
										$field_docs['TARVALU'] = '0';
										$field_docs['TARNUMB'] = $target_no;
										$field_docs['PURTVAL'] = '0';
										$field_docs['RESRVAL'] = '0';
										$field_docs['EXTRVAL'] = '0';
										$field_docs['TOTBVAL'] = '0';
										
										$field_docs['DEDVAL']  = '0';
										$insert_docs = insert_query($field_docs, $tbl_docs);
										// print_r($field_docs);
										// Insert budget_planner_branch
									}

									// echo $i.",".$fdt[1]."**".$sql_mntsal[0]['PAYMONT'].",".$fdt[1];
									if($i.",".$fdt[1] != $sql_mntsal[0]['PAYMONT'].",".$fdt[1]) { ?>
										<tr>
											<td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?>' class='form-control' value='<?=$i?>,<?=$fdt[1]?>'><span><?=$ii?>, <?=$fdt[1]?></span> : </td>
											<td style='width:5%;'></td>
											<td style='width:40%;'><input type='text' tabindex='11' <? if($can_edit == 0) { ?> readonly="readonly" <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?>' class='form-control ttlsum ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='enable_month(); return isNumber(event)' onKeyup='calculate_sum()' onblur="calculate_sum(); allow_zero(<?=$i?>, this.value, '<?=$sql_yrly[0]['BUDVALU']?>');" maxlength='10' style='margin: 2px 0px;'></td>
											<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?><input type='hidden' id='ttl_lock_<?=$i?>' name='ttl_locks[]' value='<? if($sql_yrlyttl[0]['PENDINGVALUE'] == '' or $sql_yrlyttl[0]['PENDINGVALUE'] <= 0) { echo "0"; } else { echo $sql_yrlyttl[0]['PENDINGVALUE']; } ?>'></td>
										</tr>
									<?
									}
								}
							} else {
								for($i = $fdt[0]; $i <= 12; $i++) { $ivl++;
									if($i < 10 && strlen($i) == 2) {
										$i = ltrim($i, '0');
									}
									$ii = findmonth($i);
									if($ivl == 1) {
										$fstmnth = $i+","+$fdt[1];
									}

									$sql_yrly = select_query_json("select * from budget_planner_yearly
																			where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and BUDMONTH in (".$i.") and TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep."
																			order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS');

									$sql_yr = select_query_json("select * from budget_planner_branch    
																		where taryear+1=".substr($fdt[1], 2)." and tarmont=".$i." and tarnumb=".$target_no." and BRNCODE = ".$slt_branch." and DEPCODE = ".$deptid."", "Centra", 'TCS');
									if(count($sql_yr) == 0) {
										// Insert budget_planner_branch
										$tbl_docs = "budget_planner_branch";
										$field_docs['BRNCODE'] = $slt_branch;
										$field_docs['TARYEAR'] = substr(($fdt[1] - 1), 2);
										$field_docs['TARMONT'] = $i;
										$field_docs['DEPCODE'] = $deptid;
										$field_docs['TARVALU'] = '0';
										$field_docs['TARNUMB'] = $target_no;
										$field_docs['PURTVAL'] = '0';
										$field_docs['RESRVAL'] = '0';
										$field_docs['EXTRVAL'] = '0';
										$field_docs['TOTBVAL'] = '0';
										$field_docs['DEDVAL']  = '0';
										$insert_docs = insert_query($field_docs, $tbl_docs);
										// print_r($field_docs);
										// Insert budget_planner_branch
									}

									// echo $i.",".$fdt[1]."##".$sql_mntsal[0]['PAYMONT'].",".$fdt[1];
									if($i.",".$fdt[1] != $sql_mntsal[0]['PAYMONT'].",".$fdt[1]) { ?>
										<tr>
											<td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?>' class='form-control' value='<?=$i?>,<?=$fdt[1]?>'><span><?=$ii?>, <?=$fdt[1]?></span> : </td>
											<td style='width:5%;'></td>
											<td style='width:40%;'><input type='text' tabindex='11' <? if($can_edit == 0) { ?> readonly="readonly" <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?>' class='form-control ttlsum ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='enable_month(); return isNumber(event)' onKeyup='calculate_sum()' onblur="calculate_sum(); allow_zero(<?=$i?>, this.value, '<?=$sql_yrly[0]['BUDVALU']?>');" maxlength='10' style='margin: 2px 0px;'></td>
											<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?><input type='hidden' id='ttl_lock_<?=$i?>' name='ttl_locks[]' value='<?=$sql_yrly[0]['BUDVALU']?>'></td>
										</tr>
									<?
									}
								}
								$lstmnth = ($i-1)+","+$fdt[1];

								for($i = 1; $i <= $tdt[0]; $i++) { $ivl++;
									if($i < 10 && strlen($i) == 2) {
										$i = ltrim($i, '0');
									}
									$ii = findmonth($i);
									if($ivl == 1) {
										$fstmnth = $i+","+$tdt[1];
									}

									$sql_yrly = select_query_json("select * from budget_planner_yearly
																			where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and BUDMONTH in (".$i.") and TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep."
																			order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS');
									// $ttl_lock += $sql_yrly[0]['BUDVALU'];
									$sql_yr = select_query_json("select * from budget_planner_branch where taryear+1=".substr($tdt[1], 2)." and tarmont=".$i." and tarnumb=".$target_no." and BRNCODE = ".$slt_branch." and DEPCODE = ".$deptid."", "Centra", 'TCS');
									if(count($sql_yr) == 0) {
										// Insert budget_planner_branch
										$tbl_docs = "budget_planner_branch";
										$field_docs['BRNCODE'] = $slt_branch;
										$field_docs['TARYEAR'] = substr(($tdt[1] - 1), 2);
										$field_docs['TARMONT'] = $i;
										$field_docs['DEPCODE'] = $deptid;
										$field_docs['TARVALU'] = '0';
										$field_docs['TARNUMB'] = $target_no;
										$field_docs['PURTVAL'] = '0';
										$field_docs['RESRVAL'] = '0';
										$field_docs['EXTRVAL'] = '0';
										$field_docs['TOTBVAL'] = '0';
										$field_docs['DEDVAL']  = '0';
										$insert_docs = insert_query($field_docs, $tbl_docs);
										// print_r($field_docs);
										// Insert budget_planner_branch
									}

									// echo "<br>".$i.",".$fdt[1]."++".$sql_mntsal[0]['PAYMONT'].",".$fdt[1];
									if($i.",".$fdt[1] != $sql_mntsal[0]['PAYMONT'].",".$fdt[1]) { ?>
										<tr>
											<td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?>' class='form-control' value='<?=$i?>,<?=$tdt[1]?>'><span><?=$ii?>, <?=$tdt[1]?></span> : </td>
											<td style='width:5%;'></td>
											<td style='width:40%;'><input type='text' tabindex='11' <? if($can_edit == 0) { ?> readonly="readonly" <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?>' class='form-control ttlsum ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='enable_month(); return isNumber(event)' onKeyup='calculate_sum()' onblur="calculate_sum(); allow_zero(<?=$i?>, this.value, '<?=$sql_yrly[0]['BUDVALU']?>');" maxlength='10' style='margin: 2px 0px;'></td>
											<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?><input type='hidden' id='ttl_lock_<?=$i?>' name='ttl_locks[]' value='<?=$sql_yrly[0]['BUDVALU']?>'></td>
										</tr>
									<?
									}
								}
								$lstmnth = ($i-1)+","+$tdt[1];
							} ?>
							<tr><td colspan='2' style='width:40%; text-align:right; padding-right:10%; font-weight:bold;'>TOTAL : </td><td style='width:60%; font-weight:bold;'><span id='ttl_mntyr'><? /* if($slt_submission == 6) { echo $ttl_lock; } else { */ ?>0<? /* } */ ?></span></td></tr>
							</table></td></tr>
							</table>
							</div>

							<input type='hidden' id='frmdate' name='frmdate' value='<?=$minvl?>'>
							<input type='hidden' id='todate' name='todate' value='<?=$maxvl?>'>
							<input type='hidden' id='minvl' name='minvl' value='<?=$minvl?>'>
							<input type='hidden' id='maxvl' name='maxvl' value='<?=$maxvl?>'>
							<input type='hidden' id='fstmnth' name='fstmnth' value='<?=$fstmnth?>'>
							<input type='hidden' id='lstmnth' name='lstmnth' value='<?=$lstmnth?>'>
							<input type='hidden' id='hidapryear' name='hidapryear' value='<?=$nxtyr?>'>
							<input type='hidden' id='ttl_lock' name='ttl_lock' value='<?=$ttl_lock?>'>
							<input type='hidden' id='slry_status' name='slry_status' value='<?=$sql_mntsal[0]['STATUS']?>'>

							<? } else { ?>
								<input type='hidden' id='ttl_pndlock' name='ttl_pndlock' value='<?=$ttl_lock?>'>
							<? }
						} else {
							// If budget_planner_yearly values are empty, must insert here
							// Insert budget_planner_yearly
							$tbl_docs = "budget_planner_yearly";
							$field_docs['BUDYEAR'] 		= $nxtyr;
							$field_docs['BUDMONTH']		= date('m');
							$field_docs['BRNCODE'] 		= $brnc;
							$field_docs['DEPCODE'] 		= $depc;
							$field_docs['BUDVALU'] 		= '0';
							$field_docs['SALVAL_APX'] 	= '0';
							$field_docs['SALVAL_ACT'] 	= '0';
							$field_docs['REQVALU'] 		= '0';
							$field_docs['APPVALU'] 		= '0';
							$field_docs['PORVALU'] 		= '0';
							$field_docs['PAYVALU'] 		= '0';
							$field_docs['TARNUMB'] 		= $tarno;
							$field_docs['TARVALU'] 		= '0';
							$field_docs['RESVALU'] 		= '0';
							$field_docs['EXTVALU'] 		= '0';
							$field_docs['BUDVALU_APPX'] = '0';
							$field_docs['EXPSRNO'] 		= $core_dep;
							$field_docs['EXP_BUDGET'] 	= '0';

							$insert_docs = insert_query($field_docs, $tbl_docs);
							// print_r($field_docs);
							// Insert budget_planner_yearly
							echo "1";
						}
					?>
					</table>
				</td></tr>
				</table>
			</div>
			<div class='clear clear_both'></div>
		</div>
		</div>
		<div class='clear clear_both'>&nbsp;</div>
		<? 	}
			// Monthwise Budget - End
			break;



	case 100: // Staff Night Duty ?>
			<div id='' style="padding-left: 10px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						<input type="hidden" name="partint3" id="partint3" value="1">
						<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Employee" onclick="emp_night_add(1)"><span class="glyphicon glyphicon-plus"></span></button>
						<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Product" onclick="emp_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
		            <div class="col-sm-2 colheight"  style="padding: 0px;">PHOTO</div>
		            <div class="col-sm-2 colheight"  style="padding: 0px;">DESIGNATION</div>
		            <div class="col-sm-2 colheight"  style="padding: 0px;">DEPARTMENT</div>
	            	<div class="col-sm-2 colheight"  style="padding: 0px;overflow-wrap: break-word;">NATURE OF WORK</div>
	            	<div class="col-sm-1 colheight"  style="padding: 0px;">TOTAL WORKING HOURS</div>
	            </div>
		        
		     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;1</div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">	
						<div>
							<input type="text" name="empname[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" onBlur="addempnit(1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">
						<div id="photo_1"></div>	
						<input type="hidden" name="descode[]" id="descode_1" value="">
						<input type="hidden" name="esecode[]" id="esecode_1" value="">
						<input type="hidden" name="empsrno[]" id="empsrno_1" value="">
					</div>

		            <div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" required="required">
		    		</div>
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			 <input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" required="required" >
		    		</div>
		    		 
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			  <input type="text"  name="CURWORK[]" id="curwork_1" placeholder="NATURE OF WORK" data-toggle="tooltip" data-placement="top" title="NATURE OF WORK" class="form-control"   style=" text-transform: uppercase;height: 25px;" required="required" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">	
		    			  <input type="text"  name="TOTWORK[]" id="totwork_1" placeholder="WORKING HOURS" maxlength="3" data-toggle="tooltip" data-placement="top" title="WORKING HOURS" class="form-control"   style=" text-transform: uppercase;height: 25px;" onkeypress="return validateFloatKeyPress(this,event,1);" maxlength="5"  required="required" >
		    		</div>
		    		  
		        </div>
				</div>
			</div>
			<div class='clear clear_both'></div>
		</div>
		<div class='clear clear_both'></div>
		<?
		break;

	case 11:
			$nxtyr = $current_year[0]['PORYEAR'];
			$sql_yrlyttl = select_query_json("select sum(nvl(BUDVALUE, 0)) BUDVALUE, sum(nvl(APPVALUE, 0)) APPVALUE, (sum(nvl(BUDVALUE, 0)) - sum(nvl(APPVALUE, 0))) pendingvalue
				from budget_planner_head_sum where BRNCODE=".$slt_branch." and BUDYEAR = '".$nxtyr."' and EXPSRNO = ".$core_deptid."", "Centra", 'TCS');
			$ttl_lock = $sql_yrlyttl[0]['PENDINGVALUE'];
	?>
			<input type='hidden' id='ttl_lock' name='ttl_lock' value='<?=$ttl_lock?>'>
			<div id='' style="padding-right: 5px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						<input type="hidden" name="partint3" id="partint3" value="1">
						<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_mrg_gift(1)"><span class="glyphicon glyphicon-plus"></span></button>
						<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
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

		     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;1</div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">
						<div>
							<input type="text" name="empname[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addempgift(1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">

						</div>
						<input type="hidden" name="empsrno[]" id="empsrno_1" value="">
					</div>
					
		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">
		    			 <input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">
		    			 <input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>

		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">
		    			 <input type="text"  name="OWNGIFT[]" id="owngift_1" placeholder="GRAM" data-toggle="tooltip" data-placement="top" title="GIFT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">
		    			 <input type="text"  name="TRUSTAMT[]" id="trustamt_1" placeholder="TRUST AMOUNT" data-toggle="tooltip"  data-placement="top" title="TRUST AMOUNT" class="form-control ttlsum" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		        </div>
				</div>
			</div>
			<div class='clear clear_both'></div>
		</div>
		<div class='clear clear_both'></div>
		<?
		break;

	case 7 :
			?>
			<div id='' style="padding-right: 5px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						<input type="hidden" name="partint3" id="partint3" value="1">
						<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_des_change(1)"><span class="glyphicon glyphicon-plus"></span></button>
						<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
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
		            <div class="col-sm-3 colheight"  style="padding: 0px;">CHANGE <br> DESIGNATION</div>
		            <div class="col-sm-3 colheight"  style="padding: 0px;">CHANGE <br> DEPARTMENT</div>
		            <div class="col-sm-3 colheight"  style="padding: 0px;">REPORTING TO</div>
		        </div>

		     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;1</div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff(1,1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">

						</div>
						<input type="hidden"  name="EMPSRNO[]" id="empsrno_1" value="">
		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <!-- <label id="exp_1"></label> -->
		    			 <input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <!-- <label id="doj_1"></label> -->
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <!-- <label id="cbrn_1"></label> <br> -->
		    			  <input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">
		    			 <!-- <label id="cdep_1"></label> -->
		    			 <input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">
		    			 <!-- <label id="cdes_1"></label> -->
		    			 <input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>

		    		<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    		<?
		    		$table = "designation";
					if($slt_branch == '201' or $slt_branch == '202' or $slt_branch == '203' or $slt_branch == '204' or $slt_branch == '205' or $slt_branch =='206')
					{
						$table = "new_designation";
					}
					$sql_des = select_query_json( " select * from ".$table." emp where deleted='N' order by desname ", "Centra", 'TCS');
					?>
					<select class="form-control custom-select chosn" tabindex='1' autoFocus required
					name="NEWDES[]" id='newdes_1' data-toggle="tooltip" data-placement="top"    title="designation" >
					<? foreach($sql_des as $des)
					{
					$desval = preg_replace('/[0-9]+/', '', $des['DESNAME']);
					?>
					<option value="<?=$desval?>"><?=$desval?></option>
					<?}?>
					</select>
		    		</div>
		    		<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
			    		<?
			    		$table = "empsection";
						if($slt_branch == '201' or $slt_branch == '202' or $slt_branch == '203' or $slt_branch == '204' or $slt_branch == '205' or $slt_branch =='206')
						{	$table = "new_empsection";}
						$sql_dep = select_query_json( " select * from ".$table." emp where deleted='N' order by esename ", "Centra", 'TCS');?>

						<select class="form-control custom-select chosn" tabindex='1' autoFocus required name='NEWDEP[]' id='newdep_<?=$id?>' data-toggle="tooltip" data-placement="top"    title="Department">
						<? foreach($sql_dep as $dep)
						{
						$dept = preg_replace('/[0-9]+/', '', $dep['ESENAME']);
						?>
						<option value="<?=$dept?>"><?=$dept?></option>
						<?}?>
						</select>
		    		</div>
		    		<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    		<input type="text" name="REPORTTO[]" id="txt_reportto_1" required="required" maxlength="100" placeholder="Reporting To" title="Reporting To" class="form-control" data-toggle="tooltip"   data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
		    		</div>
		    	</div>
				</div>
			</div>
			<div class='clear clear_both'></div>
		</div>
		<div class='clear clear_both'></div>

			<?
			break;

	case 8 :
			?>
			<div id='' style="padding-right: 5px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						<input type="hidden" name="partint3" id="partint3" value="1">
						<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_dept_change(1)"><span class="glyphicon glyphicon-plus"></span></button>
						<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
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

		     	<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;1</div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff(1,1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">

						</div>
						<input type="hidden"  name="EMPSRNO[]" id="empsrno_1" value="">
     		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <!-- <label id="exp_1"></label> -->
		    			 <input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <!-- <label id="doj_1"></label> -->
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <!-- <label id="cbrn_1"></label> <br> -->
		    			 <input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">
		    			 <!-- <label id="cdep_1"></label> -->
		    			 <input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;">
		    			 <!-- <label id="cdes_1"></label> -->
		    			 <input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>

		    		<div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    		<?
		    		$table = "empsection";
					if($slt_branch == '201' or $slt_branch == '202' or $slt_branch == '203' or $slt_branch == '204' or $slt_branch == '205' or $slt_branch =='206')
					{	$table = "new_empsection";}
					$sql_dep = select_query_json( " select * from ".$table." emp where deleted='N' order by esename ", "Centra", 'TCS');?>

					<select class="form-control custom-select chosn" tabindex='1' autoFocus required name='NEWDEP[]' id='newdep_<?=$id?>' data-toggle="tooltip" data-placement="top"    title="Department">
					<? foreach($sql_dep as $dep)
					{
					$dept = preg_replace('/[0-9]+/', '', $dep['ESENAME']);
					?>
					<option value="<?=$dept?>"><?=$dept?></option>
					<?}?>
					</select>
		    		</div>
		    	</div>
				</div>
			</div>
			<div class='clear clear_both'></div>
		</div>
		<div class='clear clear_both'></div>

			<?
			break;

		case 9:
			?>
			<div id='' style="padding-right: 5px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						<input type="hidden" name="partint3" id="partint3" value="1">
						<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Employee" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_increment(1)"><span class="glyphicon glyphicon-plus"></span></button>
						<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Employee" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
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

		        <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;1</div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff(1,2)" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">

						</div>
						<input type="hidden"  name="EMPSRNO[]" id="empsrno_1" value="">
		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			<input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
					</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">
		    			<input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
	    			</div>
	    			<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;overflow-wrap: break-word;overflow-wrap: break-word;">
	    				<input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
	    			</div>
	    			<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
	    				<input type="text"  name="CURBAS[]" id="curbas_1" placeholder="BASIC" data-toggle="tooltip" data-placement="top" title="BASIC" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
	    				<input type="text"  name="INCAMT[]" id="incamt_1" placeholder="INCREMENT AMOUNT" data-toggle="tooltip" data-placement="top" title="INCREMENT AMOUNT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		 <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			<input type="text"  name="NEWBAS[]" id="newbas_1" placeholder="NEW BASIC" data-toggle="tooltip" data-placement="top" title="NEW BASIC" class="form-control" style=" text-transform: uppercase;height: 25px;" onblur="newbasic(1);">
		    		</div>
				</div>
				</div>
			</div>
			<div class='clear clear_both'></div>
		</div>
		<div class='clear clear_both'></div>

			<?
			break;

	case 6 :
			?>
			<div id='' style="padding-right: 5px; text-align: center;">
			<div class="parts3 fair_border">
				<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
					<div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
						<input type="hidden" name="partint3" id="partint3" value="1">
						<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_brn_transfer(1)"><span class="glyphicon glyphicon-plus"></span></button>
						<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
					</div>
					<div class="col-sm-2 colheight"  style="padding: 0px;">EMPLOYEE</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">PHOTO</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">EXP</div>
		            <div class="col-sm-1 colheight"  style="padding: 0px;">DOJ</div>
		            <div class="col-sm-2 colheight"  style="padding: 0px;">CURRENT</div>
		            <div class="col-sm-5 colheight"  style="padding: 0px;">
		            	<div class="col-sm-12 colheight"  style="padding: 0px;">CHANGE</div>
            			<div class="col-sm-2 colheight"  style="padding: 0px;">BRANCH </div>
        	    		<div class="col-sm-5 colheight"  style="padding: 0px;">DEPARTMENT</div>
	            		<div class="col-sm-5 colheight"  style="padding: 0px;">DESIGNATION</div>
		            </div>
		        </div>

				<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div class="fg-line">&nbsp;1</div>
					</div>

					<div class="col-sm-2 colheight" style="padding: 1px 0px;">
						<div>
							<input type="text" name="EMPNAME[]" id="txt_staffcode_1" required="required" maxlength="100" placeholder="Employee Code / Name" title="Employee Code / Name" class="form-control find_empcode" data-toggle="tooltip"   data-placement="top" onBlur="addstaff(1,1)" style=" text-transform: uppercase; padding: 2px; height: 25px;">
						</div>
						<div style="clear: both;"></div>
					</div>

					<div class="col-sm-1 colheight" style="padding: 1px 0px;">
						<div id="photo_1">

						</div>
						<input type="hidden"  name="EMPSRNO[]" id="empsrno_1" value="">
		    		</div>

		            <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <!-- <label id="exp_1"></label> -->
		    			 <input type="text"  name="CUREXP[]" id="curexp_1" placeholder="EXPERIENCE" data-toggle="tooltip" data-placement="top" title="EXPERIENCE" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <!-- <label id="doj_1"></label> -->
		    			 <input type="text"  name="DATEOFJOIN[]" id="curdoj_1" placeholder="DOJ" data-toggle="tooltip" data-placement="top" title="DOJ" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    		</div>
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			<!-- <label style="font-weight:bolder;">BRANCH : </label><label id="cbrn_1"></label><br>
		    			<label style="font-weight:bolder;">DEPT : </label><label id="cdep_1"></label> <br>
		    			<label style="font-weight:bolder;">DESIG : </label><label id="cdes_1"></label> <br> -->

		    			<input type="text"  name="CURBRN[]" id="curbrn_1" placeholder="BRANCH" data-toggle="tooltip" data-placement="top" title="BRANCH" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    			<input type="text"  name="CURDEP[]" id="curdep_1" placeholder="DEPARTMENT" data-toggle="tooltip" data-placement="top" title="DEPARTMENT" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >
		    			<input type="text"  name="CURDES[]" id="curdes_1" placeholder="DESIGNATION" data-toggle="tooltip" data-placement="top" title="DESIGNATION" class="form-control" readonly="readonly" style=" text-transform: uppercase;height: 25px;" >

		    		</div>

		    		<div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
		    			 <select class="form-control custom-select chosn" tabindex='1' autoFocus required name='NEWBRN[]' id='newbrn_1' data-toggle="tooltip" data-placement="top"    title="Branch" onchange="getdept(1);">
		    			 	<option value=""> BRANCH</option>
								<? 	if($_SESSION['rights'] == 1) {
										$sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn
																					where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K') and (brn.brncode in (select distinct brncode
																						from budget_planner_head_sum) or brn.brncode in (109,114,117,119))
																					order by brn.BRNCODE","Centra","TCS"); // 108 - TRY Airport Not available
									} elseif(count($allow_branch) <= 5) {
										$sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn
																					where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K') and brn.brncode in (".$_SESSION['tcs_allowed_branch'].")
																						and (brn.brncode in (select distinct brncode from budget_planner_head_sum)
																						or brn.brncode in (109,114,117,119))
																					order by brn.BRNCODE","Centra","TCS"); // 108 - TRY Airport Not available
									} else {
										$sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn
																					where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K') and (brn.brncode in (select distinct brncode
																						from budget_planner_head_sum) or brn.brncode in (109,114,117,119))
																					order by brn.BRNCODE","Centra","TCS"); // 108 - TRY Airport Not available
									}
									for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
										<option value='<?=$sql_project[$project_i]['BRANCH']?>'><?=$sql_project[$project_i]['BRANCH']?></option>
								<? } ?>
								</select>
		    		</div>
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
					<div class="col-sm-2 colheight"  style="padding:1px opx; text-align: center; padding-left:  2px;">
		    		<div id = "staff_dept_1"></div>
		    		</div>
		    		<div class="col-sm-2 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
	    				<div id = "staff_des_1"></div>
		    		</div>
				</div>
				</div>
			</div>
			<div class='clear clear_both'></div>
		</div>
		<div class='clear clear_both'></div>

		<?
		break;
		// This is table branch add_dynamic_product_code
		case 14 :
			?>
				<table class="table table-bordered">
			    <thead>
			      <tr style="background:#666666 !important">
			        <th class="text-center">S.No</th>
							<th class="text-center">BRANCH CODE</th>
			        <th class="text-center">BRANCH NAME</th>
							<th class="text-center">NOUMBER OF EMPLOYEE</th>
			        <th class="text-center">VALUE</th>
			      </tr>
			    </thead>
			    <tbody class="text-center">
						<?// Write the php code
						$sql_project_branch = select_query_json("SELECT BRNCODE , BRNNAME, NICNAME from approval_branch_list where DELETED = 'N' order by BRNCODE","Centra","TCS");
						for($project_i = 0; $project_i < count($sql_project_branch); $project_i++) {?>
								<tr class="active">
									<td><?echo $project_i + 1;?></td>
                                     
									<td><?=$sql_project_branch[$project_i]['BRNCODE']?><input type="hidden" class="text-center form-control" name="txtesi_brncode[]" value="<?=$sql_project_branch[$project_i]['BRNCODE']?>" placeholder="" style="border:none;background: #f5f5f5 !important;color:#000"/></td>

					        <td><?=$sql_project_branch[$project_i]['BRNNAME']?></td>
									<td>
										<input type="text" class="form-control" onkeyup="validateEsi_emp_<?echo $project_i + 1;?>();" id="txtesi_no_emp_<?echo $project_i;?>" name="txtesi_no_emp[]" placeholder="NOUMBER OF EMPLOYEE"/>
									</td>
					        <td><input type="text" class="form-control esi_total" onkeyup="validateEsi_val_<?echo $project_i + 1;?>();" id="txtesi_value_<?echo $project_i;?>"  name="txtesi_value[]" onblur="calculate_sum_total('<?echo $project_i;?>' , 'esi_total')" placeholder="VALUE" /></td>
					      </tr>
								<script>
			            function validateEsi_val_<?echo $project_i + 1;?>()
			              {
			                var maintainplus = '';
											var numval = $("#txtesi_value_<?echo $project_i;?>").val();
											//alert(numval);
			                //var numval = txtesi_value[<?//echo $project_i;?>].value
			                if ( numval.charAt(0)=='+' )
			                {
			                  var maintainplus = '';
			                }
			                curphonevar = numval.replace(/[\\A-Za-z!"£$%^&\,*+_={};:'@#~,.Š\/<>?|`¬\]\[]/g,'');
			                $("#txtesi_value_<?echo $project_i;?>").val(maintainplus + curphonevar);
			                var maintainplus = '';
			                $("#txtesi_value_<?echo $project_i;?>").val().focus;
			              }

										function validateEsi_emp_<?echo $project_i + 1;?>()
				              {
				                var maintainplus = '';
												var numval = $("#txtesi_no_emp_<?echo $project_i;?>").val();
				                if ( numval.charAt(0)=='+' )
				                {
				                  var maintainplus = '';
				                }
				                curphonevar = numval.replace(/[\\A-Za-z!"£$%^&\,*+_={};:'@#~,.Š\/<>?|`¬\]\[]/g,'');
				                $("#txtesi_no_emp_<?echo $project_i;?>").val(maintainplus + curphonevar);
				                var maintainplus = '';
				                $("#txtesi_no_emp_<?echo $project_i;?>").val().focus;
				              }
			          </script>
						<? } ?>


			    </tbody>
			<div class='clear clear_both'></div>
		</div>
		<div class='clear clear_both'></div>

		<?
			break;
	// New Product Code Create Template
	case 12 :
		?>
		<div id='' style="padding-right: 5px; text-align: center;">
		<div class="parts3 fair_border">
			<div class="row" style="margin-right: -5px; text-transform: uppercase; line-height: 25px; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; text-align: center; font-weight: bold;">
				NEW PRODUCT CODE CREATE
			</div>

			<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
				<div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">EXPENSE HEAD : </div>
	            <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
	            	<select class="form-control" required name='txtdynamic_prd_exphead' id='txtdynamic_prd_exphead' data-toggle="tooltip" data-placement="top" data-original-title="EXPENSE HEAD">
	                    <option value='ORIGINAL'>ORIGINAL</option>
	                    <option value='RENEWAL'>RENEWAL</option>
	                </select>
	            </div>
	            <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">RESPONSIBLE PERSON : <input type="hidden" name="txtdynamic_prd_resperson" id="txtdynamic_prd_resperson" required="required" maxlength="100" placeholder="RESPONSIBLE PERSON" title="RESPONSIBLE PERSON" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;"></div>
	            <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;" id='txtdynamic_prd_respersonid'></div>
	        </div>

			<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
				<div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">DEPARTMENT NAME : </div>
	            <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
	            	<select class="form-control" required name='txtdynamic_prd_deptname' id='txtdynamic_prd_deptname' data-toggle="tooltip" data-placement="top" data-original-title="DEPARTMENT NAME">
	                    <option value='ORIGINAL'>ORIGINAL</option>
	                    <option value='RENEWAL'>RENEWAL</option>
	                </select>
	            </div>
	            <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">TARGET NO : <input type="hidden" name="txtdynamic_prd_tarnumb" id="txtdynamic_prd_tarnumb" required="required" maxlength="100" placeholder="TARGET NO" title="TARGET NO" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;"></div>
	            <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;" id='txtdynamic_prd_tarnumbid'></div>
	        </div>

			<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
				<div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">APPROVAL SUBJECT : </div>
	            <div class="col-sm-10 colheight" style="padding: 0px; line-height: 30px;">
	            	<select class="form-control" required name='txtdynamic_prd_aprsubject' id='txtdynamic_prd_aprsubject' data-toggle="tooltip" data-placement="top" data-original-title="APPROVAL SUBJECT">
	                    <option value='ORIGINAL'>ORIGINAL</option>
	                    <option value='RENEWAL'>RENEWAL</option>
	                </select>
	            </div>
	        </div>

			<div class="row" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
				<div class="col-sm-1 colheight" style="padding: 0px; border-top-left-radius:5px;">
					<input type="hidden" name="partint3" id="partint3" value="1">
					<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="add_dynamic_product_code(1)"><span class="glyphicon glyphicon-plus"></span></button>
					<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Product" style="padding: 2px 5px; margin-right: 0px !important;" onclick="emp_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
				</div>
				<div class="col-sm-1 colheight"  style="padding: 0px;">PRODUCT CODE</div>
	            <div class="col-sm-2 colheight"  style="padding: 0px;">PRODUCT NAME</div>
	            <div class="col-sm-1 colheight"  style="padding: 0px;">SUB PRODUCT CODE</div>
	            <div class="col-sm-2 colheight"  style="padding: 0px;">SUB PRODUCT NAME</div>
	            <div class="col-sm-1 colheight"  style="padding: 0px;">SUB PRODUCT HSN CODE</div>
	            <div class="col-sm-1 colheight"  style="padding: 0px;">SUB PRODUCT CGST %</div>
	            <div class="col-sm-1 colheight"  style="padding: 0px;">SUB PRODUCT SGST %</div>
	            <div class="col-sm-1 colheight"  style="padding: 0px;">SUB PRODUCT IGST %</div>
	            <div class="col-sm-1 colheight"  style="padding: 0px;">UNIT</div>
	        </div>

			<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
				<div class="col-sm-1 colheight" style="padding: 1px 0px;">
					<div class="fg-line">&nbsp;1</div>
				</div>
				<div class="col-sm-1 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_prdcode[]" id="txtdynamic_prd_prdcode_1" required="required" maxlength="100" placeholder="PRODUCT CODE" title="PRODUCT CODE" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>
				<div class="col-sm-2 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_prdname[]" id="txtdynamic_prd_prdname_1" required="required" maxlength="100" placeholder="PRODUCT NAME" title="PRODUCT NAME" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>
				<div class="col-sm-1 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_subprdcode[]" id="txtdynamic_prd_subprdcode_1" required="required" maxlength="100" placeholder="SUB PRODUCT CODE" title="SUB PRODUCT CODE" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>
				<div class="col-sm-2 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_subprdname[]" id="txtdynamic_prd_subprdname_1" required="required" maxlength="100" placeholder="SUB PRODUCT NAME" title="SUB PRODUCT NAME" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>

				<div class="col-sm-1 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_subprdhsn[]" id="txtdynamic_prd_subprdhsn_1" required="required" maxlength="100" placeholder="SUB PRODUCT HSN CODE" title="SUB PRODUCT HSN CODE" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>
				<div class="col-sm-1 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_subprdcgst[]" id="txtdynamic_prd_subprdcgst_1" required="required" maxlength="100" placeholder="SUB PRODUCT CGST %" title="SUB PRODUCT CGST %" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>
				<div class="col-sm-1 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_subprdsgst[]" id="txtdynamic_prd_subprdsgst_1" required="required" maxlength="100" placeholder="SUB PRODUCT SGST %" title="SUB PRODUCT SGST %" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>
				<div class="col-sm-1 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_subprdigst[]" id="txtdynamic_prd_subprdigst_1" required="required" maxlength="100" placeholder="SUB PRODUCT IGST %" title="SUB PRODUCT IGST %" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>
				<div class="col-sm-1 colheight" style="padding: 1px 0px;">
					<input type="text" name="txtdynamic_prd_subprdunit[]" id="txtdynamic_prd_subprdunit_1" required="required" maxlength="100" placeholder="UNIT" title="UNIT" class="form-control" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px; height: 25px;">
				</div>
			</div>
			</div>
		</div>
		<div class='clear clear_both'></div>
	</div>
	<div class='clear clear_both'></div>

	<?
		break;
	// New Product Code Create Template

	// New / Existing Policy Approval
	
	
	case 13 : ?>
		 <form>
			<!--<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data">    -->
					<div class="page-content-wrap">

						<div class="row">
						
							<div class="col-md-12">

								<form class="form-horizontal">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><strong>POLICY APPROVAL</strong></h3>
										<ul class="panel-controls">
											<li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
										</ul>
									</div>


									<div class="panel-body">


										<div class="row">
											
												
											   <div class="form-group">
													<label class="col-md-2 control-label" >POLICY SUBJECT<span style='color:red'>*</span></label>
													<div class="col-md-10"  >
														<select class="form-control " autofocus tabindex='1' required name='txt_dynamic_subject' id='txtdynamic_subject' " data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
														
														<?  $sql_project = select_query_json("select * from approval_policy_master where DELETED = 'N' order by aplcysr", "Centra", 'TCS');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                                <option value='<?=$sql_project[$project_i]['APLCYCD']?>' <? if($sql_reqid[0]['APLCYCD'] == $sql_project[$project_i]['APLCYCD']) { ?> selected <? } ?>><?=$sql_project[$project_i]['APLCYNM']?></option>
                                                        <? } ?>
                                                    </select>
														<span class="help-block">SELECT POLICY</span>
													</div>
												</div>
												<!-- priority filed drop down -->
												<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">EFFECTIVE DATE<span style='color:red'>*</span></label>
													<div class="col-md-9 col-xs-12">
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]['IMDUEDT'];
                                                           } else { ?>
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="target_date" id="tar_date" autocomplete="off" readonly="readonly" maxlength="11" tabindex="5" value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder="From Date" title=""  >
                                                        <? } ?>
																</div>
												</div>
												<!-- tilte text feild -->
												<div class="form-group">
                                                    <label class="col-md-3 control-label">VALID UPTO<span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]['IMDUEDT'];
                                                           } else { ?>
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="dued_date" id="due_date" autocomplete="off" readonly="readonly" maxlength="11" tabindex="5" value='' data-toggle="tooltip" data-placement="top" placeholder="To Date" title=""  >
                                                        <? } ?>
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">APPROVAL DATE<span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]['IMDUEDT'];
                                                           } else { ?>
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="appd_date" id="app_date" autocomplete="off" readonly="readonly" maxlength="11" tabindex="5" value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder="From Date" title=""  >
                                                        <? } ?>
                                                    </div>
                                                </div>
												<div class="form-group">
                            <label class="col-md-3 control-label">USERLIST</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  
									<select  class="form-control "  tabindex='6' style="text-transform: uppercase;" required name='txt_dynamic_userlist' id='txtdynamic_userlist' data-toggle="tooltip" data-placement="top" tabindex="10"> data-original-title="USER LIST"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
                                                <option>--Select one--</option>												                                                              
                                                                 <?  $sql_emp = select_query_json("select emp.EMPSRNO, emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, des.DESNAME
                                                                                                  from employee_office emp, empsection sec, designation des
                                                                                                  where  emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.EMPCODE > 1000 or emp.EMPCODE in (1, 2, 3,4,5))
                                                                                                 order by EMPCODE", "Centra", 'TCS');                                                                                                                                                                                                                                                                                                                  
                                                                                                        
																for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									<option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME']." - ". $sql_emp[$sql_emp_i]['DESNAME']." - ".  $sql_emp[$sql_emp_i]['ESENAME'].""?></option>
		
                                                        <?  } ?>					                								
							                 </select>					    
                            </div>
                        </div>
												<div class="form-group">
													<label class="col-md-3 control-label" id='attachment'>DESK PROCEDURE</label>
													<div class="col-md-9 col-xs-12">
														<div><input type="file" placeholder="" tabindex='8' onblur="find_tags();" class="form-control fileselect" name='attachments[]' id='attachments' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="QUOTATION OR ESTIMATE IN SUPPLIER LETTER PAD"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
														<div id="attachments_detail">
														<a ></a>
														</div>

                                                        <div class="tags_clear"></div>
													</div>
												</div>
												
												<div class="form-group">
                                                    <label class="col-md-3 control-label">POLICY DOCUMENTS<span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txt_dynamic_policy_docs' id='txtdynamic_policy_docs' data-toggle="tooltip" data-placement="top" data-original-title="POLICY DOCUMENTS" value=''>
                                                    </div>
                                                </div>
												</DIV>



<!--fix me-->
											<div class="col-md-6">
												<!-- core drop down -->
												<div class="form-group">
													<label class="col-md-3 control-label">POLICY TYPE</label>
													<div class="col-md-9">
														<select class="form-control policy_approval_required" required name='txtdynamic_policy_type' id='txt_dynamic_policy_type' data-toggle="tooltip" data-placement="top" data-original-title="POLICY TYPE">
                                                        <option value='ORIGINAL'>ORIGINAL</option>
                                                        <option value='RENEWAL'>RENEWAL</option>
                                                    </select>
													</div>

												</div>
												<!--assign member-->

												<div class="form-group">
                            <label class="col-md-3 control-label">CREATOR ECNO/NAME</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txt_dynamic_creator' id='txtdynamic_creator' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPNAME']." - ".$sql_emp[0]['EMPCODE']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												   <option>--Select User--</option>
												  
												    <? $sql_emp = select_query_json("select  EMPCODE,EMPNAME from employee_office  where   (EMPCODE > 1000 or EMPCODE in (1, 2, 3,4,5))                                                                                   
                                                                                    order by EMPCODE", "Centra", 'TCS');                                                                                                                                                                                                                                                                                                                  

												   for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									            <option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		
                                                        <?  } ?>
							                </select>
                            </div>
                        </div>

												<!-- Tag layout -->
												<div class="form-group">
                            <label class="col-md-3 control-label">CO-ORDINATOR ECNO/NAME<label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txt_dynamic_coordinator' id='txtdynamic_coordinator' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPNAME']." - ".$sql_emp[0]['EMPCODE']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												   <option>--Select User--</option>
												  
												    <? $sql_emp = select_query_json("select  EMPCODE,EMPNAME from employee_office  where   (EMPCODE > 1000 or EMPCODE in (1, 2, 3,4,5))                                                                                   
                                                                                    order by EMPCODE", "Centra", 'TCS');                                                                                                                                                                                                                                                                                                                  

												   for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									            <option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		
                                                        <?  } ?>
							                </select>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-3 control-label">ASSIST BY ECNO/NAME</label> 
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txt_dynamic_asistby' id='txtdynamic_asistby' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPNAME']." - ".$sql_emp[0]['EMPCODE']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												   <option>--Select User--</option>
												  
												    <? $sql_emp = select_query_json("select  EMPCODE,EMPNAME from employee_office  where   (EMPCODE > 1000 or EMPCODE in (1, 2, 3,4,5))                                                                                   
                                                                                    order by EMPCODE", "Centra", 'TCS');                                                                                                                                                                                                                                                                                                                  

												   for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									            <option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		
                                                        <?  } ?>
							                </select>
                            </div>
                        </div>
										</div>
										<div class="tags_clear"></div>
										<div class="row">
										<div class="col-md-6"></div>
										<div class="col-md-6">
												<!-- topcore drop down ValidateSingleInput(this, 'all'); -->
											<div class="row">
											<div class="col-md-12">
											   <div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-9" name="tags" id="id_tags_generation">
													</div>
												</div>
                                            </div>
										</div></div>
										<div class="tags_clear"></div>
											<div class="row">
												<label class="col-md-3 control-label" style="text-align: left;">Requirement Details <span style='color:red'>*</span> : </label>
												<div class="tags_clear height10px"></div>
												<div class="col-md-12">
													<textarea name="FCKeditor2" id="FCKeditor2" tabindex='14' onblur="find_tags();"></textarea>
                                                     <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>
                                                      <script type="text/javascript">
													  var ckedit=CKEDITOR.replace("FCKeditor2",
                                                    {
                                                        height:"450", width:"100%",
                                                        filebrowserBrowseUrl : '/ckeditor/ckfinder/ckfinder.html',
                                                        filebrowserImageBrowseUrl : '/ckeditor/ckfinder/ckfinder.html?Type=Images',
                                                        filebrowserUploadUrl : '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                        filebrowserImageUploadUrl : '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
                                                    });
													</script>
													 
                                                 <!-- Validate Form using Jquery -->
    <script src="js/form-validation.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>
    <script src="js/jquery.filer.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/jquery-customselect.js"></script>
    <script type="text/javascript" src="js/angular-route.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <script type="text/javascript" src="js/angular-route-segment.min.js"></script>
	<link href="css/fSelect.css" rel="stylesheet">
  <script src="js/fSelect.js"></script>
	<script type="text/javascript">
                                                   
	$('#tar_date').Zebra_DatePicker({
      direction: false,
      format: 'd-M-yy'
    });
	$('#due_date').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+1 days")))?>', false],
      format: 'd-M-yy'
    });
	$('#app_date').Zebra_DatePicker({
      direction: false,
      format: 'd-M-yy'
    });

    $(document).ready(function() {
        $(".chosn").customselect();
        $("#load_page").fadeOut("slow");
        find_checklist();
		getcore();

	$('#txtsaaign').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   name_startsWith: request.term,
				   // topcr: $('#slt_topocore').val(),
				   // subcr: $('#slt_subcore').val(),
				   type: 'employee'
				},
				success: function( data ) {
					if(data == 'No User Available in this Top core and Sub Core') {
						$('#txt_workintiator').val('');
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
    });
	
	/*function nsubmit(){
		alert("uable to call");
			var vurl = "nancy/inset.php";
		//var vurl = "ranjani/insert_req.php"; // the script where you handle the form input.
		alert($("#attachments").files);
		$.ajax({
            type: "POST",
            url: vurl,
			data:{
				'txtdynamic_subject':$("#txtdynamic_subject").val(),
				'txtdynamic_policy_type':$("#txtdynamic_policy_type").val(),
				
				'FCKeditor1':CKEDITOR.instances.FCKeditor1.getData(),
				'attachments':$("#attachments").val(),
			},
			dataType:'html',
            success: function(data1) {
               alert(data1);
			   
            },
			error: function(response, status, error)
			{		alert(error);
					//alert(response);
					//alert(status);
			}
			});

	}*/

	function getcore(){
		//alert("uable to call");
		var vurl = "ranjani/getcoredrop.php";
		//var vurl = "ranjani/insert_req.php"; // the script where you handle the form input.
		//alert($("#attachments").files);
		$.ajax({
            type: "POST",
            url: vurl,
			data:{
				'topcore':$("#txtTopcore").val()
			},
			dataType:'html',
            success: function(data1) {
               $('#get_core').html(data1);
            },
			error: function(response, status, error)
			{		alert(error);
					//alert(response);
					//alert(status);
			}
			});
	}

	
	
	                            $('#txtdynamic_userlist').fSelect(); 
								
	                                                  function reject_reason(iv) {
		                                               var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		                                                var cnt = $('input.common_style:checked').length;
		                                                 if(cnt >= 1) {
			                                             if(aa == false) {
				                                          $("#hid_reason_reject_"+iv).val("");
				                                         $("#hid_reason_reject_"+iv).attr("required", true);
				                                          $("#id_reason_reject_"+iv).css('display', 'block');
			                                               } else {                            
				                                         $("#hid_reason_reject_"+iv).val("");
				                                           $("#hid_reason_reject_"+iv).attr("required", false);
				                                         $("#id_reason_reject_"+iv).css('display', 'none');
			                                                  }
		                                                    } else {
			                                                alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			                                              $("#chk_reject_reason_"+iv).prop('checked', true);
		                                                       }
	                                                      }
	
	                                                $('#txtdynamic_creator').fSelect();

	                                               function reject_reason(iv) {
		                                           var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		                                           var cnt = $('input.common_style:checked').length;
		                                             if(cnt >= 1) {
			                                       if(aa == false) {
				                                     $("#hid_reason_reject_"+iv).val("");
				                                       $("#hid_reason_reject_"+iv).attr("required", true);
				                                       $("#id_reason_reject_"+iv).css('display', 'block');
			                                         } else {                            
				                                      $("#hid_reason_reject_"+iv).val("");
				                                      $("#hid_reason_reject_"+iv).attr("required", false);
				                                      $("#id_reason_reject_"+iv).css('display', 'none');
			                                            }
		                                               } else {
			                                            alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			                                      $("#chk_reject_reason_"+iv).prop('checked', true);
		                                                }
	                                                   }  
                                              $('#txtdynamic_asistby').fSelect();

	                                           function reject_reason(iv) {
		                                     var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		                                     var cnt = $('input.common_style:checked').length;
		                                        if(cnt >= 1) {
			                                   if(aa == false) {
				                                 $("#hid_reason_reject_"+iv).val("");
				                             $("#hid_reason_reject_"+iv).attr("required", true);
				                            $("#id_reason_reject_"+iv).css('display', 'block');
			                                  } else {                            
				                          $("#hid_reason_reject_"+iv).val("");
				                       $("#hid_reason_reject_"+iv).attr("required", false);
				                     $("#id_reason_reject_"+iv).css('display', 'none');
			                            }
		                                  } else {
			                      alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			                    $("#chk_reject_reason_"+iv).prop('checked', true);
		                           }
	                              }
	                           $('#txtdynamic_coordinator').fSelect();

	                        function reject_reason(iv) {
		                    var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		                var cnt = $('input.common_style:checked').length;
		                       if(cnt >= 1) {
			                  if(aa == false) {
				                $("#hid_reason_reject_"+iv).val("");
				                $("#hid_reason_reject_"+iv).attr("required", true);
				                   $("#id_reason_reject_"+iv).css('display', 'block');
			                     } else {                            
				                $("#hid_reason_reject_"+iv).val("");
				              $("#hid_reason_reject_"+iv).attr("required", false);
				                  $("#id_reason_reject_"+iv).css('display', 'none');
			                       }
		                     } else {
			                  alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			                $("#chk_reject_reason_"+iv).prop('checked', true);
		                     }
	                           }

    function find_checklist() {
        $('#load_page').show();
        var slt_targetno = $("#slt_targetno").val();
        var slt_submission = $("#slt_submission").val();
        var strURL="ajax/ajax_validate.php?action=fix_checklist&slt_targetno="+slt_targetno+"&slt_submission="+slt_submission;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // alert("++++++++++++"+data1);
                var chklist = data1.split(",");

                $("#id_txt_submission_quotations").css("display", "none");
                $('#txt_submission_quotations').prop('required', false);
                $("#id_txt_submission_fieldimpl").css("display", "none");
                $('#txt_submission_fieldimpl').prop('required', false);
                $("#id_txt_submission_clrphoto").css("display", "none");
                $('#txt_submission_clrphoto').prop('required', false);
                $("#id_txt_submission_artwork").css("display", "none");
                $('#txt_submission_artwork').prop('required', false);
                $("#id_txt_submission_othersupdocs").css("display", "none");
                $('#txt_submission_othersupdocs').prop('required', false);
                $("#id_txt_warranty_guarantee").css("display", "none");
                $('#txt_warranty_guarantee').prop('required', false);
                $("#id_txt_cur_clos_stock").css("display", "none");
                $('#txt_cur_clos_stock').prop('required', false);
                $("#id_txt_advpay_comperc").css("display", "none");
                $('#txt_advpay_comperc').prop('required', false);
                $("#id_datepicker_example4").css("display", "none");
                $('#datepicker_example4').prop('required', false);

                var enable1 = 0; var enable2 = 0; var enable3 = 0; var enable4 = 0;
                var enable5 = 0; var enable6 = 0; var enable7 = 0; var enable8 = 0; var enable9 = 0;
                for (var exp_chklisti=0; exp_chklisti < chklist.length; exp_chklisti++) {
                    // alert(chklist[exp_chklisti]+"**"+exp_chklisti+"**"+chklist.length);

                    if(chklist[exp_chklisti] == 1) {
                        enable1 = 1;
                        $("#id_txt_submission_quotations").css("display", "block");
                        // $('#txt_submission_quotations').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 2) {
                        enable2 = 1;
                        $("#id_txt_submission_fieldimpl").css("display", "block");
                        // $('#txt_submission_fieldimpl').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 3) {
                        enable3 = 1;
                        $("#id_txt_submission_clrphoto").css("display", "block");
                        // $('#txt_submission_clrphoto').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 4) {
                        enable4 = 1;
                        $("#id_txt_submission_artwork").css("display", "block");
                        // $('#txt_submission_artwork').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 5) {
                        enable5 = 1;
                        $("#id_txt_submission_othersupdocs").css("display", "block");
                        // $('#txt_submission_othersupdocs').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 6) {
                        enable6 = 1;
                        $("#id_txt_warranty_guarantee").css("display", "block");
                        // $('#txt_warranty_guarantee').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 7) {
                        enable7 = 1;
                        $("#id_txt_cur_clos_stock").css("display", "block");
                        // $('#txt_cur_clos_stock').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 8) {
                        enable8 = 1;
                        $("#id_txt_advpay_comperc").css("display", "block");
                        // $('#txt_advpay_comperc').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 9) {
                        enable9 = 1;
                        $("#id_datepicker_example4").css("display", "block");
                        // $('#datepicker_example4').prop('required', true);
                    }
                }


                $('#load_page').hide();
            }
        });
    }
	

    function find_tags() {
        //alert("**CAME**");
        $('#load_page').show();
        CKEDITOR.instances.FCKeditor2.updateElement();
        var data_serialize = $("#frm_requirement_entry").serializeArray();
        $.ajax({
            type: 'post',
            url: "ajax/ajax_tags_generator.php",
            // dataType: 'json',
            data: data_serialize,
            beforeSend: function() {
                $('#load_page').show();
            },
            success: function(response)
            {
                $("#id_tags_generation").html('');
                $("#id_tags_generation").html(response);
                $('#load_page').hide();
            },
            error: function(response, status, error)
            {
                /* var ALERT_TITLE = "Message";
                var ALERTMSG = "Tags Generation Failure. Kindly try again!!";
                createCustomAlert(ALERTMSG, ALERT_TITLE); */
                $('#load_page').hide();
            }
        });
    }
                                                    </script>
												</div>
										</div>
										<div class="tags_clear"></div>
									<?/*<div class="panel-footer">

										<button class="btn btn-success pull-right" onclick="" type="submit">Submit</button>
									</div>*/?>
										<div class="tags_clear"></div>
									
									<div class="tags_clear"></div>
								</div>
								<div class="tags_clear"></div>
								</form>
						<!-- page content wrapper ends here   onclick="nsubmit();"-->
                    </div>
                </div>
	<? 
		break;
	

	case 14:
	// Arun G - 26-02-2018
	?>
	<!-- <input type="checkbox" name="alw_create" id="alw_create" onclick="default_view();"> -->
	
		<?break;

		// Non - Budget & Non Value based approvals
		// Arun G - 26-02-2018
	
	 default:
	// Arun G - 26-02-2018
	?>
	<!-- <input type="checkbox" name="alw_create" id="alw_create" onclick="default_view();"> -->
	<input type="hidden" name="default_lock" id="default_lock" value="">
	<label style="font-size: large;color: red;" for="alw_create" class="blinking">Create General Format :</label>
		<div id="general"> <!-- //style="display: none" -->
		<div class="row">
			<div class="col-md-1">
				<input type="button" name="backcol" id="backcol" value="BACK" style="display:none;text-align: right;margin-top: 5px;" class="btn btn-primary" onclick="back_col();">
			</div>
			<div class="col-md-3"></div>
			<div class="col-md-2"><input type="number" name="noofcol" id="noofcol" value="" placeholder="No Of Columns required" required class="form-control"></div>
			<div class="col-md-3"><input type="button" name="coladd" id="coladd" class="btn" onclick="coladd_new();" value="LOAD" style="margin-top: 5px;background-color: #E91E63;color: white;"></div>
			<div class="col-md-3">
				<input type="button" name="createtemp" id="createtemp" value="CREATE" onclick="create_temp();" style="display: none;margin-top: 5px;" class="btn btn-success">
			</div>
		</div>
		<div id="genearl_temp"></div>
		</div>
		<div style="border-top: 1px solid #d4d4d4; width: 100%; padding: 0% 2%; height: 5px;margin-top: 10px;"></div>
		<?break;

		// Non - Budget & Non Value based approvals
		// Arun G - 26-02-2018
	?>
	<!-- default:  -->
	<!-- <input type="checkbox" name="alw_create" id="alw_create" onclick="default_view();"> <label style="font-size: large;color: red;" for="alw_create" class="blinking">Create General Format :</label>
			<div id="general" style="display: none">
			<div class="row">
				<div class="col-md-1">
					<input type="button" name="backcol" id="backcol" value="BACK" style="display:none;text-align: right;margin-top: 5px;" class="btn btn-primary" onclick="back_col();">
				</div>
				<div class="col-md-3"> </div>
				<div class="col-md-2"><input type="number" name="noofcol" id="noofcol" value="" placeholder="No Of Columns required" class="form-control"></div>
				<div class="col-md-3"><input type="button" name="coladd" id="coladd" class="btn" onclick="coladd_new();/*load_gen_temp(9);*/" value="LOAD" style="margin-top: 5px;background-color: #E91E63;color: white;"></div>
				<div class="col-md-3">
					<input type="button" name="createtemp" id="createtemp" value="CREATE" onclick="create_temp();" style="display: none;margin-top: 5px;" class="btn btn-success">
				</div>
			</div>
			<div id="genearl_temp"></div>
			</div>
			<div style="border-top: 1px solid #d4d4d4; width: 100%; padding: 0% 2%; height: 5px;margin-top: 10px;"></div> -->
			<?//break;
}
?>
<div class='clear clear_both'></div>

 

   
   
   
    
    
    