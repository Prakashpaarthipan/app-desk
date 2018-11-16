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
                                        <div class="col-md-10">
                                          <div class="form-group">
                                            <div id="list_head">
                                      <div class="row">
                                        <div class="form-group" style="border-bottom: 1px solid rgba(59, 59, 59, 0.83);margin-left: 1%;margin-right: 1%;">
                                         
                                          <div class="col-md-3 col-xs-3" style="text-align: center;">
                                              <label >Supplier</label>
                                          </div><!-- supplier -->
                                          <div class="col-md-1 col-xs-3" style="text-align: center;">
                                              <label >Delivery Duration(in Days)</label>
                                          </div><!-- duration -->
                                          <div class="col-md-1 col-xs-3" style="text-align: center;">
                                              <label >Rate</label>
                                          </div><!-- rate -->
                                          <div class="col-md-1 col-xs-3" style="text-align: center;">
                                              <label >Discount(%)</label>
                                          </div><!-- discount -->
                                          <div class="col-md-1 col-xs-3" style="text-align: center;">
                                              <label >Qty</label>
                                          </div><!-- amount -->
                                          <div class="col-md-2 col-xs-3" style="text-align: center;">
                                              <label >Attachemnt</label>
                                          </div><!-- attachemnt -->
                                          <div class="col-md-1 col-xs-3" style="text-align: center;">
                                              <label >Remark</label>
                                          </div><!-- remarks -->
                                        </div>
                                      </div> 
                                    </div>     
                                    <!-- List Head End -->
                                    <div id="list_content" style="margin-bottom: 20px;padding: 10px;margin-left: 1%;margin-right: 1%;">
                                    <?
                                    // echo "select * from approval_product_quotation_fix where TARNUMB='".$_REQUEST['tarnum']."' and  PRDCODE='".$prdcode."' and DEPCODE='".$depcode."' and SUBCODE='".$subcode."'"; 
                                    $sel=select_query_json("select ap.* from approval_product_quotation_fix ap where ap.TARNUMB='".$tarnum."' and  ap.PRDCODE='".$prdcode."' and ap.DEPCODE='".$depcode."' and ap.SUBCODE='".$subcode."' and ap.DELETED='N' order by ap.suppsrno desc",'Centra','TEST');
                                    if($sel){
                                      $count=1;
                                      $i=0;
                                      foreach($sel as $key=>$value){
                                        $i++;
                                       
                                     
                                    ?>
                                    <div id='content<?=$i?>' class='row' style='margin-bottom: 10px;'>
                                      <div class="row" style="margin-bottom: 10px;">
                                        <div class="form-group">
                                         
                                      <div class="col-md-3 col-xs-3">
                        <? $supname = select_query_json("select supcode,supname from supplier_asset where supcode = '".$value['SUPPCODE']."' and DELETED = 'N'","Centra","TEST");?>     
                                          <input type="text" class="form-control"  id ="slp_load_<?=$i?>"  name="slp_load1[]" style="text-transform: uppercase;" maxlength="100"  readonly value='<?=$supname[0]['SUPCODE']." - ".$supname[0]['SUPNAME']?>'/>
                                      </div><!-- supplier -->
                                      <div class="col-md-1 col-xs-3">
                                          <input type="text" class="form-control" id="duration_<?=$i?>" name="duration1[]" style="text-transform: uppercase;" maxlength="2" required readonly value='<?=$value['SUPDLVY']?>' />
                                      </div><!-- duration -->
                                      <div class="col-md-1 col-xs-3">
                                          <input type="text" class="form-control" id="rate_<?=$i?>" name="rate1[]" style="text-transform: uppercase;" maxlength="8" required readonly value='<?=$value['SUPRATE']?>'/>
                                      </div><!-- rate -->
                                      <div class="col-md-1 col-xs-3">
                                          <input type="text" class="form-control" id="discount_<?=$i?>" name="discount1[]" style="text-transform: uppercase;" maxlength="3" required readonly value='<?=$value['SUPDISC']?>'/>
                                      </div><!-- discount -->
                                      <div class="col-md-1 col-xs-3">
                                          <input type="text" class="form-control" id="qty_<?=$i?>" name="qty1[]" style="text-transform: uppercase;" maxlength="5" required readonly value='<?=$value['PRDQTY']?>'/>
                                      </div><!-- Qty -->
                                          <div class="col-md-2 col-xs-3">                                                                                      
                                         <a href='<?=$value['SUPQFILE']?>'><?if($value['SUPQFILE'] != ''){echo $value['SUPQFILE'];}else{echo "No Attchment";}?></a>
                          <!-- <div style="width: 50%">welcomd.jpg</div> -->


                                          </div><!-- attachemnt -->
                                          <div class="col-md-1 col-xs-3">
                                               <input type="text" class="form-control" id="remarks_<?=$i?>" name="remarks1[]" style="text-transform: uppercase;" maxlength="50" required value='<?=$value['REMARKS']?>'/>
                                          </div><!-- remarks -->
                                          <div class="col-md-1 col-xs-3">
                                            <div class="col-md-3">
                                              <span class="input-group-btn" style="display: inline-block;"><button id="add_button" type="button" onclick="update_row('<?=$value['ENTNUMB']?>',<?=$i?>)" class="btn btn-danger btn-add" title ="Remove This">-</button></span>
                                            </div>
                                          </div><!-- remarks -->
                                        </div>
                                      </div>
                                      <input type="hidden" class="disablebtn1" id="disablesave<?=$i?>" value = "0" name="">
                                     </div>
                                    <? }
                                    }else{?>

                                       <div class="row" style="margin-bottom: 10px;">
                                        <div class="form-group" id="newappend">
                                         
                                      <div class="col-md-3 col-xs-3">
                                          <input type="text" class="form-control required"  id ="slp_load_1"  name="slp_load[]" style="text-transform: uppercase;" maxlength="100" required oninput="javascript:return removeDupe(this)" />
                                      </div><!-- supplier -->
                                      <div class="col-md-1 col-xs-3">
                                          <input type="text" class="form-control required" id="duration_1" name="duration[]" onkeypress="javascript:return isNumber(event);" style="text-transform: uppercase;" maxlength="3" required />
                                      </div><!-- duration -->
                                      <div class="col-md-1 col-xs-3">
                                          <input type="text" class="form-control required" id="rate_1" name="rate[]" onkeypress="javascript:return validateFloatKeyPress(this,event);return isNumber(event);" style="text-transform: uppercase;" maxlength="8" required />
                                      </div><!-- rate -->
                                      <div class="col-md-1 col-xs-3">
                                          <input type="text" class="form-control required" id="discount_1" name="discount[]" onkeypress="javascript:return validateFloatKeyPress(this,event);return isNumber(event);"  style="text-transform: uppercase;" maxlength="5"  required />
                                      </div><!-- discount -->
                                      <div class="col-md-1 col-xs-3">
                                          <input type="text" class="form-control required" id="qty_1" name="qty[]" onkeypress="javascript:return isNumber(event);" style="text-transform: uppercase;" maxlength="5" required />
                                      </div><!-- Qty -->
                                          <div class="col-md-2 col-xs-3">                                                                                      
                          <input type="file" placeholder="Document Attachment" tabindex='8' class="form-control input-group filename" name="attachments[]" id="attachments_1" onchange="ValidateSingleInput(this, 'all'); displayname();"  accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Document Attachment">
                          <span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span>
                          <!-- <div style="width: 50%">welcomd.jpg</div> -->


                                          </div><!-- attachemnt -->
                                          <div class="col-md-1 col-xs-3">
                                               <input type="text" class="form-control" id="remarks_1" name="remarks[]" style="text-transform: uppercase;" maxlength="50" required />
                                          </div><!-- remarks -->
                                          <div class="col-md-1 col-xs-3">
                                            <div class="col-md-3">
                                              <span class="input-group-btn" style="display: inline-block;"><button id="add_button" type="button" onclick="add_row()" class="btn btn-success btn-add" title ="Add More">+</button></span>
                                            </div>
                                          </div><!-- remarks -->
                                        </div>
                                      </div>
                                    

                                      <?
                                    }?>
                                    </div>
                                    <!-- List Content End -->

                                  </div>
                                </div>
                                 </div>
                                  </div>
                                   </div>

                                  
                                  </div>
                                  <div class="col-md-12">
                                  <div class="input-group pull-left  " style="margin: 10px;">
                                  <button class="btn btn-primary " type="button"  id="btnsave" onclick="javascript:supplierquote();"><span class="fa fa-save"></span> Save</button>
                                  <?if($count==1){?>
                                   <button class="btn btn-primary "style="margin-left: 10px" type="button"   onclick="add_row(<?=$i?>)"><span class="fa fa-save"></span> Add Supplier</button><?}?>
                                </div>
                              </form>  
                                 </div>
                                   </div>   

                                   
                                   <script type="text/javascript">
                                     $('#slp_load_1').autocomplete({
                                        source: function( request, response ) {
                                            $.ajax({
                                                url : 'ajax/get_supplier_details.php',
                                                dataType: "json",
                                                data: {
                                                   name_startsWith: request.term,
                                                   slt_core_department: 0,
                                                   action: 'supplier_details'
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
                                          $("#slp_load_1").val('');
                                          $("#slp_load_1").focus(); 
                                        } 
                                        },
                                        autoFocus: true,
                                        minLength: 0
                                    });

                                     
                                   </script>
                               



 <? }

if($_REQUEST['action']=='update_row')

  {
    echo $_REQUEST['entnumb'];
    $table='approval_product_quotation_fix';
    $updata=array();
    $updata['DELETED']='Y';
    $updata['DELUSER']=$_SESSION['tcs_usrcode'];
    $updata['DELDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $where="ENTNUMB = '".$_REQUEST['entnumb']."'";
   print_r($updata);
    echo $upt = update_test_dbquery($updata, $table, $where);


  }



/*if($_REQUEST['expsrno']=='all')
        {
           $open_search = select_query_json("select distinct Brn.Brncode Code,substr(brn.nicname,3,10) Brnname,brn.brnname Branch,Dep.expsrno, Dep.expname Exphead,Dep.Depname Department,tar.ptdesc Ledgername,Tar.Ptnumb TargetNumber from trandata.non_purchase_target@tcscentr Tar, trandata.Department_asset@tcscentr Dep,trandata.branch@tcscentr Brn where tar.ptnumb>=9001  and tar.brncode='".$_REQUEST['branch']."' and tar.depcode=dep.depcode and tar.brncode=brn.brncode and dep.Deleted='N' order by Dep.expsrno,Dep.expname,tar.ptnumb", "Centra", 'TCS');     
        }
        else
        {  $open_search = select_query_json("select distinct Brn.Brncode Code,substr(brn.nicname,3,10) Brnname,brn.brnname Branch,Dep.expsrno, Dep.expname Exphead,Dep.Depname Department,dep.depcode,tar.ptdesc Ledgername,Tar.Ptnumb TargetNumber from trandata.non_purchase_target@tcscentr Tar, trandata.Department_asset@tcscentr Dep,trandata.branch@tcscentr Brn where tar.ptnumb>=9001  and tar.brncode='".$_REQUEST['branch']."' and tar.depcode=dep.depcode and tar.brncode=brn.brncode and dep.Deleted='N' and dep.expsrno='".$_REQUEST['expsrno']."' order by Dep.expsrno,Dep.expname,tar.ptnumb", "Centra", 'TCS');  
        }
        //print_r($open_search);
        ?>
        <form class="form-horizontal" role="form" id="frm_budget_entry<?=$_REQUEST['tabid'];?>" name="" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="commit"/>
                <input type="hidden" name="brncode" value="<? echo ($_REQUEST['branch']); ?>">
                <table id="ntable<?=$_REQUEST['tabid'];?>"  class="table datatable table-striped no-footer" style="margin-left: 20px;">
                  <thead>
                      <tr>
                        <th class="center" style='text-align:center'>S.No</th>
                        <th class="center" style='text-align:center'>EXPENSE HEAD</th>
                        <th class="center" style='text-align:center'>TARGETN NO.</th>
                        <th class="center" style='text-align:center'>PARTICULARS</th>
                        <th class="center" style='text-align:center'>REQUESTED</th>
                        <th class="center" style='text-align:left'>REASON</th>
                      </tr>
                  </thead>
                  <tbody>
                
              <?for($k=0;$k<count($open_search);$k++)
              {
                ?>    <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                      <td class="center" style='text-align:center;'>
                       <?=$k+1;?>
                      </td>
                      <td class="center" style='text-align:center'>
                        <? echo $open_search[$k]['EXPHEAD'] ?>

                        <input type="hidden" name="expsrno[]" value="<? echo ($open_search[$k]['EXPSRNO']); ?>">
                      </td>
                      <td class="center" style='text-align:center'>
                        <? echo $open_search[$k]['TARGETNUMBER'] ?>
                        <input type="hidden" name="targetno[]" value="<? echo ($open_search[$k]['TARGETNUMBER']); ?>">
                      </td>
                      <td class="center" style='text-align:left;'>
                        <? echo $open_search[$k]['LEDGERNAME'] ?>
                        <input type="hidden" name="depcode[]" value="<?echo ($open_search[$k]['DEPCODE']); ?>">
                      </td>
                      <td class="center"  style='text-align:left'>
                        <input style="width: 100%;height: 100%;" type="number" name="value[]"/>
                      </td>
                      <td class="center"  style='text-align:left'>
                       <input style="width: 100%;height: 100%;" type="text" name="reason[]"/>
                      </td>
                    </tr>
                  <?
                } ?>
                  </tbody>
              </table>
              <div  class="non-printable" style='clear:both; border-top:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;padding-top: 10px;'>
                <div class="input-group " style="margin: 10px;vertical-align: middle;float:left;">
                        <button class="btn btn-primary" type="button" style="background-color: " onclick="nsubmit(<?=$_REQUEST['tabid'];?>);"><span class="fa fa-thumbs-up"></span> Submit</button>
                </div>
              </div>
        </form>  
<?}
if($_REQUEST['action']=='commit')
{
        print_r($_REQUEST);
        $nor=count($_REQUEST['expsrno']);
        $tab=$_REQUEST['tabid'];
        $g_table="BRANCH_BUDGET_REQUEST";
        $max_search = select_query_json("select nvl(max(ENTNUMB),0)+1 TYPE,'1' SORT from branch_budget_request UNION SELECT EMPSRNO,'2' SORT FROM APPROVAL_BUDGET_FLOW_MASTER WHERE APPSRNO=1 ORDER BY SORT", "Centra", 'TEST');
        for($i=0;$i<$nor;$i++)
        {  if($_REQUEST['value'][$i]>0)
            {
              $g_fld['ENTNUMB']=$max_search[0]['TYPE'];
              $g_fld['BRNCODE']=$_REQUEST['brncode'.$tab];
              $g_fld['PTNUMB']=$_REQUEST['targetno'][$i];
              $g_fld['TARMONT']=$_REQUEST['month'.$tab];
              $g_fld['REQSRNO']='1';
              $g_fld['EXPSRNO']=$_REQUEST['expsrno'][$i];
              $g_fld['DEPCODE']=$_REQUEST['depcode'][$i];
              $g_fld['REQVALU']=$_REQUEST['value'][$i];
              $g_fld['APPVALU']='0';
              $g_fld['REDVALU']='0';
              $g_fld['BUDVALU']='0';
              $g_fld['APPMODE']='N';
              $g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
              $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
              $g_fld['EDTUSER']='';
              $g_fld['EDTDATE']='';
              $g_fld['DELETED']='N';
              $g_fld['DELUSER']='';
              $g_fld['DELDATE']='';

              $g_fld['TARSALES']=$_REQUEST['form_tarsales'.$tab];
              $g_fld['LSTSALES']=$_REQUEST['form_last_sales'.$tab];
              // $g_fld['LSTIMPP']=$_REQUEST['form_lst_mnt_imp'.$tab];
              $g_fld['LSTIMPP']='0';
              $g_fld['CUREXPP']=$_REQUEST['form_cur_est'.$tab];
              $g_fld['DEPEXPP']=$_REQUEST['form_dep_exp'.$tab];


              $g_fld['APPRESN']=$_REQUEST['reason'][$i];
              $g_fld['EMPSRNO']=$max_search[1]['TYPE'];
              $g_fld['REQAPPR']='N';
              $g_fld['ENTYEAR']=$current_yr[0]['PORYEAR'];
              $g_fld['TARYEAR']=date("Y");
              print_r($g_fld);
              $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
              echo("---------------------");
            }       
         
        }
         
}
if($_REQUEST['action']=='view')
{ //print_r($_REQUEST);
  $open_search = select_query_json("select bbr.*,brn.brnname from BRANCH_BUDGET_REQUEST bbr,branch brn WHERE brn.deleted='N' and bbr.DELETED='N' and brn.brncode=bbr.brncode and bbr.tarmont='".$_REQUEST['month']."' and bbr.brncode='".$_REQUEST['brncode']."' and taryear='".date("Y")."' ORDER BY EXPSRNO,ENTNUMB", "Centra", 'TEST'); 

  //print_r($open_search); 
  $month=array('JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');
  ?>
  <!-- <div class="row">
      <div class="input-group " style="margin: 10px;vertical-align: middle;float: right;">
             <button class="btn btn-success" type="button" style="margin-left: 10px; float: right;" onclick="view_submit();"><span class="fa fa-file-text"></span> Confirm</button>
      </div>
   </div> -->
  <center>
    <?if(count($open_search)>0){?>
       <h3 style="color: rgba(38, 26, 209, 1);"><?echo($open_search[0]['BRNNAME']." - ".$month[$_REQUEST['month']-1])." - ".date("Y");?></h3>
    <?}else{?>
      <h3 style="color: red;">NO ENTRY</h3>
    <?}?>
  </center>
  <form class="form-horizontal" role="form" id="frm_view" name="frm_view" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" id="action" value="approve"/>
    <input type="hidden" name="branch" id="branch" value="<?=$_REQUEST['brncode'];?>"/>
    <input type="hidden" name="month" id="month" value="<?=$_REQUEST['month'];?>"/>
  <table id="viewtable"  class="table datatable table-striped no-footer" style="margin-left: 20px;">
      <thead>
          <tr>
            <th class="center" style='text-align:center'>S.No</th>
            <th class="center" style='text-align:center'>EXPENSE HEAD</th>
            <th class="center" style='text-align:center'>TARGETN NO.</th>
            <th class="center" style='text-align:center'>PARTICULARS</th>
            <th class="center" style='text-align:center'>REQUESTED</th>
            <th class="center" style='text-align:left'>REASON</th>
            <th class="center" style='text-align:left'>DEPARTMENT EXPENSE</th>
          </tr>
      </thead>
      <tbody>
    
  <?for($k=0;$k<count($open_search);$k++)
  { $expname = select_query_json("select distinct(expname) from department_asset WHERE expsrno='".$open_search[$k]['EXPSRNO']."'", "Centra", 'TCS');
    $exp_part = select_query_json("select distinct(ptdesc) from non_purchase_target where depcode='".$open_search[$k]['DEPCODE']."' and brncode='".$open_search[$k]['BRNCODE']."' and ptnumb='".$open_search[$k]['PTNUMB']."'order by ptnumb", "Centra", 'TCS'); 
    ?>    <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
          <td class="center" style='text-align:center;'>
           <?=$k+1;?>
          </td>
          <td class="center" style='text-align:center'>
            <? echo $expname[0]['EXPNAME']; ?>
          </td>
          <td class="center" style='text-align:center'>
            <? echo $open_search[$k]['PTNUMB'] ?>
          </td>
          <td class="center" style='text-align:left;'>
            <? echo $exp_part[0]['PTDESC']; ?>
          </td>
          <td class="center"  style='text-align:center'>
            <? echo $open_search[$k]['REQVALU'] ?>
          </td>
          <td class="center"  style='text-align:center'>
           <? echo $open_search[$k]['APPRESN'] ?>
          </td>
          <td class="center"  style='text-align:center'>
              <?if($open_search[$k-1]['EXPSRNO']!=$open_search[$k]['EXPSRNO'] || $open_search[$k-1]['ENTNUMB']!=$open_search[$k]['ENTNUMB']){?>
                  <!-- <input type="checkbox" name="chk_cnfrm[<?=$open_search[$k]['EXPSRNO'];?>][]" id="chk_cnfrm[<?=$open_search[$k]['EXPSRNO'];?>][]" value="1"/> -->
                  <?=$open_search[$k]['DEPEXPP'];?>
              <?}?>
          </td>
        </tr>
      <?
    } ?>
      </tbody>
  </table>
  <!-- viki2 -->
 <? $back_select = select_query_json("select LSTSALES,TARSALES,(select nvl(max(CUREXPP),0) from branch_budget_request where brncode=1 and taryear=2018 and tarmont=10) expcur from branch_budget_request where brncode='".$_REQUEST['brncode']."' and taryear='".date("Y")."' and tarmont='".$_REQUEST['month']."' and rownum<=1", "Centra", "TEST");?>
  <div class="row">
      <div class="col-md-7" style="left:20%;">
          <table class="table table-bordered">
              <tbody> 
                  <tr>
                      <td style="background: #f1f5f9;width: 50%;">LAST YEAR SALES <?$month = date('M',strtotime('+1 month'));echo($month);?> - <?echo date('y')-1;?> ( in Lakhs)</td>
                      <td style="min-width: 50%;"><span id="txt_last_year_sale" name="txt_last_year_sale"><?=$back_select[0]['LSTSALES']?></span></td>
                  </tr>
                  <tr>
                      <td style="background: #f1f5f9">LAST  MONTH IMPROVEMENT %</td>
                      <td><span><?=$back_select[0]['LSTIMPP']?></span><span> %</span></td>
                  </tr>
                  <tr>
                      <td style="background: #f1f5f9">TARGETED SALES ( in Lakhs)</td>
                      <td ><?=$back_select[0]['TARSALES'];?></td>
                  </tr>
                  <tr>
                      <td style="background: #f1f5f9">ESTIMATED EXP %</td>
                      <td><span ><span id="txt_estimated_val"> <?=$back_select[0]['EXPCUR']?> </span><span> %</span></span></td>
                  </tr>
              </tbody>
          </table>
       </div>
   </div>
  </form>

<?
}
if($_REQUEST['action']=='alternativeload')
{?>
 
   <? $fnl_query = select_query_json("select distinct Brn.Brncode Code,substr(brn.nicname,3,10) Brnname,brn.brnname Branch,Dep.expsrno, Dep.expname Exphead,Dep.Depname Department,dep.depcode,tar.ptdesc Ledgername,Tar.Ptnumb TargetNumber,bud.TARMONT,
round((bud.PURTVAL),2) FIXED_BUDGET, round(TOTBVAL,2) ALLOCATED_BUDGET  from 
trandata.non_purchase_target@tcscentr Tar, trandata.Department_asset@tcscentr Dep,trandata.branch@tcscentr Brn,
trandata.budget_planner_branch@tcscentr bud where
tar.depcode=dep.depcode and tar.brncode=brn.brncode and bud.depcode=dep.depcode and bud.brncode=brn.brncode  
and Tar.ptnumb=bud.tarnumb and bud.TARYEAR+1=to_char(sysdate, 'yy') and bud.TARMONT between to_char(sysdate,'MM')+1 and to_char(sysdate,'MM')+1
and bud.TARYEAR+1=to_char(ptfdate, 'yy') and bud.TARMONT between to_char(ptfdate,'MM')+1 and to_char(ptfdate,'MM')+1
and bud.brncode='".$_REQUEST['branch']."' and dep.Deleted='N'  and tar.ptnumb>9000 order by Dep.expsrno,Dep.expname,tar.ptnumb", "Centra", "TCS");
   $sum_approval_budget=0;
   foreach ($fnl_query as $key => $value) {
     $sum_approval_budget=$sum_approval_budget+$value['APPROVED_BUDGET'];
   }
   
   $arr=array();
   foreach ($fnl_query as $key => $value) 
   {  
      $key1=count($arr[$value['EXPSRNO']]);
      $arr[$value['EXPSRNO']][$key1]=$value;
   }
   $req_val=select_query_json("select nvl(sum(REQVALU),0) reqval from branch_budget_request where brncode='".$_REQUEST['branch']."' and TARMONT='".$_REQUEST['month']."' and taryear=2018  AND DELETED='N'", "Centra", "TEST");
   $last_sales=select_query_json("select nvl(SUM(SALESVAL),0) SALESVAL from trandata.NON_SALES_TARGET@tcscentr where BRNCODE='".$_REQUEST['branch']."' AND SALYEAR=TO_CHAR(SYSDATE,'YYYY')-1 AND SALMONT=TO_CHAR(SYSDATE,'MM')+1", "Centra", "TCS");
   //echo('<pre>');
   //print_r($req_val);
   //print_r($last_sales);
   //echo('</pre>');

   $open_search=array();
    $open_search = select_query_json("select distinct(expsrno),expname from trandata.department_asset@tcscentr where deleted='N' and expsrno>0 order by expsrno", "Centra", "TCS");?>

<div class="col-md-12" style='clear:both; padding-top: 10px; margin-top: 10px;height: 500px;'>
      <!-- START VERTICAL TABS WITH HEADING -->
      <div class="panel panel-default nav-tabs-vertical" >                    
        <input type="hidden" name="cal_req_val" id="cal_req_val" value="<?=$req_val[0]['REQVAL'];?>"/>
        <input type="hidden" name="cal_last_year_val" id="cal_last_year_val" value="<?=$last_sales[0]['SALESVAL'];?>"/>
        <input type="hidden" name="cal_sum_appr_budgt" id="cal_sum_appr_budgt" value="<?=$sum_approval_budget;?>"/>

          <div class="panel-heading ui-draggable-handle">
              <h3 class="panel-title">Expenses</h3>
              <center>
                <h4 style="padding-top: 10px;color: rgba(38, 26, 209, 1);" id="expensetitle">SALARY & BONUS</h4>
              </center>
          </div>
          <div class="tabs" id="data">
              <div class="col-md-12">
                <div class="tabs">
                    <div class="show_chk">
                      <ul class="nav nav-tabs" style="height:500px;overflow-y: scroll;overflow-x: hidden;width: 300px;">
                      <?for($i=0;$i<count($open_search);$i++){?>
                        <?if($i==0){?>
                          <li class="active"><a href="#tab<?=$i;?>" data-toggle="tab" id="ntab<?=$open_search[$i]['EXPSRNO']; ?>"><?=$open_search[$i]['EXPNAME'];?></a></li>
                        <?}else{?>
                          <li><a href="#tab<?=$i?>" data-toggle="tab" id="ntab<?=$open_search[$i]['EXPSRNO'];?>" ><?=$open_search[$i]['EXPNAME'];?></a></li>
                        <?}?>
                      <?}?>
                    </ul>
                    </div>
                                        
                    <div class="panel-body tab-content" style="border-left:1px solid #ADADAD;width: 75%;margin-left: 302px; min-height: 500px;">
                      

                      <?for($i=0;$i<count($open_search);$i++){?>

                        <? 
                           $open_search1=$arr[$open_search[$i]['EXPSRNO']];
                        ?>
                        <?if($i==0){?>
                          
                            <!-- /////////////// -->
                            <div class="tab-pane active" id="tab<?=$i;?>">

                            <form class="form-horizontal" role="form" id="frm_budget_entry<?=$i;?>" name="" action="" method="post" enctype="multipart/form-data">
                                  <input type="hidden" name="action" value="commit"/>
                                  <input type="hidden" name="tabid" value="<?=$i?>"/>
                                  <input type="hidden" name="brncode<? echo $i; ?>" id="brncode<? echo $i; ?>" value="">
                                  <input type="hidden" name="month<? echo $i; ?>" id="month<? echo $i; ?>" value="">
                                  <input type="hidden" name="form_last_sales<? echo $i; ?>" id="form_last_sales<? echo $i; ?>" value="1"/>
                                  <input type="hidden" name="form_lst_mnt_imp<? echo $i; ?>" id="form_lst_mnt_imp<? echo $i; ?>" value="1"/>
                                  <input type="hidden" name="form_tarsales<? echo $i; ?>" id="form_tarsales<? echo $i; ?>" value="1"/>
                                  <input type="hidden" name="form_cur_est<? echo $i; ?>" id="form_cur_est<? echo $i; ?>" value="1"/>
                                  <input type="hidden" name="form_dep_exp<? echo $i; ?>" id="form_dep_exp<? echo $i; ?>" value="1"/>
                                  <table id="ntable<?=$i;?>"  class="table datatable table-striped no-footer" style="margin-left: 20px;">
                                    <thead>
                                        <tr>
                                          <th class="center" style='text-align:center'>S.No</th>
                                          <th class="center" style='text-align:center'>TARGETN NO.</th>
                                          <th class="center" style='text-align:center'>LEDGER</th>
                                          <th class="center" style='text-align:center'>FIXED BUDGET</th>
                                          <th class="center" style='text-align:center'>ALLOCATED BUDGET</th>
                                          <th class="center" style='text-align:center'>APPROVED BUDGET</th>
                                          <th class="center" style='text-align:center'>REQUEST VALUE</th>
                                          <th class="center" style='text-align:left'>REASON</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                  
                                <?for($k=0;$k<count($open_search1);$k++)
                                {?>
                                    <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                        <td class="center" style='text-align:center;'>
                                         <?=$k+1;?>
                                        </td>
                                          <input type="hidden" name="expsrno[]" value="<? echo ($open_search1[$k]['EXPSRNO']); ?>">
                                        <td class="center" style='text-align:center'>
                                          <? echo $open_search1[$k]['TARGETNUMBER'] ?>
                                          <input type="hidden" name="targetno[]" value="<? echo ($open_search1[$k]['TARGETNUMBER']); ?>">
                                        </td>
                                        <td class="center" style='text-align:left;'>
                                          <? echo $open_search1[$k]['LEDGERNAME'] ?>
                                          <input type="hidden" name="depcode[]" value="<?echo ($open_search1[$k]['DEPCODE']); ?>">
                                        </td>
                                        <!-- /////////////// -->
                                        <td class="center" style='text-align:center;'>
                                          <? echo $open_search1[$k]['FIXED_BUDGET'] ?>
                                        </td>
                                        <td class="center" style='text-align:center;'>
                                          <? echo $open_search1[$k]['ALLOCATED_BUDGET'] ?>
                                        </td>
                                        <td class="center" style='text-align:center;'>
                                          0
                                        </td>
                                        <!-- ////////////////// -->
                                        <td class="center"  style='text-align:left'>
                                          <input style="width: 100%;height: 100%;" class="Number req_value_calculation<?=$i;?>" onkeyup="calculate('req_value_calculation<?=$i;?>');" type="number" name="value[]"/>
                                        </td>
                                        <td class="center"  style='text-align:left'>
                                         <input style="width: 100%;height: 100%;" type="text" name="reason[]"/>
                                        </td>
                                      </tr>
                                    <?
                                  } ?>
                                    </tbody>
                                </table>
                                <div  class="non-printable" style='clear:both; border-top:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;padding-top: 10px;'>
                                  <div class="input-group " style="margin: 10px;vertical-align: middle;float:left;">
                                          <button class="btn btn-primary" type="button" style="background-color: " onclick="nsubmit(<?=$i;?>);"><span class="fa fa-thumbs-up"></span> Submit</button>
                                  </div>
                                  <div class="input-group " style="margin: 10px;vertical-align: middle;float:right;">
                                            <button class="btn btn-warning" type="button"  onclick="clearForm('req_value_calculation<?=$i;?>');"> Clear Form</button>
                                  </div>
                                </div>
                          </form>
                            <!-- ////////////// -->
                          </div> 
                        <?}else{?>
                          <div class="tab-pane" id="tab<?=$i;?>">
                            <!-- /////////////////// -->
                            <form class="form-horizontal" role="form" id="frm_budget_entry<?=$i;?>" name="" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="commit"/>
                                    <input type="hidden" name="tabid" value="<?=$i?>"/>
                                    <input type="hidden" name="brncode<? echo $i; ?>" id="brncode<? echo $i; ?>" value="">
                                    <input type="hidden" name="month<? echo $i; ?>" id="month<? echo $i; ?>" value="">
                                    <input type="hidden" name="form_last_sales<? echo $i; ?>" id="form_last_sales<? echo $i; ?>" value="1"/>
                                    <input type="hidden" name="form_lst_mnt_imp<? echo $i; ?>" id="form_lst_mnt_imp<? echo $i; ?>" value="1"/>
                                    <input type="hidden" name="form_tarsales<? echo $i; ?>" id="form_tarsales<? echo $i; ?>" value="1"/>
                                    <input type="hidden" name="form_cur_est<? echo $i; ?>" id="form_cur_est<? echo $i; ?>" value="1"/>
                                    <input type="hidden" name="form_dep_exp<? echo $i; ?>" id="form_dep_exp<? echo $i; ?>" value="1"/>
                                    <table id="ntable<?=$i;?>"  class="table datatable table-striped no-footer" style="margin-left: 20px;">
                                      <thead>
                                          <tr>
                                            <th class="center" style='text-align:center'>S.No</th>
                                            <th class="center" style='text-align:center'>TARGETN NO.</th>
                                            <th class="center" style='text-align:center'>LEDGER</th>
                                            <th class="center" style='text-align:center'>FIXED BUDGET</th>
                                            <th class="center" style='text-align:center'>ALLOCATED BUDGET</th>
                                            <th class="center" style='text-align:center'>APPROVED BUDGET</th>
                                            <th class="center" style='text-align:center'>REQUEST VALUE</th>
                                            <th class="center" style='text-align:left'>REASON</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                    
                                  <?for($k=0;$k<count($open_search1);$k++)
                                  {?>    <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                          <td class="center" style='text-align:center;'>
                                           <?=$k+1;?>
                                          </td>

                                            <input type="hidden" name="expsrno[]" value="<? echo ($open_search1[$k]['EXPSRNO']); ?>">
                                          <td class="center" style='text-align:center'>
                                            <? echo $open_search1[$k]['TARGETNUMBER'] ?>
                                            <input type="hidden" name="targetno[]" value="<? echo ($open_search1[$k]['TARGETNUMBER']); ?>">
                                          </td>
                                          <td class="center" style='text-align:left;'>
                                            <? echo $open_search1[$k]['LEDGERNAME'] ?>
                                            <input type="hidden" name="depcode[]" value="<?echo ($open_search1[$k]['DEPCODE']); ?>">
                                          </td>
                                          <!-- //////////////// -->
                                          <td class="center" style='text-align:center;'>
                                            <? echo $open_search1[$k]['FIXED_BUDGET'] ?>
                                          </td>
                                          <td class="center" style='text-align:center;'>
                                            <? echo $open_search1[$k]['ALLOCATED_BUDGET'] ?>
                                          </td>
                                          <td class="center" style='text-align:center;'>
                                            0
                                          </td>
                                          <!-- ////////////////////// -->
                                          <td class="center"  style='text-align:left'>
                                            <input style="width: 100%;height: 100%;" class="Number req_value_calculation<?=$i;?>" onkeyup="calculate('req_value_calculation<?=$i;?>');" type="number"  name="value[]"/>
                                          </td>
                                          <td class="center"  style='text-align:left'>
                                           <input style="width: 100%;height: 100%;" type="text" name="reason[]"/>
                                          </td>
                                        </tr>
                                      <?
                                    } ?>
                                      </tbody>
                                  </table>
                                  <div  class="non-printable" style='clear:both; border-top:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;padding-top: 10px;'>
                                    <div class="input-group " style="margin: 10px;vertical-align: middle;float:left;">
                                            <button class="btn btn-primary" type="button" style="background-color: " onclick="nsubmit(<?=$i;?>);"><span class="fa fa-thumbs-up"></span> Submit</button>
                                    </div>
                                    <div class="input-group " style="margin: 10px;vertical-align: middle;float:right;">
                                            <button class="btn btn-warning" type="button"  onclick="clearForm('req_value_calculation<?=$i;?>');"> Clear Form</button>
                                    </div>
                                  </div>
                            </form>
                            <!-- ///////////////////// -->
                          </div>
                        <?}?>
                        
                       <?}?>                      
                    </div>
                </div>                        
      <!-- END VERTICAL TABS WITH HEADING -->
  </div>
          </div>
      </div>                        
      <!-- END VERTICAL TABS WITH HEADING -->
  </div>
  <script>
    
    function initTable(){
       <? //$back_select = select_query_json("select expsrno,DEPEXPP from branch_budget_request where brncode='".$_REQUEST['branch']."' and tarmont='".$_REQUEST['month']."' and taryear='".date("Y")."' and deleted='N' group by expsrno,DEPEXPP", "Centra", "TEST");
       $back_select = select_query_json("sELECT DISTINCT(EXPSRNO),
(SELECT MAX(DEPEXPP) FROM branch_budget_request B WHERE B.brncode='".$_REQUEST['branch']."' and B.tarmont='".$_REQUEST['month']."' and B.taryear='".date("Y")."' and B.deleted='N' AND B.EXPSRNO=A.EXPSRNO) CURDEPEXP
FROM branch_budget_request A
WHERE brncode='".$_REQUEST['branch']."' and tarmont='".$_REQUEST['month']."' and taryear='".date("Y")."' and deleted='N' ORDER BY EXPSRNO", "Centra", "TEST");
       //echo("select distinct(expsrno) from branch_budget_request where brncode='".$_REQUEST['branch']."' and tarmont='".$_REQUEST['month']."' and deleted='N'");?>

    <?//print_r($back_select);?>
    <?for($i=0;$i<count($back_select);$i++)
    {?>
      $('#ntab<?=$back_select[$i]['EXPSRNO'] ?>').css('background-color', 'rgba(76, 192, 22, 0.82)');
      var link=$('#ntab<?=$back_select[$i]['EXPSRNO'] ?>').attr('href');
      console.log(link);
      //$(link).css('opacity','0.4');
       //$(link).css('pointer-events','none');
      $('#ntab<?=$back_select[$i]['EXPSRNO'] ?>').css('opacity','0.7');
      var depexp=$('#ntab<?=$back_select[$i]['EXPSRNO'] ?>').html();
      $('#ntab<?=$back_select[$i]['EXPSRNO'] ?>').html(depexp+'<span style="float:right;"> (<?if($back_select[$i]['CURDEPEXP']==''){echo(0);}else{echo($back_select[$i]['CURDEPEXP']);}?>%)</span>');
    <?}
    ?>
      <?for($i=0;$i<count($open_search);$i++){?>
          $('#ntable<?=$i;?>').dataTable({
            "bSort": false
          });
      <?}?>
      }
  </script>
  
<?
}
if($_REQUEST['action']=='validate')
{   $tab=$_REQUEST['tabid'];
    $rst=0;
     if($_REQUEST['brncode'.$tab]!='' && $_REQUEST['brncode'.$tab]!='')
     {
       for($i=0;$i<count($_REQUEST['value']);$i++)
       {
         if($_REQUEST['value'][$i]=='' || $_REQUEST['value'][$i]<0)
        {
         $rst=1;
        }
      }
    }
    else{
      $rst=1;
    }
    echo($rst);
}
if($_REQUEST['action']=='approve')
{  $back_select = select_query_json("select distinct(expsrno) from branch_budget_request where brncode='".$_REQUEST['branch']."' and tarmont='".$_REQUEST['month']."' and taryear='".date("Y")."'", "Centra", "TEST");
  //print_r($back_select);
  //print_r($_REQUEST);
  $g_table = "branch_budget_request";
  $g_fld4 = array();
  foreach ($back_select as $key => $value) 
  {
    if($_REQUEST['chk_cnfrm'][$value['EXPSRNO']][0]==1)
    {
      //echo($value['EXPSRNO']." = "." Y\n");
      $g_fld['REQAPPR'] = 'Y';
      $g_fld['EDTUSER'] = $_SESSION['tcs_usrcode'];
      $g_fld['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
      $g_fld['DELETED'] = 'N';
      $g_fld['DELUSER'] = '';
      $g_fld['DELDATE'] = '';
    }
    else{
      //echo($value['EXPSRNO']." = "." N\n");
      $g_fld['REQAPPR'] = 'N';
      $g_fld['EDTUSER'] = '';
      $g_fld['EDTDATE'] = '';
      $g_fld['DELETED'] = 'Y';
      $g_fld['DELUSER'] = $_SESSION['tcs_usrcode'];
      $g_fld['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    }
    $where_appplan="TARYEAR=2018 AND BRNCODE=".$_REQUEST['branch']." AND TARMONT=".$_REQUEST['month']." AND EXPSRNO=".$value['EXPSRNO']."";
    print_r($g_fld);
    print_r($where_appplan);
    $insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where_appplan);
  }
}
// viki1
if($_REQUEST['action']=='overall')
{
  //print_r($_REQUEST);
  $back_select = select_query_json("select lstimpp,LSTSALES,TARSALES,(select nvl(max(CUREXPP),0) from branch_budget_request where brncode=1 and taryear=2018 and tarmont=10) expcur from branch_budget_request where brncode='".$_REQUEST['branch']."' and tarmont='".$_REQUEST['month']."' and rownum<=1", "Centra", "TEST");
  if(count($back_select)>0){
  ?>
  <table class="table table-bordered">
      <tbody> 
          <tr>
              <td style="background: #f1f5f9;width: 50%;">LAST YEAR SALES <?$month = date('M',strtotime('+1 month'));echo($month);?> - <?echo date('y')-1;?> (in Lakhs) </td>
              <td style="min-width: 50%;"><span id="txt_last_year_sale" name="txt_last_year_sale"> <?=$back_select[0]['LSTSALES']?> </span></td>
          </tr>
          <tr>
              <td style="background: #f1f5f9">LAST  MONTH IMPROVEMENT %</td>
              <td><span name="txt_lst_mnt_imp" id="txt_lst_mnt_imp"> <?=$back_select[0]['LSTIMPP']?> </span><span> %</span></td>
          </tr>
          <tr>
              <td style="background: #f1f5f9">TARGETED SALES (in Lakhs)</td>
              <td ><input type="number" disabled name="txt_tar_sal" id="txt_tar_sal" style="width: 100%;box-sizing: border-box;" onkeyup="calculate('a');" value="<?=$back_select[0]['TARSALES']?>"/></td>
          </tr>
          <tr>
              <td style="background: #f1f5f9">ESTIMATED EXP %</td>
              <td><span class="blinking" ><span id="txt_estimated_val" name="txt_estimated_val"> <?=$back_select[0]['EXPCUR']?> </span><span> %</span></span></td>
              <input type="hidden" name="txt_cur_exp" id="txt_cur_exp" value="<?=$back_select[0]['EXPCUR']?>"/>
          </tr>
          <tr>
              <td style="background: #f1f5f9">DEPARTMENT EXP %</td>
              <td><span class="blinking" ><span id="txt_dep_exp" name="txt_dep_exp"> 0 </span><span> %</span></span></td>
          </tr>
      </tbody>
  </table><!-- viki -->
  <?}else{?>
    <table class="table table-bordered">
        <tbody> 
            <tr>
                <td style="background: #f1f5f9;width: 50%;">LAST YEAR SALES <?$month = date('M',strtotime('+1 month'));echo($month);?> - <?echo date('y')-1;?> (in Lakhs) </td>
                <td style="min-width: 50%;"><span id="txt_last_year_sale" name="txt_last_year_sale"></span></td>
            </tr>
            <tr>
                <td style="background: #f1f5f9">LAST  MONTH IMPROVEMENT %</td>
                <td><span name="txt_lst_mnt_imp" id="txt_lst_mnt_imp"> 0 </span><span> %</span></td>
            </tr>
            <tr>
                <td style="background: #f1f5f9">TARGETED SALES (in Lakhs)</td>
                <td ><input type="number" class="Number" name="txt_tar_sal" id="txt_tar_sal" style="width: 100%;box-sizing: border-box;" onkeyup="calculate('a');" /></td>
            </tr>
            <tr>
                <td style="background: #f1f5f9">ESTIMATED EXP %</td>
                <td><span class="blinking" ><span id="txt_estimated_val" name="txt_estimated_val"> 0 </span><span> %</span></span></td>
                <input type="hidden" name="txt_cur_exp" id="txt_cur_exp" value=" 0 "/>
            </tr>
            <tr>
                <td style="background: #f1f5f9">DEPARTMENT EXP %</td>
                <td><span class="blinking" ><span id="txt_dep_exp" name="txt_dep_exp"> 0 </span><span> %</span></span></td>
            </tr>
        </tbody>
    </table>
    <?}?>
<?}
if($_REQUEST['action']=='approve_user')
{   
  $g_table="APPROVAL_BUDGET_HISTORY";

  //print_r($_REQUEST);
  for($i=0;$i<count($ptnumb);$i++)
  {
    if($_REQUEST['chk_cnfrm'][$_REQUEST['expsrno'][$i]])
    {
       $back_select = select_query_json("", "Centra", "TEST");

      $g_flf['ENTYEAR']=$_REQUEST['entyear'];
      $g_flf['TARYEAR']=$_REQUEST['taryear'];
      $g_flf['TARMONT']=$_REQUEST['tarmont'];
      $g_flf['EXPSRNO']=$_REQUEST['expsrno'][$i];
      $g_flf['DEPCODE']=$_REQUEST['depcode'][$i];
      $g_flf['REQVALU']=$_REQUEST['value'][$_REQUEST['expsrno'][$i]][$_REQUEST['ptnumb'][$i]][0];
      $g_flf['APPRMK']=$_REQUEST['txt_reason'][$i];
      $g_flf['ENTNUMB']=$_REQUEST['entnumb'];
      $g_flf['ADDUSER']=$_SESSION['tcs_usrcode'];
      $g_flf['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
      $g_flf['DELUSER']='';
      $g_flf['DELDATE']='';
      $g_flf['DELETED']='N';
      $g_flf['EDTUSER']='';
      $g_flf['EDTDATE']='';
      $g_flf['APPSTAT']=$_REQUEST['appstat'];
      $g_flf['APPSTAG']='';
      $g_flf['EMPSRNO']='';
      echo("------------".$i."-----------------");
      print_r($g_flf);
      $g_insert_subject = insert_test_dbquery($g_flf, $g_table);
    }
  }
}*/
