<span data-mce-type="bookmark" style="display: inline-block; width: 0px; overflow: hidden; line-height: 0;" class="mce_SELRES_start">﻿</span><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><span data-mce-type="bookmark" style="display: inline-block; width: 0px; overflow: hidden; line-height: 0;" class="mce_SELRES_end">﻿</span> 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
 <style>

 .tab-content {
        border-left: 2px solid #ddd;
        border-right: 2px solid #ddd;
        padding: 10px;
    } 
    .panel .nav-tabs > .active > a{
        background-color: #034f84 !important;
        color:#fff !important;
    }
    
    .nav-tabs, .nav-tabs.nav {
        padding: 0px 0px;
        padding-top: 10px;
    }
    .nav-tabs > li > a {
    background: #c3d7e4 !important;
    line-height: 1.2 !important;
    font-size: 10px !important;
}
@media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}

</style>

<div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Employee Notice List</strong></h3> 
                       
                    </div>
                     

                        <div class="col-md-12">                        
                        
                            <div class="panel panel-default tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab9" data-toggle="tab" aria-expanded="false"><b>YOUR NOTICE</b></a></li>
                                    <li class=""><a href="#tab8" data-toggle="tab" aria-expanded="false"><b>ASSIGNED STATUS</b></a></li>
                                    
                                    <div class="panel-body" style="padding-top: 10PX; padding-bottom: 10PX;  border-top: 2px solid #ddd;border-left: 2px solid #ddd;border-right: 2px solid #ddd;">
                                        <div class="row" style="padding-top:10px">
                                            <div class="col-md-12">
                                                <div class="col-md-3">
                                               <label id='remarks' style="float:right">AUTHORIZED BY</label>
</div>
                                                       
                                                    <div class="col-md-3 col-xs-12">
                                                         <select class="form-control custom-select chosn" autofocus tabindex='4' required name='auth_by' id='auth_by' onChange="" data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
                                                      
                                                        <option value='20118'>MD</option>
                                                        <option value='43400'>PS MADAM</option>
                                                        <option value='21344'>SK SIR</option>
                                                        <option value='452'>ADMIN GM</option>
                                                    </select>
                                                    
                                                    </div>
                                                    <div class="col-md-6 col-xs-12">
                                                        <div class="checkbox">
                                                            <label><input type="checkbox" value="1" tabindex='5' id="all" name="all"/> All</label>
                                                        </div>
                                                    </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                </ul>

                       

                                <div class="panel-body tab-content">
                                    <div class="tab-pane" id="tab8">
                                       <div class="panel-body table" style="overflow-x:scroll">

                                         <table id="table1"  class="table table-striped table-hover" style="max-width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'><strong>S.No</strong></th>
                                                    <th class="center" style='text-align:center;width: 100px;'><strong>NOTICE NO.</strong></th>
                                                    <th class="center" style='text-align:center'><strong>EMPLOYEE</strong></th>
                                                    <th class="center" style='text-align:center'><strong>REMARKS</strong></th>
                                                    <th class="center" style='text-align:center'><strong>NOTICE</strong></th>
                                                    <th class="center" style='text-align:center'><strong>AUTH. BY</strong></th>
                                                    <th class="center" style='text-align:center'><strong>REPLY</strong></th>
                                                    <th class="center" style='text-align:center'><strong>REPLY DATE</strong></th>
                                                    <th class="center" style='text-align:center'><strong>ACTION</strong></th>
                                                    <th class="center" style='text-align:center'><strong>PRINT</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            [insert_php]  $sql_search = select_query_json_test("Select UI.USRCODE,to_char(emnt.edtdate,'dd/MM/yyyy HH:mi:ss AM') edtdate1,(select empname from employee_office where empsrno=emnt.autsrno) assignuser, emnt.*, emp.empname,emp.empcode From employee_notice_detail emnt, employee_office emp,USERID UI where emnt.EMPSRNO = emp.empsrno AND UI.USRCODE=EMNT.ADDUSER AND EMNT.ADDUSER='2280588' ORDER BY NOTNUMB DESC", "Centra", 'TEST');
 $ki = 0;
for($k=0;$k<count($sql_search );$k++){$ki++;
if($sql_search[$k]['EMP_STATUS']=='Y'){ 
       if($sql_search[$k]['NOTSTAT']!=''){
           if($sql_search[$k]['NOTSTAT']=='A'){
              $pri='A';
           }
          if($sql_search[$k]['NOTSTAT']=='R'){   
             $pri='R';
           }}else{
             $pri='NOT';
            }}
$num=explode('-',$sql_search[$k]['NOTNAME']);
															$num=intval($num[1]);
																if($num>10){  $bg_clr_class='label-danger';}
																if($num>5 && $num<=10){  $bg_clr_class='label-warning'; }
																if($num>0 && $num<=5){  $bg_clr_class='label-success'; }
echo "<tr><td class='center' style='text-align:center'>".$ki."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['NOTYEAR']."-".$sql_search[$k]['NOTNUMB']."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['EMPCODE']." - ".$sql_search[$k]['EMPNAME']."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['REMARKS']."</td>          
          <td class='center' style='text-align:center'><div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label 
                    ".$bg_clr_class." label-form'><b>".$sql_search[$k]['NOTNAME']."</b></span></div></td> 
          <td class='center' style='text-align:center'>".$sql_search[$k]['ASSIGNUSER']."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['EMP_REMARKS']."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['EDTDATE1']."</td>
          <td class='center' style='text-align:center'>".($pri == 'A' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-success label-form'><b>APPROVED</b></span></div>" : "").($pri=='R' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-success label-form'><b>DENIED</b></span></div>" : "").($pri=='NOT' ? "<span><button class='btn btn-sm btn-success'><i class='fa fa-check'></i></button></span><span><button class='btn btn-sm btn-danger'><i class='fa fa-close'></i></button></span>" : "")."</td><td><button onclick='printpage(".'"'.$sql_search[$k]['NOTYEAR'].'"'.",".'"'.$sql_search[$k]['NOTNUMB'].'"'.")' class='btn btn-default'><i class='fa fa-print'></i></button></td></tr>";

}
 [/insert_php]

                                            </tbody>
                                        </table>
                                     </div>
                                    </div> 

                          <div class="tab-pane active" id="tab9">
                                     
                                            <div class="panel-body">

                                        <table id="table2"  class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'><strong>S.No</strong></th>
                                                    <th class="center" style='text-align:center;width: 100px;'><strong>NOTICE NO.</strong></th>
                                                    <th class="center" style='text-align:center'><strong>EMPLOYEE</strong></th>
                                                    <th class="center" style='text-align:center'><strong>REMARKS</strong></th>
                                                    <th class="center" style='text-align:center'><strong>NOTICE</strong></th>
                                                    <th class="center" style='text-align:center'><strong>AUTHOURIZED BY</strong></th>
													<th class="center" style='text-align:center'><strong>REPLY</strong></th>
													<th class="center" style='text-align:center'><strong>REPLY DATE</strong></th>
                                                    <th class="center" style='text-align:center'><strong>PRINT</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            [insert_php] $sql_search = select_query_json_test("Select (select empname from employee_office where empsrno=emnt.autsrno) assignuser,to_char(emnt.edtdate,'dd/MM/yyyy HH:mi:ss AM') edtdate1, emnt.*, emp.empname,emp.empcode From employee_notice_detail emnt, employee_office emp where emnt.EMPSRNO = emp.empsrno and emnt.empsrno='94095' order by notnumb desc", "Centra", 'TEST');  
					$ki = 0;
for($k=0;$k<count($sql_search );$k++){$ki++;
if($sql_search[$k]['EMP_STATUS']=='Y'){ 
       if($sql_search[$k]['NOTSTAT']!=''){
           if($sql_search[$k]['NOTSTAT']=='A'){
              $pri='A';
           }
          if($sql_search[$k]['NOTSTAT']=='R'){   
             $pri='R';
           }}else{
             $pri='NOT';
            }}
$num=explode('-',$sql_search[$k]['NOTNAME']);
															$num=intval($num[1]);
																if($num>10){  $bg_clr_class='label-danger';}
																if($num>5 && $num<=10){  $bg_clr_class='label-warning'; }
																if($num>0 && $num<=5){  $bg_clr_class='label-success'; }
echo "<tr><td class='center' style='text-align:center'>".$ki."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['NOTYEAR']."-".$sql_search[$k]['NOTNUMB']."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['EMPCODE']." - ".$sql_search[$k]['EMPNAME']."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['REMARKS']."</td>          
          <td class='center' style='text-align:center'><div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label ".$bg_clr_class." label-form'><b>".$sql_search[$k]['NOTNAME']."</b></span></div></td> 
          <td class='center' style='text-align:center'>".$sql_search[$k]['ASSIGNUSER']."</td>
          <td class='center' style='text-align:center'>".$sql_search[$k]['EMP_REMARKS']." ".($pri == 'A' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-success label-form'><b>APPROVED</b></span></div>" : "").($pri=='R' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-danger label-form'><b>DENIED</b></span></div>" : "").($pri=='NOT' ? "<div style='text-align:center;line-height: 35px; padding-left: 10px;'><span class='label label-warning label-form'><b>WAITING FOR REPLY</b></span></div>" : "")."</td>          
          <td class='center' style='text-align:center'>".$sql_search[$k]['EDTDATE1']."</td>
<td class='center' style='text-align:center'><button onclick='printpage(".'"'.$sql_search[$k]['NOTYEAR'].'"'.",".'"'.$sql_search[$k]['NOTNUMB'].'"'.")' class='btn btn-default'><i class='fa fa-print'></i></button></td></tr>";

}
 [/insert_php]

                                            </tbody>
                                        </table>
                                     </div>
                                  
                                    </div>                                                                    
                                </div>
                            </div>                                         
                         
                        </div>
                        
       <div id="printdiv" style="display:none"></div>               