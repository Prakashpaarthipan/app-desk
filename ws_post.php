<?php
session_start();

/*$username = $_POST['uname'];
$password = $_POST['password'];


$client = new SoapClient("http://172.16.0.166:8080/cdata.asmx?Wsdl");

    
    $brn_parameter->Brn_Code_='$username';
    $brn_parameter->Dep_Code_='$password';
    
    try{
        $brn_result=$client->Get_PerCentage($brn_parameter)->Get_PerCentageResult;
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
    $brn =  json_decode($brn_result,true);
     echo '<script>window.location="home.php";</script>';

    echo "<pre>";
    print_r($brn);

*/

echo "1";

?>