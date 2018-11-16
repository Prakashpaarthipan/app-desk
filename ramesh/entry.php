<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data" autocomplete="on">
           
                    <div class="page-content-wrap">

                        <div class="row">
                            <div class="col-md-12">

                                <form class="form-horizontal">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><strong>Employee Notice Entry</strong></h3>
                                       
                                    </div>
[insert_php] 
$sql_project = select_query_json("select usrcode,useracc,allbran,brncode,MULTIBRN from tcs_centra_attn where  usrcode='1444288'", "Centra", 'TCS');
$sql_project[0]["ID"]="1";
if($sql_project[0]['ALLBRAN']=='N')
                                                                {
$sql_brn = select_query_json("Select Brncode,Substr(Nicname,3,10) Brnname From Branch Where brncode='".$sql_project[0]['BRNCODE']."' ORDER BY BRNCODE", "Centra", 'TCS');                          
                                                                }
                                                                else
                                                                { $sql_brn = select_query_json("Select Brncode,Substr(Nicname,3,10) Brnname From Branch Where brncode IN (".$sql_project[0]['MULTIBRN'].") ORDER BY BRNCODE", "Centra", 'TCS');
                                                               
                                                                
                                                                }


[/insert_php]

                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                
                                              
                                               <div class="form-group">
                                                    <div class="col-md-3 control-label"><label>BRANCH <span style='color:red'>*</span></label></div>
                                                    <div class="col-md-9">


      <select class="form-control custom-select chosn" autofocus tabindex='1' required name='branch' id='branch' onChange="clearme();" data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >

[insert_php] for($i = 0; $i < count($sql_brn); $i++) { echo "<option value=".$sql_brn[$i][BRNCODE].">".$sql_brn[$i][BRNNAME]."</option>";}[/insert_php]

                                                    </select>

                                                    </div>
                                                </div>
                                                <div style="clear: both;"></div>
                                               [insert_php]$result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY from employee_office emp, empsection sec, designation des, employee_salary sal where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno union 		select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 										from employee_office emp, new_empsection sec, new_designation des, employee_salary sal where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and										sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno order by EMPCODE Asc", "Centra", 'TCS');
//print_r($result);

[/insert_php]

                                                <div class="form-group">
                                                 <label class="col-md-3 control-label">EMPLOYEE <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">

<input type='text' class="form-control" tabindex='2' required style="text-transform: uppercase;" name='employee' id='employee' data-toggle="tooltip" onblur="" onchange="" data-placement="top" data-original-title="Assign member" value=''>
<div id='result' style='max-height:400px;overflow-y:scroll;'></div>


                                                    
                                                      
                                                    </div>
                                                </div>
                                              
                                                <div style="clear: both;"></div>
                                                
                                                <div class="form-group">
                                                        <label class="col-md-3 control-label" id='remarks'>COMMENTS <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <div class="">
                                                            <textarea required id="message" maxlength=250 name="message" type="text" tabindex="3" class="form-control" style="text-transform:uppercase; height:75px; padding-right: 5px;" multiple placeholder="YOUR COMMENTS.." required></textarea>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div style="clear: both;"></div>


                                               <div class="form-group">
                                                        <label class="col-md-3 control-label" id='remarks'>AUTHORIZED BY</label>
                                                    <div class="col-md-6 col-xs-12">
                                                         <select class="form-control custom-select chosn" autofocus tabindex='4' required name='auth_by' id='auth_by' onChange="" data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
                                                      
                                                        <option value='20118'>MD</option>
                                                        <option value='43400'>PS MADAM</option>
                                                        <option value='21344'>SK SIR</option>
                                                        <option value='452'>ADMIN GM</option>
                                                    </select>
                                                    
                                                    </div>
                                                     <div class="col-md-3 col-xs-12">
                                                             <div class="checkbox">
                                                              <label><input type="checkbox" value="1" tabindex='5' id="all" name="all"/> All</label>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                
                                            </div>



                                            <div class="col-md-6">
                                               

                                                <div class="form-group">
                                                    <div class="col-md-3 control-label"><label id='attachment'>NOTICE</label></div>
                                                    <div class="col-md-9 col-xs-12">
                                                        <div class="">
                                                            <input type="text" disabled class="form-control" id="notice" tabindex='6' name="notice" style="text-transform: uppercase;" maxlength="100" required />
                                                        </div>
                                                    </div>
                                                    <div style="clear: both;"></div>

                                                    <div class="col-md-3 control-label"></div>
                                                    <div class="col-md-9 col-xs-12">
                                                        
                                                       <div id="my-canvas"></div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                </div>


                                               
                                                
                                        </div>
                                
                                        <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                              
                                        </div>
                                        

                                         
                                                            
                                    
                                        
                                    
                                    <div class="tags_clear"></div>
                                </div>
                                

                                <div class="panel-footer">

                                        <center><button class="btn btn-success" onclick="nsubmit();" tabindex='7' type="button">Submit</button></center>
                                    </div>
                           
                                <div class="tags_clear"></div>
                                </form>

