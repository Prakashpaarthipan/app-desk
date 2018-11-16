<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year


if($_REQUEST['action']=='load')
{       if($_REQUEST['expsrno']=='all')
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
        
        $max_search = select_query_json("select nvl(max(ENTNUMB),0)+1 MAXVAL from branch_budget_request", "Centra", 'TEST');

        $advt=array(8,9,39,40,41,42,43,44,45,46);
        $memb='';
        if(in_array($_REQUEST['expsrno'][0],$advt))
        {
          $memb=',177';
        }
        $sql_prdlist = select_query_json("select hea.empsrno,hea.empcode,emp.empname,emp.descode,hea.brnhdsr from trandata.approval_branch_head@tcscentr hea,trandata.employee_office@tcscentr emp,trandata.designation@tcscentr des where 
        emp.empsrno=hea.empsrno and emp.descode=des.descode and hea.brncode='".$_REQUEST['brncode'.$tab]."' and hea.deleted='N' and hea.aprvalu>0 
        and ((emp.empsrno in (188,19256,125,1682,452,21344,43400,20118,83815".$memb.")) or (emp.descode in (92,189) and emp.brncode not in (888) ) ) group by hea.empsrno,hea.empcode,emp.empname,emp.descode,hea.brnhdsr order by hea.brnhdsr" ,"Centra","TCS");
        $arr_head=array();
        foreach($sql_prdlist as $key => $value)
        {
          if($arr_head[$value['EMPSRNO']]=='')
          {
            $arr_head[$value['EMPSRNO']]=$value;
          }
        }
        $arr_head_sort=array();
        foreach($arr_head as $key => $value)
        {
          $arr_head_sort[$value['BRNHDSR']]=$value;
        }
        ksort($arr_head_sort);  
        //print_r($arr_head_sort);
        $i=0;
        $g_table="APPROVAL_BUDGET_FLOW_MASTER";
        foreach ($arr_head_sort as $key => $value) 
        { 
          $i++;
          $g_fld1['ENTNUMB']=$max_search[0]['MAXVAL'];
          $g_fld1['ENTYEAR']=$current_yr[0]['PORYEAR'];
          $g_fld1['EMPSRNO']=$value['EMPSRNO'];
          $g_fld1['APPSRNO']=$i;
          print_r($g_fld1);
          $g_insert_subject = insert_test_dbquery($g_fld1, $g_table);
        }
       
        // $g_table="APPROVAL_BUDGET_FLOW_MASTER";
        // $g_fld1['ENTNUMB']=$max_search[0]['MAXVAL'];
        // $g_fld1['ENTYEAR']=$current_yr[0]['PORYEAR'];
        // $g_fld1['EMPSRNO']='92631';
        // $g_fld1['APPSRNO']='1';
        // print_r($g_fld);
        // $g_insert_subject = insert_test_dbquery($g_fld1, $g_table);
        

        // $g_fld1['ENTNUMB']=$max_search[0]['MAXVAL'];
        // $g_fld1['ENTYEAR']=$current_yr[0]['PORYEAR'];
        // $g_fld1['EMPSRNO']='94095';
        // $g_fld1['APPSRNO']='2';
        // print_r($g_fld);
        // $g_insert_subject = insert_test_dbquery($g_fld1, $g_table);

        // $g_fld1['ENTNUMB']=$max_search[0]['MAXVAL'];
        // $g_fld1['ENTYEAR']=$current_yr[0]['PORYEAR'];
        // $g_fld1['EMPSRNO']='43878';
        // $g_fld1['APPSRNO']='3';
        // print_r($g_fld);
        // $g_insert_subject = insert_test_dbquery($g_fld1, $g_table);
        
         $usr_search = select_query_json("select empsrno from approval_budget_flow_master where entyear='".$current_yr[0]['PORYEAR']."' and entnumb='".$max_search[0]['MAXVAL']."' and appsrno='1'", "Centra", 'TEST');
         print_r($usr_search);
         echo("++++++++sELECT EMPSRNO FROM APPROVAL_BUDGET_FLOW_MASTER WHERE ENTYEAR='".$current_yr[0]['PORYEAR']."' AND ENTNUMB='".$max_search[0]['MAXVAL']."' APPSRNO=1+++");

        $g_table="BRANCH_BUDGET_REQUEST";
        for($i=0;$i<$nor;$i++)
        {  if($_REQUEST['value'][$i]>0)
            {
              $g_fld['ENTNUMB']=$max_search[0]['MAXVAL'];
              $g_fld['BRNCODE']=$_REQUEST['brncode'.$tab];
              $g_fld['PTNUMB']=$_REQUEST['targetno'][$i];
              $g_fld['TARMONT']=$_REQUEST['month'.$tab];
              $g_fld['REQSRNO']='1';
              $g_fld['EXPSRNO']=$_REQUEST['expsrno'][$i];
              $g_fld['DEPCODE']=$_REQUEST['depcode'][$i];
              $g_fld['REQVALU']=$_REQUEST['value'][$i];
              //$g_fld['APPVALU']=$_REQUEST['value'][$i];
              $g_fld['APPVALU']=$_REQUEST['value'][$i];
              $g_fld['REDVALU']='0';
              //changed
              $g_fld['BUDVALU']=$_REQUEST['fixed_budget'][$_REQUEST['expsrno'][$i]][$_REQUEST['targetno'][$i]];
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
              $g_fld['APPSTAG']='1';


              $g_fld['APPRESN']=strtoupper($_REQUEST['reason'][$i]);
              $g_fld['EMPSRNO']=$usr_search[0]['EMPSRNO'];
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
  $open_search = select_query_json("select brn.brnname,bbr.* from branch_budget_request bbr,branch brn where bbr.tarmont='".$_REQUEST['month']."' and bbr.taryear='".date('Y')."' and bbr.deleted='N' and bbr.brncode='".$_REQUEST['brncode']."' and reqappr='N' and brn.brncode=bbr.brncode", "Centra", 'TEST'); 
  $back_select = select_query_json("select LSTSALES,TARSALES,(select nvl(max(CUREXPP),0) from branch_budget_request where brncode='".$_REQUEST['brncode']."' and taryear='".date('Y')."' and tarmont='".$_REQUEST['month']."' and deleted='N') expcur from branch_budget_request where brncode='".$_REQUEST['brncode']."' and taryear='".date("Y")."' and tarmont='".$_REQUEST['month']."' and rownum<=1 and deleted='N'", "Centra", "TEST");
  $head_expense = select_query_json(" select EXPSRNO,ROUND(((SUM(APPVALU)/100000)/LSTSALES*100),2) EXPPER from branch_budget_request where BRNCODE='".$_REQUEST['brncode']."' AND TARMONT='".$_REQUEST['month']."' AND delEted='N' AND REQAPPR NOT IN('R') AND APPSTAG>=1 GROUP BY BRNCODE,EXPSRNO,TARSALES,LSTSALES", "Centra", 'TEST');
  //echo("select brn.brnname,bbr.* from branch_budget_request bbr,branch brn where bbr.tarmont='".$_REQUEST['month']."' and bbr.taryear='".date('Y')."' and bbr.deleted='N' and bbr.brncode='".$_REQUEST['brncode']."' and reqappr='N' and brn.brncode=bbr.brncode");
  //print_r($open_search); 
  $head_expense_key=array();
  foreach ($head_expense as $key => $value) 
  {
    $head_expense_key[$value['EXPSRNO']]=$value['EXPPER'];
  }
  $month=array('JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');
  ?>
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
            <th class="center" style='text-align:left'>HEAD EXPENSE</th>
             <th class="center" style='text-align:left'>DELETE</th>
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
              <?if($open_search[$k-1]['EXPSRNO']!=$open_search[$k]['EXPSRNO']){?>
                  <?=$head_expense_key[$open_search[$k]['EXPSRNO']];?>
              <?}?>
          </td>
          <td class="center"  style='text-align:center'>
           <input type="button" class="btn btn-danger" style="border-radius: 10px;" onclick="delete_entry('<?=date('Y');?>','<?=$_REQUEST['brncode']?>','<?=$_REQUEST['month']?>','<?=$open_search[$k]['EXPSRNO'];?>','<?=$open_search[$k]['PTNUMB'] ?>','<?=$open_search[$k]['DEPCODE'];?>','<?=$open_search[$k]['DEPEXPP'];?>','<?=$back_select[0]['EXPCUR']?>','<? echo $open_search[$k]['REQVALU'] ?>','<?=$back_select[0]['LSTSALES']?>','<?=$open_search[$k]['ENTNUMB']?>')" value="Delete">
          </td>
        </tr>
      <?
    } ?>
      </tbody>
  </table>
  <!-- viki2 -->
 <? 
    $estimated_expense = select_query_json(" select LSTSALES,TARSALES,ROUND(((SUM(APPVALU)/100000)/LSTSALES*100),2) ESTPER from branch_budget_request where BRNCODE='".$_REQUEST['brncode']."' AND TARMONT=11 AND delEted='N' AND REQAPPR NOT IN('R') AND APPSTAG>=1 GROUP BY BRNCODE,TARSALES,LSTSALES", "Centra", 'TEST');
 ?>
  <div class="row">
      <div class="col-md-7" style="left:20%;">
          <table class="table table-bordered">
              <tbody> 
                  <tr>
                      <td style="background: #f1f5f9;width: 50%;">LAST YEAR SALES <?$month = date('M',strtotime('+1 month'));echo($month);?> - <?echo date('y')-1;?> ( in Lakhs)</td>
                      <td style="min-width: 50%;"><span id="txt_last_year_sale" name="txt_last_year_sale"><?=$estimated_expense[0]['LSTSALES']?></span></td>
                  </tr>
                  <tr>
                      <td style="background: #f1f5f9">LAST  MONTH IMPROVEMENT %</td>
                      <td><span>0</span><span> %</span></td>
                  </tr>
                  <tr>
                      <td style="background: #f1f5f9">RUN TARGET ( Lakhs)</td>
                      <td ><span id="tar_val" ><?$val=0.1;$val=$val*$estimated_expense[0]['LSTSALES'];$val=$val+$estimated_expense[0]['LSTSALES'];echo(round($val,2));?></span></td>
                  </tr>
                  <tr>
                      <td style="background: #f1f5f9">TARGETED SALES (<span id="tar_val" ><?$val=$estimated_expense[0]['TARSALES']/100;$val=$val*$estimated_expense[0]['LSTSALES'];$val=$val+$estimated_expense[0]['LSTSALES'];echo(round($val,2));?></span> Lakhs)</td>
                      <td ><input type="number" disabled name="txt_tar_sal" id="txt_tar_sal" style="width: 100%;box-sizing: border-box;" onblur="calculate('a');" value="<?=$estimated_expense[0]['TARSALES']?>"/></td>
                  </tr>
                  <tr>
                      <td style="background: #f1f5f9">ESTIMATED EXP %</td>
                      <td><span ><span id="txt_estimated_val"> <?=$estimated_expense[0]['ESTPER']?> </span><span> %</span></span></td>
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
 
   <? $fnl_query = select_query_json("select distinct Brn.Brncode Code,substr(brn.nicname,3,10) Brnname,brn.brnname Branch,Dep.expsrno, Dep.expname Exphead,Dep.Depname Department,dep.depcode,tar.ptdesc Ledgername,Tar.Ptnumb TargetNumber,bud.TARMONT,round((bud.REQVAL),2) APPROVED_BUDGET,
round((bud.PURTVAL),2) FIXED_BUDGET, round(TOTBVAL,2) ALLOCATED_BUDGET  from 
trandata.non_purchase_target Tar, trandata.Department_asset Dep,trandata.branch Brn,
trandata.budget_planner_branch bud where
tar.depcode=dep.depcode and tar.brncode=brn.brncode and bud.depcode=dep.depcode and bud.brncode=brn.brncode  
and Tar.ptnumb=bud.tarnumb and bud.TARYEAR+1=to_char(sysdate, 'yy') and bud.TARMONT between to_char(sysdate,'MM')+1 and to_char(sysdate,'MM')+1
and bud.TARYEAR+1=to_char(ptfdate, 'yy') and bud.TARMONT between to_char(ptfdate,'MM')+1 and to_char(ptfdate,'MM')+1
and bud.brncode='".$_REQUEST['branch']."' and dep.Deleted='N'  and tar.ptnumb>9000 order by Dep.expsrno,Dep.expname,tar.ptnumb", "Centra", "TEST");
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
   $req_val=select_query_json("select nvl(sum(appvalu),0) reqval from branch_budget_request where brncode='".$_REQUEST['branch']."' and TARMONT='".$_REQUEST['month']."' and taryear='".date('Y')."'  AND DELETED='N'", "Centra", "TEST");
   $last_sales=select_query_json("select nvl(SUM(SALESVAL),0) SALESVAL from trandata.NON_SALES_TARGET@tcscentr where BRNCODE='".$_REQUEST['branch']."' AND SALYEAR=TO_CHAR(SYSDATE,'YYYY')-1 AND SALMONT=TO_CHAR(SYSDATE,'MM')+1", "Centra", "TCS");
   //echo('<pre>');
   //print_r($req_val);
   //print_r($last_sales);
   //echo('</pre>');
   if($_REQUEST['month']>=4 && $_REQUEST['month']<=6)
   {$quad=1;}
   if($_REQUEST['month']>=7 && $_REQUEST['month']<=9)
   {$quad=2;}
   if($_REQUEST['month']>=10 && $_REQUEST['month']<=12)
   {$quad=3;}
   if($_REQUEST['month']>=1 && $_REQUEST['month']<=3)
   {$quad=4;}
   //$quad=3;
   $open_search=array();
    $open_search = select_query_json("select distinct(expsrno),expname from trandata.department_asset@tcscentr where deleted='N' and expsrno>0 order by expsrno", "Centra", "TCS");
    $bud_xpense = select_query_json("select EXPSRNO,(budquarter_".$quad.")-(appquarter_".$quad.") resvalue from trandata.budget_planner_head_sum where  budyear='".$current_yr[0]['PORYEAR']."' and brncode='".$_REQUEST['branch']."'", "Centra", "TEST");
   // echo("select EXPSRNO,(budquarter_".$quad.")-(appquarter_".$quad.") resvalue from trandata.budget_planner_head_sum@tcscentr where  budyear='".$current_yr[0]['PORYEAR']."' and brncode='".$_REQUEST['branch']."'");?>
    <?$arr_res=array();
      foreach ($bud_xpense as $key => $value) 
      {
           $arr_res[$value['EXPSRNO']]=$value['RESVALUE'];
      }
    ?>
    <?
     $req_val_expense=select_query_json("select nvl(sum(appvalu),0) reqval,expsrno from branch_budget_request where brncode='".$_REQUEST['branch']."' and TARMONT='".$_REQUEST['month']."' and taryear='".date('Y')."'  AND DELETED='N' group by expsrno", "Centra", "TEST");
      $arr_res_exp=array();
      foreach ($req_val_expense as $key => $value) 
      {
           $arr_res_exp[$value['EXPSRNO']]=$value['REQVAL'];
      }
    ?>
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
                          <li><a href="#tab<?=$i;?>" data-toggle="tab" id="ntab<?=$open_search[$i]['EXPSRNO']; ?>"><?=$open_search[$i]['EXPNAME'];?></a></li>
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
                            <div class="pull-left">
                              <table class="table table-bordered">
                                <tbody>
                                  <tr>
                                    <td style="background: #f1f5f9;vertical-align: middle;">
                                      Reserved Value (in Lakhs ) : 
                                    </td>
                                    <td style="font-size: 20px;color: green;" id="resval_<?=$i;?>">
                                      <?$temp=intval($arr_res[$open_search[$i]['EXPSRNO']])-intval($arr_res_exp[$open_search[$i]['EXPSRNO']]);
                                      $reserve=number_format((float)(intval(intval($temp))/100000), 2, '.', '');
                                      echo($reserve);?>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <!-- viki current_yr -->
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
                                          <th class="center" style='text-align:center'>ALLOCATED BUDGET</th>
                                          <th class="center" style='text-align:center'>FIXED BUDGET</th>
                                          <th class="center" style='text-align:center'>RESERVED BUDGET</th>
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
                                          <? echo $open_search1[$k]['ALLOCATED_BUDGET'] ?>
                                        </td>
                                        <td class="center" style='text-align:center;'>
                                          <? echo $open_search1[$k]['FIXED_BUDGET'] ?>
                                          <!-- //changed -->
                                          <input type="hidden" name="fixed_budget[<? echo ($open_search1[$k]['EXPSRNO']); ?>][<? echo ($open_search1[$k]['TARGETNUMBER']); ?>]" id="fixed_budget[<? echo ($open_search1[$k]['EXPSRNO']); ?>][<? echo ($open_search1[$k]['TARGETNUMBER']); ?>]" value="<? echo $open_search1[$k]['FIXED_BUDGET'] ?>"/>
                                        </td>
                                        <td class="center" style='text-align:center;'>
                                          <? echo $open_search1[$k]['APPROVED_BUDGET'] ?>
                                        </td>
                                        <!-- ////////////////// -->
                                        <td class="center"  style='text-align:left'>
                                          <input style="width: 100%;height: 100%;" id="reqval_<?=$i;?>_<?=$k;?>" maxlength="8" class="Number req_value_calculation<?=$i;?>" onkeyup="calculate('req_value_calculation<?=$i;?>');" type="text" value="0" name="value[]"/>
                                        </td>
                                        <td class="center"  style='text-align:left'>
                                         <input style="width: 100%;height: 100%;" id="reqreason_<?=$i;?>_<?=$k;?>" class="reason" maxlength="100" type="text" name="reason[]"/>
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
                            <div class="pull-left">
                              <table class="table table-bordered">
                                <tbody>
                                  <tr>
                                    <td style="background: #f1f5f9;vertical-align: middle;">
                                      Reserved Value (in Lakhs ) : 
                                    </td>
                                    <td style="font-size: 20px;color: green;" id="resval_<?=$i;?>">
                                      <?$temp=intval($arr_res[$open_search[$i]['EXPSRNO']])-intval($arr_res_exp[$open_search[$i]['EXPSRNO']]);
                                      $reserve=number_format((float)(intval(intval($temp))/100000), 2, '.', '');
                                      echo($reserve);?>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <!-- viki/////////////////// -->
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
                                            <th class="center" style='text-align:center'>ALLOCATED BUDGET</th>
                                            <th class="center" style='text-align:center'>FIXED BUDGET</th>
                                            <th class="center" style='text-align:center'>RESERVED BUDGET</th>
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
                                            <? echo $open_search1[$k]['ALLOCATED_BUDGET'] ?>
                                          </td>
                                          <td class="center" style='text-align:center;'>
                                            <? echo $open_search1[$k]['FIXED_BUDGET'] ?>
                                            <input type="hidden" name="fixed_budget[<? echo ($open_search1[$k]['EXPSRNO']); ?>][<? echo ($open_search1[$k]['TARGETNUMBER']); ?>]" id="fixed_budget[<? echo ($open_search1[$k]['EXPSRNO']); ?>][<? echo ($open_search1[$k]['TARGETNUMBER']); ?>]" value="<? echo $open_search1[$k]['FIXED_BUDGET'] ?>"/>
                                          </td>
                                          <td class="center" style='text-align:center;'>
                                            <? echo $open_search1[$k]['APPROVED_BUDGET'] ?>
                                          </td>
                                          <!-- ////////////////////// -->
                                          <td class="center"  style='text-align:left'>
                                            <input style="width: 100%;height: 100%;" id="reqval_<?=$i;?>_<?=$k;?>" maxlength="8" class="Number req_value_calculation<?=$i;?>" onkeyup="calculate('req_value_calculation<?=$i;?>');" value="0" type="text"  name="value[]"/>
                                          </td>
                                          <td class="center"  style='text-align:left'>
                                           <input style="width: 100%;height: 100%;" id="reqreason_<?=$i;?>_<?=$k;?>" class="reason" maxlength="100" type="text" name="reason[]"/>
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
      $('#ntab<?=$back_select[$i]['EXPSRNO'] ?>').html(depexp+'<span style="float:right;"> |<?if($back_select[$i]['CURDEPEXP']==''){echo(0);}else{echo($back_select[$i]['CURDEPEXP']);}?>%)</span>');
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
  $back_select = select_query_json("select lstimpp,LSTSALES,TARSALES,(select nvl(max(CUREXPP),0) from branch_budget_request where brncode='".$_REQUEST['branch']."' and taryear='".date('Y')."' and tarmont='".$_REQUEST['month']."' and deleted='N') expcur from branch_budget_request where brncode='".$_REQUEST['branch']."' and tarmont='".$_REQUEST['month']."' and rownum<=1 and deleted='N'", "Centra", "TEST");
 // echo("select lstimpp,LSTSALES,TARSALES,(select nvl(max(CUREXPP),0) from branch_budget_request where brncode='".$_REQUEST['branch']."' and taryear='".date('Y')."' and tarmont='".$_REQUEST['month']." and deledted='N') expcur from branch_budget_request where brncode='".$_REQUEST['branch']."' and tarmont='".$_REQUEST['month']."' and rownum<=1 and deleted='N'");
  $last_sales=select_query_json("select nvl(SUM(SALESVAL),0) SALESVAL from trandata.NON_SALES_TARGET@tcscentr where BRNCODE='".$_REQUEST['branch']."' AND SALYEAR=TO_CHAR(SYSDATE,'YYYY')-1 AND SALMONT=TO_CHAR(SYSDATE,'MM')+1", "Centra", "TCS");
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
              <td style="background: #f1f5f9">RUN TARGET ( Lakhs)</td>
              <td ><span id="tar_val" ><?$val=0.1;$val=$val*$back_select[0]['LSTSALES'];$val=$val+$back_select[0]['LSTSALES'];echo(round($val,2));?></span></td>
          </tr>
          <tr>
              <td style="background: #f1f5f9">TARGETED SALES (<span id="tar_val" ><?$val=$back_select[0]['TARSALES']/100;$val=$val*$back_select[0]['LSTSALES'];$val=$val+$back_select[0]['LSTSALES'];echo(round($val,2));?></span> Lakhs)</td>
              <td ><input type="number" disabled name="txt_tar_sal" id="txt_tar_sal" style="width: 100%;box-sizing: border-box;" onblur="calculate('a');" value="<?=$back_select[0]['TARSALES']?>"/></td>
          </tr>
          <tr>
              <td style="background: #f1f5f9">ESTIMATED EXP % : <span class="blinking" ><span id="txt_estimated_val" name="txt_estimated_val"> <?=$back_select[0]['EXPCUR']?> </span> %</span></td>
              <td style="background: #f1f5f9">DEPARTMENT EXP % : <span class="blinking" id="txt_dep_exp" name="txt_dep_exp"> 0 </span><span class="blinking"> %</span></span></td>
              <input type="hidden" name="txt_cur_exp" id="txt_cur_exp" value="<?=$back_select[0]['EXPCUR']?>"/>
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
                <td style="background: #f1f5f9">RUN TARGET ( Lakhs)</td>
                <td ><span><?$val=0.1;$val=$val*$last_sales[0]['SALESVAL'];$val=$val/100000;$val=$val+($last_sales[0]['SALESVAL']/100000);echo(round($val,2));?></span></td>
            </tr>
            <tr>
                <td style="background: #f1f5f9">TARGETED SALES (<span id="tar_val" ></span> Lakhs)</td>
                <td ><input type="text" class="Number" name="txt_tar_sal" id="txt_tar_sal" value="0" maxlength="5" style="width: 100%;box-sizing: border-box;" onblur="calculate('a');" /></td>
            </tr>
            <tr>
              <td style="background: #f1f5f9">ESTIMATED EXP % : <span class="blinking" ><span id="txt_estimated_val" name="txt_estimated_val"> 0 </span> % </span></td>
              <td style="background: #f1f5f9">DEPARTMENT EXP % : <span class="blinking" id="txt_dep_exp" name="txt_dep_exp"> 0 </span><span class="blinking"> %</span></td>
              <input type="hidden" name="txt_cur_exp" id="txt_cur_exp" value="0"/>
          </tr>
        </tbody>
    </table>
    <?}?>
<?}
if($_REQUEST['action']=='approve_user')
{   
  

  print_r($_REQUEST);
  for($i=0;$i<count($ptnumb);$i++)
  {
    if($_REQUEST['chk_cnfrm'][$_REQUEST['expsrno'][$i]][0]!='N')
    {
       $back_select = select_query_json("select DISTINCT(nvl(max(aphsrno),0))+1 MAXVAL from approval_budget_history where taryear='".$_REQUEST['taryear'][$_REQUEST['expsrno'][$i]]."' and tarmont='".$_REQUEST['tarmont'][$_REQUEST['expsrno'][$i]]."' and expsrno='".$_REQUEST['expsrno'][$i]."' and entnumb='".$_REQUEST['entnumb'][$i]."'", "Centra", "TEST");
       //print_r($back_select);
      $app_nxt_user = select_query_json("select empsrno,appsrno from approval_budget_flow_master WHERE APPSRNO='".($appstag[$i]+1)."'", "Centra", "TEST");
      $master = select_query_json("select max(appsrno) max from approval_budget_flow_master", "Centra", "TEST");
      //print_r($app_nxt_user);
      $g_table="APPROVAL_BUDGET_HISTORY";
      $g_flf['APHSRNO']=$back_select[0]['MAXVAL'];
      $g_flf['ENTYEAR']=$_REQUEST['entyear'][$_REQUEST['expsrno'][$i]];
      $g_flf['TARYEAR']=$_REQUEST['taryear'][$_REQUEST['expsrno'][$i]];
      $g_flf['TARMONT']=$_REQUEST['tarmont'][$_REQUEST['expsrno'][$i]];
      $g_flf['EXPSRNO']=$_REQUEST['expsrno'][$i];
      $g_flf['PTNUMB']=$_REQUEST['ptnumb'][$i];
      $g_flf['DEPCODE']=$_REQUEST['depcode'][$i];
      $g_flf['REQVALU']=$_REQUEST['reqvalue'][$_REQUEST['expsrno'][$i]][$_REQUEST['ptnumb'][$i]][0];
      $g_flf['APPVAL']=$_REQUEST['appvalue'][$_REQUEST['expsrno'][$i]][$_REQUEST['ptnumb'][$i]][0];
      $g_flf['APPRMK']=strtoupper($_REQUEST['txt_reason'][$i]);
      $g_flf['ENTNUMB']=$_REQUEST['entnumb'][$i];
      $g_flf['ADDUSER']=$_SESSION['tcs_usrcode'];
      $g_flf['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
      $g_flf['DELUSER']='';
      $g_flf['DELDATE']='';
      $g_flf['DELETED']='N';
      $g_flf['EDTUSER']='';
      $g_flf['EDTDATE']='';
      $g_flf['APPSTAT']=$_REQUEST['chk_cnfrm'][$_REQUEST['expsrno'][$i]][0];
      $g_flf['APPSTAG']=$appstag[$i];
      $g_flf['EMPSRNO']=$_SESSION['tcs_empsrno'];
      $g_flf['CUREXPP']=$_REQUEST['new_cur_expense'];
      $g_flf['DEPEXPP']=$_REQUEST['new_dep_expense'][$_REQUEST['expsrno'][$i]];
      echo("------------".$i."-----------------\n");
      print_r($g_flf);
      $g_insert_subject = insert_test_dbquery($g_flf, $g_table);
      echo("\n");
    }
  }
}
if($_REQUEST['action']=='delete')
{
  //print_r($_REQUEST);
  
  $g_table='BRANCH_BUDGET_REQUEST';
  $g_fld['DELETED'] = 'Y';
  $g_fld['DELUSER'] = $_SESSION['tcs_usrcode'];
  $g_fld['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
  $where_appplan="TARYEAR='".$_REQUEST['taryear']."' AND BRNCODE='".$_REQUEST['brncode']."' AND TARMONT='".$_REQUEST['tarmont']."' AND EXPSRNO='".$_REQUEST['expsrno']."' AND PTNUMB='".$_REQUEST['tarnumb']."' AND DEPCODE='".$_REQUEST['depcode']."' AND ENTNUMB='".$_REQUEST['entnumb']."'";
  print_r($where_appplan);
  $insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where_appplan);

  $g_table1='BRANCH_BUDGET_REQUEST';
  $per_ded=(intval($_REQUEST['reqval'])/(intval($_REQUEST['lastsal']*100000)))*100;
  echo("per_ded = ".$per_ded);

  $g_fld2['CUREXPP'] = number_format((float)($_REQUEST['curexpp']-$per_ded), 2, '.', '');
  $where_appplan2=" taryear='".$_REQUEST['taryear']."' AND TARMONT='".$_REQUEST['tarmont']."' and entnumb='".$_REQUEST['entnumb']."'";
  $insert_appplan2 = update_test_dbquery($g_fld2, $g_table1, $where_appplan2);

  $g_fld1['DEPEXPP'] = number_format((float)($_REQUEST['depexpp']-$per_ded), 2, '.', '');
  $where_appplan1=" taryear='".$_REQUEST['taryear']."' and entnumb='".$_REQUEST['entnumb']."' and expsrno='".$_REQUEST['expsrno']."'";
  print_r($g_fld1);
  print_r($where_appplan1);
  $insert_appplan1 = update_test_dbquery($g_fld1, $g_table1, $where_appplan1);
}
?>