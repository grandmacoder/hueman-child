<?php
/*
Template Name: tc presentations template
*/
/*
 * @package WordPress
/* Displays customize output of links for library modules.
*/
	get_header();
?>
		<?php
		$stateList=createStateSelectList("");
		$roleList = createTCroleList("");
		$current_user = wp_get_current_user();
		$current_id = $current_user->ID;
		if (!$current_id){
		$current_id =0;
		}
		$base_url = get_bloginfo('wpurl');
		$current_id = $current_user->ID;
?>
<style>
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
    #dialog-form{display:none; font-size: 72.5%; }
  </style>
  
<script src='https://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.cookie.js" type="text/javascript"></script>
<section class="content">
<div class="template_content">
<br>
<?php the_content(); 
		?>
		<div id='nestedAccordion'>
		<?php
		//get presentation simple link posts 
		$presentations_simple_links = tc_tc_presentations_template_get_simple_links();
		foreach($presentations_simple_links as $parent_simple_link){
			$parent_id = $parent_simple_link->term_id;
			$parent_term = get_term($parent_id, 'simple_link_category');
			$parent_name = $parent_term->name;
			//print parent header
			?>
			<h3 id =<?php echo $parent_id; ?>><?php echo $parent_name; ?></h3>
			<div>
			<?php
			$posts = get_objects_in_term($parent_id, 'simple_link_category');		
			 //simple links in this category     
				for($i = 0; $i < count($posts); $i++){ 
					$current_post = get_post($posts[$i]);
					if ($current_post->post_status =='publish'){
						$current_content = "";
						$current_content = $current_post->post_content;
						$current_title = $current_post->post_title;
						$simple_link_url = get_post_meta($posts[$i], 'web_address', true);
						$link_style="";
						?><article id='post-<?php echo $current_post->ID; ?>'>
						<?php
						$endStr=substr($simple_link_url,(strlen($simple_link_url)-5), strlen($simple_link_url));
						//place image for file type extention
						if(strpos($endStr,'doc')){ 

						$link_style="doc-ico";
                                }elseif(strpos($endStr,'pdf')){
	                                        $link_style="pdf-ico";
                                }elseif(strpos($endStr,'pps')){
                                               $link_style="pps-ico";
                                    }else{
                                               $link_style="web-ico";
                                    }
						//get post id if user has pinned this post simple link in their pinned items (favorites)
                        $find_post_id = tc_learning_module_library_get_user_favorites($current_id,$current_post->ID);

						$favorite_lib = "";
						$not_pinned = false;
						if($current_id > 0){
						if($find_post_id > 0){
							$favorite_lib = "<img src='wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Already pinned in favorites'></li><BR>";

						}else{
						$not_pinned = true;
							//$favorite_lib = "<a  href='#' class=addfavorite id=".$current_post->ID."><img src='wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'></a></li><BR>";	
						}
						}
						if($not_pinned){
							?><li class='<?php echo $link_style; ?>'><a href='<?php echo $simple_link_url; ?>' target=_blank title='<?php echo $current_title; ?>'><?php echo $current_title; ?></a>&nbsp;&nbsp;&nbsp 
							<a href='#' onClick="ga('send', 'event', { eventCategory: 'Favorite', eventAction: 'Pinned', eventLabel: '<?php echo "Resource: ".$current_title." Post ID: ". $posts[$i].", User ID: ".$current_id; ?>'});" class=addfavorite id="<?php echo $posts[$i]; ?>">
							<img src='wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'></a></li><BR>
						<?php
						}else{
						echo "<li class='".$link_style."'><a href='".$simple_link_url."' target=_blank title='". $current_title ."' data-selectedpostid='".$current_post->ID."'  data-currentuserid='".$current_id."'>".$current_title."</a>&nbsp;&nbsp;&nbsp".$favorite_lib;     
						}
						echo "</article>"; //end post 
			}// end if
		}// end for loop
	echo "</div>";	
} //end foreach		
echo "</div>";	
?>

<?php //echo $content;?>

</div>
</section><!--/.content-->
<div id="dialog-form" title="">
  <p class="validateTips">Please enter the following information before accessing resources on our site. Thank you.</p>
 <form>
    <fieldset>
      <label for="state">Your State</label>
      <select name="state" id="state" >
      <?php echo $stateList;?>
      </select>
      <label for="email">Your Email</label>
      <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all">
      <label for="role">Your Role</label>
      <select  name="role" id="role">
     <?php echo $roleList;?>
      </select>
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
	
	