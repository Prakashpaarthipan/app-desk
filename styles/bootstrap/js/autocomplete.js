$('.suppcodename').autocomplete({
	source: function( request, response ) {
		$.ajax({
			url : 'ajax_suppliers_details.php',
			dataType: "json",
			async: true,
			data: {
			   name_startsWith: request.term,
			   type: 'supplier'
			},
			success: function( data ) {
				response( $.map( data, function( item ) {
					return {
						label: item,
						value: item
					}
				}));
			}
		});
	},
	autoFocus: true,
	minLength: 0
});