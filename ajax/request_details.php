<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
$ftp_conn_159 = ftp_connect($ftp_server_159);//  or die("Could not connect to $ftp_server");
$login_159 = ftp_login($ftp_conn_159, $ftp_user_name_159, $ftp_user_pass_159);

//$supplier = select_query_json("select sup.supcode,sup.supname from service_request rre,supplier sup where rre.reqnumb='".$_REQUEST['reqnumb']."' and sup.supcode = rre.requser","Centra","TCS");
$reqsrno = select_query_json("select COUNT(REQSRNO) MAXREQSRNO from service_request where reqnumb='".$_REQUEST['reqnumb']."'","Centra","TCS");
$MAXREQSRNO=$reqsrno[0]['MAXREQSRNO'];
//echo($MAXREQSRNO);
?>
<input type="hidden" name='reqnumb' id='reqnumb' val='<?=$_REQUEST['reqnumb']?>'/>
<?if($choice == 1) { ?>
  <div style="text-align:center; font-size:16px; color:#FF0000; font-weight:bold;">Request ID : <?=$_REQUEST['reqnumb']?></div>
<?
}

$hl=0;
for($l=1;$l<=$MAXREQSRNO;$l++)
{ 
  $supplier = select_query_json("select sup.supcode,sup.supname,C.CTYNAME,C.CTYCODE,SUP.SUPPHN1, com.comcode, com.comname, usr.usrname, usr.usrcode, rre.REQUSRTYP 
                                        from service_request rre, supplier sup, CITY C, APP_COMPLAINT_MASTER com, userid usr 
                                        where C.CTYCODE=SUP.CTYCODE and com.COMCODE = rre.REQMODE and rre.ADDUSER = usr.usrcode and com.deleted='N' AND rre.reqnumb='".$_REQUEST['reqnumb']."' 
                                            and rre.reqsrno='".$l."' and sup.supcode = rre.requser", "Centra", "TCS");
  $request = select_query_json("select SRE.*,to_char(SRE.REQDATE,'dd/MM/yyyy HH:mi:ss AM') REQDATE1 from service_request SRE where reqnumb='".$_REQUEST['reqnumb']."' and reqsrno='".$l."'", "Centra", "TCS");
  
 ///////////////2
  $suppsec1 = select_query_json("SELECT DISTINCT replace(SECLIST,'-',''),COUNT(*) FROM SUPPLIER WHERE SUPCODE='".$supplier[0]['SUPCODE']."' AND SECLIST IS NOT NULL GROUP BY SECLIST","Centra","TCS");
  //print_r($suppsec1);
  $grp='';
  if($suppsec1[0]['SECLIST']=='')
  {
     $suppsec1 = select_query_json("SELECT listagg(G.SECGRNO, ',') within group (order by G.SECGRNO) as SECLIST 
                      FROM TRANDATA.REORDER_CONTENT_SUPPLIER@TCSCENTR R,TRANDATA.PRODUCT@TCSCENTR P,TRANDATA.SECTION@TCSCENTR S,TRANDATA.SECTION_GROUP_REPORT@TCSCENTR G 
                      WHERE R.PRDCODE=P.PRDCODE AND P.SECCODE=S.SECCODE AND S.SECCODE=G.SECCODE AND R.MINQNTY>0 AND R.SUPCODE='".$supplier[0]['SUPCODE']."'", "Centra", "TCS");
  }
  $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT WHERE SECGRNO IN(".$suppsec1[0]['SECLIST'].")","Centra","TCS");


  if($suppsec1[0]['SECLIST']=='') 
  {
     $suppsec1 = select_query_json("SELECT DISTINCT SECCODE,COUNT(*) FROM SUPPLIER WHERE SUPCODE='".$supplier[0]['SUPCODE']."' AND NVL(SECCODE,0)>0 GROUP BY SECCODE","Centra","TCS");
     $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT WHERE SECCODE IN('".$suppsec1[0]['SECCODE']."')","Centra","TCS");
  }

  if(count($suppsec2) == 0) {
    $suppsec2 = select_query_json("SELECT DISTINCT GRPSRNO,SECGRNO,SECNAME FROM SECTION_GROUP_REPORT","Centra","TCS");
  }
  
  for($i=0;$i<sizeof($suppsec2);$i++)
    {
        $grp=$grp.$suppsec2[$i]['SECNAME']." , ";
    }
    
  ///////////////2
  
  
  //echo("select * from service_request where reqnumb='".$_REQUEST['reqnumb']."' and reqsrno='".$l."'");
  //print_r($supplier);
  //print_r($request);
  $response=select_query_json("SELECT SRR.*,to_char(SRR.RESDATE,'dd/MM/yyyy HH:mi:ss AM') resdate1 FROM SERVICE_RESPONSE SRR,SERVICE_REQUEST SRE WHERE SRE.REQNUMB=SRR.REQNUMB AND SRE.REQSRNO=SRR.REQSRNO AND SRE.REQNUMB='".$_REQUEST['reqnumb']."' AND SRE.REQSRNO='".$l."' ORDER BY REQDATE","Centra","TCS");
  //echo("SELECT SRR.*,to_char(SRR.RESDATE,'dd/MM/yyyy HH:mi:ss Am') resdate1 FROM SERVICE_RESPONSE SRR,SERVICE_REQUEST SRE WHERE SRE.REQNUMB=SRR.REQNUMB AND SRE.REQSRNO=SRR.REQSRNO AND SRE.REQNUMB='".$_REQUEST['reqnumb']."' AND SRE.REQSRNO='".$l."' ORDER BY REQDATE");
  //echo("-------");
  //print_r($response);
  //echo("-------");
  ?>
  <div class="item item-visible">
      <div class="image">
          <img src="images/customers.png" style="width: 44px; height: 44px;" alt="<?echo $supplier[0]['SUPNAME']?>">
      </div>
      <div class="text cls_left">
          <div class="heading">
              <a href="#"><?echo $supplier[0]['SUPNAME']?>&nbsp(&nbsp<?echo $supplier[0]['CTYNAME']?>,&nbsp<?echo $supplier[0]['SUPPHN1']?>)</a>&nbsp;&nbsp;&nbsp;<span><small class="label label-warning">Req. Type : <? echo $supplier[0]['COMNAME']; ?></small></span>&nbsp;&nbsp;&nbsp;<span><small class="label label-danger">Req. By : <? if($supplier[0]['REQUSRTYP'] == 'S') { echo "Supplier"; } else { echo $supplier[0]['USRCODE']." - ".$supplier[0]['USRNAME']; } ?></small></span>
              <span class="date"><?echo $request[0]['REQDATE1']?></span>
         <? if($grp!=''){echo("<br><strong>Section Group : </strong>".$grp);} ?>
          </div>
          <?echo $request[0]['REQMSG']?>
          <div class="row">
            <?
            if($request[0]['REQPATH'] != '' and $request[0]['REQPATH'] != '-')
            {
              $filepath=explode('ftp://172.16.0.159/', $request[0]['REQPATH']);
              if($filepath[1]=='')
              {
                $filepath=explode('ftp://ftp1.thechennaisilks.com/', $request[0]['REQPATH']);
              }
              echo($request[0]['REQPATH']);
              echo("<pre>");
              print_r($filepath);
              echo("</pre>");

              $file_list = ftp_nlist($ftp_conn_159, $filepath[1]);
              echo("<br><ul class='list-unstyled'>");
              if(count($file_list) > 0) {
                for($ij = 0; $ij < count($file_list); $ij++)
                {
                    $filename =   $file_list[$ij];
                    $dataurl = $filepath[1];
                    $exp = explode(".", $filename);
                    $name = explode("/", $filename);
                    //print_r($name);
                    switch($exp[1])
                    {
                        case 'png':
                        case 'jpg':
                        case 'jpeg':
                                echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'><i class='fa fa-picture-o'></i> ".$name[3]."</span></a></li>&nbsp");
                                break;
                        case 'pdf':
                                echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'><i class='fa fa-bars'></i> ".$name[3]."</span></a></li>&nbsp");
                                break;
                        case 'mp3':
                                echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'><i class='fa fa-bullhorn'></i> ".$name[3]."</span></a></li>&nbsp");
                              break;
                        case 'mp4':
                                echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'><i class='fa fa-toggle-right'></i> ".$name[3]."</span></a></li>&nbsp");
                              break;
                        default:
                              echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'><i class='fa fa-file'></i> ".$name[3]."</span></a></li>&nbsp");
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
  {     //echo("****".$l."****");
        for($fg=0;$fg<sizeof($response);$fg++)
        { //print_r($response[$fg]);
          $hl++;
          $employee = select_query_json("select eof.empcode,eof.empname from service_response rre,employee_office eof where rre.reqnumb='".$_REQUEST['reqnumb']."' and rre.reqsrno='".$l."' and rre.ressrno='".$hl."' and eof.empsrno = rre.resfusr","Centra","TCS");
          $assignmbr = select_query_json("select eof.empname,eof.empcode,deg.desname,ese.esename from employee_office eof,designation deg,empsection ese,service_response srr where eof.empsrno=srr.restusr and srr.reqnumb='".$_REQUEST['reqnumb']."' and srr.reqsrno='".$l."' and srr.ressrno='".$hl."' and deg.descode=eof.descode and ese.esecode=eof.esecode","Centra","TCS");
          //print_r("select eof.empname,eof.empcode,deg.desname,ese.esename from employee_office eof,designation deg,empsection ese,service_response srr where eof.empcode=srr.restusr and srr.reqnumb='".$_REQUEST['reqnumb']."' and srr.reqsrno='".$l."' and srr.ressrno='".$hl."' and deg.descode=eof.descode and ese.esecode=eof.esecode");
          //echo("select eof.empcode,eof.empname from service_response rre,employee_office eof where rre.reqnumb='".$_REQUEST['reqnumb']."' and rre.reqsrno='".$l."' and rre.ressrno='".($fg+1)."' and eof.empcode = rre.resfusr");
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
                      echo($response[$fg]);
                      $file_list = ftp_nlist($ftp_conn_159,$filepath[1]);
                      //print_r($file_list);
                      echo("<br><ul>");
                      for($ij = 0; $ij < count($file_list); $ij++) {
                          $filename =   $file_list[$ij];
                          $dataurl = $filepath[1];
                          $exp = explode(".", $filename);
                          $name = explode("/", $filename);
                          switch($exp[1])
                          {
                              case 'png':
                              case 'jpg':
                              case 'jpeg':
                              case 'pdf':
                                        echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'>".$name[3]."</span></a></li>&nbsp");
                                      break;
                              case 'mp4':
                                        echo ("<li style='display:inline-block'><a href=\"ftp://".$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159."/".$filename."\" target=\"_blank\" ><span class='badge badge-info'> ".$name[3]."</span></a></li>&nbsp");
                                      break;
                              default:
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
<?}

if($_REQUEST['choice'] != 1) { ?>
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
          <textarea maxlength='250' id="message" name="message" type="text" tabindex="3" class="form-control" style="height:150px; padding-right: 5px;" multiple placeholder="TCS Messages..">
DEAR SIR / MADAM,



THANKS AND REGARDS,
THE CHENNAI SILKS - CORP</textarea>
          <div class="input-group-btn"><button class="btn btn-success" tabindex="4" style="margin-left: 10px;" onclick="return sendmessage()">Send</button></div>
        </div>
    </div>
</div>
<? } ?>