<?php
/*
Template Name: Models of Success
*/
/**
 * Confirms that the activation key that is sent in an email after a user signs
 * up for a new blog matches the key for that user and then displays confirmation.
 *
 * @package WordPress
 */

/* Get models of success posts and loop through to only show title, 
link to content, first couple sentences of paragraph 
*/
	get_header(); ?>
	<?php
	$args = array(
	'posts_per_page'   => -1,
	'category'         => 9,    //9 on missouri transition
	'orderby'          => 'id',
	'order'            => 'ASC',
	'post_type'        => 'post',
	'post_status'      => 'publish',
	'suppress_filters' => true ); ?>
<section class="content">
<div class="template_content">
<h3><span>Models of Success</span></h3>
<br>
<div style="padding-right: 50px;" >
<?php 
         the_content();
         $myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post ); 
	?>
	
       <?php if ( has_post_thumbnail()): ?>
		<?php 
		echo "<div style='height: 40px; width: 35px; float: left; margin: 0px 15px 15px 0px;'>";
		the_post_thumbnail();
		echo "</div>";
        endif 
	?>	 
	<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		<?php the_excerpt(); ?>  
          <br>    
	
<?php endforeach;?>
</div>
</div>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?> 

