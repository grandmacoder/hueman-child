<?php
/*
Template Name: webinars
*/
/**
 *
 * @package WordPress
 */
/* Displays customize output of links for a category.
*/
/* Get roster if there are any if not show the create roster form
*/
?>

<?php get_header(); ?>
<style>
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
    #dialog-form{display:none; font-size: 72.5%; }
	img.attachment-post-thumbnail.wp-post-image {height: 212px important!;width: 140px important!;}
  </style>
  
<script src='https://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.21/themes/base/jquery-ui.css" type="text/css" media="all" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.cookie.js" type="text/javascript"></script>
	<?php
	 if (isset($_GET['cat_ID']) && is_numeric($_GET['cat_ID']) ){
	 $cat_id =$_GET['cat_ID'];
	 }
	 elseif (isset($_GET['cat_ID']) && !is_numeric($_GET['cat_ID']) ){
	 die("invalid input");
	 }
	 else{
		 if (get_the_ID() == 9069){
	      $cat_id = 341; 
		 }
		 else{
			$cat_id=431;  
		 }
	 }
	 $args1 = array(
	'posts_per_page'   => -1,
	'category'         => $cat_id,
	'orderby'          => 'id',
	'order'            => 'DESC',
	'post_type'		   => array('post','webinar'),
	'post_status'      => 'publish',
	'suppress_filters' => true
	 ); 

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
    <section class="content">
    <div class="template_content">
    <h3><?php echo get_the_title();?></h3>
	<BR>
	 <div id="nocookiedialog" title="Attention!" style="display:none;">
             Your browser is not accepting cookies. Therefore you will be prompted each time you download a resource. To avoid this, enable cookies and restart your browser. Thank you.
    </div>
    <br>
    <?php the_content();?>	
	<select id="sort_assessments">
	<option value="Select">Select</option>
    <option value="(TITLE) A-Z">(TITLE) A-Z</option>
	<option value="(TITLE) Z-A">(TITLE) Z-A</option>
	</select>
	<br><br>
	<?php
	$myposts = get_posts( $args1 );
	?> 
	<ul class="js-sort-assessments">
	<?php
	foreach ( $myposts as $post ) : setup_postdata( $post ); 
	$postid = get_the_ID();
	$child_posts = tc_webinars_get_child_posts($postid);
?>
	<li class="assessment_sort" data-name="<?php the_title(); ?>">
	<article id="post-<?php the_ID(); ?>">
	 <div class="post-inner post-hover">
        <?php $webinar = get_post_meta($postid, 'link_to_webinar', true); ?>
	  	<table class=bare_table> <tr ><td width=30%> 
	    <a  href='<?php the_permalink();?>' title='Go to <?php the_title(); ?>' alt='Go to <?php the_title(); ?>'><?php the_post_thumbnail(); ?> </a>
        </td>
	    <td > 
		<a class='post-title' href='<?php the_permalink();?>'><?php the_title(); ?></a></span><!--/.post-title-->
		<?php the_content();
		$loopcount = 0;	
		foreach($child_posts as $post){
				$guid = $post->guid;
	            $post_title = $post->post_title;
	            $linkStyle = linkIcon($guid);
			    $linkPostID = $post->post_id;
                $currentUserID = get_current_user_id();
                if ($currentUserID > 0){
						$current_user=get_userdata($currentUserID);
						$userEmail = $current_user->user_email;
						$userState=get_user_meta($currentUserID, 'state', true );
						$userRole=get_user_meta($currentUserID, 'transition_profile_role', true );
				       }
						else{
						$currentUserID=0;
						$userEmail = "";
						$userState="";
						$userRole="";
						}
				if($loopcount < 1 AND $linkPostID > 0){
					echo " <br><strong>Download resources for this Webinar below</strong><br>";	
				}
				$loopcount++;
					
	  ?>
				<li class="<?php echo $linkStyle;?>"><a class="captureLinkClick" data-selectedpostid="<?php echo $linkPostID; ?>" data-role="<?php echo $userRole;?>" data-state="<?php echo $userState;?>" data-email="<?php echo $userEmail;?>" data-selected_post_title="<?php echo $post_title;?>"  data-currentuserid="<?php echo $currentUserID;?>"  href="<?php echo $guid; ?>" title='<?php echo $post_title; ?>' alt='<?php echo $post_title; ?>' target='_blank'>
				<?php echo $post_title; ?>
				</a></li><br>
			<?php																	
			} //end foreach
		?> 
        </td></tr></table>      

	</div>
</article><!--/.post-->
</li>
<?php endforeach;?>
</ul>
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

