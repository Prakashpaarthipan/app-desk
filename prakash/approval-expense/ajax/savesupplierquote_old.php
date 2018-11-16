<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
//include_once('general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

if($_REQUEST['action']=='save'){
 	
    $g_table = "approval_product_quotation_fix";
    $g_field_fix = array();


    for ($k= 0 ; $k < sizeof($slp_load); $k++)
  {
  	$prdcode = explode(":", $prd);

	$ENTNUMB =  select_query_json("Select count(ENTNUMB) maxarqcode From approval_product_quotation_fix where APRNUMB = '".trim($ap)."'","Centra","TEST");
	$PRDSRNO = select_query_json("select count(PRDSRNO)  maxarqcode from approval_product_quotation_fix where APRNUMB = '".trim($ap)."' and TARNUMB = '".$tar."' and PRDCODE = '".$prdcode[0]."' and  SUBCODE = '".$prdcode[2]."' ","Centra","TEST");

	if($ENTNUMB[0]['MAXARQCODE'] !=0 && $PRDSRNO[0]['MAXARQCODE'] ){
		$ENTNUMB =  select_query_json("Select nvl(Max(ENTNUMB),0) maxarqcode From approval_product_quotation_fix where APRNUMB = '".trim($ap)."'","Centra","TEST");
		$ENTNUMB[0]['MAXARQCODE'] = $ENTNUMB[0]['MAXARQCODE']+1;
		$PRDSRNO = select_query_json("select nvl(Max(PRDSRNO),0)  maxarqcode from approval_product_quotation_fix where APRNUMB = '".trim($ap)."' and TARNUMB = '".$tar."' and PRDCODE = '".$prdcode[0]."' and  SUBCODE = '".$prdcode[2]."' ","Centra","TEST");
		$PRDSRNO[0]['MAXARQCODE'] = $PRDSRNO[0]['MAXARQCODE']+1;
	}
	else{
		$ENTNUMB =  select_query_json("Select nvl(Max(ENTNUMB),0)+1 maxarqcode From approval_product_quotation_fix where ARQYEAR = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
		$PRDSRNO = select_query_json("select nvl(Max(PRDSRNO),0)+1  maxarqcode from approval_product_quotation_fix where APRNUMB = '".trim($ap)."' and TARNUMB = '".$tar."' and PRDCODE = '".$prdcode[0]."' and  SUBCODE = '".$prdcode[2]."' ","Centra","TEST");
	}	
  		$suppcode = explode(" - ", $slp_load[$k]);
  		
  		$SUPPSRNO = select_query_json("Select nvl(Max(SUPPSRNO),0)+1 maxarqcode From approval_product_quotation_fix where TARNUMB = '".$tar."' and PRDCODE = '".$prdcode[0]."' and SUBCODE = '". $prdcode[2]."'" ,"Centra","TEST");

		  				/// File Upload
		//$noa = sizeof($_FILES['attachments']['name']);  
	
  		 if($_FILES['attachments']['name'][$k] != null)
      { 
        
        ///----------updating the index to attachment to local
        $q=$_FILES['attachments']['name'][$k];
        $path_parts = pathinfo($q);
        $tmp_name = $_FILES["attachments"]["tmp_name"][$k];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
        
        $name=$current_year[0]['PORYEAR'].'_'.$ENTNUMB[0]['MAXARQCODE'].'_'.$prdcode[0].'_'.$prdcode[2].'_'.$suppcode[0].'.'.strtolower($path_parts['extension']);
        // echo "\n".$name."\n";
        $a1local_file = "../uploads/admin_projects_local/attachments/".$name;
        move_uploaded_file($tmp_name, $a1local_file);

            ///---------updating the index to attachment to server
        
        $nameforserver = $current_year[0]['PORYEAR'].'_'.$ENTNUMB[0]['MAXARQCODE'].'_'.$prdcode[0].'_'.$prdcode[2].'_'.$suppcode[0].'.'.strtolower($path_parts['extension']);
        // echo "\n".$name."\n";
        //$alocal_file = "../uploads/admin_projects_local/attachments/".$name;
        $a1local_file = "../uploads/admin_projects_local/attachments/".$name;
        //echo($a1local_file);
        //echo ($nameforserver);
        //$server_file = 'Approval_Desk/product_quotation_fix/2018-19/'.$nameforserver;
        $server_file = 'approval_desk/product_quotation_fix/2018-19/'.$nameforserver;
        //echo ($server_file);
        if ((!$conn_id) || (!$login_result)) {
           $upload = ftp_put($ftp_conn, $server_file, $a1local_file, FTP_BINARY);
          // echo($upload);

            echo "file uploaded";
           //unlink($alocal_file);
        }
        else{
          echo ("error");
        	}
    	}
	

        /// File Upload
         $g_supplier_rate_fix['ENTNUMB '] = ;
         $g_supplier_rate_fix['ARQYEAR '] = ;
         $g_supplier_rate_fix['APRNUMB '] = ;
         $g_supplier_rate_fix['BRNCODE '] = ;
         $g_supplier_rate_fix['TARNUMB '] = ;
         $g_supplier_rate_fix['PRDCODE '] = ;
         $g_supplier_rate_fix['PRDSRNO '] = ;
         $g_supplier_rate_fix['DEPCODE '] = ;
         $g_supplier_rate_fix['SUBCODE '] = ;
         $g_supplier_rate_fix['SUPCODE '] = ;
         $g_supplier_rate_fix['SUPSRNO '] = ;
         $g_supplier_rate_fix['SUPDLVY '] = ;
         $g_supplier_rate_fix['SUPRATE '] = ;
         $g_supplier_rate_fix['SUPDISC '] = ;
         $g_supplier_rate_fix['PRDQTY  '] = ;
         $g_supplier_rate_fix['REMARKS '] = ;
         $g_supplier_rate_fix['SUPQFILE'] = ;
         $g_supplier_rate_fix['QUOFINIS'] = ;
         $g_supplier_rate_fix['SELSUPP '] = ;
         $g_supplier_rate_fix['CGSTPER '] = ;
         $g_supplier_rate_fix['SGSTPER '] = ;
         $g_supplier_rate_fix['IGSTPER '] = ;
         $g_supplier_rate_fix['SUPCESS '] = ;
         $g_supplier_rate_fix['ADDUSER '] = ;
         $g_supplier_rate_fix['ADDDATE '] = ;
         $g_supplier_rate_fix['DELETED '] = ;
         $g_supplier_rate_fix['EDTUSER '] = ;
         $g_supplier_rate_fix['EDTDATE '] = ;
         $g_supplier_rate_fix['DELUSER '] = ;
         $g_supplier_rate_fix['DELDATE '] = ;


		$g_field_fix['ENTNUMB' ] = $ENTNUMB[0]['MAXARQCODE'];
		
		$g_field_fix['ARQYEAR' ] = $current_year[0]['PORYEAR'];
		$g_field_fix['APRNUMB' ] = trim($ap);
		
		$g_field_fix['TARNUMB' ] = $tar;
		$g_field_fix['PRDCODE' ] = $prdcode[0];
		$g_field_fix['DEPCODE' ] = $prdcode[1];
		$g_field_fix['PRDSRNO' ] = $PRDSRNO[0]['MAXARQCODE'];
		$g_field_fix['SUBCODE' ] = $prdcode[2];
		
		$g_field_fix['SUPPCODE'] = $suppcode[0];
		$g_field_fix['SUPPSRNO' ] = $SUPPSRNO[0]['MAXARQCODE'];
		$g_field_fix['SUPDLVY' ] = $duration[$k];
		$g_field_fix['SUPRATE' ] = $rate[$k];
		$g_field_fix['SUPDISC'] =  $discount[$k];
		$g_field_fix['PRDQTY '] =  $qty[$k];
		$g_field_fix['REMARKS' ] = $remarks[$k];
		$g_field_fix['SUPQFILE' ] = $nameforserver;
		$g_field_fix['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_field_fix['ADDDATE' ] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_field_fix['DELETED' ] = 'N';
		//$g_field_fix['EDTUSER'] =  '';
		//$g_field_fix['EDTDATE '] =  
		//$g_field_fix['DELUSER' ] = 
		//$g_field_fix['DELDATE'] = 
		$g_field_fix['FINISHS'] = 'N';
        $g_field_fix['SELSUPP'] = 'N';


    $g_insert_subject = insert_test_dbquery($g_field_fix,$g_table);
    print_r($g_field_fix);
    

  }


}

if($_REQUEST['action']=='finish')
    
    {   
        $tbl_product_quote = "approval_product_quotation_fix";
        $field_quote = array();
        $field_quote['FINISHS'] = 'S';
        $where_quote = "APRNUMB = '".trim($ap)."' and FINISHS = 'N'";

        $quotation_fix = update_test_dbquery($field_quote, $tbl_product_quote,$where_quote);


        if($quotation_fix == 1)
        {
        $tbl_appreq = "APPROVAL_REQUEST";
        $field_appreq=array();
        $field_appreq['APPSTAT'] = 'N';
        $where_appreq = "APRNUMB = '".trim($ap)."' and APPSTAT = 'E'";

        $insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq,$where_appreq);

        }

    }
    
   
?>


	
		




