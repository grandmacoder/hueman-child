<?php
/*
Template Name: tc selfstudy
*/
/**
 *
 * @package WordPress
 */
/* Displays customize output of self study content
*/
?>

<?php
global $wpdb;
	get_header(); ?>
	<?php
	//get the category ID for this page
	$meta = get_post_meta( get_the_ID() ); 
    $forum_id = $meta['page_discussion_forum'][0];
	$shortcode ="[bbp-single-forum id=". $forum_id."]";
	$categories = get_the_category(get_the_ID());
	foreach ($categories as $category){
	 if ($category->parent > 0){
		$category_id= $category->cat_ID;
	 }
	}
	$args = array(
	'posts_per_page'   => -1,
	'category'         => $category_id,
	'orderby'          => 'publish_date',
	'order'            => 'ASC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'post',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',
	'post_status'      => 'publish',
	'suppress_filters' => true 
	 );	 
	?>
<section class="content">
<div class="template_content">
<h3><?php the_title();?></h3>
	
<?php the_content();?>
 <?php if ($category_id> 0 ){  
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post );
if (!is_page(  $post->ID ) ){
?>
<article id="post-<?php the_ID(); ?>">	
<p >
<?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
 echo "<span style='padding-right: 10px; vertical-align:top;float:left;'>";
the_post_thumbnail( 'thumb-small');
echo "</span>";
}
 ?>
<a class="newsitem" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" ><strong><?php the_title(); ?></strong></a><br>
<!--/.post-title-->
 <?php the_excerpt(); ?>
<br></p>
<div style="clear:both;"></div>
</article><!--/.post-->	
<?php } // end if ?>	
<?php endforeach;?>
<!-- output the forum topics -->

<?php  
if ( $forum_id > 0 ){
echo '<div style="clear:both;"></div><hr />';
echo  do_shortcode($shortcode);?>
<?php
}
}
else{
echo "<p>Sorry,nothing has been posted for this Self-study course yet.>";
}
?>
</div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 

