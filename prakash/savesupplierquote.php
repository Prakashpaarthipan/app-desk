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
  		
  		$SUPPSRNO = select_query_json("Select nvl(Max(SUPSRNO),0)+1 maxarqcode From approval_product_quotation_fix where TARNUMB = '".$tar."' and PRDCODE = '".$prdcode[0]."' and SUBCODE = '". $prdcode[2]."'" ,"Centra","TEST");

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
         $g_supplier_rate_fix['ENTNUMB '] = $ENTNUMB[0]['MAXARQCODE'];
         $g_supplier_rate_fix['ARQYEAR '] = $current_year[0]['PORYEAR'];
         $g_supplier_rate_fix['APRNUMB '] = trim($ap);
         $g_supplier_rate_fix['BRNCODE '] = trim($brncode);
         $g_supplier_rate_fix['TARNUMB '] = trim($tar);
         $g_supplier_rate_fix['PRDCODE '] = trim($prdcode[0]);
         $g_supplier_rate_fix['PRDSRNO '] = $PRDSRNO[0]['MAXARQCODE'];
         $g_supplier_rate_fix['DEPCODE '] = trim($prdcode[1]);
         $g_supplier_rate_fix['SUBCODE '] = trim($prdcode[2]);
         $g_supplier_rate_fix['SUPCODE '] = trim($suppcode[0]);
         $g_supplier_rate_fix['SUPSRNO '] = $SUPPSRNO[0]['MAXARQCODE'];
         $g_supplier_rate_fix['SUPDLVY '] = trim($duration[$k]);
         $g_supplier_rate_fix['SUPRATE '] = trim($rate[$k]);
         $g_supplier_rate_fix['SUPDISC '] = trim($discount[$k]);
         $g_supplier_rate_fix['PRDQTY  '] = trim($qty[$k]);
         $g_supplier_rate_fix['REMARKS '] = strtoupper(trim($remarks[$k]));
         $g_supplier_rate_fix['SUPQFILE'] = $nameforserver;
         $g_supplier_rate_fix['QUOFINIS'] = 'N';
         $g_supplier_rate_fix['SLTSUPP '] = 0;
         $g_supplier_rate_fix['NETAMT '] = 0;
         $g_supplier_rate_fix['CGSTPER '] = trim($cgst[$k]);
         $g_supplier_rate_fix['SGSTPER '] = trim($sgst[$k]);
         $g_supplier_rate_fix['IGSTPER '] = trim($igst[$k]);
         $g_supplier_rate_fix['SUPCESS '] = trim($cess[$k]);
         $g_supplier_rate_fix['ADDUSER '] = $_SESSION['tcs_usrcode'];
         $g_supplier_rate_fix['ADDDATE '] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
         $g_supplier_rate_fix['DELETED '] = 'N';


         $g_supplier_rate_fix['QTRATE '] = trim($rate[$k]);
         $g_supplier_rate_fix['QTDISC '] = trim($discount[$k]);
         $g_supplier_rate_fix['QTCGST '] = trim($cgst[$k]);
         $g_supplier_rate_fix['QTSGST '] = trim($sgst[$k]);
         $g_supplier_rate_fix['QTIGST '] = trim($igst[$k]);
         $g_supplier_rate_fix['QTCESS '] = trim($cess[$k]);


		

    $g_insert_subject = insert_test_dbquery($g_supplier_rate_fix,$g_table);
    print_r($g_supplier_rate_fix);
    

  }


}

if($_REQUEST['action']=='finish')
    
    {   $tarnum=explode("-" , $tar);
        $prdcode = explode(":", $prd);
        $tbl_product_quote = "approval_product_quotation_fix";
        $field_quote = array();
        $field_quote['QUOFINIS'] = 'S';
       // $where_quote = "APRNUMB = '".trim($ap)."' and QUOFINIS = 'N' and TARNUMB = '".trim($tarnum[0])."' and PRDCODE = '".trim($prdcode[0])."' and SUBCODE = '".trim($prdcode[2])."'";
        $where_quote = "APRNUMB = '".trim($ap)."' and QUOFINIS = 'N' ";
        //echo "APRNUMB = '".trim($ap)."' and QUOFINIS = 'N' and TARNUMB = '".trim($tarnum[0])."' and PRDCODE = '".trim($prdcode[0])."' and SUBCODE = '".trim($prdcode[2])."'";

        $quotation_fix = update_test_dbquery($field_quote, $tbl_product_quote,$where_quote);


        if($quotation_fix == 1)
        {
        $tbl_appreq = "APPROVAL_REQUEST";
        $field_appreq=array();
        $field_appreq['APPSTAT'] = 'N';
        $where_appreq = "APRNUMB = '".trim($ap)."' and APPSTAT = 'E'";

        $insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq,$where_appreq);

        }
        if($insert_appreq == 1){
            echo '1';
        }
        else{
            echo '0';
        }

    }

    if($_REQUEST['action']=='finishCheck')
    
    {   
        
        
        
        $search_finish =select_query_json("select APRNUMB from approval_product_quotation_fix where QUOFINIS = 'N' and DELETED = 'N' and APRNUMB = '".trim($ap)."' ","Centra","TEST");

        if(count($search_finish)>0)
        {
            echo 1;
        }
        else{
            echo 0;
            
        }
        

    }


if($_REQUEST['action'] =='get_state') 
{

    $expld1 = explode(":", strtoupper($prdcodestate));
    $prdcd = $expld1[0];  
    $sbprd = $expld1[2];
    
    $result_sup = select_query_json("select distinct SUPCODE, SUPNAME, SUPMOBI from supplier_asset
                                            where deleted = 'N' and (SUPCODE = ".trim($supcode).")
                                            order by SUPCODE, SUPNAME", "Centra", 'TCS');
    $sql_sup_city = select_query_json("select stacode from city where ctycode in (select ctycode from supplier_asset where supcode=".trim($supcode).")", "Centra", 'TCS');

    $sql_brn = select_query_json("select stacode from city where ctycode in (select ctycode from branch where brncode=".$brncode.")", "Centra", 'TCS');
    if(count($result_sup) > 0) {
        $rtrn = 1;
    } else {
        $rtrn = 0;
    }

    if($sql_brn[0]['STACODE'] == $sql_sup_city[0]['STACODE'])
    {
        $rtrn .= "~1";
    }else{
        $rtrn .= "~0";
    }
    echo trim($rtrn);
    
    
    
}

if($_REQUEST['action'] =='get_tax') 
{   
    $expld1 = explode(":", strtoupper($prdcodetax));
    $prdcd = $expld1[0];  
    $sbprd = $expld1[2];
    //echo "select CGSTPER||'-'||SGSTPER||'-'||IGSTPER TAX_PERCENTAGE from product_asset_gst_per where prdcode = '".trim($prdcd)."' and subcode = '".trim($sbprd)."' and rownum <= 1";

    $result = select_query_json("select CGSTPER||'-'||SGSTPER||'-'||trim(IGSTPER) TAX_PERCENTAGE
                                    from product_asset_gst_per
                                    where prdcode = '".trim($prdcd)."' and subcode = '".trim($sbprd)."' and rownum <= 1", "Centra", 'TCS');
    $sql_tax_n = select_query_json("select * from subproduct_asset where prdcode='".trim($prdcd)."' and subcode=".trim($sbprd)." and rownum = 1", "Centra", 'TCS');
    if(count($result) > 0) {
        
        echo trim($result[0]['TAX_PERCENTAGE']);
    }
    elseif($sql_tax_n[0]['PRDTAX'] == "N") {
        echo '0-0-0';
    }else{
        echo '';
    }


}
?>