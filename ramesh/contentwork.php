<div id="post-<?php the_ID();?>" <?php post_class(); ?>>
  <div>
<style type="text/css">
   .rounded {
  background-color: #666;
  color: #fff;
  font-weight: bold;
  padding: 10px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px; }
 </style>	  
<div id="post-<?php the_ID();?>" <?php post_class();?>> 

 <style type="text/css">
   .rounded {
  background-color: #666;
  color: #fff;
  font-weight: bold;
  padding: 10px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px; }
 </style>
	  
		   

		   
  <div>
	 
	 
   <?php 



    if (is_page('21')) {

      echo '<ul class="nav nav-tabs" role="tablist">';
$args = array(
    'hide_empty'=> 1,
    'orderby' => 'name',
    'order' => 'ASC'
);
$categories = get_categories($args);
foreach($categories as $category) { 
    echo 
        '<li>
            <a href="#'.$category->slug.'" role="tab" data-toggle="tab">    
                '.$category->name.'
            </a>
        </li>';
}
echo '</ul>';

echo '<div class="tab-content">';
foreach($categories as $category) { 



    echo '<div class="tab-pane"  id="' . $category->slug.'">';
    $the_query = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => 100,
        'category_name' => $category->slug
    ));

    while ( $the_query->have_posts() ) : 
    $the_query->the_post();
    echo '<div style="height:20px;"></div><p><i class="fa fa-arrow-right" aria-hidden="true"></i>';

        the_title();
    echo '<a  class="btn btn-default" style="float:right" href="'.get_permalink().'">Readmore</a>';
    endwhile; 
    //2018/01/09/testing-chennai-branch/
   // wp_reset_postdata();
    //.$is_year."/".$is_month."/".$is_date."/".get_the_title()
    //'.get_site_url()."/".date("Y/m/d")."/".(str_replace(' ', '-', strtolower(get_the_title()))).'
    echo '<p style="border:1px solid #fff;"> </p>';
    echo '</div>';
} 

echo '</div>';

      
    }

else if(is_page('50'))
{
  ?>




 

  <?php

$exsite_url = "http://portal.thechennaisilks.com/";
extract($_REQUEST);
    
    echo do_shortcode('[wp-datatable id="empreport_branch" fat="LEVEL"]
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]');
      
      echo '<p>'.the_title().'</p>';
      error_reporting(0);
      
    
      // $and = " And tar.payyear=".date("Y")." And tar.paymont=".date("m")." ";
    $and = "";
      
      if($_REQUEST['slt_brn'] != '') {
       /* $and .= " and EMP.BRNCODE in (".$_REQUEST['selected_empreport_branch'].") ";*/
        $and .= " And tar.BRNCODE='".$_REQUEST['slt_brn']."' ";
      }


      if($_REQUEST['slt_section'] != ''){
          $and  .= " And tar.esecode='".$_REQUEST['slt_section']."' ";
      } 
       
          
      
//echo $and;

           $result = select_query_json("select tar.payyear, tar.paymont, tar.BRNCODE, substr(regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10),1,50) branch, tar.esecode, ese.esename, tar.rangemode, 
  sum(tar.PART1_TARQNTY+tar.PART2_TARQNTY+tar.PART3_TARQNTY+tar.PART4_TARQNTY+tar.PART5_TARQNTY) tarqty, 
  round(sum(tar.PART1_TARVALUE+tar.PART2_TARVALUE+tar.PART3_TARVALUE+tar.PART4_TARVALUE+tar.PART5_TARVALUE) / 10000, 2) tarval,
  sum(tar.PART1_SALQTY+tar.PART2_SALQTY+tar.PART3_SALQTY+tar.PART4_SALQTY+tar.PART5_SALQTY) salqty, 
  round(sum(tar.PART1_SALVAL+tar.PART2_SALVAL+tar.PART3_SALVAL+tar.PART4_SALVAL+tar.PART5_SALVAL) / 10000, 2) salval
from trandata.attn_target_mode_section@tcscentr tar, trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn 
where ese.esecode=tar.esecode and tar.brncode=brn.brncode  and tar.payyear=2018 and tar.paymont=1 ".$and."  
group by tar.payyear, tar.paymont, tar.BRNCODE, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10), tar.esecode, ese.esename, tar.rangemode 
order by tar.payyear, tar.paymont, tar.BRNCODE, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10), ese.esename 
");    

    

      ?>  

  <form name="frm_custom_employee" id="frm_custom_employee" method="post" action="">
     
     <div class="container rounded">
          
          <div class="col-md-2" style="border:1px solid lightgrey;border-radius: 4px;">
            <p style="text-align: center;padding-top:3px;"><?php echo date('F-Y');  ?> </p>
          </div>


          <div class="col-md-4">

            <select tabindex="1" name="slt_brn" id="slt_brn" onchange="select_floor()" class="form-control custom-select"  style="margin-left:0px">
                <option value=""> Choose Any Branch </option>
                
                <?php $sql_branch = select_query_json("Select brncode,substr(nicname,3) brnname from Branch where deleted='N'  order by BRNCODE");
                  foreach($sql_branch as $branchrow) { ?>
                    <option value="<?php echo $branchrow['BRNCODE']?>" <?php if($_REQUEST['slt_brn'] == $branchrow['BRNCODE']) { ?>selected<?php } ?>><?php echo $branchrow['BRNNAME']?></option>
                <?php } ?>
              </select>


          </div> 

          <div class="col-md-4">
          <select name="slt_section" id="slt_section" class="form-control">
                       <option value="">-- All Section --</option>
                       <?php 
                        $sql_section = select_query_json("select distinct S.Secsrno, S.SECCODE, S.SECNAME 
                                        from section s, section_group_report g 
                                        where s.seccode=g.seccode and s.grpsrno not in (3) 
                                          and s.secname not like '%ALBUM%' and S.deleted = 'N'
                                        order by S.SECSRNO Asc");
                        foreach($sql_section as $sectionrow) { ?>
                <option value="<?php echo $sectionrow['SECCODE']?>" <?php if($_REQUEST['slt_section'] == $sectionrow['SECCODE']) { ?> selected <?php } ?>><?php echo $sectionrow['SECNAME']?></option>
                       <?php } ?>
                     </select>

          </div>  


      

          <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-info">Search</button>
          </div>

        </div>

    </form>


      
 


            <div class="table-responsive">
     
            <table class="table table-striped table-bordered table-hover" id="empreport_branch" style="font-size:14px;">
        
        
      <thead>
        <tr>
          <th class="colth">
            S.No
          </th>
          <th class="colth">
            Year
          </th>
          <th class="colth">
            Payment
          </th>
          <th class="colth">
            Branch Code
          </th>
          <th class="colth">
            Branch Name
          </th>
          <th class="colth">
            Section Code
          </th>
          <th class="colth">
            Section Name
          </th>
          <th class="colth">
            Range Mode
          </th>
          <th class="colth">
            Target Quantity
          </th>
          <th class="colth">
            Target Value
          </th>
          <th class="colth">
            Sales Quantity
          </th>
          <th class="colth">
            Sales Value
          </th>
        </tr>
        </thead>
        <tbody>
        
      <?php
      $ga_i = 0;
      foreach($result as $res)
      {
        $ga_i++; 
        if($ga_i % 2 == 1) {
          $clsname = "coltd_grid1";
        } else {
          $clsname = "coltd_grid2";
        } ?>
        

                                                                                

        <tr class="<?php $clsname?>">
          <td style="text-align:center;">
            <?php echo $ga_i;  ?>
          </td>
          <td style="text-align:center;">
            <?php echo $res['PAYYEAR'];  ?>
          </td>
          <td style="text-align:center;">
            <?php echo $res['PAYMONT'];  ?>
          </td>
          <td style="text-align:left;">
            <?php echo $res['BRNCODE'];  ?>
          </td>
          <td style="text-align:left;">
            <?php echo $res['BRANCH'];  ?>
          </td>
          <td style="text-align:left;">
            <?php echo $res['ESECODE'];  ?>
          </td>
          <td style="text-align:center;">
            <?php echo $res['ESENAME'];  ?>
          </td>
          <td style="text-align:center;">
            <?php echo $res['RANGEMODE'];  ?>
          </td>
          <td style="text-align:center;">
            <?php echo $res['TARQTY'];  ?>
          </td>
          <td style="text-align: center;">
            <?php echo $res['TARVAL']; ?>
          </td>
          <td style="text-align:center;">
            <?php echo $res['SALQTY'];  ?>
          </td>
          <td style="text-align: center;">
            <?php echo $res['SALVAL']; ?>
          </td>

        </tr>
        
      <?php
        //var_dump();
      } ?>
      </tbody>
      </table>
      </div>
      </div>
    
     <?php
}

  

 else if (is_page('439')) {
 
$exsite_url = "http://portal.thechennaisilks.com/";
	extract($_REQUEST);
	 
	 /*
	 $and = "";
      
      if($_REQUEST['emp_brn'] != '') {
        $and .= " And emp.BRNCODE='".$_REQUEST['emp_brn']."' ";
      }


      if($_REQUEST['emp_section'] != ''){
          $and  .= " And emp.esecode='".$_REQUEST['emp_section']."' ";
      } 



    ?>
    <form name="frm_custom_employee1" id="frm_custom_employee1" method="post" action="">

<style type="text/css">
        .bordered {
   
    padding: 20px;
    border: 1px solid #666;
    border-radius: 8px;
   width:100%;
	}
</style>

          <div class="container bordered" >
          
          <div class="col-md-2" style="border:1px solid lightgrey;border-radius: 4px;">
            <p style="text-align: center;padding-top:3px;"><?php echo date('F-Y');  ?> </p>
          </div>


          <div class="col-md-4">

            <select tabindex="1" name="emp_brn" id="emp_brn" onchange="select_floor()" class="form-control custom-select"  style="margin-left:0px">
                <option value=""> Choose Any Branch </option>
                
                <?php $sql_branch = select_query_json("Select brncode,substr(nicname,3) brnname from Branch where deleted='N'  order by BRNCODE");
                  foreach($sql_branch as $branchrow) { ?>
                    <option value="<?php echo $branchrow['BRNCODE']?>" <?php if($_REQUEST['slt_brn'] == $branchrow['BRNCODE']) { ?>selected<?php } ?>><?php echo $branchrow['BRNNAME']?></option>
                <?php } ?>
              </select>


          </div> 

          <div class="col-md-4">
          <select name="emp_section" id="emp_section" class="form-control">
                       <option value="">-- All Section --</option>
                       <?php 
                        $sql_section = select_query_json("select distinct S.Secsrno, S.SECCODE, S.SECNAME 
                                        from section s, section_group_report g 
                                        where s.seccode=g.seccode and s.grpsrno not in (3) 
                                          and s.secname not like '%ALBUM%' and S.deleted = 'N'
                                        order by S.SECSRNO Asc");
                        foreach($sql_section as $sectionrow) { ?>
                <option value="<?php echo $sectionrow['SECCODE']?>" <?php if($_REQUEST['slt_section'] == $sectionrow['SECCODE']) { ?> selected <?php } ?>><?php echo $sectionrow['SECNAME']?></option>
                       <?php } ?>
                     </select>

          </div>  


      

          <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-info">Search</button>
          </div>

        </div>

      </form>
      <?php
	 */
      
      
   
   echo do_shortcode('[wp-datatable id="empreport_branch" fat="LEVEL"]
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]');
      
      error_reporting(0);
      
      $result = select_query_json("select det.brncode, regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch, sum(TOTAL_TARVALUE) target, 
                                              sum(SALES_VALUE-EXCH_SALVALUE) salesval, round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) target_percentage  
                                          from attn_tar_sec_daywise_detail det, empsection ese, branch brn 
                                          where det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' and det.brncode in(1,10,14,23) 
                                          group by det.brncode, brn.nicname 
                                          order by TARGET_PERCENTAGE desc"); 
      ?>  
     <div class="table-responsive col-md-6">
       <table class="table table-striped table-bordered table-hover" id="empreport_branch" style="font-size:14px;">
        <thead>
          <tr>
            <th style="text-transform: uppercase;border: 1px solid;text-align: center;" colspan="4">
              Branch Rankwise
            </th>
          </tr>

          <tr>
            <th class="colth" style="text-align: center;">
              SR. NO
            </th>
            <th class="colth" style="text-align: center;">
              BRANCH
            </th>
            <th class="colth" style="text-align: center;">
              TARGET PERCENTAGE
            </th>
            <th class="colth" style="text-align: center;">
              RANK
            </th>
          </tr>
          </thead>
          <tbody>
          
        <?php
        $ga_i = 0;
        foreach($result as $res)
        {
          $ga_i++; 
          if($ga_i % 2 == 1) {
            $clsname = "coltd_grid1";
          } else {
            $clsname = "coltd_grid2";
          } ?>
          
          <tr class="<?php echo $clsname?>">        
            <td style="text-align:center;"><?php echo $ga_i;  ?></td>
            <td style="text-align:center;">
              <?php echo $res['BRANCH'];  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['TARGET_PERCENTAGE'];  ?>
            </td>      
            <td style="text-align:center;">
              <span style="background-color: #00a65a;font-weight:bold;font-size: 20px;color:#FFFFFF;padding: 0px 10px;"><?php echo $ga_i;  ?></span>
            </td>  
          </tr>
          
        <?php
        } ?>
        </tbody>
        </table>
      </div>
    
	<div style="height:10px;line-height: 25px;border-bottom: 2px dashed red;margin-bottom: 10px;">&nbsp;</div>
  <? 
	echo do_shortcode('[wp-datatable id="empreport_section" fat="LEVEL"]
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]'); 
  $result = select_query_json("select * from (
                                        select ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, 
                                            round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) target_percentage  
                                        from attn_tar_sec_daywise_detail det, empsection ese, branch brn 
                                        where det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' and det.brncode in(1,10,14,23) 
                                        group by ese.esecode, ese.esename 
                                        order by TARGET_PERCENTAGE desc) 
                                        where rownum <= 3"); ?>
      <div class="table-responsive col-md-6">
       <table class="table table-striped table-bordered table-hover" id="empreport_section" style="font-size:14px;">
        <thead>
          <tr>
            <th style="text-transform: uppercase;border: 1px solid;text-align: center;" colspan="4">
              Individual Section Rankwise
            </th>
          </tr>

          <tr>
            <th class="colth" style="text-align: center;">
              SR. NO
            </th>
            <th class="colth" style="text-align: center;">
              SECTION
            </th>
            <th class="colth" style="text-align: center;">
              TARGET PERCENTAGE
            </th>
            <th class="colth" style="text-align: center;">
              RANK
            </th>
          </tr>
          </thead>
          <tbody>
          
        <?php
        $ga_i = 0;
        foreach($result as $res)
        {
          $ga_i++; 
          if($ga_i % 2 == 1) {
            $clsname = "coltd_grid1";
          } else {
            $clsname = "coltd_grid2";
          } ?>
          
          <tr class="<?php echo $clsname?>">       
            <td style="text-align:center;"><?php echo $ga_i;  ?></td>
            <td style="text-align:center;">
              <?php echo $res['ESENAME'];  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['TARGET_PERCENTAGE'];  ?>
            </td>    
            <td style="text-align:center;">
              <span style="background-color: #00a65a;font-weight:bold;font-size: 20px;color:#FFFFFF;padding: 0px 10px;"><?php echo $ga_i;  ?></span>
            </td>  
          </tr>
          
        <?php
        } ?>
        </tbody>
        </table>
      </div>

	<div style="height:10px;line-height: 25px;border-bottom: 2px dashed red;margin-bottom: 10px;">&nbsp;</div>

<?php
echo do_shortcode('[wp-datatable id="empreport_brnwisesection" fat="LEVEL"]
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]'); 
  $result = select_query_json("select * from (select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) target_percentage from trandata.attn_tar_sec_daywise_detail@tcscentr det, 
trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' and det.brncode in(1) group by Brn.BrnCode,Brn.NicName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3 
Union
select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) target_percentage from trandata.attn_tar_sec_daywise_detail@tcscentr det, 
trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' and det.brncode in(10) group by Brn.BrnCode,Brn.NicName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3
Union
select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) target_percentage from trandata.attn_tar_sec_daywise_detail@tcscentr det, 
trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' and det.brncode in(14) group by Brn.BrnCode,Brn.NicName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3
Union
select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) target_percentage from trandata.attn_tar_sec_daywise_detail@tcscentr det, 
trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' and det.brncode in(23) group by Brn.BrnCode,Brn.NicName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3)
order by brncode, Target_Percentage desc"); ?>
      <div class="table-responsive col-md-6">
       <table class="table table-striped table-bordered table-hover" id="empreport_brnwisesection" style="font-size:14px;">
        <thead>
          <tr>
            <th style="text-transform: uppercase;border: 1px solid;text-align: center;" colspan="5">
              Individual Branchwise Section Rankwise
            </th>
          </tr>

          <tr>
            <th class="colth" style="text-align: center;">
              SR. NO
            </th>
            <th class="colth" style="text-align: center;">
              BRANCH
            </th>
            <th class="colth" style="text-align: center;">
              SECTION
            </th>
            <th class="colth" style="text-align: center;">
              TARGET PERCENTAGE
            </th>
            <th class="colth" style="text-align: center;">
              RANK
            </th>
          </tr>
          </thead>
          <tbody>
          
        <?php
        $ga_i = 0; $ga_ii = 0;
        foreach($result as $res)
        {
		  $ga_ii++;
          if($res['BRNCODE'] != $brncd) {
            $ga_i = 0;
          }
          $brncd = $res['BRNCODE'];

          $ga_i++; 
          if($ga_ii % 2 == 1) {
            $clsname = "coltd_grid1";
          } else {
            $clsname = "coltd_grid2";
          } ?>
          
          <tr class="<?php echo $clsname?>">       
            <td style="text-align:center;">
              <?php echo $ga_ii;  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['BRNNAME'];  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['ESENAME'];  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['TARGET_PERCENTAGE'];  ?>
            </td>   
			<td style="text-align:center;">
              <span style="background-color: #00a65a;font-weight:bold;font-size: 20px;color:#FFFFFF;padding: 0px 10px;"><?php echo $ga_i;  ?></span>
            </td>
          </tr>
          
        <?php
        } ?>
        </tbody>
        </table>
      </div>

	<div style="height:10px;line-height: 25px;border-bottom: 2px dashed red;margin-bottom: 10px;">&nbsp;</div>

<?php
 echo do_shortcode('[wp-datatable id="empreport_staffallbrn" fat="LEVEL"]
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]'); 
  $result = select_query_json("Select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, 
(Case When sum(TOTAL_TARVALUE)>0 Then Round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) Else 0 End) Target_Percentage
from trandata.attn_tar_Emp_daywise_detail@tcscentr det,trandata.Employee_Office@tcscentr Emp,trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where Det.EmpSrno=Emp.Empsrno And det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' 
and det.brncode in (1,10,14,23) group by Brn.BrnCode,Brn.NicName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3"); ?>
      <div class="table-responsive col-md-6">
       <table class="table table-striped table-bordered table-hover" id="empreport_staffallbrn" style="font-size:14px;">
        <thead>
          <tr>
            <th style="text-transform: uppercase;border: 1px solid;text-align: center;" colspan="5">
              Staff Rankwise - All Branch
            </th>
          </tr>

          <tr>
            <th class="colth" style="text-align: center;">
              SR. NO
            </th>
            <th class="colth" style="text-align: center;">
              STAFF
            </th>
            <th class="colth" style="text-align: center;">
              Branch
            </th>
            <th class="colth" style="text-align: center;">
              TARGET PERCENTAGE
            </th>
            <th class="colth" style="text-align: center;">
              RANK
            </th>
          </tr>
          </thead>
          <tbody>
          
        <?php
        $ga_i = 0;
        foreach($result as $res)
        {
          $ga_i++; 
          if($ga_i % 2 == 1) {
            $clsname = "coltd_grid1";
          } else {
            $clsname = "coltd_grid2";
          } ?>
          
          <tr class="<?php echo $clsname?>">       
            <td style="text-align:center;"><?php echo $ga_i;  ?></td>
            <td style="text-align:center;">
              <?php echo $res['EMPCODE']." - ".$res['EMPNAME']." <br> ( ".$res['ESENAME']." ) ";  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['BRNNAME'];  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['TARGET_PERCENTAGE'];  ?>
            </td>        
            <td style="text-align:center;">
              <span style="background-color: #00a65a;font-weight:bold;font-size: 20px;color:#FFFFFF;padding: 0px 10px;"><?php echo $ga_i;  ?></span>
            </td>  
          </tr>
          
        <?php
        } ?>
        </tbody>
        </table>
      </div>

	<div style="height:10px;line-height: 25px;border-bottom: 2px dashed red;margin-bottom: 10px;">&nbsp;</div>

<?php
 echo do_shortcode('[wp-datatable id="empreport_brnwisestaff" fat="LEVEL"]
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]'); 
  $result = select_query_json("select * from (Select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, 
(Case When sum(TOTAL_TARVALUE)>0 Then Round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) Else 0 End) Target_Percentage
from trandata.attn_tar_Emp_daywise_detail@tcscentr det,trandata.Employee_Office@tcscentr Emp,trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where Det.EmpSrno=Emp.Empsrno And det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' 
and det.brncode in (1) group by Brn.BrnCode,Brn.NicName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3
Union
Select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, 
(Case When sum(TOTAL_TARVALUE)>0 Then Round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) Else 0 End) Target_Percentage
from trandata.attn_tar_Emp_daywise_detail@tcscentr det,trandata.Employee_Office@tcscentr Emp,trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where Det.EmpSrno=Emp.Empsrno And det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' 
and det.brncode in (10) group by Brn.BrnCode,Brn.NicName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3
Union
Select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, 
(Case When sum(TOTAL_TARVALUE)>0 Then Round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) Else 0 End) Target_Percentage
from trandata.attn_tar_Emp_daywise_detail@tcscentr det,trandata.Employee_Office@tcscentr Emp,trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where Det.EmpSrno=Emp.Empsrno And det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' 
and det.brncode in (14) group by Brn.BrnCode,Brn.NicName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3
Union
Select * from (Select Brn.BrnCode,SubStr(Brn.NicName,3,10) BrnName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, 
(Case When sum(TOTAL_TARVALUE)>0 Then Round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) Else 0 End) Target_Percentage
from trandata.attn_tar_Emp_daywise_detail@tcscentr det,trandata.Employee_Office@tcscentr Emp,trandata.empsection@tcscentr ese, trandata.branch@tcscentr brn where Det.EmpSrno=Emp.Empsrno And det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' 
and det.brncode in (23) group by Brn.BrnCode,Brn.NicName,Emp.EmpCode,Emp.EmpName,ese.esecode, ese.esename Order By TARGET_PERCENTAGE desc) Where RowNum<=3)
order by brncode, Target_Percentage desc"); ?>
      <div class="table-responsive col-md-6">
       <table class="table table-striped table-bordered table-hover" id="empreport_brnwisestaff" style="font-size:14px;">
        <thead>
          <tr>
            <th style="text-transform: uppercase;border: 1px solid;text-align: center;" colspan="5">
              Individual Branch Staff Rankwise
            </th>
          </tr>

          <tr>
            <th class="colth" style="text-align: center;">
              SR. NO
            </th>
            <th class="colth" style="text-align: center;">
              BRANCH
            </th>
            <th class="colth" style="text-align: center;">
              STAFF
            </th>
            <th class="colth" style="text-align: center;">
              TARGET PERCENTAGE
            </th>
            <th class="colth" style="text-align: center;">
              RANK
            </th>
          </tr>
          </thead>
          <tbody>
          
        <?php
        $ga_i = 0; $ga_ii = 0;
        foreach($result as $res)
        {
		  $ga_ii++;
		  if($res['BRNCODE'] != $brncd) {
            $ga_i = 0;
          }
          $brncd = $res['BRNCODE'];

          $ga_i++; 
          if($ga_ii % 2 == 1) {
            $clsname = "coltd_grid1";
          } else {
            $clsname = "coltd_grid2";
          } ?>
          
          <tr class="<?php echo $clsname?>">       
            <td style="text-align:center;"><?php echo $ga_ii;  ?></td>
            <td style="text-align:center;">
              <?php echo $res['BRNNAME'];  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['EMPCODE']." - ".$res['EMPNAME']." <br> ( ".$res['ESENAME']." ) ";  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['TARGET_PERCENTAGE'];  ?>
            </td>  
			<td style="text-align:center;">
              <span style="background-color: #00a65a;font-weight:bold;font-size: 20px;color:#FFFFFF;padding: 0px 10px;"><?php echo $ga_i;  ?></span>
            </td> 
          </tr>
          
        <?php
        } ?>
        </tbody>
        </table>
      </div>

	<div style="height:10px;line-height: 25px;border-bottom: 2px dashed red;margin-bottom: 10px;">&nbsp;</div>

<?php
 /* echo do_shortcode('[wp-datatable id="empreport_brnwise_sectstaff" fat="LEVEL"]
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]'); 
  $result = select_query_json("select * from (
                                        select ese.esecode, ese.esename, sum(TOTAL_TARVALUE) target, sum(SALES_VALUE-EXCH_SALVALUE) salesval, 
                                            round(sum(SALES_VALUE - EXCH_SALVALUE) / sum(TOTAL_TARVALUE) * 100, 2) target_percentage  
                                        from attn_tar_sec_daywise_detail det, empsection ese, branch brn 
                                        where det.esecode=ese.esecode and det.brncode=brn.brncode and brn.deleted='N' and det.brncode in(1,10,14,23) 
                                        group by ese.esecode, ese.esename 
                                        order by TARGET_PERCENTAGE desc) 
                                        where rownum <= 3"); ?>
      <div class="table-responsive col-md-6">
       <table class="table table-striped table-bordered table-hover" id="empreport_brnwise_sectstaff" style="font-size:14px;">
        <thead>
          <tr>
            <th style="text-transform: uppercase;border: 1px solid;text-align: center;" colspan="3">
              Individual Branchwise Section Staff Rankwise
            </th>
          </tr>

          <tr>
            <th class="colth" style="text-align: center;">
              SR. NO
            </th>
            <th class="colth" style="text-align: center;">
              RANK
            </th>
            <th class="colth" style="text-align: center;">
              SECTION
            </th>
            <th class="colth" style="text-align: center;">
              TARGET PERCENTAGE
            </th>
          </tr>
          </thead>
          <tbody>
          
        <?php
        $ga_i = 0;
        foreach($result as $res)
        {
          $ga_i++; 
          if($ga_i % 2 == 1) {
            $clsname = "coltd_grid1";
          } else {
            $clsname = "coltd_grid2";
          } ?>
          
          <tr class="<?php echo $clsname?>">       
            <td style="text-align:center;">
              <span style="background-color: #00a65a;font-weight:bold;font-size: 20px;color:#FFFFFF;padding: 0px 10px;"><?php echo $ga_i;  ?></span>
            </td>
            <td style="text-align:center;">
              <?php echo $res['ESENAME'];  ?>
            </td>
            <td style="text-align:center;">
              <?php echo $res['TARGET_PERCENTAGE'];  ?>
            </td>   
          </tr>
          
        <?php
        } ?>
        </tbody>
        </table>
      </div>
	<div style="height:10px;line-height: 25px;border-bottom: 2px dashed red;margin-bottom: 10px;">&nbsp;</div>
<?php
*/
	 ?>
	<script type="text/javascript">
    $(document).ready(function() {
        $('#empreport_brnwisesection').dataTable({
			"aLengthMenu": [25,50,100],
			"iDisplayLength": 25
		}); 
		
        $('#empreport_brnwisestaff').dataTable({
			"aLengthMenu": [25,50,100],
			"iDisplayLength": 25
		}); 
	}); 
	</script>
  <? 
 }

/*else if(is_page('73'))
{
  echo alphabetical_order_cat();
}*/ else
{
   the_content(); 



        
}
       
        wp_link_pages(array(
              'before'      => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'one-page-express') . '</span>',
              'after'       => '</div>',
              'link_before' => '<span>',
              'link_after'  => '</span>',
              'pagelink'    => '<span class="screen-reader-text">' . __('Page', 'one-page-express') . ' </span>%',
              'separator'   => '<span class="screen-reader-text">, </span>',
          ));   
    ?>
	  </div></div>
	<div>&nbsp;</div>
	</div>
    <?php 
      if (comments_open() || get_comments_number()):
        comments_template();
      endif;
    ?>
</div>