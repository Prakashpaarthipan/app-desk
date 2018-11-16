<script>
 function printpage(notyear,notnum)
    {
alert("hai")
    var opt;
       
   var auth_by=jQuery('#auth_by').val();
   console.log(auth_by);
       if(jQuery("#all").prop('checked')==true)
       {opt=4;
       }
       else
       {
        opt=1;
       }
     jQuery.post(ajax_url,{action:'print_report',notyear:notyear,notnumb:notnum,all:opt,authby:auth_by}, function(result){
                jQuery('#printdiv').html(result);
              setTimeout(function(){printDiv('printdiv');},2000);

   
});
    }

function printDiv(divName){
			var printContents = document.getElementById(divName).innerHTML;    
                        printContents+='<style>@media print {  @page { margin: 0; }  body { margin: 1.6cm; }
}
</style>'                
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}

</script>