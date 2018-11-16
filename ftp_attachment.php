<?php 
session_start();
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
} ?>
<link href="../ktmportal/css/lightgallery.css" rel="stylesheet">
<?
$sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS where aprnumb = '".$aprnumb."' order by APRHEAD, apdcsrn", 'Centra', 'TEST');
$apphed = '';
?>
<div><? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
for($ij = 0; $ij < count($sql_docs); $ij++) {
	// echo "<br>**".$sql_docs[$ij]['APRHEAD']."**".$apphed."**";
	if($sql_docs[$ij]['APRHEAD'] != $apphed) { // echo "@@"; ?>
		</div><div style="clear: both;"></div><div>
		<label style="font-weight: bolder;font-size: large;text-transform: uppercase; border-bottom: 1px solid #000;">
			<?
			if($sql_docs[$ij]['APRHEAD'] == 'fieldimpl'){
				echo "Budget / Common / Reference Approval";	
			}elseif($sql_docs[$ij]['APRHEAD'] == 'othersupdocs'){
				echo "Consultant Approval";	
			}elseif($sql_docs[$ij]['APRHEAD'] == 'quotations'){
				echo "Quotations & Estimations";	
			}elseif($sql_docs[$ij]['APRHEAD'] == 'clrphoto'){
				echo "Work Place Before / After Photo / Drawing Layout";	
			}elseif($sql_docs[$ij]['APRHEAD'] == 'artwork'){
				echo "Art Work Design with MD Approval";	
			} elseif($sql_docs[$ij]['APRHEAD'] == 'lastapproval'){
				echo "Last Approval";	
			}
			?>
		</label>
		</div><div style="clear: both;"></div><div>
		<?
		echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
	}
	$apphed = $sql_docs[$ij]['APRHEAD'];
	
	$filename = $sql_docs[$ij]['APRDOCS']; 
	$dataurl = $sql_docs[$ij]['APRHEAD'];
	$exp = explode("_", $filename);
	switch($exp[5])
	{
		case 'i':
				$folder_path = "approval_desk_test/request_entry/".$dataurl."/";
				$thumbfolder_path = "approval_desk_test/request_entry/".$dataurl."/thumb_images/";
				
				echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li>";
				break;
		case 'n':
				echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk_test/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" ><img src='../images/npdf.png' style='width:100px;height: 100px;margin-left:  10px;margin-top: 11px;' class=\"style_box\"> </a><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
				break;
		case 'w':
				echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk_test/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" ><img src='../images/npdf.png' style='width:100px;height: 100px;margin-left:  10px;margin-top: 11px;' class=\"style_box\"></a><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
				break;
		case 'e':
				echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk_test/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" ><img src='../images/npdf.png' style='width:100px;height: 100px;margin-left:  10px;margin-top: 11px;' class=\"style_box\"></a><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
				break;
		case 'p':
				echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk_test/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" ><img src='../images/npdf.png' style='width:100px;height: 100px;margin-left:  10px;margin-top: 11px;' class=\"style_box\"></a><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
				break;
		default:
				echo $fieldindi = '';
				break;
	}
}
echo "</ul>";
// } ?></div>
<div style='clear:both'></div>