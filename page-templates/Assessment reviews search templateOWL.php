<?php
error_reporting(E_ERROR);

/*
Template Name: assessment reviews search template
*/
/**By: Amy Carlson

This template is built to display assessment reviews for TC. 

 */

/* Get assessment review posts and loop through to show thumbnail image, title, 
link to content, average rating, author, publisher info, link to resource, and 
whether the assessment review is a free resource.
*/
get_header(); 
$cat_id=48;
	$args1 = array(
	'posts_per_page'   => -1,
	'category'         =>$cat_id,
	'orderby'          => 'id',
	'order'            => 'DESC',
	'post_type'		   => array('post','assessment_review'),
	'post_status'      => 'publish',
	'suppress_filters' => true,
); 
$myposts = get_posts( $args1 );?> 
<!-- add the js for the slider only -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet' type='text/css'>
<link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/google-code-prettify/prettify.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/owl_assessments_css.css" rel="stylesheet">

<div style="padding-left: 5%; padding-top: 2%;">
<?php
echo "<h3>". get_the_title() ."</h3><BR><BR>";
the_content();
?>
<div style="clear:both;"></div>
<hr>
<div id="search_category_form">
<form><input name="search_assessments" id="search_assessments" value="Find an assessment"></input></form>
<BR>
</div>
<div id="search_category_links">
<p style="font-size: 18px; font-weight:bold;">Some categories to search with</p>
</div>

<div id="indexCarousel">
	<div id="owl-demo" class="owl-carousel">
	        <?php 
                  foreach ($myposts as $post ) {
				   $id=$post->ID;
                   $post_title= $post->post_title;
			       $post_thumbnail_id = get_post_thumbnail_id( $post );
				   $average_rating = get_post_meta($id, 'crfp-average-rating', true);
				//   select AVG(meta_value) from wp_commentmeta  where comment_id in (Select comment_id from wp_comments where comment_post_id = 6420) and meta_key= 'crfp-rating'
				   $numRatings = tc_reviews_get_number_reviews_per_post($id);
			?>
				<div class="assessment_item">
				 <a href="/?p=<?php echo $id;?>" title='<?php echo $post_title;?>' alt='<?php echo $post_title;?>' ><img src="<?php  echo wp_get_attachment_image_url( $post_thumbnail_id, 'post-thumbnail' );;?>"></a>
		         <p class='assessment_item_p'><?php echo $post_title;?></p>
		        </div>
				<?php } //foreach ?>
</div>
</div>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 

<!-- js -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-1.9.1.min.js"></script> 
<script src="<?php echo get_stylesheet_directory_uri(); ?>/owl-carousel/owl.carousel.js"></script>
<script>
jQuery(document).ready(function($){
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
    navigation: true,
    beforeInit : function(elem){
    //Parameter elem pointing to $("#owl-demo")
    random(elem);
    }
       
    });	 
    });
</script>
