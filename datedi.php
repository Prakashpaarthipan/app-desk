
                                                <div class="form-group trbg" style='min-height:90px; display:none'>
                                                    <div class="col-lg-3 col-xs-3">
                                                        <label style='height:27px;'>Process Duration <span style='color:red'>*</span></label>
                                                    </div>
                                                    <div class="col-lg-9 col-xs-9">

                                                    <div>
                                                    <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo "<b>From Date</b> : ".$sql_reqid[0]['APPRSFR_TIME'];
                                                           } else { ?>
                                                                <div class='input-group date' id='datetimepicker9' tabindex='14' data-date='<? echo date("Y-m-d"); ?>' data-date-format="dd MM yyyy - HH:ii:ss p" data-link-field="dtp_input1">
                                                                    <input type='text' class="form-control" size="20" tabindex='15' name='txtfrom_date' required placeholder='From Date' id='txtfrom_date' <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSFR_TIME']?>"<? } else { ?>value="<?=strtoupper(date("d-M-Y h:i:s A"))?>"<? } ?> readonly data-toggle="tooltip" data-placement="top" title="From Date" />
                                                                    <input type='hidden' class="form-control" size="20" tabindex='16' name='txtfrom_date1' required placeholder='From Date' id='txtfrom_date1' <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSFR_TIME']?>"<? } else { ?>value="<?=date("m-d-Y")?>"<? } ?> readonly data-toggle="tooltip" data-placement="top" title="From Date" />
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" id="dtp_input1" name='dtp_input1' value="" />
                                                        <? } ?>
                                                    </div>

                                                    <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo "<b>To Date</b> : ".$sql_reqid[0]['APPRSTO_TIME'];
                                                           } else { ?>
                                                                <div class='input-group date' id='datetimepicker10' tabindex='17' data-date='<? echo date("Y-m-d"); ?>' data-date-format="dd MM yyyy - HH:ii:ss p" data-link-field="dtp_input2" onblur="call_days()">
                                                                    <input type='text' class="form-control" size="20" tabindex='17' name='txtto_date' required placeholder='To Date' id='txtto_date' onblur="call_days()" type="text" <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSTO_TIME']?>"<? } else { ?>value="<?=strtoupper(date("d-M-Y h:i:s A"))?>"<? } ?> onblur="call_days()" readonly data-toggle="tooltip" data-placement="top" title="To Date" />
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" id="dtp_input2" name='dtp_input2' value="" />
                                                        <? } ?>
                                                    </div>
                                                    </div>
                                                    <? if($_REQUEST['action'] != 'view') { ?><div class='clear' style='padding-top:10px;'></div><? } else { ?><div class='clear'></div><? } ?>

                                                        <div>
                                                        <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') { ?>
                                                            <b>No of Hours</b> : <?=$sql_reqid[0]['APRHURS']?>
                                                        <? } else { ?>
                                                            <div class="input-group margin" title="No of Hours">
                                                                <div class="input-group-btn">
                                                                    <button type="button" class="btn btn-warning">No of Hours</button>
                                                                </div><!-- /btn-group -->
                                                                <input class="form-control" placeholder="No of Hours" onKeyPress="return isNumber(event)" tabindex='18' maxlength='5' required name='txtnoofhours' id='txtnoofhours' readonly onfocus="date_diff()" <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRHURS']?>'<? } else { ?>value='24'<? } ?> data-toggle="tooltip" data-placement="top" title="No of Hours">
                                                            </div>
                                                        <? } ?>
                                                        </div>

                                                        <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') { ?>
                                                            <b>No of Days</b> : <?=$sql_reqid[0]['APRDAYS']?>
                                                        <? } else { ?>
                                                            <div class="input-group margin" title="No of Days">
                                                                <div class="input-group-btn">
                                                                    <button type="button" class="btn btn-warning">No of Days</button>
                                                                </div><!-- /btn-group -->
                                                                <input class="form-control" placeholder="No of Days" onKeyPress="return isNumber(event)" maxlength='3' tabindex='19' required name='txtnoofdays' id='txtnoofdays' readonly <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRDAYS']?>'<? } else { ?>value='1'<? } ?> data-toggle="tooltip" data-placement="top" title="No of Days">
                                                            </div>
                                                        <? } ?>
                                                        </div>
                                                        </div>
                                                        <div class="tags_clear"></div>

                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Process Duration -->








function date_diff()
    {
        var date1 = document.getElementById('txtfrom_date').value;
        var date2 = document.getElementById('txtto_date').value;
        //alert(date1+"HI"+date2);

        var datefrom = date1.split(' ');
        var dateto = date2.split(' ');
        //alert(datefrom[0]+"!!!!!!"+dateto[0]); //alert(parseDate(datefrom[0]));

        Date.prototype.days=function(to){
            return  Math.abs(Math.floor( to.getTime() / (3600*24*1000)) -  Math.floor( this.getTime() / (3600*24*1000)))
        }
        var ga = new Date(parseDate(datefrom[0])).days(new Date(parseDate(dateto[0]))) // 3 days
        var cntdate = +ga + 1;
        //var cntdate = ga;
        document.getElementById('txtnoofdays').value = cntdate;
        document.getElementById('txtnoofhours').value = cntdate * 24;
    }

    function parseDate(str) {
        var mdy = str.split('-')
        //alert(mdy[2]+"~~"+mdy[0]+"~~"+mdy[1]);
        return mdy[1]+"-"+mdy[0]+"-"+mdy[2];
        //return new Date(mdy[1], mdy[0], "20"+mdy[2]);
    }

	
	
	<input type="text" id="startdate">
<input type="text" id="enddate">
<input type="text" id="days">

<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
<script src="https://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
<script>
$(document).ready(function() {

$( "#startdate,#enddate" ).datepicker({
changeMonth: true,
changeYear: true,
firstDay: 1,
dateFormat: 'dd/mm/yy',
})

$( "#startdate" ).datepicker({ dateFormat: 'dd-mm-yy' });
$( "#enddate" ).datepicker({ dateFormat: 'dd-mm-yy' });

$('#enddate').change(function() {
var start = $('#startdate').datepicker('getDate');
var end   = $('#enddate').datepicker('getDate');

if (start<end) {
var days   = (end - start)/1000/60/60/24;
$('#days').val(days);
}
else {
alert ("You cant come back before you have been!");
$('#startdate').val("");
$('#enddate').val("");
$('#days').val("");
}
}); //end change function
}); //end ready
</script>
