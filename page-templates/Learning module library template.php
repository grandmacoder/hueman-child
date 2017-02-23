<?php
/*
Template Name: Learning module library
*/
/*
 * @package WordPress
/* Displays customize output of links for library modules.
*/
get_header();
?>
<?php
	global $wpdb;
	$stateList=createStateSelectList("");
	$roleList = createTCroleList("");
	$current_user = wp_get_current_user();
	$current_id = $current_user->ID;
	$user_state = get_user_meta($current_id, 'state', true);
	$user_role = get_user_meta($current_id, 'transition_profile_role', true);
	$user_email = $current_user->user_email;
	//category ids
	$category_a = array(153,157,161,165,169,193,197);

	//get category ids for the learning module
	$parent_categories = tc_learning_module_library_get_catergories($category_a);
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
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.cookie.js" type="text/javascript"></script>
<section class="content">
    <div style="padding-left: 30px;">
       <?php the_content();?>
    </div>        
        <div id="nestedAccordion"> <?php
		foreach($parent_categories as $parent_category){
			$parent_id = $parent_category->term_id;
			$parent_term = get_term($parent_id, 'simple_link_category');
			$parent_name = $parent_term->name;
			$logo_path ='/wp-content/originalSiteAssets/images/modules/libraryLogos/'.$parent_name.'.png';
			//print parent header
			echo "<h2 id =".$parent_id."><span class = accordionImg>        
		<img src = '".$logo_path."' height = 100 width = 150>".$parent_name."</span></h2>";
			echo "<div>";
			//get child categories for parent id of module
			$child_categories = tc_learning_module_library_get_child_categories($parent_id);
				
				foreach($child_categories as $child_category){
					$posts = get_objects_in_term($child_category->term_id, 'simple_link_category');
					$child_id = $child_category->term_id;
					$child_term = get_term($child_id, 'simple_link_category');
					$child_name = $child_term->name;
					
					//print child header
                       if (count($posts) <= 0 ){
					   echo "<h3 id =".$child_id.">".$child_name."</h3>";
					   echo "<div>";
					   echo "<li>Currently there are no resources for this category</li>";
					}
					else{  //there are simple links in this category
	                for($i = 0; $i < count($posts); $i++){
					$current_post = get_post($posts[$i]);
					       //start div tag for list of posts
						if($i == 0){
						        echo "<h3 id =".$child_id.">".$child_name."</h3>";
							echo "<div>";
						}
						if ($current_post->post_status =='publish'){
						$current_content = "";
						$current_content = $current_post->post_content;
						$current_title = $current_post->post_title;
						$current_post_id = $current_post->ID;
						$simple_link_url = get_post_meta($posts[$i], 'web_address', true);
						?>
                         <article id="post-<?php the_ID(); ?>">
				        <?php
						 $link_style="";
						 $link_class="";
						 
						 if ($current_id > 0){
						 $link_user_data='data-selectedpostid="'.$current_post_id.'" data-role="'. $user_role.'" data-state="'.$user_state .'" data-email="'.$user_email.'" data-currentuserid="'. $current_id.'"';
						 }
						 else{
						 $link_user_data=""; 
						 }
					
						$endStr=substr($simple_link_url,(strlen($simple_link_url)-5), strlen($simple_link_url));
						//place image for file type extention
						if(strpos($endStr,'doc')){
						$link_style="doc-ico";
                                               } elseif(strpos($endStr,'pdf')){
	                                         $link_style="pdf-ico";
											 $link_class="class='captureLinkClick'";
                                               }elseif(strpos($endStr,'pps')){
                                              $link_style="pps-ico";
											  $link_class="class='captureLinkClick'";
                                              }else{
                                               $link_style="web-ico";
											   $link_class="";
                                               }                                      
						 //get post ids for users favorites for library module
                        $find_post_id = tc_learning_module_library_get_user_favorites($current_id, $posts[$i]);
						$favorite_lib = "";
						$not_pinned = false;
						if($current_id > 0){
						if($find_post_id > 0){
							$favorite_lib = "<img src='wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Already pinned in favorites'>";
						}else{
							$not_pinned = true;
						}
						}
						if($not_pinned){
							?><li class='<?php echo $link_style; ?>'><a <?php echo  $link_class . " " . $link_user_data ;?>  href='<?php echo $simple_link_url; ?>' data-selected_post_title='<?php echo $current_title; ?>' data-selectedpostid='<?php echo $current_post->ID; ?>' data-role='<?php echo $user_role; ?>' data-state='<?php echo $user_state; ?>' data-email='<?php echo $user_email; ?>'  data-currentuserid='<?php echo $current_id; ?>' target=_blank title='<?php echo $current_title; ?>'><?php echo $current_title; ?></a>&nbsp;&nbsp;&nbsp 
							<a href='#' onClick="ga('send', 'event', { eventCategory: 'Favorite', eventAction: 'Pinned', eventLabel: '<?php echo "Resource: ".$current_title." Post ID: ". $posts[$i].", User ID: ".$current_id; ?>'});" class=addfavorite id="<?php echo $posts[$i]; ?>">
							<img src='wp-content/uploads/2014/09/favorite_pin.png' height=15 width=15 title='Pin to favorites!'></a></li><BR>
						<?php
						}else{
						?>
						<li class='<?php echo $link_style; ?>'><a <?php echo  $link_class . " " . $link_user_data ; ?>  href='<?php echo $simple_link_url; ?>' target=_blank title='<?php echo $current_title; ?>' data-selected_post_title='<?php echo $current_title; ?>' data-selectedpostid='<?php echo $current_post->ID; ?>' data-role='<?php echo $user_role; ?>' data-state='<?php echo $user_state; ?>' data-email='<?php echo $user_email; ?>'  data-currentuserid='<?php echo $current_id; ?>'><?php echo $current_title; ?></a>&nbsp;&nbsp;&nbsp<?php echo $favorite_lib; ?>     
						<?php
						}
						if($current_content <> ""){
						echo "<p>".$current_content."</p><BR>";
						}
?>	
</article><!--/.post-->
<?php       }//end output only if published
		}// end for loop
    }//end else there are child items		
    echo "</div>";
    }// end child_categories
echo "</div>";
}//end parent_categories
?>
</div> <!--end accordion -->
</section>
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

		
	

	