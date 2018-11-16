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
else if(is_page('1302')){?>
	<style> input[type='text'], textarea{
		font-family:inherit;
		font-size:12px !important;
		}
		select{
			font-size:12px !important;
		}
		#divLoading
{
    display : none;
}
#divLoading.show
{
    display : block;
    position : fixed;
    z-index: 100;
    background-image : url('http://loadinggif.com/images/image-selection/3.gif');
    background-color:#666;
    opacity : 0.4;
    background-repeat : no-repeat;
    background-position : center;
    left : 0;
    bottom : 0;
    right : 0;
    top : 0;
}
#loadinggif.show
{
    left : 50%;
    top : 50%;
    position : absolute;
    z-index : 101;
    width : 32px;
    height : 32px;
    margin-left : -16px;
    margin-top : -16px;
}

.modal {
  text-align:center;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
		#result a{padding:5px}	
	#result a:hover{
					background-color:#42bff4;
					color:#fff ;
			}
	</style>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<div id="divLoading"> 
    </div>
	<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    

      <div class="modal-content">
       
        <div class="modal-body">
			<h4 class="text-success">Successfully submitted.</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick='pagereset()'>OK</button>
        </div>
      </div>
      
    </div>
  </div>
	<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
   

      <div class="modal-content">
		  
        <div class="modal-body">
			<h4 class="text-danger">Invalid User...Please try again...</h4>
			
       
        </div>
		   <div class="modal-footer">
			<button type="button" onclick='setfocus()' class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        
      </div>
      
    </div>
  </div>
	 <div class="page-content-wrap">
		  <form class="form-horizontal"> 
                          <div class="row" style="margin:25px 0px">
							   <h3><i class="um-faicon-user"></i> Employee Notice Entry</h3>
						  </div>
          <div class="row">
                <div class="col-md-12">
                       <div class="col-md-6">	
											<div class="form-group col-md-10">
      											  <label for="branch"><i class="fa fa-building-o"></i> Branch:</label>
      											  <select class="form-control branch" id="branch" name="branch" onChange="clearme();">
		  <?
$sql_project = select_query_json("select usrcode,useracc,allbran,brncode,MULTIBRN from tcs_centra_attn where  usrcode='1444288'", "Centra", 'TCS');
$sql_project[0]["ID"]="1";
if($sql_project[0]['ALLBRAN']=='N')
                                                                {
$sql_brn = select_query_json("Select Brncode, NICNAME From Branch Where brncode='".$sql_project[0]['BRNCODE']."' ORDER BY BRNCODE", "Centra", 'TCS');                          
                                                                }
                                                                else
                                                                { $sql_brn = select_query_json("Select Brncode,NICNAME From Branch Where brncode IN (".$sql_project[0]['MULTIBRN'].") ORDER BY BRNCODE", "Centra", 'TCS');                                                             
                                                                
                                                                }
	for($i = 0; $i < count($sql_brn); $i++) { 
    $name=str_split($sql_brn[$i][NICNAME]);
    $branch='';
   for($x=0;$x<count($name);$x++){
   if(!is_numeric($name[$x]))
        $branch.=$name[$x];
    }
		  ?>
		  									<option value="<?=$sql_brn[$i][BRNCODE]?>"><?=$branch?></option><?}?>
											</select>
   										 </div>
										<div class="form-group col-md-10">
											<label for="employee"><i class="fa fa-user"></i> Employee: <span style="color:red">*</span></label>
										  <input type='text' class="form-control" tabindex='2' required style="text-transform: uppercase;" 														name='employee' id='employee' onkeyup='checkemp()' placeholder='ENTER EMPLOYEE CODE OR NAME'  value=''>
										   <div id='result' style='max-height:400px;overflow-y:scroll;'></div>
										</div>
										<div class="form-group col-md-10">
										  <label for="comments"><i class="fa fa-comments-o"></i> Comments: <span style="color:red">*</span></label>
										  <textarea required id="message" maxlength=100 name="message" type="text" tabindex="3" class="form-control" style="text-transform:uppercase; height:75px; padding-right: 5px;" onkeyup='checkmsg()' multiple placeholder="YOUR COMMENTS.." required></textarea>
										</div>
										<div class="form-group col-md-10">
											
                                           <label for="authby"><i class="fa fa-user"></i> Authorized by</label>
											
                                                       <select class="form-control"  tabindex='4' required name='auth_by' id='auth_by' onChange="">
                                                      
                                                        <option value='20118'>MD</option>
                                                        <option value='43400'>PS MADAM</option>
                                                        <option value='21344'>SK SIR</option>
                                                        <option value='452'>ADMIN GM</option>
                                                    </select>
                                                   
											 
                                                             <div class="checkbox">
                                                              <label><input type="checkbox" value="1" tabindex='5' id="all" name="all"/> All</label>
                                                            </div>
                                                   
                                                    </div>
                                                    
                                                </div>  
									  <div class="col-md-6">
									  <div class="form-group col-md-10">
										  <label for="notice"><i class="fa fa-envelope-o" aria-hidden="true"></i> Notice:</label>
										  <input type="text" disabled class="form-control" id="notice" tabindex='6' name="notice" style="text-transform: uppercase;" maxlength="100" required />
										  
										</div>
										  <div class="col-md-10 col-xs-12">
                                                        
                                                       <div id="my-canvas" style="text-align:center"></div>
                                         </div>
									  </div> 
			 </div>
		 </div>
			  
		 <div class="row">
								<center><button class="btn btn-success" onclick="nsubmit();" tabindex='7' type="button">Submit</button></center>
						  </div>
			  </form>
		 </div>
<script>

function setfocus(){
	jQuery("#employee").val('');
	jQuery('#result').hide();
	jQuery("#employee").focus();
	
}
function setvalue(val){
			jQuery('#employee').val(val);
			jQuery('#result').hide();
			getprofile_img();
}
 function clearme()
   {
           jQuery('#employee').val('');
		   jQuery('#profile_img').html('');
		   jQuery('#notice').val('');
           jQuery('#result').hide();
      
   }

function getprofile_img(){

					var branch= jQuery('#branch').val();

					var ececode=jQuery('#employee').val();
					var ececode1=ececode.split(' - ');
					console.log(ececode1[0]);console.log(ececode1[1]);
					var empcode=ececode1[0];
					var empname=ececode1[1];
					if (typeof ececode1[1]  !== "undefined")
					{
					   var branch=jQuery('#branch').val();

				jQuery.post(ajax_url,{action:'check_photo',branch:branch,profile_img:empcode,empname:empname,action2:'userprofileimg'}, 					function(result){

					var src = "data:image/jpeg;base64,";
					src += result;
					var newImage = document.createElement('img');
					newImage.src = src;
					newImage.height = "150";
					newImage.width = "150";
					document.querySelector('#my-canvas').innerHTML = newImage.outerHTML;
					jQuery.post(ajax_url,{action:'check_report',empsrno:empcode,action3:'alerttext'}, function(result){
					jQuery('#notice').val(result);
					});

					});
					 }        	 
}
function checkemp(){
	var emp=jQuery("#employee").val();
	if(emp.trim()==''){
		 jQuery('#employee').css('border','1px solid red');
	}
	else{
		jQuery('#employee').css('border','1px solid #ccc');
	}
	
}
	function checkmsg(){
	var emp=jQuery("#message").val();
	if(emp.trim()==''){
		 jQuery('#message').css('border','1px solid red');
	}
	else{
		jQuery('#message').css('border','1px solid #ccc');
	}
	
}
jQuery(document).ready(function() {
    
    jQuery('#employee').autocomplete({

					source: function( request, response ) {
					var branch=jQuery('#branch').val();
				    jQuery.post(ajax_url, {action: 'check_user',name_startsWith:request.term,branch:branch,type:'branch_employee'}, 							function(result){
						console.log(result)
				if(result!=='error'){
				var datasplit=result.split('[');
				var split2=datasplit[1].split(',');
				console.log(split2);
				
				var htmlText='';
				for(var i=0;i<split2.length;i++){
				var splitfinal=split2[i].replace('"','');
				splitfinal=splitfinal.replace('"','');
				console.log(splitfinal);
				if(i!=(split2.length-1)){
				htmlText+="<p><a value="+JSON.stringify(splitfinal)+"  onclick='setvalue("+JSON.stringify(splitfinal)+");'                                                  style='text-decoration:none;color:#000;cursor:pointer'>"+splitfinal+"</a></p>";
				}
				else{
				var split2last=splitfinal.replace(']','');

				htmlText+="<p><a value="+JSON.stringify(split2last)+ " onclick='setvalue("+JSON.stringify(split2last)+");' 				         style='text-decoration:none;color:#000;cursor:pointer'>"+split2last+"</a></p>";
				}

				}
				jQuery('#result').html(htmlText);
				jQuery('#result').show();
				}else{
					jQuery('#myModal1').modal();
					
				}
					});
				}
            });

    		});
function nsubmit(){
     
				var msg=jQuery("#message").val();
				var emp=jQuery("#employee").val();
				var branch=jQuery("#branch").val();
				var message=jQuery("#message").val();
				var employee=jQuery("#employee").val();
				var auth_by=jQuery("#auth_by").val();
				var all=jQuery("#all").val();
				var action5='insert';
				if(msg.trim()!='' && emp.trim()!='')
				{
					setTimeout(function(){
		jQuery('#divLoading').addClass('show');},1000);
				  jQuery.post(ajax_url, {action:'insert_user',branch:branch,message:message,employee:employee,auth_by:auth_by,all:all,action5:action5}, function(result){
				  
        jQuery('#divLoading').removeClass('show');
        jQuery('#myModal').modal({
					backdrop: 'static',
					keyboard: false
				});
             
            }
            ); 
        }
        else { if(emp.trim()==''){
           jQuery('#employee').css('border','1px solid red');
			return false;
        }else{
			jQuery('#employee').css('border','1px solid #ccc');
		}
	     if(msg.trim()==''){
           jQuery('#message').css('border','1px solid red');
		   return false;
        }else{
			jQuery('#message').css('border','1px solid #ccc');
		  
		}
			 }

}

function pagereset(){
location.reload();
}
</script>
						
  
	
<?} else if(is_page('1309')){
	 echo do_shortcode('[wp-datatable id="table1"]
	
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]'); 
	echo do_shortcode('[wp-datatable id="table2"]
	
    paging: true,
    responsive: true,
    search: true
  [/wp-datatable]'); 
	
	?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	 <style>

 .tab-content {
        border-left: 2px solid #ddd;
        border-right: 2px solid #ddd;
        padding: 10px;
    } 
    .nav-tabs > .active > a{
        background-color: #034f84 !important;
        color:#fff !important;
    }
    
    .nav-tabs.nav {
        padding: 0px 0px;
        padding-top: 10px;
    }
    .nav-tabs > li > a {
    background: #c3d7e4 !important; 
    line-height: 1.2 !important;
    font-size: 10px !important;
	color: #000 !important;	
}
@media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}
#divLoading
{
    display : none;
}
		 table th{
			 vertical-align:middle !important;
		 }
#divLoading.show
{
    display : block;
    position : fixed;
    z-index: 100;
    background-image : url('http://loadinggif.com/images/image-selection/3.gif');
    background-color:#666;
    opacity : 0.4;
    background-repeat : no-repeat;
    background-position : center;
    left : 0;
    bottom : 0;
    right : 0;
    top : 0;
}
		
#loadinggif.show
{
    left : 50%;
    top : 50%;
    position : absolute;
    z-index : 101;
    width : 32px;
    height : 32px;
    margin-left : -16px;
    margin-top : -16px;
}
#printdiv td{
      padding:20px;
}
			 
.modal {
	text-align:center;
  margin-top:100px;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
</style>
<div id="divLoading"> 
    </div>
<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
    

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3>Send Your Reply</h3>
        </div>
        <div class="modal-body">
        <div id="modal_data" class="modal-content1">
                                    <textarea id="message" maxlength=250 name="message" maxlength=200 type="text" tabindex="3" class="form-control" style="text-transform:uppercase; height:100px; padding-right: 5px;" multiple placeholder="TCS Messages.."></textarea>
                                    <center><input type="button" class="btn btn-success" value="SEND" onclick="reply()"/></center>
                          </div>
        </div>
        
      </div>
      
    </div>
  </div>
 
<div class="page-content-wrap">
               
                  
                      <div class="row" style="margin:25px 0px">
							   <h3><i class="um-faicon-user"></i> Employee Notice List</h3>
						  </div>
                   
                     

                        <div class="col-md-12">                        
                        
                           
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab9" data-toggle="tab" aria-expanded="false"><b>YOUR NOTICE</b></a></li>
                                    <li class=""><a href="#tab8" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li>
							</ul>    
                                    <div class="panel-body" style="padding-top: 10PX; padding-bottom: 10PX;  border-top: 2px solid #ddd;border-left: 2px solid #ddd;border-right: 2px solid #ddd;">
                                        <div class="row" style="padding-top:10px">
                                            <div class="col-md-12">
                                                <div class="col-md-3">
                                               <label id='remarks' style="float:right">AUTHORIZED BY</label>
</div>
                                                       
                                                    <div class="col-md-3 col-xs-12">
                                                         <select class="form-control custom-select chosn" autofocus tabindex='4' required name='auth_by' id='auth_by' onChange="" data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
                                                      
                                                        <option value='20118'>MD</option>
                                                        <option value='43400'>PS MADAM</option>
                                                        <option value='21344'>SK SIR</option>
                                                        <option value='452'>ADMIN GM</option>
                                                    </select>
                                                    
                                                    </div>
                                                    <div class="col-md-6 col-xs-12">
                                                        <div class="checkbox">
                                                            <label><input type="checkbox" value="1" tabindex='5' id="all" name="all"/> All</label>
                                                        </div>
                                                    </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                          

                       

                                <div class="panel-body tab-content" style="border-bottom:2px solid #ddd">
                                    <div class="tab-pane" id="tab8">
                                       <div class="panel-body table" style="overflow-x:auto">

                                         <table id="table1"  class="table table-striped table-hover table-responsive" style='width:100%'>
                                            <thead>
                                                <tr>
                                                    <th class="colth" style='text-align:center;'><strong>S.No</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>NOTICE NO.</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>EMPLOYEE</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>REMARKS</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>NOTICE</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>AUTH. BY</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>REPLY</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>REPLY DATE</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>ACTION</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>PRINT</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                           <?  $sql_search = select_query_json_test("Select UI.USRCODE,to_char(emnt.edtdate,'dd/MM/yyyy HH:mi:ss AM') edtdate1,(select empname from employee_office where empsrno=emnt.autsrno) assignuser, emnt.*, emp.empname,emp.empcode From employee_notice_detail emnt, employee_office emp,USERID UI where emnt.EMPSRNO = emp.empsrno AND UI.USRCODE=EMNT.ADDUSER AND EMNT.DELETED='N' AND EMNT.ADDUSER='1444288'  ORDER BY NOTNUMB DESC", "Centra", 'TEST');
 $ki = 0;
for($k=0;$k<count($sql_search );$k++){$ki++;
if($sql_search[$k]['EMP_STATUS']=='Y'){ 
       if($sql_search[$k]['NOTSTAT']!=''){
           if($sql_search[$k]['NOTSTAT']=='A'){
              $pri='A';
           }
          if($sql_search[$k]['NOTSTAT']=='R'){   
             $pri='R';
           }}else{
             $pri='NOT';
            }}
$num=explode('-',$sql_search[$k]['NOTNAME']);
															$num=intval($num[1]);
																if($num>10){  $bg_clr_class='label-danger';}
																if($num>5 && $num<=10){  $bg_clr_class='label-warning'; }
																if($num>0 && $num<=5){  $bg_clr_class='label-success'; }
echo "<tr class='coltd_grid1'><td style='text-align:center'>".$ki."</td>
          <td style='text-align:center'>".$sql_search[$k]['NOTYEAR']."-".$sql_search[$k]['NOTNUMB']."</td>
          <td style='text-align:center'>".$sql_search[$k]['EMPCODE']." - ".$sql_search[$k]['EMPNAME']."</td>
          <td style='text-align:center'>".$sql_search[$k]['REMARKS']."</td>          
          <td style='text-align:center'><div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label 
                    ".$bg_clr_class." label-form'><b>".$sql_search[$k]['NOTNAME']."</b></span></div></td> 
          <td style='text-align:center'>".$sql_search[$k]['ASSIGNUSER']."</td>
          <td style='text-align:center'>".$sql_search[$k]['EMP_REMARKS']."</td>
          <td style='text-align:center'>".$sql_search[$k]['EDTDATE1']."</td>
          <td style='text-align:center'>".($pri == 'A' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-success label-form'><b>APPROVED</b></span></div>" : "").($pri=='R' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-danger label-form'><b>DENIED</b></span></div>" : "").($pri=='NOT' ? "<span><button class='btn btn-sm btn-success' title='APPROVE' onclick='approve(".'"'.$sql_search[$k]['NOTYEAR'].'"'.",".'"'.$sql_search[$k]['NOTNUMB'].'"'.",1)'><i class='fa fa-check'></i></button></span><span><button class='btn btn-sm btn-danger' title='DENY' onclick='approve(".'"'.$sql_search[$k]['NOTYEAR'].'"'.",".'"'.$sql_search[$k]['NOTNUMB'].'"'.",2)'><i class='fa fa-close'></i></button></span>" : "")."</td><td style='text-align:center'><button onclick='printpage(".'"'.$sql_search[$k]['NOTYEAR'].'"'.",".'"'.$sql_search[$k]['NOTNUMB'].'"'.")' class='btn btn-default'><i class='fa fa-print'></i></button></td></tr>";

}
?>

                                            </tbody>
                                        </table>
                                     </div>
                                    </div> 

                          <div class="tab-pane active" id="tab9">
                                     
                                            <div class="panel-body table" style="overflow-x:auto">

                                        <table id="table2"  class="table table-striped table-hover table-responsive" style='width:100%'>
                                            <thead>
                                                <tr>
                                                    <th class="colth" style='text-align:center;'><strong>S.No</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>NOTICE NO.</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>EMPLOYEE</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>REMARKS</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>NOTICE</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>AUTHOURIZED BY</strong></th>
													<th class="colth" style='text-align:center;'><strong>REPLY</strong></th>
													<th class="colth" style='text-align:center;'><strong>REPLY DATE</strong></th>
                                                    <th class="colth" style='text-align:center;'><strong>PRINT</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                           <? $sql_search = select_query_json_test("Select (select empname from employee_office where empsrno=emnt.autsrno) assignuser,to_char(emnt.edtdate,'dd/MM/yyyy HH:mi:ss AM') edtdate1, emnt.*, emp.empname,emp.empcode From employee_notice_detail emnt, employee_office emp where emnt.EMPSRNO = emp.empsrno and emnt.DELETED='N' and emnt.empsrno='43878' order by emnt.notnumb desc", "Centra", 'TEST');  
					$ki = 0;
for($k=0;$k<count($sql_search );$k++){$ki++;$pri='';
if($sql_search[$k]['EMP_STATUS']=='Y'){       
           if($sql_search[$k]['NOTSTAT']=='A'){
            $pri='A';
           }
          if($sql_search[$k]['NOTSTAT']=='R'){   
           $pri='R';
           }if($sql_search[$k]['NOTSTAT']==''){
           $pri='NOT';
            }}

																$num=explode('-',$sql_search[$k]['NOTNAME']);
															    $num=intval($num[1]);
																if($num>10){  $bg_clr_class='label-danger';}
																if($num>5 && $num<=10){  $bg_clr_class='label-warning'; }
																if($num>0 && $num<=5){  $bg_clr_class='label-success'; }
echo "<tr class='coltd_grid2'><td style='text-align:center'>".$ki."</td>
          <td style='text-align:center'>".$sql_search[$k]['NOTYEAR']."-".$sql_search[$k]['NOTNUMB']."</td>
          <td  style='text-align:center'>".$sql_search[$k]['EMPCODE']." - ".$sql_search[$k]['EMPNAME']."</td>
          <td  style='text-align:center'>".$sql_search[$k]['REMARKS']."</td>          
          <td style='text-align:center'><div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label ".$bg_clr_class." label-form'><b>".$sql_search[$k]['NOTNAME']."</b></span></div></td> 
          <td  style='text-align:center'>".$sql_search[$k]['ASSIGNUSER']."</td>
          <td style='text-align:center'>".$sql_search[$k]['EMP_REMARKS']." ".($pri == 'A' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-success label-form'><b>APPROVED</b></span></div>" : "").($pri=='R' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-danger label-form'><b>DENIED</b></span></div>" : "").($pri=='NOT' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-warning label-form'><b>WAITING FOR REPLY</b></span></div>" : "")."</td>          
          <td  style='text-align:center'>".$sql_search[$k]['EDTDATE1']."</td>
<td style='text-align:center;padding:5px'>".($sql_search[$k]['EMP_STATUS']!='Y'?"<button title='REPLY' onclick='showreply(".'"'.$sql_search[$k]['NOTYEAR'].'"'.",".'"'.$sql_search[$k]['NOTNUMB'].'"'.")' class='btn btn-success' style='padding-bottom:5px'><i class='fa fa-pencil'></i></button>":'')."<button title='PRINT' onclick='printpage(".'"'.$sql_search[$k]['NOTYEAR'].'"'.",".'"'.$sql_search[$k]['NOTNUMB'].'"'.")' class='btn btn-default' style='padding-top:5px'><i class='fa fa-print'></i></button></td></tr>";

}
?>

                                            </tbody>
                                        </table>
                                     </div>
                                  
                                    </div>                                                                    
                                </div>
                                                                
                         
					</div></div>
                        
       <div id="printdiv" style="display:none;font-weight:bold"></div>           

	<script>

var gnotyear=0;
var gnotnumb=0;

function showreply(notyear,notnumb){
            jQuery('#myModal1').modal();
			gnotyear=notyear;
			gnotnumb=notnumb;
        }

function reply()
		{	

                 var remarks=jQuery('#message').val();
			     jQuery.post(ajax_url,{action:'emp_reply',notyear:gnotyear,notnumb:gnotnumb,act:'reply',remarks:remarks}, function(result){
                              
                  location.reload();

                              });
		}
function printpage(notyear,notnum)
    {
                      setTimeout(function(){
                     jQuery('#divLoading').addClass('show');},2000);
                    var opt;       
                    var auth_by=jQuery('#auth_by').val();
                     console.log(auth_by);
                      if(jQuery("#all").prop('checked')==true)
                     {opt=4;
                       }
                    else
                     {
                   opt=1;
                     }
                   jQuery.post(ajax_url,{action:'print_report',notyear:notyear,notnumb:notnum,all:opt,authby:auth_by}, function(result){
                  jQuery('#printdiv').html(result);
                
                     setTimeout(function(){

                  printDiv('printdiv');},3000);  

                   });

    }

function printDiv(divName)
         {
                         jQuery('#divLoading').removeClass('show');
			             var printContents = document.getElementById(divName).innerHTML;    
                        printContents+='<style>@media print {  @page { margin: 0; }  body { margin: 1.6cm; }</style>';     
                        printContents+='<style><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></style>';          
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
	}

function approve(notyear,notnumb,ch){
                    setTimeout(function(){
                     jQuery('#divLoading').addClass('show');},1000);
                    jQuery.post(ajax_url,{action:'check_approve',notyear:notyear,notnumb:notnumb,val:ch,act:'approve'}, function(result){
                   
                   location.reload();
                   jQuery('#divLoading').removeClass('show');
                   });
}

</script>
	
	
	
<?}
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