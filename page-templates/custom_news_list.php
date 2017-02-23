<?php
/*
Template Name: custom news list
*/
/**
 * Confirms that the activation key that is sent in an email after a user signs
 * up for a new blog matches the key for that user and then displays confirmation.
 *
 * @package WordPress
 */

/* Get News items
*/
	get_header(); ?>
	<?php
	
	$args = array(
	'posts_per_page'   => 30,
	'category'         => 38,
	'orderby'          => 'id',
	'order'            => 'DESC',
	'post_type'        => 'post',
	'post_status'      => 'publish',
	'suppress_filters' => true ); ?>
      
    <section class="content">

	<?php 
	the_content();?>

	<?php
	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post ); 
	?>
        <article id="post-<?php the_ID(); ?>">	
<p >
	  <?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
	            echo "<span style='padding-right: 10px; vertical-align:top;float:left;'>";
		    the_post_thumbnail( 'thumb-small');
		    echo "</span>";
		    }
		   ?>
		<a class="newsitem" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" ><strong><?php the_title(); ?></strong></a>
			<!--/.post-title-->
			<br><span class="post-date-newslist"><?php the_time('M j, Y'); ?></span><br>
		        <?php the_excerpt(); ?>
		
              <br></p>
	  <div style="clear:both;"></div>
         </article><!--/.post-->		
          <?php endforeach;?>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 

