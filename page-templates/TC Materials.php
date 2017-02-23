<?php
/*
Template Name: tc materials
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
<?php the_content();?>
	<div style="padding-left: 30px; padding-top: 20px; padding-bottom: 10px;">
    
	<h3>Products and Materials</h3>
	<br>
	<select id="sort_assessments">
	    <option value="Select">Select</option>
		<option value="(TITLE) A-Z">(TITLE) A-Z</option>
		<option value="(TITLE) Z-A">(TITLE) Z-A</option>
		</select>
	</div>
	<?php
	$myposts = get_posts( $args1 );
	
	?> 
	<ul class="js-sort-assessments">
	<?php
	foreach ( $myposts as $post ) : setup_postdata( $post ); 
	$postid = get_the_ID();
	//get child posts by calling function in plugins folder for TC
	$child_posts = tc_materials_template_get_child_posts($postid);
	
	?>
	<li class="assessment_sort" data-name="<?php the_title(); ?>">
        <article id="post-<?php the_ID(); ?>">
       <div class="post-inner post-hover">
       <div style="padding-left: 30px; padding-right: 50px; ">
        <div style='float: left; margin: 10px;'>
		<?php the_post_thumbnail( 'single-post-thumbnail' ); ?>
	
		</div>
        <div style="padding-left: 180px;" ><span class="post-title">        
		<a class="post-title" href='<?php the_permalink();?>' title='<?php the_title(); ?>'><?php the_title(); ?></a> </span><!--/.post-title-->
		<?php the_content();
		
		$details_for_pdf = get_post_meta($postid, 'details_pdf_resources', true);
		
		echo "<div style='margin-bottom: 70px;'> <strong>".$details_for_pdf."</strong><br>";		
		foreach($child_posts as $post){
				$guid = $post->guid;
				$post_title = $post->post_title;
				$post_title = str_replace("_", " ", $post_title, $count);
				$linkStyle = linkIcon($guid);
				//echo "<a class=pdf-ico href='".$guid."' title='".$post_title."' alt='".$post_title."' target='_blank'>".$post_title."</a>";
				?><a class='<?php echo $linkStyle; ?>' onClick="ga('send', 'event', 'category', 'action', { eventCategory: 'Site Resource', eventAction: 'selected', eventLabel: 'Material:<?php echo $post_title; ?><?php echo ', Post ID: '.$postid;?>'});" href='<?php echo $guid; ?>' title='<?php echo $post_title; ?>' alt='<?php echo $post_title; ?>' target='_blank'><?php echo $post_title; ?></a>
				                     
				<?php
				echo "<br>";		
			}
			echo "<br></div>";
		?> 
		
	
               </div> <!--end margin-top-->
		</div> <!--end padding-->		
	</div><!--/.post-inner-->	
	
	
</article><!--/.post-->
</li>
<?php endforeach;?>
</ul>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 

