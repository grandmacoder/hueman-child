/*
	assessment_review.js
	
*/
jQuery(document).ready(function($) {
var current_page = $(location).attr('href');
var baseURL = window.location.protocol+"//"+window.location.host;
//hidden field with category id
var cat_id = $('input[name="saved_cat_ID"]').val();

//*******************************************************
//captures clicks on tag links (links are created from an array on the template )
$('.assessment_tag_selection').click(function(e) {
e.preventDefault();
var tag_id= $(this).data('tag_id');      
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
	$.ajax({       type: "POST",
                   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'get_assessment_reviews','set': 'tags','cat_id': cat_id, 'tag_id': tag_id},
                   success: function(response){
				    var sliderhtml=response['sliderhtml'];
					var messagehtml=response['messagehtml'];
					$( "div.owl-carousel" ).html(sliderhtml);
					$( "div.assessment_results_header").html(messagehtml);
			         // reinit carousel would have been created on first screen load
								var owl = $('.owl-carousel');
                                // get owl instance from element
								var owlInstance = owl.data('owlCarousel');
                                // if instance is existing
								if(owlInstance != null)
									owlInstance.reinit();
									//show the stars with font awesome
									 $.fn.stars = function() {
											return $(this).each(function() {
												var rating = $(this).data("rating");
												var numStars = $(this).data("numStars");
												var totalstars = $(this).data("totalStars");
												var fullStar = new Array(Math.floor(rating + 1)).join('<i class="fa fa-star" style="color:#f4db54;font-size:18px;"></i>');
												var halfStar = ((rating%1) !== 0) ? '<i class="fa fa-star-half-empty" style="color:#f4db54;font-size:18px;"></i>': '';
												var noStar = new Array(Math.floor(totalstars+1 - rating)).join('<i class="fa fa-star-o" style="color:#838484;font-size:18px;"></i>');
												$(this).html(fullStar + halfStar + noStar);

											});
										}    
					$('.stars').stars();	
					},
					error: function(xhr, textStatus, errorThrown){
					console.log(textStatus);
					},

			 });//end ajax call
});
//*******************************************************
//END capture clicks on tag links 


//*******************************************************
//Captures enter key on input field
$('#assessment_search').keypress(function(e) {
if ( e.keyCode == 13 ) {  // detect the enter key
		e.preventDefault();
		var keyword = $(this).val();
					var saved_cat_ID = $('input[name="saved_cat_ID"]').val();
					//get the output as html and return it to the slider
					//set the results message
					var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";
						$.ajax({       type: "POST",
									   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'get_assessment_reviews','set': 'keywordset','cat_id': saved_cat_ID, 'keyword':keyword},
                   success: function(response){
				    var sliderhtml=response['sliderhtml'];
					var messagehtml=response['messagehtml'];
					$( "div.owl-carousel" ).html(sliderhtml);
					$( "div.assessment_results_header").html(messagehtml);
					$('input[name="saved_cat_ID"]').val(response['catid']);
					            // reinit carousel would have been created on first screen load
								var owl = $('.owl-carousel');
                                // get owl instance from element
								var owlInstance = owl.data('owlCarousel');
                                // if instance is existing
								if(owlInstance != null)
									owlInstance.reinit();
									//show the stars with font awesome
									 $.fn.stars = function() {
											return $(this).each(function() {
												var rating = $(this).data("rating");
												var numStars = $(this).data("numStars");
												var totalstars = $(this).data("totalStars");
												var fullStar = new Array(Math.floor(rating + 1)).join('<i class="fa fa-star" style="color:#f4db54;font-size:18px;"></i>');
												var halfStar = ((rating%1) !== 0) ? '<i class="fa fa-star-half-empty" style="color:#f4db54;font-size:18px;"></i>': '';
												var noStar = new Array(Math.floor(totalstars+1 - rating)).join('<i class="fa fa-star-o" style="color:#838484;font-size:18px;"></i>');
												$(this).html(fullStar + halfStar + noStar);

											});
										}
									 $('.stars').stars();	

					},
					error: function(xhr, textStatus, errorThrown){
					console.log(textStatus);
					},
			 });//end ajax call 
           
        }
 });
 //*******************************************************
//END Captures enter key on input field


 //*******************************************************
//Initial page load, gets all assessment reviews, filters on ratings in output
var urltoget = baseURL+"/wp-content/themes/hueman-child/processCustomAjax.php";

	$.ajax({       type: "POST",
                   url: urltoget,
                   dataType: 'json', 
                   data: {'action':'get_assessment_reviews','set': 'all','cat_id': cat_id},
                   success: function(response){
				    var sliderhtml=response['sliderhtml'];
					var messagehtml=response['messagehtml'];
					$( "div.owl-carousel" ).html(sliderhtml);
					$( "div.assessment_results_header").html(messagehtml);
					$('input[name="saved_cat_ID"]').val(response['catid']);
					    //Sort random function
										function random(owlSelector){
										owlSelector.children().sort(function(){
										return Math.round(Math.random()) - 0.5;
										}).each(function(){
										$(this).appendTo(owlSelector);
										});
										}
										 
										$("#owl-demo").owlCarousel({
											items: 6,
											itemsDesktop: [1400, 6],
											itemsDesktopSmall: [1100, 4],
											itemsTablet: [700, 3],
											itemsMobile: [500, 1],
											autoHeight:true,
										navigation: true,
										beforeInit : function(elem){
										//Parameter elem pointing to $("#owl-demo")
										random(elem);
										}
										   
										});
							//show the stars with font awesome
									 $.fn.stars = function() {
											return $(this).each(function() {
												var rating = $(this).data("rating");
												var numStars = $(this).data("numStars");
												var totalstars = $(this).data("totalStars");
												var fullStar = new Array(Math.floor(rating + 1)).join('<i class="fa fa-star" style="color:#f4db54;font-size:18px;"></i>');
												var halfStar = ((rating%1) !== 0) ? '<i class="fa fa-star-half-empty" style="color:#f4db54;font-size:18px;"></i>': '';
												var noStar = new Array(Math.floor(totalstars+1 - rating)).join('<i class="fa fa-star-o" style="color:#838484;font-size:18px;"></i>');
												$(this).html(fullStar + halfStar + noStar);

											});
										}
									 $('.stars').stars();	
			
						},
					error: function(xhr, textStatus, errorThrown){
					console.log(textStatus);
					},
			 });//end ajax call
 //*******************************************************
//END Initial page load, gets all assessment reviews, filters on ratings in output
});//end document ready



					
					