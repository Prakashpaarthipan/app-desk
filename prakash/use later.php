<?  $open_search = select_query_json("SELECT SRR.RESTUSR,to_char(SRE.REQDATE,'dd/MM/yyyy HH:mi:ss AM') REQDATE, SRE.REQCONT,SRE.REQMAIL,SRE.REQDESKNO,SRE.REQALTCONT,TO_CHAR(SRE.REQNUMB) REQNUMB,SUP.SUPCODE,SUP.SUPNAME,SRE.REQSTAT,SRE.REQDESKNO
FROM SERVICE_REGISTER_ENTRY SRE, SUPPLIER SUP, SERVICE_REGISTER_RESPONSE SRR
WHERE SRR.REQNUMB(+)=SRE.REQNUMB AND SRR.REQSRNO(+)=SRE.REQSRNO AND SRR.RESSRNO(+)='1' AND SRE.REQUSRTYP='S' AND SUP.SUPCODE=SRE.REQUSER", "Centra", 'TEST');
                          //print_r($open_search);
                              $ki = 0;
                              for($k=0;$k<sizeof($open_search);$k++)
                              {?>     <?$employee = select_query_json("select eof.empname,eof.empcode from employee_office eof,service_register_response srr where srr.restusr=eof.empcode and srr.reqnumb='".$open_search[$k]['REQNUMB']."' and srr.reqsrno='1'", "Centra", 'TEST');
                              //print_r($employee);?>
                                      <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                      <td class="center" style='text-align:center;'>
                                        <? echo $k+1; // SERIAL NUMBER OF THE RECORD ?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for entryno-->
                                        <? echo $open_search[$k]['REQDATE'] ?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for top core-->
                                        <? echo $open_search[$k]['SUPCODE']; ?>-<? echo $open_search[$k]['SUPNAME']; ?><br>

                                      </td>
                                      <td class="center" style='text-align:left'><!-- for  editor details-->
                                        Contact :<?echo ' '.$open_search[$k]['REQCONT'];?><br>
                                        <?if($open_search[$k]['REQALTCONT']!='-'){echo 'Alternate Contact : '.$open_search[$k]['REQALTCONT'].'<br>';}?>
                                        Mail :<?echo ' '.$open_search[$k]['REQMAIL'];?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for attachment count-->
                                        <? echo $employee[0]['EMPCODE'].' - '.$employee[0]['EMPNAME']; ?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for attachment count-->
                                        <? echo $open_search[$k]['REQDESKNO']?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for USER DETAIL-->
                                        <?if($open_search[$k]['REQSTAT']=='A'){echo("ASSIGNED");}if($open_search[$k]['REQSTAT']=='N'){echo("NOT ASSIGNED");}
                                        if($open_search[$k]['REQSTAT']=='C'){echo("CLOSED");}?>
                                      </td>
                                      <td class="center" style='text-align:center'><!-- for USER DETAIL-->
                                        <a href="process_requirement_view.php?entryno=<?echo $sql_search[$k]['ENTRYNO'];?>&entryyr=<?echo $sql_search[$k]['ENTRYYR'];?>&entsrno=<?echo $sql_search[$k]['ENTSRNO'];?>" class="btn btn-warning btn-sm"><span class="fa fa-eye"></span></a>

                                      </td>
                                    </tr>
                                  <?
                                } ?>

                                style='padding:6px 12px !important'

                                 <form class="form-horizontal" role="form" id="frm_supplier_list" name="frm_supplier_list" action="" method="post" enctype="multipart/form-data">
                                    <? /* <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 0;'>
                                        <input type='text' class="form-control" tabindex='1' autofocus name='search_subject' id='search_subject' value='<?=$_REQUEST['search_subject']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Details' title="Details" style='text-transform: uppercase;'>
                                    </div> */ ?>

                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px 5px 0 5px;'>
                                        <input type='text' class="form-control" tabindex='1' name='search_sprno' id='search_sprno' value='<?=$_REQUEST['search_sprno']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='SUPPLIER NAME/MOBILE NO' title="SUPPLIER NAME/MOBILE NO" style='text-transform: uppercase;'>
                                    </div>

                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px;'>
                                        <input type='text' class="form-control" tabindex='2' name='search_value' id='search_value' value='<?=$_REQUEST['search_value']?>' data-toggle="tooltip" data-placement="top" maxlength="10" placeholder='REQUEST ID' title="REQUEST ID" style='text-transform: uppercase;'>
                                    </div>
                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px;'>
                                        <input type='text' class="form-control" tabindex='3' style="text-transform: uppercase;"  name='search_user' id='search_user' value='<?=$_REQUEST['search_user']?>' data-toggle="tooltip" data-placement="top" maxlength="10" placeholder='ASSIGNED USER' title="ASSIGNED USER" style='text-transform: uppercase;' value=''> 
                                       
                                    </div>
                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px;'>
                                        <select class="form-control" tabindex='4'  name='status_type' id='status_type' data-toggle="tooltip" data-placement="top" title="status type" >
                                                 <option value=""selected>CHOOSE THE MODE </option>
                                                 <option value="O" >OPEN</option>
                                                 <option value="C" >CLOSED</option>
                                            </select>
                                    </div>

                                    <div class="col-lg-2 col-sm-3" style='text-align:center; padding:5px 5px 0 6px;'>
                                        <input type='hidden' name='search_add_findate' id='search_add_findate' value='ADDDATE' >
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                    </div>

                                    <div class="col-lg-2 col-sm-2" style='text-align:center; padding:5px;'>
                                        <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
                                    </div>

                                    <div class="col-lg-2 col-sm-2 pull-right" style='text-align:right; padding:5px;'>
                                        <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
                                    </div>
                                </form>


     <script type="text/javascript" src="js/jquery.js"></script>

    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- END PLUGINS -->

    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
    
    <!-- START THIS PAGE PLUGINS-->
    
     <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
 <script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>

    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <!-- Select2 -->
    <script src="../dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>


       <? $and ="";
                            if($search_sprno != '') {
                                        $and .= " SUPNAME like '%".strtoupper($search_sprno)."%' and SUPCODE like  '%".strtoupper($search_sprno)."%' and SUBMOBI like '%".strtoupper($search_sprno)."%' ";
                                    }
                                    /*
                            if($search_value!=''){
                                $and .= " And REQNUMB like '%".strtoupper($search_value)."%' ";
                            }
                            if($search_user!=''){
                                 $and .= " And RESTUSR like '%".strtoupper($search_user)."%' ";
                            }
                            if($status_type!=''){
                                 $and .= " And REQSTAT like '%".strtoupper($search_user)."%' ";
                            }
                            if($search_fromdate != '' or $search_todate != '') {
                                        if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                        $exp1 = explode("-", $search_fromdate);
                                        $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                        if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                        $exp2 = explode("-", $search_todate);
                                        $to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));

                                        if($search_add_findate == 'ADDDATE') {
                                            $and .= " And trunc(rq.REQDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                        } elseif($search_add_findate == 'FINDATE') {
                                            $and .= " And trunc(ar.FINDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                        }
                                    }
                            if($and != '') {
                                        
                                        $sql_brn = select_query_json("select BRNCODE, esecode from employee_office where empsrno = '".$_SESSION['tcs_userid']."'", "Centra", 'TCS');
                                        if($sql_brn[0]['BRNCODE'] == 888 or $sql_brn[0]['BRNCODE'] == 100) {
                                            // $rqusr = " and ar.REQESEC=".$sql_brn[0]['ESECODE']." ";
                                            $rqusr = " and ar.REQESEC=".$_SESSION['tcs_esecode']." ";
                                        }

                                        $sql_search = select_query_json("select ", "Centra", 'TCS');
                                    } else {
                                       
                                        $sql_search = select_query_json("select ", "Centra", 'TCS');
                                    }*/
                         $sql_search = select_query_json("SELECT SRR.RESTUSR, SRE.REQCONT,SRE.REQMAIL,SRE.REQDESKNO,SRE.REQALTCONT,TO_CHAR(SRE.REQNUMB) REQNUMB,SUP.SUPCODE,SUP.SUPNAME,SRE.REQSTAT,SRE.REQDESKNO
                                                                FROM service_request SRE, SUPPLIER SUP, service_response SRR
                                                                WHERE '".$and."'", "Centra", 'TEST');


                            ?>
                            <tr><?print_r($sql_search);?></tr>

                                
