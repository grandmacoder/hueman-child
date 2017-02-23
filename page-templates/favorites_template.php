<?php
/*
Template Name: my_favorites_template
*/
/** By: Greg Carlson
 * Template allows users to view a list of their favorite posts  or 
 * delete the post from their favorites. Favorites are chosen(pinned) by the user throughout 
 * posts and lists of links on the TC website. Template uses the user ID and pulls favorites from the wp_favorites 
 * table in the database.
 *
 * @package WordPress
*/
	get_header(); 
	global $wpdb;
	$current_id = $current_user->ID;
	$base_url = get_bloginfo('wpurl');
	$category_ID = $_GET['cat_ID']; //from the url
	?>

	<section class="content">
	  <div class="template_content">
          <h3><span>My Pinned Items</span></h3>
          <br>
        <!--Start building favorites list for the user-->
	<article id="post-<?php the_ID(); ?>">
    	<div class="post-inner post-hover">		
<?php
        //retrieve saved news items favorites
	$category_links =tc_favorites_get_news_items($current_id);
	//Build a table if there are any saved news items or display no items
	?>
	<BR><span class="post-title">Pinned News items: </span>
	<article id="post-<?php the_ID(); ?>">
<?php
	if(sizeof($category_links) > 0){	?>
<table class ="basic_table"><tr><th width = '50%'>Link</th><th width ='10%'>Date</th><th width = '30%'>Category</th><th width '10%'>Delete</th></tr>
<?php
		// List favorite saved news item link and display 
		foreach ($category_links as $unit){
			$post_category = get_the_category($unit->post_id);
				// Get the categories for favorite
				foreach($post_category as $category){
					$category_list .= $category->cat_name.", ";	
				}
			$category_list = substr($category_list, 0, -2);
			$the_link=get_permalink($unit->post_id);
			$link_title=$unit->post_title;				
			//Display favorites post link, date saved, categories, and delete favorite option
			echo "<tr id='row-".$unit->favorite_id."'><td width = '50%'><li class='".linkIcon($the_link)."'><a href='".$the_link."' alt='link to ".$link_title."' title='". $link_title."' target=_blank >".$link_title."</a></li></td><td width ='10%'>".favoriteformatdate($unit->entry_date).
			"</td><td width = '30%'>".$category_list."</td><td width = '10%' style='text-align: center; padding-top: 6px;'><a href='?deleteFavorite=".$unit->favorite_id."' class=deleteFavorite id=".$unit->favorite_id." name = fromFavorites>
			<img src='wp-content/uploads/2014/10/deleteicon.png' height=25 width=25 title='Delete from favorites!' alt='click to delete favorite'></a></td></tr>";
	
			$category_list = "";
		} //end for loop
?>
</table>
<?php
	} //end if 
	else{	
			echo "<span style=''>No favorites for news items.</span>";	
	}
?>
	
</article><!--/.post-->
<BR><span class="post-title">Pinned TC items: </span>
<article id="post-<?php the_ID(); ?>">
<?php
	//Retrieve saved TC items favorites
	$tc_items = tc_favorites_get_tc_items($current_id);
	
	//Build a table if there are any saved TC items or display no items
	if(sizeof($tc_items) > 0){
?>
<table class ="basic_table"><tr><th width = '50%'>Link</th><th width ='10%'>Date</th><th width = '30%'>Category</th><th width '10%'>Delete</th></tr>
<?php
	
		// List favorite saved TC item link and display
		foreach ($tc_items as $unit){
			$post_category = get_the_category($unit->post_id);
				// Get the categories for favorite
				foreach($post_category as $category){
					$category_list .= $category->cat_name.", ";	
				}
			$category_list = substr($category_list, 0, -2);
			$the_link=get_permalink($unit->post_id);
			$link_title=$unit->post_title;		
			//Display favorites post link, date saved, categories, and delete favorite option
			echo "<tr id='row-".$unit->favorite_id."'><td width = '50%'><li class='".linkIcon($the_link)."'><a href='".$the_link."' alt='link to ".$link_title."' title='". $link_title."' target=_blank >".$link_title."</a></li></td><td width ='10%'>".favoriteformatdate($unit->entry_date).
			"</td><td width = '30%'>".$category_list."</td><td width = '10%' style='text-align: center; padding-top: 6px;'><a href class=deleteFavorite id=".$unit->favorite_id." name = fromFavorites>
			<img src='wp-content/uploads/2014/10/deleteicon.png' height=25 width=25 title='Delete from favorites!'></a></td></tr>";
			$category_list = "";
		} //end for loop
?>
</table>
<?php
	} //end if
	else{
			echo "<span style=''>No favorites for TC items.</span>";
	}

?>
</article><!--/.post-->
<br><span class="post-title">Pinned Learning Module items: </span>
<article id="post-<?php the_ID(); ?>">
<?php
	//Retrieve saved Learning Module items favorites
	$course_units = tc_favorites_get_learning_module_items($current_id);
	//Build a table if there are any saved Learning Module items or display no items
	if(sizeof($course_units) > 0){
	
	?>
	<table class ="basic_table"><tr><th width = '50%'>Link</th><th width ='10%'>Date</th><th width = '30%'>Category</th><th width '10%'>Delete</th></tr>
	<?php
		// List favorite Learning Module item link and display
		foreach ($course_units as $unit){
			$post_category = get_the_category($unit->post_id);
				// Get the categories for favorite
				foreach($post_category as $category){
					$category_list .= $category->cat_name.", ";	
				}
			$category_list = substr($category_list, 0, -2);
			$the_link=get_permalink($unit->post_id);
			$link_title=$unit->post_title;
			//Display favorites post link, date saved, categories, and delete favorite option
			echo "<tr id='row-".$unit->favorite_id."'><td width = '50%'><li class='".linkIcon($the_link)."'><a href='".$the_link."' alt='link to ".$link_title."' title='". $link_title."' target=_blank >".$link_title."</a></li></td><td width ='10%'>".favoriteformatdate($unit->entry_date).
			"</td><td width = '30%'>".$category_list."</td><td width = '10%' style='text-align: center; padding-top: 6px;'><a href class=deleteFavorite id=".$unit->favorite_id." name = fromFavorites>
			<img src='wp-content/uploads/2014/10/deleteicon.png' height=25 width=25 title='Delete from favorites!'></a></td></tr>";
                        $category_list = "";

		} //end for loop
?></table>
<?php
} //end if
	else{	
			echo "<span style=''>No favorites for learning module items.</span>";
	}
?>	
</article><!--/.post-->
<br><span class="post-title">Pinned Resource Links: </span>
<article id="post-<?php the_ID(); ?>">
	<?php
	//Query to retrieve saved Resource Links favorites
 	$resource_links = tc_favorites_get_resource_items($current_id);
	//Build a table if there are any saved Resource Links items or display no items
	if(sizeof($resource_links) > 0){
?>
<table class ="basic_table"><tr><th width = '50%'>Link</th><th width ='10%'>Date</th><th width = '30%'>Category</th><th width '10%'>Delete</th></tr>
<?php
		// List favorite Resource Links link and display
		foreach ($resource_links as $resource_link){
		
			$post_category = get_the_terms($resource_link->post_id, 'simple_link_category');
				// Get the categories for favorite
				foreach($post_category as $category){
					$category_list .= $category->name.", ";	
				}
			
			$category_list = substr($category_list, 0, -2);
			$post_id =$resource_link->post_id;
			$meta_value = get_post_meta( $post_id, 'web_address', true );
			$link_title=$resource_link->post_title;
			//Display favorites post link, date saved, categories, and delete favorite option
			echo "<tr id='row-".$resource_link->favorite_id."'><td width = '50%'><li class='".linkIcon($meta_value)."'><a href='".$meta_value."' alt='link to ".$link_title."' title='". $link_title."' target=_blank >".$link_title."</a></li></td><td width ='10%'>".favoriteformatdate($resource_link->entry_date).
			"</td><td width = '30%'>".$category_list."</td><td width = '10%' style='text-align: center; padding-top: 6px;'><a href class=deleteFavorite id=".$resource_link->favorite_id." name = fromFavorites>
			<img src='wp-content/uploads/2014/10/deleteicon.png'  height=25 width=25 title='Delete from favorites!'></a></td></tr>";

$category_list = "";
} //end for loop
?>
</table>
<?php
} //end if
else{
echo "<span style=''>No favorites for resource links.</span>";
}
?>	
</article><!--/.post-->
<!--<br><span class="post-title">Pinned Discussion items: </span>
 	<article id="post-<?php //the_ID(); ?>">
-->
	<?php
	/*
	//Retrieve saved Discussion items favorites
	$resource_links = tc_favorites_get_discussion_items($current_id);
	//Build a table if there are any saved Discussion items or display no items
	if(sizeof($resource_links) > 0){
	*/
?>
<!--<table class ="basic_table"><tr><th width = '50%'>Link</th><th width ='10%'>Date</th><th width = '30%'>Category</th><th width '10%'>Delete</th></tr>-->
<?php
/*		// List favorite Discussion item link and display
		foreach($resource_links as $resource_link){
			$the_link=get_permalink($resource_link->post_id);
			$link_title=$resource_link->post_title;
			//Display favorites post link, date saved, categories, and delete favorite option
			echo "<tr id='row-".$resource_link->favorite_id."'><td width = '50%'><li class='".linkIcon($the_link)."'><a href='".$the_link."' alt='link to ".$link_title."' title='". $link_title."' target=_blank >".$link_title."</a></li></td><td width ='10%'>".favoriteformatdate($resource_link->entry_date).
			"</td><td width = '30%'>Discussion Forum</td><td width = '10%'  style='text-align: center; padding-top: 6px;'><a href class=deleteFavorite id=".$resource_link->favorite_id." name = fromFavorites>
			<img src='wp-content/uploads/2014/10/deleteicon.png' height=25 width=25 title='Delete from favorites!'></a></td></tr>";
			$category_list = "";
		} //end for loop
*/
?>
<!--</table>-->
<?php
/*
} //end if
else{	
echo "<span style=''>No favorites for discussion items.</span>";
}
*/
?>				
<!-- </article><!--/.post-->
</div>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?> 



