
 <!DOCTYPE html>
    <html lang="en">
    <head>
    
    <title>Custom Templates</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- StyleSheet -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/jquery.lwMultiSelect.css">
	
    <!-- Scripts -->
    <script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type = "text/javascript" src = "../js/jquery.lwMultiSelect.min.js"></script>

    <!-- Custom Style -->
    <style type="text/css">
		.entry:not(:first-of-type)
		{
			margin-top: 10px;
		}

		.glyphicon
		{
			font-size: 12px;
		}
		ul{  
			background-color:#eee;  
			cursor:pointer;  
		  }  
		li{  
			padding:12px;  
		  }  
	</style>
	</head>

	<body>

	<div class="container">
	<div class="row" style="margin-top:100px;">
	<div class = "col-lg-6" >
        <div class="control-group" id="fields" style="margin-left:100px;padding-bottom:10px;">
            <label class="control-label" for="field1" style="padding-bottom:10px;">Validation Multiple Form Fields</label>
            <div class="controls" > 
                <form role="form" autocomplete="off">
                    <!--<div class="entry input-group col-lg-8" >
                        <input class="form-control" name="fields[]" type="text" placeholder="Type something" />
                    	<span class="input-group-btn">
                            <button class="btn btn-success btn-add" type="button">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </span>
                    </div>-->     
					<!--  Above the code will work -->
					 <div class="entry input-group col-lg-8" >
                        <input class="form-control" name="emp[]" type="text" id ="emp" placeholder="Type something" />
						<div id="emplist"></div>
                    	<span class="input-group-btn">
                            <button class="btn btn-success btn-add" type="button">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </span>
						
                    </div>
					
					
                
            </div> 
           <br>
            <small>Press <span class="glyphicon glyphicon-plus gs"></span> to add another form field :)</small>
			
			
        
		<label>Enter Country Name</label>  
                <input type="text" name="country" id="country" class="form-control" placeholder="Enter Country Name" />  
                <div id="countryList"></div>
				
			</form>
		</div>
		</div>
		<div class = "col-lg-6">
			
		</div>
	</div>
</div>

	<script type="text/javascript">
		$(function()
		{
			$(document).on('click', '.btn-add', function(e)
			{
				e.preventDefault();
				var id = 1;
				var controlForm = $('.controls form:first'),
					currentEntry = $(this).parents('.entry:first'),
					newEntry = $(currentEntry.clone()).appendTo(controlForm);

					newEntry.find('input').val('');
					controlForm.find('.entry:not(:last) .btn-add')
					.removeClass('btn-add').addClass('btn-remove')
					.removeClass('btn-success').addClass('btn-danger')
					.html('<span class="glyphicon glyphicon-minus"></span>');
			}).on('click', '.btn-remove', function(e)
			{
				$(this).parents('.entry:first').remove();

				e.preventDefault();
				return false;
			});
		});
		
		
	$(document).ready(function(){  
      $('#country').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"search.php",  
                     type:"POST",  
                     data:({query:query}),  
					 dataType:'text',
                     success:function(data)  
                     {  
							console.log("sent");
                          $('#countryList').fadeIn();  
                          $('#countryList').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#country').val($(this).text());  
           $('#countryList').fadeOut();  
      });  
	});  
	$(document).ready(function(){  
      $('#emp').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"search.php",  
                     type:"POST",  
                     data:({query:query}),  
					 dataType:'text',
                     success:function(data)  
                     {  
							console.log("sent");
                          $('#emplist').fadeIn();  
                          $('#emplist').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#emp').val($(this).text());  
           $('#emplist').fadeOut();  
      });  
	});  

		
	</script>	
	
	</body>
</html>
