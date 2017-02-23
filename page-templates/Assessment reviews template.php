<?php
/*
Template Name: assessment reviews template
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
	<?php
	$cat_id=48;
	$args1 = array(
	'posts_per_page'   => -1,
	'category'         =>$cat_id,
	'orderby'          => 'post_title',
	'order'            => 'ASC',
	'post_type'		   => array('post','assessment_review'),
	'post_status'      => 'publish',
	'suppress_filters' => true
	 ); 
	?>
    <div class="template_content">
    <h3><span>Assessment Reviews</span></h3>
    <br>
    <section class="content">
    <?php the_content();?>
	
	<select id="sort_assessments">
	    <option value="Select">Select</option>
		<option value="(TITLE) A-Z">(TITLE) A-Z</option>
		<option value="(TITLE) Z-A">(TITLE) Z-A</option>
		<option value="(Rating) High-Low">(Rating) High-Low </option>
		<option value="(Rating) Low-High">(Rating) Low-High</option>
		</select>
	<br>
	<?php
	$myposts = get_posts( $args1 );
	?> 
	<ul class="js-sort-assessments">
	<?php
	foreach ( $myposts as $post ) : setup_postdata( $post ); 
	
	?>
	<li class="assessment_sort" data-name="<?php the_title(); ?>" data-rating="<?php echo get_post_meta($id, 'crfp-average-rating', true); ?>">
         <article id="post-<?php the_ID(); ?>">
         <div class="post-inner post-hover">
         <div style='float: left; margin: 10px;'>
		<?php if (get_post_type($id) == "assessment_review"){ ?>
		<?php 
		$image_post_id = get_meta_value_by_key($id, 'assessment_image_file');
		?>
		<a href="<?php the_permalink(); ?>" rel="bookmark" title="Click to get the assessment: <?php the_title(); ?>"><img style ="height: 144px; width: 110px; border: 0px;" src='<?php echo get_the_guid($image_post_id); ?>' alt='' /></a>
	        <?php 
		}
		//else it is an assessment review category from the imported items
		else { ?>
		<a href="<?php the_permalink(); ?>" rel="bookmark" title="Click to get the assessment: <?php the_title(); ?>"><img style ="height: 144px; width: 110px; border: 0px;" src='<?php echo get_site_url();?>/wp-content/originalSiteAssets/files/forums/images/<?php echo get_post_meta($id, 'assessment_image_file', true); ?>' alt='' /></a>
		<?php } ?>
		</div>
                <div style="padding-top: 10px;" ><span class="post-title">        
		<a href="<?php the_permalink(); ?>" rel="bookmark" title="Click to get the assessment:<?php the_title(); ?>"><?php the_title(); ?></a> </span><!--/.post-title-->
		
		<?php $average_rating = get_post_meta($id, 'crfp-average-rating', true);
		       $numRatings = tc_reviews_get_number_reviews_per_post($id);
		//output average rating for assessment review
			if($average_rating > 0){ ?>
		        <br> Average Rating&nbsp;&nbsp;
			<div  class='crfp-rating crfp-rating-<?php echo $average_rating; ?>'> 
			</div><span style="font-size:12px;">(<?php echo "  " .$numRatings; if ($numRatings==1){echo " rating";}else{echo " ratings";}?>)</span>
			<?php } else{  ?>
			<p style="color: #c2132f; font-size: 12px;">No ratings yet! Be the first to rate this assessment!</p>
                <?php	} ?>
			
		<p><?php  echo get_post_meta($id, 'assessment_author', true); ?> <?php  echo get_post_meta($id, 'assessment_year', true); ?></p>
		
		<?php  $isFreeResource = get_post_meta($id, 'free_resource', true);
		                       if($isFreeResource == 1){
					echo "<p style='color:#c2132f'>Free Resource</p>";
					} 
					else{
					echo "<p>&nbsp;</p>";
					}
		?>
		<br><br>
	    </div> <!--end margin-top-->
	</div><!--/.post-inner-->	
</article><!--/.post-->
</li>
<?php endforeach;?>
</ul>
</div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 

