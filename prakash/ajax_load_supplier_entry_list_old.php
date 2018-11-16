<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
extract($_REQUEST);
include_once('../lib/function_connect.php');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year


if($_REQUEST['action']=='load_subproducts')
{      

$sublist = select_query_json("select distinct pst.PRDNAME, pst.DEPCODE, sub.SUBCODE,pst.PRDCODE, sub.SUBNAME, dep.DEPNAME, dep.EXPNAME, npt.PTNUMB, pst.PRDCODE||'-'||pst.PRDNAME||'/'||sub.SUBCODE||'-'||sub.SUBNAME prd from trandata.product_asset@tcscentr pst,trandata.subproduct_asset@tcscentr sub,trandata.department_asset@tcscentr dep,trandata.non_purchase_target@tcscentr npt,
trandata.approval_budget_product@tcscentr app where  pst.prdcode= sub.prdcode and pst.DEPCODE = dep.DEPCODE and npt.depcode=dep.depcode and pst.prdcode=app.prdcode and npt.ptnumb ='".$tarnumb."' and trunc(sysdate) between trunc(npt.ptfdate) and trunc(npt.pttdate) and pst.deleted = 'N'and npt.deleted = 'N' and sub.deleted = 'N' and dep.deleted = 'N'  order by sub.subcode","Centra","TCS");

 ?>                   
                      <div class="row">
                        <div class="col-md-12">

                           
                               
                            <div class="panel panel-default">
                              

                                <div class="panel-heading" id="panel_exp_head">
                                    <h3 class="panel-title" id="panel_exp_title">SUB PRODUCT LIST</h3>                                   
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="col-md-3 control-label" style="padding-top:10px">Choose Sub-Product <span style='color:red'>*</span></label>
                                          
                                              <div class="col-md-9">
                                                    
                                                        <select class="form-control custom-select chosn" autofocus tabindex='1' required name='slt_subproduct' id='slt_subproduct' data-toggle="tooltip" data-placement="top" data-original-title="Project" required onChange="getsuppliertable(this.value,'<?=$tarnumb?>')" data-toggle="tooltip" data-placement="top" data-original-title="Type of Product" style="padding:10px">
                                                           <option value = "">-- Choose Sub-Product --</option>
                                                        <? 
                                                            for($i=0;$i<count($sublist);$i++){
                                                              if($i==0){?>
                                                                <option value = "<?=$sublist[$i]['PRDCODE']?>:<?=$sublist[$i]['DEPCODE']?>:<?=$sublist[$i]['SUBCODE']?>"><?=$sublist[$i]['PRD']?></option>
                                                              <?}else{?>
                                                                <option value = "<?=$sublist[$i]['PRDCODE']?>:<?=$sublist[$i]['DEPCODE']?>:<?=$sublist[$i]['SUBCODE']?>"> <?=$sublist[$i]['PRD']?></option>
                                                              <?}
                                                            }?>
                                                        </select>
                                                   
                                                </div>
                                            </div>
                                            <div class="tags_clear"></div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                 
                                </div>
                              </div>
                              <script type="text/javascript">
                                  $(".chosn").customselect();
                                
                              </script>
                     





<? }
if($_REQUEST['action']=='load_supplier_entry')

  {
   
    ?>
              <div class="row">
                        <div class="col-md-12">

                            <form class="form-horizontal" role="form" id='frm_supplier_entry' name='frm_supplier_entry' action='' method='post' enctype="multipart/form-data">
                               
                            <div class="panel panel-default">
                              

                                <div class="panel-heading">
                                    <h3 class="panel-title">SUPPLIER ENTRY</h3>                                   
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                          <div class="form-group">
                                            <div id="list_head">
                                      <div class="row">
                                        <div class="form-group" style="border-bottom: 1px solid rgba(59, 59, 59, 0.83);margin-left: 1%;margin-right: 1%;">
                                         
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:300px;font-size: 12px">
                                              <label >Supplier</label>
                                          </div><!-- supplier -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:100px;font-size: 12px">
                                              <label >Delivery Duration (in Days)</label>
                                          </div><!-- duration -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:120px;font-size: 12px">
                                              <label >Rate</label>
                                          </div><!-- rate -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:100px;font-size: 12px">
                                              <label >Discount(%)</label>
                                          </div><!-- discount -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:100px;font-size: 12px">
                                              <label >Qty</label>
                                          </div><!-- amount -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:100px;font-size: 12px">
                                              <label >CGST%</label>
                                          </div><!-- CGST -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:100px;font-size: 12px">
                                              <label >SGST%</label>
                                          </div><!-- SGST -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:100px;font-size: 12px">
                                              <label >IGST%</label>
                                          </div><!-- IGSTt -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:100px;font-size: 12px">
                                              <label >CESS%</label>
                                          </div><!-- IGSTt -->
                                          <div class="col-md-1 col-sm-1" style="text-align: center;width:200px;font-size: 12px">
                                              <label >Attachemnt</label>
                                          </div><!-- attachemnt -->
                                          <div class="col-md-2 col-sm-2" style="text-align: center;width:200px;font-size: 12px">
                                              <label >Remark</label>
                                          </div><!-- remarks -->
                                        </div>
                                      </div> 
                                    </div>     
                                    <!-- List Head End -->
                                    <div id="list_content" style="margin-bottom: 20px;padding: 10px;margin-left: 1%;margin-right: 1%;">
                                    <?
                                    // echo "select * from approval_product_quotation_fix where TARNUMB='".$_REQUEST['tarnum']."' and  PRDCODE='".$prdcode."' and DEPCODE='".$depcode."' and SUBCODE='".$subcode."'"; 
                                    $sel=select_query_json("select ap.* from approval_product_quotation_fix ap where ap.TARNUMB='".$tarnum."' and  ap.PRDCODE='".$prdcode."' and ap.DEPCODE='".$depcode."' and ap.SUBCODE='".$subcode."' and ap.DELETED='N' order by ap.supsrno",'Centra','TEST');
                                    if(count($sel)>0){
                                      $count=1;
                                      $i=1;
                                      foreach($sel as $key=>$value){
                                       
                                       
                                     
                                    ?>
                                    <div id='content<?=$i?>' class='row' style='margin-bottom: 10px;'>
                                      <div class="row" style="margin-bottom: 10px;">
                                        <div class="form-group">
                                         
                                      <div class="col-md-1 col-sm-3" style="width:300px">
                        <? $supname = select_query_json("select supcode,supname from supplier_asset where supcode = '".$value['SUPCODE']."' and DELETED = 'N'","Centra","TEST");?>     
                                          <input type="text" class="form-control findspold"  id ="slp_load_<?=$i?>"  name="slp_load1[]" style="text-transform: uppercase;" maxlength="100"  readonly value='<?=$supname[0]['SUPCODE']." - ".$supname[0]['SUPNAME']?>'/>
                                      </div><!-- supplier -->
                                      <div class="col-md-1 col-sm-3" style="width:100px">
                                          <input type="text" class="form-control" id="duration_<?=$i?>" name="duration1[]" style="text-transform: uppercase;" maxlength="2" required readonly value='<?=$value['SUPDLVY']?>' />
                                      </div><!-- duration -->
                                      <div class="col-md-1 col-sm-3" style="width:120px">
                                          <input type="text" class="form-control" id="rate_<?=$i?>" name="rate1[]" style="text-transform: uppercase;" maxlength="8" required readonly value='<?=$value['SUPRATE']?>'/>
                                      </div><!-- rate -->
                                      <div class="col-md-1 col-sm-3" style="width:100px">
                                          <input type="text" class="form-control" id="discount_<?=$i?>" name="discount1[]" style="text-transform: uppercase;" maxlength="3" required readonly value='<?=$value['SUPDISC']?>'/>
                                      </div><!-- discount -->
                                      <div class="col-md-1 col-sm-3" style="width:100px" >
                                          <input type="text" class="form-control" id="qty_<?=$i?>" name="qty1[]" style="text-transform: uppercase;" maxlength="5" required readonly value='<?=$value['PRDQTY']?>'/>
                                      </div><!-- Qty -->
                                      <div class="col-md-1 col-sm-1" style="width:100px">
                                             <input type="text" class="form-control" id="cgst_<?=$i?>" name="cgst1[]" readonly value='<?=$value['CGSTPER']?>' />
                                          </div><!-- CGST -->
                                          <div class="col-md-1 col-sm-1" style="width:100px" >
                                              <input type="text" class="form-control" id="sgst_<?=$i?>" name="sgst1[]" readonly value='<?=$value['SGSTPER']?>' />
                                          </div><!-- SGST -->
                                          <div class="col-md-1 col-sm-1" style="width:100px" >
                                              <input type="text" class="form-control" id="igst_<?=$i?>" name="igst1[]" readonly value='<?=$value['IGSTPER']?>' />
                                          </div><!-- IGSTt -->
                                          <div class="col-md-1 col-sm-1" style="width:100px" >
                                              <input type="text" class="form-control" id="cess_<?=$i?>" name="cess1[]"  value='<?=$value['SUPCESS']?>'/>
                                          </div><!-- IGSTt -->
                                          <div class="col-md-1 col-sm-1" style="width:200px">                                                                                      
                                         <!-- <a href='<?=$value['SUPQFILE']?>'><?if($value['SUPQFILE'] != ''){echo $value['SUPQFILE'];}else{echo "No Attchment";}?></a> -->
                          <!-- <div style="width: 50%">welcomd.jpg</div> -->
                          <a href = "../approval-desk/ftp_image_view_pdf.php?pic=<?=$value['SUPQFILE']?>&path=approval_desk/product_quotation_fix/2018-19/" target="_blank" ><?if($value['SUPQFILE'] != ''){echo $value['SUPQFILE'];}else{echo "No Attchment";}?> </a>


                                          </div><!-- attachemnt -->
                                          <div class="col-md-1 col-sm-1" style="width:200px">
                                               <input type="text" class="form-control" id="remarks_<?=$i?>" name="remarks1[]" style="text-transform: uppercase;" maxlength="50" required value='<?=$value['REMARKS']?>'/>
                                          </div><!-- remarks -->
                                          <div class="col-md-1 col-sm-1">
                                            <div class="col-md-2">
                                              <span class="input-group-btn" style="display: inline-block;"><button id="add_button" type="button" onclick="update_row('<?=$value['ENTNUMB']?>',<?=$i?>)" class="btn btn-danger btn-add" title ="Remove This">-</button></span>
                                            </div>
                                          </div><!-- remarks -->
                                        </div>
                                      </div>
                                      <input type="hidden" class="disablebtn1" id="disablesave<?=$i?>" value = "0" name="">
                                      <input type="hidden" class="enablefinish" id="enablefinish" value = "0" name="">
                                      

                                     </div>

                                    <? $i++; }
                                    ?><input type="hidden" class="inputcount" id="loopcount" value = "<?=$i-1?>" name="">
                                    <input type="hidden" class="disablesavead" id="disablesavead" value = "0" name="">
                                  </div>
                                    <div class="col-md-12">
                                  <div class="input-group pull-left  " style="margin: 10px;">
                                  <button class="btn btn-primary " type="button"  id="saveadditional" onclick="javascript:supplierquote();"><span class="fa fa-save"></span> Save</button>
                                  <button class="btn btn-primary "style="margin-left: 10px" type="button"  id ="addadditional" onclick="add_addtionalrow()"><span class="fa fa-save"></span> Add Supplier</button>
                                 
                                </div>
                              
                                 </div>

                                    <?}else{?>
                                    
                                          
                                          <?
                                          for($k=1;$k<4;$k++){
                                      ?>

                                       <div id="content<?=$k?>" class='row' style='margin-bottom: 10px;'>
                                        <div class='form-group'>
                                         
                                      <div class="col-md-1 col-sm-1" style="width:300px" >
                                        <input type="hidden" name="suppliers" value='3' id='suppliers'/>
                                          <input type="text" class="form-control required"  id ="slp_load_<?=$k?>"  name="slp_load[]" style="text-transform: uppercase;" maxlength="100" required onchange="javascript:return removeDupeRun(this);" onblur="javascript:fix_state(this);" />
                                          <input type="hidden" name="supplierstate" value='' id='supstate_<?=$k?>'/>

                                      </div><!-- supplier -->
                                      <div class="col-md-1 col-sm-1" style="width:100px">
                                          <input type="text" class="form-control required" id="duration_<?=$k?>" name="duration[]" onkeypress="javascript:return isNumber(event);" style="text-transform: uppercase;" onblur ="javascript:return valueNotZero(this);return fix_tax(this);" maxlength="3" required />
                                      </div><!-- duration -->
                                      <div class="col-md-1 col-sm-1" style="width:120px">
                                          <input type="text" class="form-control required" id="rate_<?=$k?>" name="rate[]" onkeypress="javascript:return validateFloatKeyPress(this,event);return isNumber(event);" onblur ="javascript:return valueNotZero(this)" style="text-transform: uppercase;" maxlength="10" required />
                                      </div><!-- rate -->
                                      <div class="col-md-1 col-sm-1"style="width:100px">
                                          <input type="text" class="form-control required" id="discount_<?=$k?>" name="discount[]" onkeypress="javascript:return validateFloatKeyPress(this,event);return isNumber(event);" oninput ='checkNumber(this)' style="text-transform: uppercase;" maxlength="5"  required />
                                      </div><!-- discount -->
                                      <div class="col-md-1 col-sm-1" style="width:100px">
                                          <input type="text" class="form-control required" id="qty_<?=$k?>" name="qty[]" onkeypress="javascript:return isNumber(event);" style="text-transform: uppercase;" maxlength="7" required onblur ="javascript:return valueNotZero(this)" />
                                      </div><!-- Qty -->
                                      <div class="col-md-1 col-sm-1" style="width:100px">
                                             <input type="text" class="form-control" id="cgst_<?=$k?>" name="cgst[]" onkeypress="javascript:return validateFloatKeyPress(this,event);return isNumber(event);"  maxlength="5" oninput ='checkNumberCgst(this)'/>
                                          </div><!-- CGST -->
                                          <div class="col-md-1 col-sm-1" style="width:100px" >
                                              <input type="text" class="form-control" id="sgst_<?=$k?>" name="sgst[]" onkeypress="javascript:return validateFloatKeyPress(this,event);return isNumber(event);" maxlength="5" oninput ='checkNumberCgst(this)' />
                                          </div><!-- SGST -->
                                          <div class="col-md-1 col-sm-1" style="width:100px" >
                                              <input type="text" class="form-control" id="igst_<?=$k?>" name="igst[]" onkeypress="javascript:return validateFloatKeyPress(this,event);return isNumber(event);" maxlength="5" oninput ='checkNumberIgst(this)' />
                                          </div><!-- IGSTt -->
                                          <div class="col-md-1 col-sm-1" style="width:100px" >
                                              <input type="text" class="form-control" id="cess_<?=$k?>" name="cess[]" onkeypress="javascript:return validateFloatKeyPress(this,event);return isNumber(event);" maxlength="5" oninput ='checkNumberCess(this)' />
                                          </div><!-- IGSTt -->
                                          <div class="col-md-1 col-sm-1" style="width:200px" >                                                                                      
                          <input type="file" placeholder="Document Attachment" tabindex='8' class="form-control input-group filename" name="attachments[]" id="attachments_<?=$k?>" onchange="ValidateSingleInput(this, 'all'); displayname();"  accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Document Attachment" style="font-size: 12px">
                          <span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span>
                          <!-- <div style="width: 50%">welcomd.jpg</div> -->


                                          </div><!-- attachemnt -->
                                          <div class="col-md-2 col-sm-2" style="width:200px;height: 50px" >
                                               <input type="text" class="form-control" id="remarks_<?=$k?>" name="remarks[]" style="text-transform: uppercase;" maxlength="50"  />
                                          </div><!-- remarks -->
                                          <div class='col-sm-1 col-xs-3' style='width:100px'>
                                          <div class='col-sm-1' >
                                          <span style='width: 40%;float:right;display: inline-block;' class='input-group-btn btn-remove'><button id='remove_button_<?=$k?>' type='button' onclick='remove_row()' class='btn btn-danger' title ='Remove'>-</button>
                                          </span>
                                          </div>
                                        </div>
                                          

                                 
                                <script type="text/javascript">
                                     $('#slp_load_<?=$k?>').autocomplete({
                                        source: function( request, response ) {
                                            $.ajax({
                                                //url : 'ajax/get_supplier_details.php',
                                               url : 'ajax/ajax_product_details.php',
                                                dataType: "json",
                                                data: {
                                                   name_startsWith: request.term,
                                                   slt_core_department: 0,
                                                   action: 'supplier_withcity'
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
                                          $("#slp_load_<?=$k?>").val('');
                                          $("#slp_load_<?=$k?>").focus(); 
                                        } 
                                        },
                                        autoFocus: true,
                                        minLength: 0
                                    });

                                     $( document ).on( 'click', '.btn-remove', function ( event ) {
                                            event.preventDefault();
                                            $(this).closest( '.row' ).remove();
                                       });


                                        // function removeDupeRun(t)    {
                                        //  console.log("sjdghfkd");
                                        //   //$('input[name^="slp_load"]').each(function() {
                                        //     var values = $('input[name="slp_load[]"]').map(function() {
                                        //           return this.value;
                                        //         }).toArray();
                                        //       // values = values.filter(function(e){return e}); 
                                        //         var hasDups = !values.every(function(v,i) {
                                        //           return values.indexOf(v) == i;
                                        //         });
                                        //         if(hasDups){
                                        //            // having duplicate values
                                        //            jAlert("please do not repeat the same suppliers");

                                        //           // e.preventDefault();
                                        //           t.value = '';

                                        //         }
                                         
                                        //  }
                                     
                                   </script>
                                    </div>
                                </div>

                                          <?}?>

                                 </div>
                                  </div>
                                   </div>
                                     
                                 </form>  
                                     </div>
                                   </div>   
                             

                                  
                                
                                  <div class="col-md-12">
                                  <div class="input-group pull-left  " style="margin: 10px;">
                                  <button class="btn btn-primary " type="button"  id="btnsave" onclick="javascript:supplierquote();"><span class="fa fa-save"></span> Save</button>
                                  <button class="btn btn-primary "style="margin-left: 10px" type="button"  id ="adddef" onclick="add_newrow(<?=$k?>)"><span class="fa fa-save"></span> Add Supplier</button>
                                  <?//if($count==1){?>
                                   <!-- <button class="btn btn-primary "style="margin-left: 10px" type="button"   onclick="add_row(<?=$i?>)"><span class="fa fa-save"></span> Add Supplier</button><?//}?> -->
                                </div>
                              
                                 </div>
                                   

                 <? }}?>
 <?
if($_REQUEST['action']=='update_row')

  {
    echo $_REQUEST['entnumb'];
    $table='approval_product_quotation_fix';
    $g_supplier_rate_fix=array();
    $g_supplier_rate_fix['DELUSER'] =$_SESSION['tcs_usrcode'];
    $g_supplier_rate_fix['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $g_supplier_rate_fix['DELETED']='Y';
    
    $where="ENTNUMB = '".$_REQUEST['entnumb']."'";
    print_r($g_supplier_rate_fix);
    echo $upt = update_test_dbquery($g_supplier_rate_fix, $table, $where);


  }

 ?>
 <?
  if($_REQUEST['action']=='preview')

  {
    
    $prdcode = explode(":", $prd);
    
?>      <div class="non-printable " style='clear:both; border-bottom:1px solid #122344 !important; margin-bottom:10px; margin-top: 10px;'></div>
                
                <table id="tbl_history_list" class="table datatable dataTable " style="font-size: 12px;" >
                <thead >
                <tr style="background-color:#3f444c;">
                <th class="center" style="text-align:center">S NO</th>
                <th class="center" style="text-align:center">PROJECT ID</th>
                <th class="center" style="text-align:center">ADD USER</th>
                <th class="center" style="text-align:center">ADD DATE</th>
                <th class="center" style="text-align:center" >TARNUMB</th>
                <th class="center" style="text-align:center" >DELUSER</th>
                <th class="center" style="text-align:center" >DELDATE</th>
                
                <th class="center" style="text-align:center">ACTION</th>
                </tr>
              </thead>
              <tbody> 
                <?                  
                  $preview_search = select_query_json("select * from approval_product_quotation_fix where APRNUMB = '".trim($ap)."' and TARNUMB = '".trim($tarnumb)."' and PRDCODE = '".trim($prdcode)."' and FINISHS = 'N'" ,"Centra","TEST");
                  for($j = 0 ; $j<sizeof($preview_search) ; $j++){
                  $srno =1;
                  ?>
             
                        <tr>
                         <td><? echo ($srno)+1 ?></td>
                        <td class="center" style="text-align:center"><? echo $preview_search[$j]['PRMSCOD'];?></td>
                        
                        <td class="center" style="text-align:center"><?echo $preview_search[$j]['EDTUSER'];?></td>
                      
                    
                        <td class="center" style="text-align:center"><? echo $preview_search[$j]['EDTDATE'];?></td>
                        
                        <td class="center" style="text-align:center"><? echo $preview_search[$j]['TARNUMB'];?></td>
                        <td class="center" style="text-align:center"><? echo $preview_search[$j]['DELUSER'];?></td>
                        <td class="center" style="text-align:center"><? echo $preview_search[$j]['DELDATE'];?></td>
                        
                        <td class="center" style="text-align:left"><? echo $preview_search[$j]['REMARKS'];?></td>
                        </tr>
                        
                        <? }?>
                    
                                       
                  
                  </tbody>
                  </table>
<?
  }
    ?>

