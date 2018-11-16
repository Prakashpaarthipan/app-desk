<?php
session_start();
error_reporting(0);
include('lib/config.php');
include("db_connect/public_functions.php");
extract($_REQUEST);

printf("<img src='ftp_image_display.php?pic=%s&path=%s' class=\"img-responsive\" id='image'/><br><a target='_blank' download href='ftp_image_display.php?pic=%s&path=%s' class='btn btn-success'><i class='fa fa-fw fa-download'></i> Download Image</a>&nbsp;&nbsp;<a href='javascript:void(0)' class='idrotate btn btn-primary'><i class='fa fa-fw fa-rotate-right'></i> Rotate</a>&nbsp;&nbsp;<button class='btn btn-primary zoom-in'>Zoom In <i class='fa fa-fw fa-plus'></i></button>&nbsp;&nbsp;<button class='btn btn-primary zoom-out'>Zoom Out <i class='fa fa-fw fa-minus'></i></button>&nbsp;&nbsp;<button class='btn btn-warning reset'>Reset <i class='fa fa-fw fa-refresh'></i></button>", $pic, $path, $pic, $path); ?>
<!-- yea, yea, not a cdn, i know -->
<script src="js/ekko-lightbox-min.js"></script>
<script src="js/jquery.panzoom.js"></script>
<script src="js/jquery.mousewheel.js"></script>
<script type="text/javascript">
	$(document).ready(function ($) {
		// delegate calls to data-toggle="lightbox"
		$(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
			event.preventDefault();
			return $(this).ekkoLightbox({
				onShown: function() {
					if (window.console) {
						return console.log('Checking our the events huh?');
					}
				},
				// IMG ROTATE
				onContentLoaded: function() {
					var value = 0
					$(".idrotate").rotate({ 
						 bind: 
						 { 
							click: function(){
								value +=90;
								$('.img-responsive').rotate({ animateTo:value})
							}
						 }
					});
					
					var $section = $('.ekko-lightbox').first();
					$section.find('.ekko-lightbox-container').panzoom({
						$zoomIn: $section.find(".zoom-in"),
						$zoomOut: $section.find(".zoom-out"),
						$reset: $section.find(".reset")
					});
				}, // IMG ROTATE
				onNavigate: function(direction, itemIndex) {
					if (window.console) {
						return console.log('Navigating '+direction+'. Current item: '+itemIndex);
					}
				}
			});
		});
		

		//Programatically call
		$('#open-image').click(function (e) {
			e.preventDefault();
			$(this).ekkoLightbox();
		});
		$('#open-youtube').click(function (e) {
			e.preventDefault();
			$(this).ekkoLightbox();
		});

		$(document).delegate('*[data-gallery="navigateTo"]', 'click', function(event) {
			event.preventDefault();
			return $(this).ekkoLightbox({
				onShown: function() {
					var a = this.modal_content.find('.modal-footer a');
					if(a.length > 0) {
						a.click(function(e) {
							e.preventDefault();
							this.navigateTo(2);
						}.bind(this));
					}
				}
			});
		});
	});
</script>
<!-- Light Box -->