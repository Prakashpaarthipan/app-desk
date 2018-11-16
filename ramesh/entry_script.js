<script>

$(document).ready(function(){
alert("hai")

});
function setvalue(val){
jQuery('#employee').val(val);
jQuery('#result').hide();
getprofile_img();
}
 function clearme()
   {
 alert("hai");
 jQuery('#employee').val('ram');
	   jQuery('#profile_img').html('');
	   jQuery('#notice').val('');
   }

function getprofile_img(){
	alert("hai");	
		var branch= jQuery('#branch').val();
                var ececode=jQuery('#employee').val();
                var ececode1=ececode.split(' - ');
                 console.log(ececode1[0]);console.log(ececode1[1]);
                 var empcode=ececode1[0];
var empname=ececode1[1];
if (typeof ececode1[1]  !== "undefined")
		{
           var branch=jQuery('#branch').val();

	jQuery.post(ajax_url,{action:'check_photo',branch:branch,profile_img:empcode,empname:empname,action2:'userprofileimg'}, function(result){

var src = "data:image/jpeg;base64,";
src += result;
var newImage = document.createElement('img');
newImage.src = src;
newImage.height = "150";
newImage.width = "150";
document.querySelector('#my-canvas').innerHTML = newImage.outerHTML;
jQuery.post(ajax_url,{action:'check_report',empsrno:empcode,action3:'alerttext'}, function(result){
jQuery('#notice').val(result);
});

});
 }        	 
}

jQuery(document).ready(function() {
   
var branch=jQuery('#branch').val();
    jQuery('#employee').autocomplete({
        source: function( request, response ) {

 jQuery.post(ajax_url, {action: 'check_user',name_startsWith:request.term,branch:branch,type:'branch_employee'}, function(result){

     var datasplit=result.split('[');
    var split2=datasplit[1].split(',');

var htmlText='';
for(var i=0;i<split2.length;i++){
var splitfinal=split2[i].replace('"','');
splitfinal=splitfinal.replace('"','');
if(i!=(split2.length-1))
htmlText+="<p><a value="+JSON.stringify(splitfinal)+"  onclick='setvalue("+JSON.stringify(splitfinal)+");' style='text-decoration:none;color:#000;cursor:pointer'>"+splitfinal+"</a></p>";
else{
var split2last=split2[i].replace(']','');
var split2last1=split2last[0].replace('"','');
htmlText+="<p><a  value="+JSON.stringify(split2last1)+ " onclick='setvalue("+JSON.stringify(split2last1)+");' style='text-decoration:none;color:#000;cursor:pointer'>"+split2last1+"</a></p>";
}

}
jQuery('#result').html(htmlText);
jQuery('#result').show();

    });
}
            });

    });


function nsubmit(){
        
        var msg=jQuery("#message").val();
        var emp=jQuery("#employee").val();
var branch=jQuery("#branch").val();
var message=jQuery("#message").val();
var employee=jQuery("#employee").val();
var auth_by=jQuery("#auth_by").val();
var all=jQuery("#all").val();
var action5='insert';
        if(msg.trim()!='' && emp.trim()!='')
        {
  jQuery.post(ajax_url, {action: 'insert_user',branch:branch,message:message,employee:employee,auth_by:auth_by,all:all,action5:action5}, function(result){
       
               var data=data1.split(',');
             
               var opt;
	       var authby=jQuery("#auth_by").val();
               if(jQuery("#all").prop('checked')==true)
               {opt=4;
               }
               else
               {
                opt=jQuery("#auth_by").val();
               }
               
            },
            error: function(response, status, error)
            {       alert1(error);
                   
            }
            ); 
        }
        else{
            alert1("Remarks or Employee name required");
        }


}


</script>
