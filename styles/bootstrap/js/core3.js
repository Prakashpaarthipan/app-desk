function notomonth(mnth) {
	switch(mnth) {
		case 1:
			return "Jan";
			break;
		case 2:
			return "Feb";
			break;
		case 3:
			return "Mar";
			break;
		case 4:
			return "Apr";
			break;
		case 5:
			return "May";
			break;
		case 6:
			return "Jun";
			break;
			
		case 7:
			return "Jul";
			break;
		case 8:
			return "Aug";
			break;
		case 9:
			return "Sep";
			break;
		case 10:
			return "Oct";
			break;
		case 11:
			return "Nov";
			break;
		case 12:
			return "Dec";
			break;
			
		default:
			return "Jan";
			break;
	}
}

$(document).ready(function() {

    $('#datepicker-example1').Zebra_DatePicker();

    $('#datepicker-example2').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: 1    // boolean true would've made the date picker future only
                        // but starting from today, rather than tomorrow
    });

    $('#datepicker-example3').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
       // disabled_dates: ['* * * 0,6']   // all days, all monts, all years as long
                                        // as the weekday is 0 or 6 (Sunday or Saturday)
    });

    $('#datepicker-example4').Zebra_DatePicker({
		format: 'd-M-y',
        direction: 0,
       // disabled_dates: ['* * * 0,6']   // all days, all monts, all years as long
                                        // as the weekday is 0 or 6 (Sunday or Saturday)
    });
	
	/* for(var idp1 = 0; idp1 < 1000; idp1+=1) 
	{
		$('#datepicker-pair-start_'+idp1).Zebra_DatePicker({
		  format: 'd-M-y',
		  direction: true,
		  pair: $('#datepicker-example3_'+idp1)
		});
		var today = new Date();
		var tomorrow = new Date();
		alert(tomorrow.setDate(today.getDate()+10));
		alert(tomorrow.toLocaleDateString());
	} */
	
	
	
	for(var idp = 0; idp < 1000; idp+=1) 
	{
		/* var today = new Date();
		var tomorrow = new Date();
		// var expl = tomorrow.toLocaleDateString(tomorrow.setDate(today.getDate() + 15)); // 3/10/2016
		var explod = expl.split("/");
		
		var dd = explod[1];
		var mm = notomonth(explod[0]);
		// var yy = explod[2].substring(0, 2);
		var yy = explod[2].substring(2, 4);
		// alert(dd+"-"+mm+"-"+yy);
		var strt_date = dd+"-"+mm+"-"+yy; */
		
		var expl = document.getElementById('datepicker-pair-start_'+idp).value;
		var explod = expl.split("-");
		var dd = explod[0];
		var mm = explod[1];
		var yy = explod[2];
		var strt_date = dd+"-"+mm+"-"+yy;
		
		// $("#datepicker-example3_"+idp).val(strt_date).data('Zebra_DatePicker').show();
		$('#datepicker-example3_'+idp).Zebra_DatePicker({
			format: 'd-M-Y',
			direction: [strt_date, false]
		});
	}
	
	
	for(var idp = 100001; idp < 101001; idp+=1) 
	{
		$('#datepicker-example3_'+idp).Zebra_DatePicker({
			format: 'd-M-y',
			direction: true,
		});
	}

    /* $('#datepicker-example3_0').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_1').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_2').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_3').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_4').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_5').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_6').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_7').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_8').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_9').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_10').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_11').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_12').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_13').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_14').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_15').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_16').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_17').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_18').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_19').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_20').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_21').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_22').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_23').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_24').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_25').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_26').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_27').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_28').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_29').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_30').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_31').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_32').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_33').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_34').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_35').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_36').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_37').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_38').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_39').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_40').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_41').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_42').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_43').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_44').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_45').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	$('#datepicker-example3_46').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_47').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_48').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_49').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	$('#datepicker-example3_50').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
    });
	
	
	
	

    $('#datepicker-example4').Zebra_DatePicker({
        direction: [1, 10]
    }); */

    $('#datepicker-example5').Zebra_DatePicker({
        // remember that the way you write down dates
        // depends on the value of the "format" property!
        direction: ['2012-08-01', '2012-08-12']
    });

    $('#datepicker-example6').Zebra_DatePicker({
        // remember that the way you write down dates
        // depends on the value of the "format" property!
        direction: ['2012-08-01', false]
    });

    $('#datepicker-example7-start').Zebra_DatePicker({
        direction: true,
        pair: $('#datepicker-example7-end')
    });

    $('#datepicker-example7-end').Zebra_DatePicker({
        direction: 1
    });

    $('#datepicker-example8').Zebra_DatePicker({
        format: 'M d, Y'
    });

    $('#datepicker-example9').Zebra_DatePicker({
        show_week_number: 'Wk'
    });

    $('#datepicker-example10').Zebra_DatePicker({
        view: 'years'
    });

    $('#datepicker-example11').Zebra_DatePicker({
        format: 'm Y'
    });

    $('#datepicker-example12').Zebra_DatePicker({
        onChange: function(view, elements) {
            if (view == 'days') {
                elements.each(function() {
                    if ($(this).data('date').match(/\-24$/))
                        $(this).css({
                            background: '#C40000',
                            color:      '#FFF'
                        });
                });
            }
        }
    });

    $('#datepicker-example13').Zebra_DatePicker({
        always_visible: $('#container')
    });

    $('#datepicker-example14').Zebra_DatePicker();

});