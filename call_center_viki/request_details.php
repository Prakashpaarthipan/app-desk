<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
$ftp_conn_159 = ftp_connect($ftp_server_159) or die("Could not connect to $ftp_server");
$login_159 = ftp_login($ftp_conn_159, $ftp_user_name_159, $ftp_user_pass_159);

//$supplier = select_query_json("select sup.supcode,sup.supname from service_register_entry rre,supplier sup where rre.reqnumb='".$_REQUEST['reqnumb']."' and sup.supcode = rre.requser","Centra","TEST");
$reqsrno = select_query_json("select COUNT(REQSRNO) MAXREQSRNO from service_register_entry where reqnumb='".$_REQUEST['reqnumb']."'","Centra","TEST");
$MAXREQSRNO=$reqsrno[0]['MAXREQSRNO'];
//echo($MAXREQSRNO);
?><input type="hidden" name='reqnumb' id='reqnumb' val='<?$_REQUEST['reqnumb']?>'/>
<?
for($l=1;$l<=$MAXREQSRNO;$l++)
{ $supplier = select_query_json("select sup.supcode,sup.supname,C.CTYNAME,C.CTYCODE,SUP.SUPPHN1 from service_register_entry rre,supplier sup,CITY C where C.CTYCODE=SUP.CTYCODE AND rre.reqnumb='".$_REQUEST['reqnumb']."' and rre.reqsrno='".$l."' and sup.supcode = rre.requser","Centra","TEST");
  print_r($supplier);
  $request = select_query_json("select SRE.*,to_char(SRE.REQDATE,'dd/MM/yyyy HH:mi:ss Am') REQDATE1 from service_register_entry SRE where reqnumb='".$_REQUEST['reqnumb']."' and reqsrno='".$l."'" ,"Centra","TEST");
  print_r($request);
  //echo("select * from service_register_entry where reqnumb='".$_REQUEST['reqnumb']."' and reqsrno='".$l."'");
  //print_r($supplier);
  //print_r($request);
  $response=select_query_json("SELECT SRR.*,to_char(SRR.RESDATE,'dd/MM/yyyy HH:mi:ss Am') resdate1 FROM SERVICE_REGISTER_RESPONSE SRR,SERVICE_REGISTER_ENTRY SRE WHERE SRE.REQNUMB=SRR.REQNUMB AND SRE.REQSRNO=SRR.REQSRNO AND SRE.REQNUMB='".$_REQUEST['reqnumb']."' AND SRE.REQSRNO='".$l."' ORDER BY REQDATE","Centra","TEST");
  //echo("SELECT SRR.* FROM SERVICE_REGISTER_RESPONSE SRR,SERVICE_REGISTER_ENTRY SRE WHERE SRE.REQNUMB=SRR.REQNUMB AND SRE.REQSRNO=SRR.REQSRNO AND SRE.REQNUMB='".$_REQUEST['reqnumb']."' AND SRE.REQSRNO='".$l."' ORDER BY REQDATE");
  //print_r($response);
  ?>
  <div class="item item-visible">
      <div class="image">
          <img src="images/customers.png" style="width: 44px; height: 44px;" alt="<?echo $supplier[0]['SUPNAME']?>">
      </div>
      <div class="text cls_left">
          <div class="heading">
              <a href="#"><?echo $supplier[0]['SUPNAME']?>&nbsp(&nbsp<?echo $supplier[0]['CTYNAME']?>,&nbsp<?echo $supplier[0]['SUPPHN1']?>)</a>
              <span class="date"><?echo $request[0]['REQDATE1']?></span>
          </div>
          <?echo $request[0]['REQMSG']?>
          <div class="row">
            <?
            if($request[0]['REQPATH'] != '' and $request[0]['REQPATH'] != '-')
            {
              $filepath=explode('ftp://172.16.0.159/', $request[0]['REQPATH']);
              $file_list = ftp_nlist($ftp_conn_159, $filepath[1]);
              echo("<br><ul class='list-unstyled'>");
              if(count($file_list) > 0) {
                for($ij = 0; $ij < count($file_list); $ij++)
                {
                    $filename = 	$file_list[$ij];
                    $dataurl = $filepath[1];
                    $exp = explode(".", $filename);
                    $name = explode("/", $filename);
                    //print_r($name);
                    switch($exp[1])
                    {
                        case 'png':
                        case 'jpg':
                        case 'jpeg':
                        case 'pdf':
                                echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'> ".$name[3]."</span></a></li>&nbsp");
                                break;
                        case 'mp4':
                                echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'> ".$name[3]."</span></a></li>&nbsp");

                              break;
                        default:
                              echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159.$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                              break;
                              break;
                    }
                }
            }
          }echo("</ul>");
            ?>
          </div>
      </div>
  </div>

<?
  if($response[0]!='')
  {
        for($fg=0;$fg<sizeof($response);$fg++)
        { //print_r($response[$fg]);
          $employee = select_query_json("select eof.empcode,eof.empname from service_register_response rre,employee_office eof where rre.reqnumb='".$_REQUEST['reqnumb']."' and rre.reqsrno='".$l."' and rre.ressrno='".($fg+1)."' and eof.empcode = rre.resfusr","Centra","TEST");
          $assignmbr = select_query_json("select eof.empname,eof.empcode,deg.desname,ese.esename from employee_office eof,designation deg,empsection ese,service_register_response srr where eof.empcode=srr.restusr and srr.reqnumb='".$_REQUEST['reqnumb']."' and srr.reqsrno='".$l."' and srr.ressrno='".($fg+1)."' and deg.descode=eof.descode and ese.esecode=eof.esecode","Centra","TEST");
          //echo("select eof.empcode,eof.empname from service_register_response rre,employee_office eof where rre.reqnumb='".$_REQUEST['reqnumb']."' and rre.reqsrno='".$l."' and rre.ressrno='".($fg+1)."' and eof.empcode = rre.resfusr");
          ?>
        	<div class="item in item_in item-visible">
        			<div class="image">
        					<img src="images/logo-original.png" alt="<?echo $employee[0]['EMPNAME']?>">
        			</div>
        			<div class="text cls_right">
        					<div class="heading">
        							<a href="#"><?echo $employee[0]['EMPNAME']?></a>
        							<span style="padding-left: 10px;" class="date"><?echo $response[$fg]['RESDATE1'];?></span>
        					</div>
        					<?echo $response[$fg]['RESMSG']?><br>
                    <?if($response[$fg]['RESTUSR']!=''){?>
                        <small>ASSIGNED MEMBER : <?echo $assignmbr[0]['EMPCODE'].' - '.$assignmbr[0]['EMPNAME'].' - '.$assignmbr[0]['DESNAME'].' - '.$assignmbr[0]['ESENAME']?></small>
                      <?}?>

        					<div class="heading">
        						<div class="row">

        							<?if($response[$fg]['RESPATH']!=''){
											$filepath=explode('ftp://172.16.0.159/',$response[$fg]['RESPATH']);
											//echo($response[$fg]);
        							$file_list = ftp_nlist($ftp_conn_159,$filepath[1]);
        							//print_r($file_list);
        							echo("<br><ul>");
        							for($ij = 0; $ij < count($file_list); $ij++) {
        									$filename = 	$file_list[$ij];
        									$dataurl = $filepath[1];
        									$exp = explode(".", $filename);
                          $name = explode("/", $filename);
        									switch($exp[1])
        									{
        											case 'png':
        											case 'jpg':
        											case 'jpeg':
        											case 'pdf':
        															// $folder_path = "".$dataurl."/";
        															// $thumbfolder_path = "".$dataurl."/thumb_images/";
        															//
        															// echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?ftp=159&pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?ftp=159&pic=".$filename."&path="."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?ftp=159&pic=".$filename."&path="."\"></li>";
        															// break;
        																echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'>".$name[3]."</span></a></li>&nbsp");
        															//echo $fieldindi = "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a></ul>";
        															break;
        											case 'mp4':
        																echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'> ".$name[3]."</span></a></li>&nbsp");
        															//echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
        															break;
        											default:
        															//echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159.$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
        															//break;
        															break;
        									}
        							}echo("</ul>");}?>
        						</div>
        					</div>
        	</div><?
        }
      //print_r($response);
  }
?>
<?}?>
<div class="panel panel-default push-up-10">
		<div class="panel-body panel-body-search">
			<div class="form-group">
					<label class="col-md-2 control-label">Assign Member<span style='color:red'></span></label>
					<div class="col-md-10 col-xs-12">
							<b>
								<input type='text' class="form-control" tabindex='1' style="text-transform: uppercase;" required name='txt_assign' id='txt_assign' data-toggle="tooltip" onblur="" data-placement="top" data-original-title="Assign member" value=''>
							 <span class="help-block">NOTE : ASSIGN TCS MEMBER HERE</span>
					</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label" id='attachment'>Attachments</label>
				<div class="col-md-10 col-xs-12">
					<div><input type="file" placeholder="no files" tabindex='2' onblur="find_tags();" class="form-control fileselect" name='attachments[]' id='attachments' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="ATTACHMENTS"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
					<div id="attachments_detail">
					<a ></a>
					</div>
				<div class="tags_clear"></div>
				</div>
			</div>
				<div class="input-group">
					<textarea id="message" name="message" type="text" tabindex="3" class="form-control" style="height:75px; padding-right: 5px;" multiple placeholder="TCS Messages.."></textarea>
					<div class="input-group-btn"><button class="btn btn-success" tabindex="4" style="margin-left: 10px;" onclick="return sendmessage()">Send</button></div>
				</div>
		</div>
</div>
