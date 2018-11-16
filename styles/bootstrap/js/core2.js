$(document).ready(function() {

    $('#datepicker-example1').Zebra_DatePicker();

    $('#datepicker-example2').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: 1    // boolean true would've made the date picker future only
                        // but starting from today, rather than tomorrow
    });
	
	$('#datepicker-date1').Zebra_DatePicker({
        format: 'd-M-Y',
        direction: -1
    });

    $('#date_id').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: [-1,40]//10
       // disabled_dates: ['* * * 0,6']   // all days, all monts, all years as long
                                        // as the weekday is 0 or 6 (Sunday or Saturday)
    });

    /* $('#duedate_change').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: ['20-APR-2015', '20-JUN-2015']	//10
       // disabled_dates: ['* * * 0,6']   // all days, all monts, all years as long
                                        // as the weekday is 0 or 6 (Sunday or Saturday)
    }); */
	
	    $('#invoice_id').Zebra_DatePicker({
		 format: 'd-M-Y',
        direction: [-1,48]//10
    });
	 
	 /* $('#invoice_id').Zebra_DatePicker({
		format: 'd-M-Y',
        direction: true,
       // disabled_dates: ['* * * 0,6']   // all days, all monts, all years as long
                                        // as the weekday is 0 or 6 (Sunday or Saturday)
    });*/

    $('#datepicker-example4').Zebra_DatePicker({
		format: 'd-M-y',
        direction: 0,
       // disabled_dates: ['* * * 0,6']   // all days, all monts, all years as long
                                        // as the weekday is 0 or 6 (Sunday or Saturday)
    });
	$('#usr_dob').Zebra_DatePicker({
		format: 'd-M-y',
        direction: 0,
       // disabled_dates: ['* * * 0,6']   // all days, all monts, all years as long
                                        // as the weekday is 0 or 6 (Sunday or Saturday)
    });
/*	for(var idp = 0; idp < 1000; idp+=1) 
	{
		$('#date_id').Zebra_DatePicker({
			format: 'd-M-y',
			direction: true,
		});
	}
	
	
	for(var idp = 100001; idp < 101001; idp+=1) 
	{
		$('#datepicker-example3_'+idp).Zebra_DatePicker({
			format: 'd-M-y',
			direction: true,
		});
	}*/

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
        format: 'd-M-Y',
		
    });

    $('#datepicker-example13').Zebra_DatePicker({
        always_visible: $('#container')
    });

    $('#datepicker-example14').Zebra_DatePicker();

});