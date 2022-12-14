/**
 * FedCMS Slider - Unique jQuery Slider
 * @version: 1.0.1 - (04/28/2013)
 * @requires jQuery v1.8
 * @author GSA / OCSIT
 
 * Licensed under MIT licence:
 *   http://www.opensource.org/licenses/mit-license.php
**/

jQuery(document).ready(function( $ ) {

	$("div.box").hide();
	$("div#box1").show();
	$('#slide1').removeClass('fedcms_slider_button').addClass('fedcms_slider_button_active');

	$("#next").click(function(e) {
		e.preventDefault();
	    $("div.box").cyclediv($(this).parent().attr('id'),'next');
	});
	
	$("#previous").click(function(e) {
		e.preventDefault();
		var this_id = $(this).parent().attr('id');
	    $("div.box").cyclediv(this_id,'previous');
	});

$('.fedcms_slider_button, .fedcms_slider_button_active').click(function(){ 
	var option = $(this).parent().parent().attr('id');
	var slide_num = $(this).attr('id');
	slide_num = slide_num.replace(/slide/g, '');
	
	$('div.box').each(function(index){
		if($(this).is(":visible"))
		{
			var box_num2 = $(this).attr('id');
		  	box_num2 = box_num2.replace(/box/g, '');
		  	$('#slide'+box_num2).removeClass('fedcms_slider_button_active').addClass('fedcms_slider_button');
		  	$(this).hide();
		}
	});
	$("div#box"+slide_num).show();
	$('#slide'+slide_num).removeClass('fedcms_slider_button').addClass('fedcms_slider_button_active');

	return false; });

$.fn.cyclediv = function(option,direction){
	var all_elem = $(this)
	var total_divs = $(this).length;

	$(this).each(function(index) {
	  if ($(this).is(":visible"))
	  {
	  	if(direction == 'next')
	  	{ 
	      var box_num = $(this).attr('id');
		  box_num = box_num.replace(/box/g, '');
	      if(box_num < total_divs)
	      {
	      	$('#box'+(index+2)).show().fadeIn();
	      	$('#slide'+(index+2)).removeClass('fedcms_slider_button').addClass('fedcms_slider_button_active');
	      }
	      else
	      {
	         $('#box1').show().fadeIn();
	         $('#slide1').removeClass('fedcms_slider_button').addClass('fedcms_slider_button_active');
	      }
	      $('#slide'+box_num).removeClass('fedcms_slider_button_active').addClass('fedcms_slider_button');
	      $(this).hide();

	      return false;
	    }
	    else
	    {
	      if($(this).is('#box1'))
	      {
	          $('.box:last').show().fadeIn();
	          $('#slide'+total_divs).removeClass('fedcms_slider_button').addClass('fedcms_slider_button_active');
	          $('#slide1').removeClass('fedcms_slider_button_active').addClass('fedcms_slider_button');
	      }
	      else
	      {
	          $('#box'+index).show().fadeIn();
	          $('#slide'+index).removeClass('fedcms_slider_button').addClass('fedcms_slider_button_active');
	          $('#slide'+(index+1)).removeClass('fedcms_slider_button_active').addClass('fedcms_slider_button');
	          //console.log('showing slide'+index);
	      }
	      $(this).hide();
	      //console.log(index);
	      return false;
	    }
	  }
	});
}

});