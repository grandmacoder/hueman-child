<?php
/*
Template Name: assessment reviews template 2
*/
/**By: Greg Carlson

This template is built to display assessment reviews for TC. 
The template displays a list of the assessment reviews for users 
to filter and select.
 */

/* Get assessment review posts and loop through to show thumbnail image, title, 
link to content, average rating, author, publisher info, link to resource, and 
whether the assessment review is a free resource.
*/
	get_header(); ?>
	<style>
	img.assessmentthumbnail{
	width:100%;
    max-width:130px;
    height:auto;
	}
	</style>
	<?php
	$cat_id=48;
	$args1 = array(
	'posts_per_page'   => -1,
	'category'         =>$cat_id,
	'orderby'          => 'id',
	'order'            => 'DESC',
	'post_type'		   => array('post','assessment_review'),
	'post_status'      => 'publish',
	'suppress_filters' => true
	 ); 
	?>
<section class="content">
<h3>Assessment Reviews</h3>
<BR><BR>
<div style="padding-right: 50px; padding-left: 30px;">
<?php the_content();?>
<?php $myposts = get_posts( $args1 );?> 
<?php
foreach ( $myposts as $post ) {
$id=$post->ID;
$excerpt= $post->post_excerpt;
$excerpt=str_replace('<em>','',$excerpt);
$excerpt=str_replace('</em>','',$excerpt);
$img_url = wp_get_attachment_image_src(get_post_thumbnail_id()); 
?>
<div class="content-column one_third">
<?php echo "<img class='assessmentthumbnail' src='". $img_url[0] ."'><BR><BR>"; ?>
</div>
<div class="content-column two_third last_column">
<a class="post-title" onClick="ga('send', 'event', 'category', 'action', { eventCategory: 'Site Resource', eventAction: 'selected', eventLabel: 'Assessment:<?php echo the_title(); ?><?php echo ', Post ID: '.the_ID();?>'});" href='<?php the_permalink(); ?>' title='<?php echo the_title(); ?>' alt='<?php the_title(); ?>' ><?php echo the_title(); ?></a> <BR>
<?php $average_rating = get_post_meta($id, 'crfp-average-rating', true);
      $numRatings = tc_reviews_get_number_reviews_per_post($id);
		//output average rating for assessment review
		if($average_rating > 0){ ?>
		<div  class='crfp-rating crfp-rating-<?php echo "Average Rating ". $average_rating; ?>'>
		</div><span style="font-size:12px;">(<?php echo "  " .$numRatings; if ($numRatings==1){echo " rating";}else{echo " ratings";}?>)</span>
		<?php } 
		else{  ?>
		<p style="color: #c2132f; font-size: 12px;">No ratings yet! Be the first to rate this assessment!</p>
        <?php	} ?>
		<?php  $isFreeResource = get_post_meta($id, 'free_resource', true);
		            if($isFreeResource == 1){
					echo "<p style='color:#c2132f'>Free Resource</p>";
					} 
					else{
					echo "<p>&nbsp;</p>";
					}
		?>
<?php echo $excerpt ."<p>&nbsp;</p>"; ?>

</div>
<div class="clear_column"></div>


<?php } ?>
</div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 

