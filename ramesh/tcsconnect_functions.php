<?php

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 */




if(!defined('OPE_THEME_REQUIRED_PHP_VERSION')){
    define('OPE_THEME_REQUIRED_PHP_VERSION','5.3.0');
}

function ajaxurl() {

?>
<script type="text/javascript">
var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

</script>
<?php
}

add_action('wp_head','ajaxurl');


function theme_enqueue_scripts() {

	// Enqueue jQuery UI and autocomplete
	wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-autocomplete' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );

add_action( 'after_switch_theme', 'one_page_express_check_php_version' );

function one_page_express_check_php_version(){
  // Compare versions.
  if ( version_compare(phpversion(), OPE_THEME_REQUIRED_PHP_VERSION, '<') ) :
    // Theme not activated info message.
    add_action( 'admin_notices', 'one_page_express_php_version_notice' );
    

    // Switch back to previous theme.
    switch_theme(get_option( 'theme_switched' )  );
    return false;
  endif;
}

function one_page_express_php_version_notice() {
    ?>
    <div class="notice notice-alt notice-error notice-large">
        <h4><?php _e('One Page Express theme activation failed!','one-page-express'); ?></h4>
        <p>
            <?php _e( 'You need to update your PHP version to use the <strong>One Page Express</strong>.', 'one-page-express' ); ?> <br />
            <?php _e( 'Current php version is:', 'one-page-express' ) ?> <strong>
            <?php echo phpversion(); ?></strong>, <?php _e( 'and the minimum required version is ', 'one-page-express' ) ?> 
            <strong><?php echo OPE_THEME_REQUIRED_PHP_VERSION; ?></strong>
        </p>
    </div>
    <?php
}
function my_wp_nav_menu_args( $args = '' ) {
 
if( is_user_logged_in() ) { 
    $args['menu'] = 'Menu 2';
} else { 
    $args['menu'] = 'Menu 1';
} 
    return $args;
}
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
if( version_compare(phpversion(), OPE_THEME_REQUIRED_PHP_VERSION, '>=')){
    require_once get_template_directory() . "/inc/functions.php";
}

    
////////////////////////////////////////////////////////////////////////
// MyCred User Ranks and Badges Integration ////////////////////////////
////////////////////////////////////////////////////////////////////////
add_filter('wpdiscuz_after_label', 'wpdiscuz_mc_after_label_html', 110, 2);
function wpdiscuz_mc_after_label_html($afterLabelHtml, $comment) {
    if ($comment->user_id) {
        if (function_exists('mycred_get_users_rank')) { //User Rank
            $afterLabelHtml .= mycred_get_users_rank($comment->user_id, 'logo', 'post-thumbnail', array('class' => 'mycred-rank'));
        }
        if (function_exists('mycred_get_users_badges')) { //User Badges
            $users_badges = mycred_get_users_badges($comment->user_id);
            if (!empty($users_badges)) {
                foreach ($users_badges as $badge_id => $level) {
                    $imageKey = ( $level > 0 ) ? 'level_image' . $level : 'main_image';
                    $afterLabelHtml .= '<img src="' . get_post_meta($badge_id, $imageKey, true) . '" width="22" height="22" class="mycred-badge earned" alt="' . get_the_title($badge_id) . '" title="' . get_the_title($badge_id) . '" />';
                }
            }
        }        
    }
    return $afterLabelHtml;
}



function select_query_json($sqlqry_select) { 
	error_reporting(0);
  $client = new SoapClient("http://172.16.0.166:8080/cdata.asmx?Wsdl"); 
  $get_parameter->Qry_String=$sqlqry_select;
  try{
    $get_result=$client->Get_Data_xml($get_parameter)->Get_Data_XMLResult;
  }
  catch(SoapFault $fault){
    echo "Fault code:{$fault->faultcode}".NEWLINE;
    echo "Fault string:{$fault->faultstring}".NEWLINE;
    if ($client != null)
    {
      $client=null;
    }
    // exit();
  }  
  $soapClient = null;
  return json_decode($get_result,true); 
}



// Select Query JSON Response
function select_query_json_test($sqlqry_select, $brn_connection = 'Centra', $schema_source = 'TEST') {
	// echo "**".$sqlqry_select."**".$brn_connection."**".$schema_source."**";
	$client = new SoapClient("http://www.templiveservice.com/service.asmx?Wsdl"); 
	$get_parameter->str = $sqlqry_select;
	$get_parameter->B_Code = $brn_connection; // 'Centra';
	$get_parameter->C_Mode = $schema_source; // 'TCS';
	// $get_parameter->C_Mode='TEST';
	try{
		//echo "!!";
		// print_r($get_parameter);
		$get_result=$client->GetData_Json($get_parameter)->GetData_JsonResult;
	}
	catch(SoapFault $fault){
		/* echo "##";
		echo "Fault code:{$fault->faultcode}".NEWLINE;
		echo "Fault string:{$fault->faultstring}".NEWLINE; */
		if ($client != null)
		{
			$client=null;
		}
		// exit();
	}
	$soapClient = null;
	return json_decode($get_result,true); 
}
// Select Query JSON Response



function checkUser() {  
if($_REQUEST['type'] == 'branch_employee'){
	
	$result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY from employee_office emp, empsection sec, designation des, employee_salary sal where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_REQUEST['name_startsWith'])."%' or emp.EMPNAME like '%".strtoupper($_REQUEST['name_startsWith'])."%' ) and emp.brncode = '".$_REQUEST['branch']."' union	select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY from employee_office emp, new_empsection sec, new_designation des, employee_salary sal where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_REQUEST['name_startsWith'])."%' or 
emp.EMPNAME like '%".strtoupper($_REQUEST['name_startsWith'])."%' ) and emp.brncode = '".$_REQUEST['branch']."' order by EMPCODE Asc", "Centra", 'TCS');	
    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi]['EMPCODE']." - ".$result[$rowi]['EMPNAME']." - ".substr($result[$rowi]['ESENAME'], 3));
    }  
	if(!empty($data))
     echo json_encode($data);
	else
		echo 'error';
   
}
	die();
}
add_action('wp_ajax_check_user', 'checkUser');
add_action('wp_ajax_nopriv_check_user', 'checkUser');

//function for insert test query
//
function insert_test_dbquery($field_values, $tbl_names)
{
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);

		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			// echo "!!"; print_r($expld); echo "!!";
			if($expld[1] != '')
			{
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "to_date('".$expld[1]."', '".$expld[0]."'), ";
				$kkvl[] = "".$key_values[$ii]."";
			} else {
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "'".$org_value[$ii]."', ";
				$kkvl[] = "".$key_values[$ii]."";
			}
		}
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");

		echo $sql_insert ="insert into ".$tbl_names." (".$kvl.") values (".$kyvl.")";
		$save_query = save_test_query_json($sql_insert, "Centra", 'TEST');
		return $save_query;
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
//update test query

function update_test_dbquery($field_values, $tbl_names, $where_condition)
{	
	try{
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$org_values = array_values($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			if($expld[1] != '')
			{
				$kyvl .= $key_values[$ii]." = to_date('".$expld[1]."', '".$expld[0]."'), ";
			} else {
				$kyvl .= $key_values[$ii]." = '". $org_values[$ii]."', ";	
			}	
		}
		$kyvl = rtrim($kyvl, ", ");
		
		echo "<br>##".$sql_update = "UPDATE ".$tbl_names." SET ".$kyvl." WHERE ".$where_condition;
		echo "<br>**".$save_query = save_test_query_json($sql_update, "Centra", 'TEST');
		return $save_query;
	}
	catch(Exception $e) {
		//echo 'Message: ' .$e->getMessage();
	}
}


//save test query
//
function save_test_query_json($sqlqry_select, $brn_connection = 'Centra', $schema_source = 'TEST') {
	$client = new SoapClient("http://templive.thechennaisilks.com:5088/service.asmx?Wsdl");
	$get_parameter->str = $sqlqry_select;
	$get_parameter->B_Code = $brn_connection; 
	$get_parameter->C_Mode = $schema_source; 
	
	try{
		$get_result = $client->Php_Store_Data($get_parameter)->Php_Store_DataResult;
	}
	catch(SoapFault $fault){
		$get_result = 0;
		
		if ($client != null)
		{
			$client=null;
		}
		
	}
	$soapClient = null;
	return json_decode($get_result,true);
}




//insert user

function insertUser() {  
if($_REQUEST['action5'] == 'insert'){
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');

$EMP=explode(' - ',$_REQUEST['employee']);
$EMP_DET = select_query_json_test("select BRNCODE,EMPSRNO,EMPCODE,EMPNAME,ESECODE,DESCODE,ESECODE from EMPLOYEE_OFFICE where EMPCODE='".$EMP[0]."'","Centra","TEST");

 $notval = select_query_json_test("select count(*)+1 MAXNUM from employee_notice_detail", "Centra", 'TEST');
 $usrnot = select_query_json_test("select count(*)+1 MAXNOT from employee_notice_detail WHERE EMPCODE='".$EMP[0]."'", "Centra", 'TEST');

        $g_table = "employee_notice_detail";
        $g_fld4 = array();
        $g_fld4['BRNCODE']= $EMP_DET[0]['BRNCODE'];
        $g_fld4['NOTYEAR']= $current_yr[0]['PORYEAR'];
        $g_fld4['NOTNUMB']= $notval[0]['MAXNUM'];
        $g_fld4['EMPSRNO']= $EMP_DET[0]['EMPSRNO'];
        $g_fld4['EMPCODE']= $EMP_DET[0]['EMPCODE'];
        $g_fld4['EMPNAME']= $EMP_DET[0]['EMPNAME'];
        $g_fld4['ESECODE']= $EMP_DET[0]['ESECODE'];
        $g_fld4['DESCODE']= $EMP_DET[0]['DESCODE'];
        $g_fld4['NOTCODE']= '1';
        $g_fld4['NOTNAME']= 'ALERT NOTICE-'.$usrnot[0]['MAXNOT'];
        $g_fld4['REMARKS']= strtoupper($_REQUEST['message']);
        $g_fld4['AUTSRNO']= $_REQUEST['auth_by'];
        $g_fld4['STATUS']= 'N';
        $g_fld4['NOTMODE']= 'N';
        $g_fld4['EMP_STATUS']='N'; 
        $g_fld4['EMP_REMARKS']= '';
        $g_fld4['ADDUSER']= '1444288';
        $g_fld4['ADDDATE']= 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
        $g_fld4['EDTUSER']= '';
        $g_fld4['EDTDATE']= '';
        $g_fld4['DELETED']= 'N';
        $g_fld4['DELUSER']= '';
        $g_fld4['DELDATE']='';
       
        $g_insert_subject = insert_test_dbquery($g_fld4,$g_table);
        $val=$current_yr[0]['PORYEAR'].','.$notval[0]['MAXNUM'];
        
      




}
  die();
}
add_action('wp_ajax_insert_user', 'insertUser');
add_action('wp_ajax_nopriv_insert_user', 'insertUser');



//insert user

function checkApprove() {  
if($_REQUEST['act'] == 'approve'){
	    $currentdate = strtoupper(date('d-M-Y h:i:s A'));
        $g_table = "employee_notice_detail";
        $g_fld4 = array();
        if($_REQUEST['val']=='1')
        {
                $g_fld['NOTSTAT'] = 'A'; 
        }
        if($_REQUEST['val']=='2')
        {
                $g_fld['NOTSTAT'] = 'R'; 
        }
        $g_fld['APPUSER'] = '1444288';
        $g_fld['APPDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
        $where_appplan="NOTYEAR = '".$_REQUEST['notyear']."' and NOTNUMB = '".$_REQUEST['notnumb']."'";
        print_r($g_fld);
        print_r($where_appplan);
        $insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where_appplan);


}

}
add_action('wp_ajax_check_approve', 'checkApprove');
add_action('wp_ajax_nopriv_check_approve', 'checkApprove');

//employee reply
function empReply() {  
if($_REQUEST['act'] == 'reply'){
	  $currentdate = strtoupper(date('d-M-Y h:i:s A')); 
	$g_table = "employee_notice_detail";
	$g_fld4 = array();
	$g_fld['EMP_STATUS'] = 'Y'; 
	$g_fld['EMP_REMARKS'] = strtoupper($_REQUEST['remarks']);
	$g_fld['EDTUSER'] = '1444288';
	$g_fld['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$where_appplan="NOTYEAR = '".$_REQUEST['notyear']."' and NOTNUMB = '".$_REQUEST['notnumb']."'";	
	$insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where_appplan);


}

}
add_action('wp_ajax_emp_reply', 'empReply');
add_action('wp_ajax_nopriv_emp_reply', 'empReply');





function checkphoto() { 
	$branch=$_REQUEST['branch'];
	$profile_img=$_REQUEST['profile_img'];
	if($_REQUEST['action2']=='userprofileimg')
{
	$result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, empsection sec, designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".$_REQUEST['profile_img']."%' or 
											emp.EMPNAME like '%".strtoupper($_REQUEST['empname'])."%' ) and emp.brncode = '".$branch."' 
									union
										select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, new_empsection sec, new_designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".$_REQUEST['profile_img']."%' or 
											emp.EMPNAME like '%".strtoupper($_REQUEST['empname'])."%' ) and emp.brncode = '".$branch."' 
										order by EMPCODE Asc", "Centra", 'TCS');
	if(count($result) > 0) {
		$sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empcode=".$profile_img, "Centra", "TCS");
		$data = base64_decode($sql_sub[0]['EMPPHOT']);
		$im = imagecreatefromstring($data);
		if ($im !== false) {
			echo $sql_sub[0]['EMPPHOT'];
			//echo imagepng($im);
			// imagedestroy($im);
		}
		else {
			echo 'An error occurred.';
		}
	} else {
		echo 'An error occurred.';
	}
}
	die();
}
add_action('wp_ajax_check_photo', 'checkphoto');
add_action('wp_ajax_nopriv_check_photo', 'checkphoto');

function checkreport() { 
	if($_REQUEST['action3']=='alerttext')
{       
		$usrnot = select_query_json_test("select count(*)+1 MAXNOT from employee_notice_detail WHERE EMPCODE='".$_REQUEST['empsrno']."'", "Centra", 'TEST');
	 echo ('ALERT NOTICE-'.$usrnot[0]['MAXNOT']);
 	
}
       
	    die();
}
add_action('wp_ajax_check_report', 'checkreport');
add_action('wp_ajax_nopriv_check_report', 'checkreport');

function printReport() {	
	
	
	$SIGNAME=array("KUMARAN K", "S KAARTHI", "PADHMA SIVLINGAM S", "SIVALINGAM K");
		$SIGN=array('452', '21344', '43400', '20118');
		 			
$sql_brn = select_query_json_test("Select end.*,c.ctyname,esec.eseNAME From employee_notice_detail end,empsection esec,city c,branch b 
									Where esec.esecode=end.esecode and c.ctycode=b.ctycode and b.brncode=end.brncode and notyear='".$_REQUEST['notyear']."' and notnumb='".$_REQUEST['notnumb']."'", "Centra", 'TEST');
	$addname = select_query_json("Select EMPNAME From employee_OFFICE Where empsrno='".$_REQUEST['authby']."'", "Centra", 'TCS');
	
	$authname = select_query_json("Select EMPNAME From employee_OFFICE Where EMPSRNO='".$sql_brn[0]['AUTSRNO']."'", "Centra", 'TCS');
	   $id1="<tr>
					    <td style='text-align: center; width: 20%;'>RECEIVER</td>
					    	<td style='text-align: center; padding-left: 10px; width: 20%;'>KUMARAN K</td><td style='text-align: center; padding-left: 10px; width: 20%;'>S KAARTHI</td><td style='text-align: center; padding-left: 10px; width: 20%;'>PADHMA SIVLINGAM S</td><td style='text-align: center; padding-left: 10px; width: 20%;'>SIVALINGAM K</td></tr>" ;   
		$id2="<tr>
					    <td style='text-align: center; width: 20%;'>RECEIVER</td>			   
					    	<td style='text-align: center; padding-left: 10px; width: 20%;'>".$addname[0]['EMPNAME']."</td></tr>" ;  
	$sign1="<tr>
					    <td style='text-align: center; width: 20%;'></td><td style='text-align: center; width: 20%;'><label style='color:#0088CC; font-weight:bold'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2018/09/452.png' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label></td><td style='text-align: center; width: 20%;'><label style='color:#0088CC; font-weight:bold'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2018/09/21344.png' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label></td><td style='text-align: center; width: 20%;'><label style='color:#0088CC; font-weight:bold'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2018/09/43400.png' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label></td><td style='text-align: center; width: 20%;'><label style='color:#0088CC; font-weight:bold'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2018/09/20118.png' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label></td>					   
					    	</tr>";
	$sign2="<tr>
					    <td style='text-align: center; width: 20%;'></td><td style='text-align: center; width: 20%;'><label style='color:#0088CC; font-weight:bold'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2018/09/43400.png' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label></td></tr>";
		$sign3="<tr>
					    <td style='text-align: center; width: 20%;'></td><td style='text-align: center; width: 20%;'><label style='color:#0088CC; font-weight:bold'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2018/09/452.png' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label></td></tr>";
		$sign4="<tr>
					    <td style='text-align: center; width: 20%;'></td><td style='text-align: center; width: 20%;'><label style='color:#0088CC; font-weight:bold'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2018/09/21344.png' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label></td></tr>";
		$sign5="<tr>
					   <td style='text-align: center; width: 20%;'></td> <td style='text-align: center; width: 20%;'><label style='color:#0088CC; font-weight:bold'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2018/09/20118.png' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label></td></tr>";					    	
	
	echo "<div class='container'>
		<div class='row'>
			<div class='col-md-12'>
				<h1 style='text-align:center;'><img src='http://www.thechennaisilks.co/tcs_connect/wp-content/uploads/2017/12/cropped-the-chennai-silks-logo-1-1.png'></h1>
			
				<table style='width:100%'>
					<tr style='padding-bottom:10px'>
					    <td style='text-align:right;' width='85%'>DATE  :&nbsp;&nbsp;  </td><td style='text-align:left;' width='15%'>"."  ".strtoupper(date('d-M-Y'))."</td></tr>
					  <tr>  <td style='text-align:right;' width='85%'>REF NO  :&nbsp;&nbsp;  </td> <td style='text-align:left;' width='15%'>"."  ".$sql_brn[0]['NOTYEAR']."&nbsp;-&nbsp;".$sql_brn[0]['NOTNUMB']."</td></tr>	
					  </tr>
				</table>
				
				<table style='width:100%'>
					  
					  <tr>
					    <td align='right' width='15%'>TO :</td>
					    <td align='left'></td> 					   
					  </tr>
					  <tr>
					    <td align='right' width='15%'></td>
					    <td align='left'>".$sql_brn[0]['EMPNAME']."</td> 					   
					  </tr>
					  <tr>
					    <td align='right' width='15%'></td>
					    <td align='left'>EC NO : ".$sql_brn[0]['EMPCODE']."</td> 					   
					  </tr>
					  <tr>
					    <td align='right' width='15%'></td>
					    <td align='left'>".$sql_brn[0]['ESENAME']."</td> 					   
					  </tr>
					  <tr>
					    <td align='right' width='15%'></td>
					    <td align='left'>THE CHENNAI SILKS - ".($sql_brn[0]['BRNCODE']=='888'?"CORPORATE OFFICE":"")."</td> 					   
					  </tr>
					  <tr>
					    <td align='right' width='15%'></td>
					    <td align='left'>TIRUPPUR</td> 					   
					  </tr>
						<tr height='50'></tr>	
					   <tr>
					    <td align='right' width='15%'>FROM :</td>
					    <td align='left'></td> 					   
					  </tr>
					  <tr>
					    <td align='right' width='15%'></td>
					    <td align='left'>".$authname[0]['EMPNAME']."</td> 					   
					  </tr>
					  <tr>
					    <td align='right' width='15%'></td>
					    <td align='left'>THE CHENNAI SILKS - ( CORPORATE OFFICE )</td> 					   
					  </tr>
					  <tr>
					    <td align='right' width='15%'></td>
					    <td align='left'>".$sql_brn[0]['CTYNAME']."</td> 					   
					  </tr>
					  <tr height='50'></tr>
					  <tr>
					    <td align='right' width='15%'>SUBJECT :</td>
					    <td align='left'></td> 						     
					  </tr>
					  <tr class='subject'>
					  	<td align='right' width='15%'></td> 	
					  	<td align='left' width='85%'>".$sql_brn[0]['REMARKS']."</td>  
					  </tr>
					  
				</table>
				
			</div>
		</div>
		<div class='row sign' style='margin-top:100px'>
			<div class='col-md-12'>".($_REQUEST['all'] == 4?"<table style='width:100%' class='signature'>".$sign1."</table>":"").($_REQUEST['authby']=='43400'?"<table style='width:100%' class='signature'>".$sign2."</table>":"").($_REQUEST['authby']=='452'?"<table style='width:100%' class='signature'>".$sign3."</table>":"").($_REQUEST['authby']=='21344'?"<table style='width:100%' class='signature'>".$sign4."</table>":"").($_REQUEST['authby']=='20118'?"<table style='width:100%' class='signature'>".$sign5."</table>":"")."
			
		</div>
		</div>
		<div class='row sign' style='margin-top:10px'>
			<div class='col-md-12'>
			<table style='width:100%' class='signature'>".($_REQUEST['all'] == 4?".$id1.":".$id2.")."
				
			</table>
		</div>
		</div>
		</div>";
	die();
}
add_action('wp_ajax_print_report', 'printReport');
add_action('wp_ajax_nopriv_print_report', 'printReport');

global $wpdb;

$user_login = $wpdb->get_results("SELECT * FROM $wpdb->tcs_users");	
print_r[$user_login];
// Test if the query exists at the URL
global $_GET;
echo $_GET['userec'];
if(isset($_GET['userec'])!=''){
$user_login = $wpdb->get_results("SELECT user_login FROM $wpdb->users WHERE (user_login ='".$_GET['userec']."' )");	
if($user_login>0){	
	echo $user_login['user_login'];
if(!is_user_logged_in()){

    $username = $_GET['userec'];

    if($user=get_user_by('login',$username)){

        clean_user_cache($user->ID);

        wp_clear_auth_cookie();
        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID , true, false);

        update_user_caches($user);

        if(is_user_logged_in()){

            $redirect_to = user_admin_url();
            wp_safe_redirect( $redirect_to );
            exit;
        }
    }
}}
else{
wp_logout();
$response1 = select_query_json("select * from userid where USRCODE = ".$_GET['userec']."", "Centra", 'TEST');	
echo $resvalue = $response1[0]['EMPSRNO'];	
$sql_emp = select_query_json("select e.*, (select ATCNAME from APPROVAL_TOPCORE where ATCCODE in ((select topcore from empcore_section 
												where esecode = e.esecode))) topcore, (select CORNAME from empcore_section where esecode = e.esecode) subcore,
												(select ATCCODE from APPROVAL_TOPCORE where ATCCODE in ((select topcore from empcore_section
												where esecode = e.esecode))) topcore_code, ESECODE subcore_code
											from employee_office e where EMPSRNO = ".$resvalue, "Centra", 'TEST');

$empcode = select_query_json("select * from employee_office where EMPSRNO = ".$resvalue, "Centra", 'TEST');
$empmail = select_query_json("select * from employee_personal where EMPSRNO = ".$empcode[0]['EMPSRNO'], "Centra", 'TEST');

    $user_login=$response1[0]['USRCODE'];	
	$user_nicename=$empcode[0]['EMPNAME'];
	$user_email=$empmail[0]['EMAIL'];
	$username = $_GET['userec'];
	$user_registered=date('Y-m-d H:i:s');
	$status=0;
	$display_name=$empcode[0]['EMPNAME'];
$userdata = array(
    'user_login'  =>  $user_login,
    'user_nicename'    =>  $user_nicename,
	'user_email'    =>  $user_email,
	'user_registered'    =>  $user_registered,
	'display_name'    =>  $display_name,
	'user_status'    =>  '0',
    'user_pass'   =>  NULL  // When creating an user, `user_pass` is expected.
);

$user_id = wp_insert_user( $userdata ) ;

//On success
if ( ! is_wp_error( $user_id ) ) {
    echo "User created : ". $user_id;
	if($user=get_user_by('login',$user_login)){

        clean_user_cache($user->ID);

        wp_clear_auth_cookie();
        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID , true, false);

        update_user_caches($user);

        if(is_user_logged_in()){

            $redirect_to = user_admin_url();
            wp_safe_redirect( $redirect_to );
            exit;
        }
    }
	
}
    
}
}




?>