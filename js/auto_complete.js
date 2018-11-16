function auto_complete(){
      $('.auto_complete').each(function(){
        $('#load_page').fadeIn('fast');
            $(this).autocomplete({
              source: function( request, response ) {
                $.ajax({
                  url : 'ajax/ajax_employee_details.php',
                  dataType: "json",
                  
                  data: {
                     name_startsWith: request.term,
                     type: 'employee'
                  },
                  success: function( data ) {
                    $('#load_page').fadeOut('fast');
                    if(data=='')
                    {
                      $(element).val('');
                    } 
                    else {
                      response( $.map( data, function( item ) {
                        return {
                          label: item,
                          value: item
                        }
                      }));
                    }
                  }
                });
              },
              autoFocus: true,
              minLength: 1
            });
          });
      $('#load_page').fadeOut('fast');
     }