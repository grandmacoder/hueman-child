<?php
/*
Template Name: tc materials 2
*/
/**
 * shows the materials category of posts
 *
 * @package WordPress
 */
/* Displays customize output of links for a category.
*/
?>

<?php get_header(); ?>
<?php
//get current post category and go to the brochure area
	if (isset($_GET['cat_ID']) && is_numeric($_GET['cat_ID'])){
	$cat_id = $_GET['cat_ID'];
	}
	elseif (isset($_GET['cat_ID']) && !is_numeric($_GET['cat_ID'])){
	die("Invalid qs input.");
	}
	else{
	$cat_id = 331;
	}
	$args1 = array(
	'posts_per_page'   => -1,
	'category'         => $cat_id,
	'orderby'          => 'ID',
	'order'            => 'DESC',
	'post_type'         => array('post','tc_materials'),
	'post_status'      => 'publish',
	'suppress_filters' => true
	 ); 

?>
<section class="content">
<h3>Products and Materials</h3>
<BR><BR>
<div style="padding-right: 50px; padding-left: 30px;">
<?php the_content();?>
<?php $myposts = get_posts( $args1 );?> 
<?php
foreach ( $myposts as $post ) {
$excerpt= $post->post_excerpt;
$excerpt=str_replace('<em>','',$excerpt);
$excerpt=str_replace('</em>','',$excerpt);
?>
<div class="content-column one_third">
    <?php the_post_thumbnail( 'single-post-thumbnail' ); ?><BR><BR>
</div>
<div class="content-column two_third last_column">
<a class="post-title" onClick="ga('send', 'event', 'category', 'action', { eventCategory: 'Site Resource', eventAction: 'selected', eventLabel: 'Material:<?php echo the_title(); ?><?php echo ', Post ID: '.the_ID();?>'});" href='<?php the_permalink(); ?>' title='<?php echo the_title(); ?>' alt='<?php the_title(); ?>' ><?php echo the_title(); ?></a> 
<br>
<?php echo $excerpt; ?>
</div>
<div class="clear_column"></div>


<?php } ?>
</div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 

