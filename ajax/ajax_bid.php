<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year

if($_SESSION['tcs_user'] == '') { 
echo "Login";
exit();
}
if($_REQUEST['action'] == "CREATE")
{
	$today = date("Y-m-d H:i:s"); 
	//$pbdcode = $head.str_pad($code, 6, '0', STR_PAD_LEFT);
	$pbdcode = 2002423;
	

	$sql_list = select_query_json("select * from approval_productlist where pbdyear='".$year."' and pbdcode= ".$pbdcode."", "Centra", 'TCS');
	$sql_qua  = select_query_json("select * from approval_product_quotation where pbdyear='".$year."' and pbdcode= ".$pbdcode."", "Centra", 'TCS');

	foreach ($sql_list as $res) {
		    $list[] = array('product_id' =>$res['PRDCODE']."-".$res['SUBCODE'],'qty' => (int)$res['TOTLQTY']);
          }
     foreach ($sql_qua as $res) {
     		$q_list = array();
     		foreach ($sql_list as $li) {
     			$q_list[] = array('product_id' =>$li['PRDCODE']."-".$li['SUBCODE'],'unit_price' => (int)$res['PRDRATE'],'qty' => (int)$li['TOTLQTY'],'supplier_ref' => $res['PBDYEAR']."~".$res['PBDCODE']."~".$res['PBDLSNO']."~".$res['PRLSTSR']);
     		}
			$qua[] = array('partner_id' =>$res['SUPCODE'],'dept_id' => "PAYMENT",'line_ids'=>$q_list);
          }
      $fin_val = array();
      $fin_val[] = array('userid' => 'Administrator','date_order' => $today,'line_ids'=>$list,'partner'=>$qua );
      $fin_val = json_encode($fin_val);

      //$url = "http://rfq.thechennaisilks.com:8069/api/purchase.requisition/create_rfq?token=d22d137e03bc4118b83358a7641b2a21&create_vals=".$fin_val;

      //print_r($url);
      
      /* $data = array ('token' => 'd22d137e03bc4118b83358a7641b2a21', 'create_vals' => $fin_val);
		$data = http_build_query($data);

$context_options = array (
        'http' => array (
            'method' => 'POST',
            'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($data) . "\r\n",
            'content' => $data
            )
        );*/

//$context = stream_context_create($context_options);
 //$result = file_get_contents('http://rfq.thechennaisilks.com:8069/api/purchase.requisition/create_rfq', false, $context);
 /*$result = file_get_contents($url);
 $result = json_decode($result);
 print_r($result);*/
 //var_dump($result);
$fin_val1 = "[{'user_id':'Administrator','date_order':'2017-11-28','line_ids':[{'product_id':'ABN-14','qty':4}],'partner':[{'partner_id':'15','dept_id':'PAYMENT','line_ids':[{'product_id':'ABN-12','unit_price':1000,'qty':4,'supplier_ref':'2017-18~4002428~1~1'}]}]}]";

$fin_val1 = json_decode($fin_val1);
var_dump($fin_val1);

function isJson($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

var_dump(isJson($fin_val1));
exit;
/*$data = array("token" => "d22d137e03bc4118b83358a7641b2a21", "create_vals" => $fin_val1);*/
/*$url = "http://rfq.thechennaisilks.com:8069/api/purchase.requisition/create_rfq?token=d22d137e03bc4118b83358a7641b2a21&create_vals=".$fin_val1;                                                                    
$data_string = json_encode($data);                                                                                   
$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      

$result = curl_exec($ch);

print_r($result);*/
$ch = curl_init( 'http://rfq.thechennaisilks.com:8069/api/purchase.requisition/create_rfq' );
# Setup request to send json via POST.
$payload = json_encode( array( "token"=> 'd22d137e03bc4118b83358a7641b2a21',"create_vals"=> $fin_val1 ) );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
curl_close($ch);
# Print response.
echo "<pre>$result</pre>";


}

/*http://rfq.thechennaisilks.com:8069/api/purchase.requisition/create_rfq?token=d22d137e03bc4118b83358a7641b2a21&create_vals=[{'user_id':'Administrator','date_order':'2017-11-28','line_ids':[{'product_id':'ABN-14','qty':4}],'partner':[{'partner_id':'15','dept_id':'PAYMENT','line_ids':[{'product_id':'ABN-12','unit_price':1000,'qty':4,'supplier_ref':'2017-18~3002428~1~1'}]}]}]*/
 

