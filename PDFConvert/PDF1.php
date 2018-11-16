<?php
require 'autoload.php';
use mikehaertl\wkhtmlto\Pdf;
		
ob_start();
?>




<html>
<body>
<ul>
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
// print_r($_REQUEST);
//echo('1');

	$commentno = select_query_json("select prc.reqcmnt,eof.empname,eof.empcode,usid.empsrno,to_char(prc.adddate,'dd/MM/yyyy HH:mi:ss Am') add_time from process_requirement_comment prc,employee_office eof,userid usid where prc.entryyr='2018-19' and prc.entryno='1' and prc.entsrno='1' and usid.usrcode=prc.adduser and eof.empsrno=usid.empsrno","Centra","TEST");
	
	for($k=0;$k<sizeof($commentno);$k++) {?>
	<p>hi</p>
	<li class="media">
		<a class="pull-left" href="#">
			<img class="media-object img-text" alt="Cinque Terre" src="profile_img.php?profile_img=<?=$commentno[$k]['EMPSRNO']?>" style="height:70px; width:70px; border-radius:100px; border: 1px solid #A0A0A0;" title="	">
			<!--<img class="media-object img-text" src="assets/images/users/user.jpg" alt="<?php echo $commentno[$k]['EMPNAME']?>" width="64">-->
		</a>
		<div class="media-body">
			<h4 class="media-heading"><?php echo $commentno[$k]['EMPNAME']." - ".$commentno[$k]['ADD_TIME']?></h4>
			
			<p><?php echo $commentno[$k]['REQCMNT']?> </p>
		</div>                                            
	</li>
	
<?php } ?>
</ul>
</body>
</html>




<?php
$invoice_html = ob_get_contents();
ob_end_clean();	

$pdf = new Pdf();

$pdf->addPage($invoice_html);
if (!$pdf->saveAs('page.pdf')) {
    echo $pdf->getError();
}
$pdf->send('page.pdf');		
?>