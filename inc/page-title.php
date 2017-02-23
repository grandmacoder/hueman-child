<?php
//this is set in the child theme functions file, on the main_query  
global $top_sub_menu; 
global $wpdb;
	
?>
<div class="page-title pad group">
    <?php if ( is_home() ) : ?>
		<h2><?php echo alx_blog_title(); ?></h2>

	<?php elseif ( is_single() ): ?>
		<ul class="meta-single group">
			<li class="category">
			<?php
			if ($top_sub_menu <> ""){
			wp_nav_menu( array( 'theme_location' => $top_sub_menu ) ); 
			}
			else{
			$base_url = get_bloginfo('wpurl');
		        $current_user = wp_get_current_user();
			$current_id = $current_user->ID;
			$postid = get_the_ID(); 
			$queryStr = "SELECT post_id FROM wp_favorites WHERE user_id = ".$current_id." AND post_id = ".$postid;
                        $find_post_id = $wpdb->get_var($queryStr);
			$post_title = $wpdb->get_var("SELECT post_title FROM wp_posts WHERE ID = ".$postid."");
                        $favorite_lib = "";
			if($current_id > 0){
			if($find_post_id > 0){
	                $favorite_lib = "<div style='padding-left: 600px;'><img src='$base_url/wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Already pinned in favorites'><span style='color:#2295de; font-weight: bold;'> Pinned in your favorites</a></span></div>";
						?>
						<div style='padding-left: 600px;'><img src='<?php echo $base_url; ?>/wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Already pinned in favorites'><span style='color:#2295de; font-weight: bold;'> Pinned in your favorites</a></span></div>
						<?php
                        }else{
			$favorite_lib = "<div style='padding-left: 600px;'><a href='#' class=addfavorite id=".$postid." name = fromPost><img src='$base_url/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'> Pin as favorite</a></span></div>";	
			?>
			<div style='padding-left: 600px;'><a href='#' class=addfavorite  onClick="ga('send', 'event', { eventCategory: 'Favorite', eventAction: 'Pinned', eventLabel: '<?php echo "Title: ".$post_title; ?>'});" id=<?php echo $postid; ?> name = fromPost><img src='<?php echo $base_url ?>/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'> Pin as favorite</a></span></div>
			<?php
			}
			}
			else{
			$favorite_lib = "<div style='padding-left: 600px;'><img src='$base_url/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'><abbr title='Log in to pin favorite items!' rel='tooltip'>Pin to favorites!</abbr></span></div>";
			?>
			<div style='padding-left: 600px;'><img src='<?php echo $base_url; ?>/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'><abbr title='Log in to pin favorite items!' rel='tooltip'>Pin to favorites!</abbr></span></div>
			<?php
			}
			//echo $favorite_lib;
			}
			?>
			</li>
       <?php if ( comments_open() && ( ot_get_option( 'comment-count' ) != 'off' ) ): ?>
			<li class="comments"><a href="<?php comments_link(); ?>"><i class="fa fa-comments-o"></i><?php comments_number( '0', '1', '%' ); ?></a></li>
			<?php endif; ?>
		</ul>
		
	<?php elseif ( is_page() ): ?>
		<ul class="meta-single group">
			<li class="category">
			<?php
			if ($top_sub_menu <> ""){
                        wp_nav_menu( array( 'theme_location' => $top_sub_menu ) ); 
			}
			else{
			$base_url = get_bloginfo('wpurl');
		        $current_user = wp_get_current_user();
			$current_id = $current_user->ID;
			$postid = get_the_ID(); 
			$queryStr = "SELECT post_id FROM wp_favorites WHERE user_id = ".$current_id." AND post_id = ".$postid;
                        $find_post_id = $wpdb->get_var($queryStr);
			$post_title = $wpdb->get_var("SELECT post_title FROM wp_posts WHERE ID = ".$postid."");
			
                        $favorite_lib = "";
			if($current_id > 0){
			if($find_post_id > 0){
	                $favorite_lib = "<div style='padding-left: 600px;'><img src='$base_url/wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Already pinned in favorites'><span style='color:#2295de; font-weight: bold;'> Pinned in your favorites</a></span></div>";
                        ?>
						<div style='padding-left: 600px;'><img src='<?php echo $base_url;?>/wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Already pinned in favorites'><span style='color:#2295de; font-weight: bold;'> Pinned in your favorites</a></span></div>
						<?php
						}else{
			$favorite_lib = "<div style='padding-left: 600px;'><a href='#' class=addfavorite id=".$postid." name = fromPost><img src='$base_url/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'> Pin as favorite</a></span></div>";	
			?>
			<div style='padding-left: 600px;'><a href='#' onClick="ga('send', 'event', { eventCategory: 'Favorite', eventAction: 'Pinned', eventLabel: '<?php echo "Post title: ".$post_title." Post ID: ".$postid.", User ID: ".$current_id; ?>'});" class=addfavorite id=<?php echo $postid;?> name = fromPost><img src='<?php echo $base_url; ?>/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'> Pin as favorite</a></span></div>
			<?php
			}
			}
			else{
			$favorite_lib = "<div style='padding-left: 600px;'><img src='$base_url/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'><abbr title='Log in to pin favorite items!' rel='tooltip'>Pin to favorites!</abbr></span></div>";
			?>
			<div style='padding-left: 600px;'><img src='<?php echo $base_url; ?>/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'><abbr title='Log in to pin favorite items!' rel='tooltip'>Pin to favorites!</abbr></span></div>
			<?php
			}
			//echo $favorite_lib . $postid;
			}
			?>
			</li>
		</ul>

	   <?php 
	   if ($top_sub_menu == ""){
	   echo "<h3>". alx_page_title() ."</h3>";
	   }	   
	   ?>
   <?php elseif ( is_search() ): ?>
		<p class=post-title>
			<?php if ( have_posts() ): ?><i class="fa fa-search"></i><?php endif; ?>
			<?php if ( !have_posts() ): ?><i class="fa fa-exclamation-circle"></i><?php endif; ?>
			<?php $search_count = 0; 							
			$search = new WP_Query("s=$s & showposts=-1"); 
			if($search->have_posts()) : while($search->have_posts()) : $search->the_post(); $search_count++; 
			endwhile; endif; 
			$searchResultMsg='Search results for &quot;'. get_search_query() .'&quot;'; 
			echo $search_count;?> <?php _e($searchResultMsg,'hueman'); ?></p>
		
	<?php elseif ( is_404() ): ?>
		<!-- <h1><i class="fa fa-exclamation-circle"></i><?php _e('Error 404.','hueman'); ?> <span><?php _e('Page not found!','hueman'); ?></span></h3> -->
		<h1><i class="fa fa-exclamation-circle"></i><span><?php _e('Page not found!','hueman'); ?></span></h3>
		
	<?php elseif ( is_author() ): ?>
		<?php $author = get_userdata( get_query_var('author') );?>
		<h3><i class="fa fa-user"></i><?php _e('Author:','hueman'); ?> <span><?php echo $author->display_name;?></span></h3>
		
	<?php elseif ( is_category() ): ?>
		<!-- <h3><i class="fa fa-folder-open"></i> --><?php // _e('Category:','hueman'); ?><!-- <span>--><?php // echo single_cat_title('', false); ?><!-- </span></h3> -->
	<?php elseif ( is_tag() ): ?>
		<h1><i class="fa fa-tags"></i><?php _e('Tagged:','hueman'); ?> <span><?php echo single_tag_title('', false); ?></span></h3>
		
	<?php elseif ( is_day() ): ?>
		<h3><i class="fa fa-calendar"></i><?php _e('Daily Archive:','hueman'); ?> <span><?php echo get_the_time('F j, Y'); ?></span></h3>
		
	<?php elseif ( is_month() ): ?>
		<h3><i class="fa fa-calendar"></i><?php _e('Monthly Archive:','hueman'); ?> <span><?php echo get_the_time('F Y'); ?></span></h3>
			
	<?php elseif ( is_year() ): ?>
		<h3><i class="fa fa-calendar"></i><?php _e('Yearly Archive:','hueman'); ?> <span><?php echo get_the_time('Y'); ?></span></h3>
	
	<?php else: ?>
		<h3><?php the_title(); ?></h3>
	
	<?php endif; ?>

</div><!--/.page-title-->