<?php
/*
Template Name: tc resources 
*/
/*
 * @package WordPress
/* Displays customize output of links for library modules.
*/
	get_header();
?>
		<?php
		global $wpdb;
		$current_user = wp_get_current_user();
		$base_url = get_bloginfo('wpurl');
		$current_id = $current_user->ID;
		$postid = get_the_ID(); 
		$find_post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM wp_favorites WHERE user_id = %d AND post_id = %d",$current_id,$postid )) ;
                $favorite_lib = "";
		if($current_id > 0){
			if($find_post_id > 0){
	                $favorite_lib = "<div style='padding-left: 600px;'><img src='$base_url/wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Already pinned in favorites'><span style='color:#2295de; font-weight: bold;'> Pinned in your favorites</a></span></div>";
                        }else{
			$favorite_lib = "<div style='padding-left: 600px;'><a href='#' class=addfavorite id=".$postid." name = fromPost><img src='$base_url/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'> Pin as favorite</a></span></div>";	
			}
			}
			else{
			$favorite_lib = "<div style='padding-left: 600px;'><img src='$base_url/wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'><span style='color:#2295de; font-weight: bold;'><abbr title='Log in to pin favorite items!' rel='tooltip'>Pin to favorites!</abbr></span></div>";
			}
			echo $favorite_lib;

	$content .= "<div id='nestedAccordion'>";
		//get presentation simple links 
		$presentations_simple_links = tc_resources_template_get_simple_links();
		foreach($presentations_simple_links as $parent_simple_link){
			
			$parent_id = $parent_simple_link->term_id;
			$parent_term = get_term($parent_id, 'simple_link_category');
			$parent_name = $parent_term->name;
			
			//print parent header
			$content .= "<h3 id =".$parent_id.">".$parent_name."</h3>";
			$content .= "<div>";
			$posts = get_objects_in_term($parent_id, 'simple_link_category');
					
			 //simple links in this category     
				for($i = 0; $i < count($posts); $i++){ 
				$promptUser=0;
					$current_post = get_post($posts[$i]);
					if ($current_post->post_status =='publish'){
						$current_content = "";
						$current_content = $current_post->post_content;
						$current_title = $current_post->post_title;
						$simple_link_url = get_post_meta($posts[$i], 'web_address', true);
						$link_style="";
						$content .= "<article id='post-".$current_post->ID."'>";
						$endStr=substr($simple_link_url,(strlen($simple_link_url)-5), strlen($simple_link_url));
						//place image for file type extention
						if(strpos($endStr,'doc')){
                                                   $link_style="doc-ico";
						   $promptUser=1;
                                                }    
						elseif(strpos($endStr,'pdf')){
	                                            $link_style="pdf-ico";
						    $promptUser=1;
                                                }elseif(strpos($endStr,'pps')){
                                                     $link_style="pps-ico";
						     $promptUser=1;
						}else{
						    $link_style="web-ico";
						}
						//find post id to see if user has any favorites saved for resource links 
                        $find_post_id = tc_resources_template_get_pinned_resources($current_id,$posts[$i] );
						$favorite_lib = "";
						if($current_id > 0){
						if($find_post_id > 0){
	                                               $favorite_lib = "<img src='wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Already pinned in favorites'></li><BR>";

						}else{
							$favorite_lib = "<a href='#' class=addfavorite id=".$posts[$i]."><img src='wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'></a></li><BR>";	
						}
						}
						if ($promptUser == 1){ //prompt for an email for non logged in users
						$content .= "<li class='".$link_style."'><a class='captureLinkClick' href='".$simple_link_url."' target=_blank title='". $current_title ."' data-selectedpostid='".$posts[$i]."'  data-currentuserid='".$current_id."'>".$current_title."</a>&nbsp;&nbsp;&nbsp".$favorite_lib;     
						}
						else{
						$content .= "<li class='".$link_style."'><a href='".$simple_link_url."' target=_blank title='". $current_title ."'>".$current_title."</a>&nbsp;&nbsp;&nbsp".$favorite_lib;
						}
						$content .= "</article>"; //end post 
			}// end if
		}// end for loop
	$content .= "</div>";	
} //end foreach		
$content .= "</div>";	
?>
<script src='https://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
<script src="<?php echo get_stylesheet_directory_uri();  ?>/js/jquery.alerts.js" type="text/javascript"></script>
<script src="<?php echo get_stylesheet_directory_uri();  ?>/js/jquery.cookie.js" type="text/javascript"></script>
<link rel='stylesheet' href='https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css'>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/jquery.alerts.css?v=<?php echo time(); ?>" media="screen" />

<section class="content">
<div class="template_content">
<h3>Websites</h3>
<br>
<?php the_content(); ?>
<?php echo $content;?>
</div>
</section><!--/.content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?> 