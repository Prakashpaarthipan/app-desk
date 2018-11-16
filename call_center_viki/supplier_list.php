<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
//include_once('general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

if(isset($_POST["limit"], $_POST["start"]))
{	$limit = $_POST["limit"];
	$supplier_list = select_query_json("select distinct SUPCODE, SUPNAME, SUPADD1, SUPADD2,SUPADD3,SUPPHN1,SUPPHN2,SUPMOBI,SUPMAIL from supplier where deleted = 'N' and SUPCODE>=7000 and SUPCODE between '".$_POST["start"]."' and '".$_POST["limit"]."' ORDER BY SUPCODE","Centra","TCS");
	
	$datas = array();
	
	for($i = 0 ; $i<count($supplier_list) ; $i++){?>

		<div class="col-md-3" style="height:330px">
							
            <!-- CONTACT ITEM -->
            <div class="panel panel-default list">
                <div class="panel-body profile">
                    <div class="profile-image">
                        <img src="images/logo-original.png" alt="<?echo $supplier_list[$i]['SUPNAME'];?>"/>
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name" style="text-transform:capitalize;font-size:13px;font-weight:bold"><?echo ucwords(str_replace("**","",strtolower($supplier_list[$i]['SUPNAME'])));?></div>
                        <div class="profile-data-title" style="text-transform:capitalize;font-size:14px;font-weight:bold"><?echo $supplier_list[$i]['SUPCODE'];?></div>
                    </div>
                    <div class="profile-controls">
                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                        <a href="call_center_support.php" class="profile-control-right"><span class="fa fa-comments"></span></a>
                    </div>
                </div>                                
                <div class="panel-body" style="height:100px;overflow:hidden; display:inline-block">                                    
                    <div class="contact-info">
                        <p><small>Mobile</small><br/><?if($supplier_list[$i]['SUPMOBI']!="" && $supplier_list[$i]['SUPMOBI']!=0 ){echo $supplier_list[$i]['SUPMOBI'];}else{echo " - ";}?></p>
                        <p><small>Email</small><br/><?if($supplier_list[$i]['SUPMAIL']!="" && $supplier_list[$i]['SUPMAIL']!=0 ){echo $supplier_list[$i]['SUPMAIL'];}else{echo " - ";}?></p>
                        <p  style="display:none"><small>Address</small><br/><?echo ucwords(strtolower($supplier_list[$i]['SUPADD1'].' '.$supplier_list[$i]['SUPADD2'].' '.$supplier_list[$i]['SUPADD3']));?></p>                              
                    </div>
                </div>                                
            </div>    
        </div>

							
	
	<?}
	/*echo ($_POST["limit"]);
	echo ($_POST["start"]);*/
						
}
	if($action=='searchdb')
			{
			if(isset($_POST["search"])){
				$supplier_list_db = select_query_json("select distinct SUPCODE, SUPNAME,'-' SUPADD1,'-' SUPADD2,'-' SUPADD3,SUPPHN1,SUPPHN2,SUPMOBI,SUPMAIL from supplier where deleted = 'N' and SUPNAME like '%".$_POST["searchdb"]."%' OR SUPMOBI like '%".$_POST["searchdb"]."%'  ORDER BY SUPCODE ","Centra","TCS");
	
				$datas_db = array();
				
				//print_r($supplier_list_db);
				for($j = 0 ; $j<count($supplier_list_db); $j++){?>
					<div class="col-md-3" style="height:350px">

                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default list">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="images/logo-original.png" alt="<?echo $supplier_list_db[$j]['SUPNAME'];?>"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name" style="text-transform:capitalize;font-size:14px;font-weight: bold;"><?echo ucwords(str_replace("**","",strtolower($supplier_list_db[$j]['SUPNAME'])));?></div>
                                        <div class="profile-data-title"><?echo $supplier_list_db[$j]['SUPCODE'];?></div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="call_center_support.php" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body" style="height:100px;overflow:hidden;">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/><?if($supplier_list_db[$j]['SUPMOBI']!="" && $supplier_list_db[$j]['SUPMOBI']!=0){echo $supplier_list_db[$j]['SUPMOBI'];}else{echo " - ";}?></p>
                                        <p><small>Email</small><br/><?if($supplier_list_db[$j]['SUPMAIL']!="" && $supplier_list_db[$j]['SUPMAIL']!=0){echo $supplier_list_db[$j]['SUPMAIL'];}?></p>
                                        <!--<p><small>Address</small><br/><?echo ucwords(strtolower($supplier_list_db[$j]['SUPADD1'].' '.$supplier_list_db[$j]['SUPADD2'].' '.$supplier_list_db[$j]['SUPADD3']));?></p> -->                                  
                                    </div>
                                </div>                                
                            </div>    
                        </div>
					
				<?}
				
				
			}
			
			}		
			
			if($action == 'textsearch'){
				
				$supplier_list_db_txt = select_query_json("select distinct SUPCODE, SUPNAME, SUPADD1, SUPADD2, SUPADD3,SUPPHN1,SUPPHN2,SUPMOBI,SUPMAIL from supplier where deleted = 'N' and SUPCODE >= 7000 and (SUPNAME like '%".$_POST["search"]."%' OR SUPCODE like '%".$_POST["search"]."%' OR SUPMOBI like '%".$_POST["search"]."%')   ORDER BY SUPCODE","Centra","TCS");
				echo '<div class="col-md-12 result">  <div class="panel panel-default">
                                <div class="panel-body"><p style="color:green; font-weight:normal; font-size:12px">  About '.count($supplier_list_db_txt).' similar results found. Search Term : <b>'.$_POST["search"].'</b></p></div></div></div>';
				//var_dump($supplier_list_db_txt);
				//echo $_POST["search"];
				//print_r($supplier_list_db_txt);				
				for($m = 0 ; $m <count($supplier_list_db_txt); $m++){?>
					
					<div class="col-md-3" style="height:350px">
							
                            <!-- CONTACT ITEM -->
                            <div class="panel panel-default list">
                                <div class="panel-body profile">
                                    <div class="profile-image">
                                        <img src="images/logo-original.png" alt="<?echo $supplier_list_db_txt[$m]['SUPNAME'];?>"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name" style="text-transform:capitalize;font-size:13px;font-weight: bold;"><?echo ucwords(str_replace("**","",strtolower($supplier_list_db_txt[$m]['SUPNAME'])));?></div>
                                     <div class ="profile-data-title" style="text-transform:capitalize;font-size:14px;font-weight:bold"> <?echo $supplier_list_db_txt[$m]['SUPCODE'];?></div>
                                    </div>
                                    <div class="profile-controls">
                                        <a href="#" class="profile-control-left"><span class="fa fa-info"></span></a>
                                        <a href="call_center_support.php" class="profile-control-right"><span class="fa fa-comments"></span></a>
                                    </div>
                                </div>                                
                                <div class="panel-body" style="height:100px;overflow:hidden;">                                    
                                    <div class="contact-info">
                                        <p><small>Mobile</small><br/><?if($supplier_list_db_txt[$m]['SUPMOBI']!="" && $supplier_list_db_txt[$m]['SUPMOBI']!=0 ){echo $supplier_list_db_txt[$m]['SUPMOBI'];}else{echo " - ";}?></p>
                                        <p><small>Email</small><br/><?if($supplier_list_db_txt[$m]['SUPMAIL']!="" && $supplier_list_db_txt[$m]['SUPMAIL']!=0 ){echo $supplier_list_db_txt[$m]['SUPMAIL'];}else{echo " - ";}?></p>
                                        <p style="display:none"><small>Address</small><br/><?echo ucwords(strtolower($supplier_list_db_txt[$m]['SUPADD1'].' '.$supplier_list_db_txt[$m]['SUPADD2'].' '.$supplier_list_db_txt[$m]['SUPADD3']));?></p>                                   
                                    </div>
                                </div>                                
                            </div>    
                        </div>	
						
										
				<?	}
				}
	
?>
