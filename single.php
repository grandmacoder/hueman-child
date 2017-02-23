<?php get_header(); ?>
<section class="content">
	<?php get_template_part('inc/page-title'); ?>
	<div class="pad group">
		<?php while ( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?> >	
				<div class="post-inner group">
					<span class="post-title"><?php the_title(); ?></span>
					<p class="post-byline"><?php //_e('by','hueman'); ?> <?php //the_author_posts_link(); ?> <?php //the_time(get_option('date_format')); ?></p>
					<?php if( get_post_format() ) { get_template_part('inc/post-formats'); } ?>
					<div class="entry">	
						<div class="entry-inner">
						 <?php if ( get_post_type() == 'assessment_review'){ 
					               $average_rating = get_post_meta($id, 'crfp-average-rating', true); 
						       $numRatings = tc_reviews_get_number_reviews_per_post($id);
						 ?>
							<?php if (get_post_meta($id, 'assessment_image_file', true)){ ?>
							       <?php 
								$image_post_id = get_meta_value_by_key($id, 'assessment_image_file');
								$imageData = unserialize($image_post_data);
					
								?>
								<div style='float: left; margin-right: 10px; margin-bottom: 20px; margin-top: 20px; width: 200px;'>
								<img src='<?php echo get_the_guid($image_post_id); ?>' alt='' style='width: 180px; height: auto;'/> <!-- IMAGE FOR POST --></div>
								&nbsp;	
								<!---------------ratings----------->
							         <?php }	
								if ($average_rating > 0){  ?>
								<?php get_post_meta($id, 'crfp-average-rating', true);?>
								Average Rating&nbsp;&nbsp;<div  class='crfp-rating crfp-rating-<?php echo $average_rating; ?>'></div><span style="font-size:12px;">(<?php echo "  " .$numRatings; if ($numRatings==1){echo " rating";}else{echo " ratings";}?>)</span>
								<?php } 
								else{
								echo "<p style='color: #c2132f; font-size: 12px;'>No ratings yet! Be the first to rate this assessment!</p>";
								}//end if there are any ratings
								//get the author and year
								if (get_post_meta($id, 'assessment_author', true)){ ?>
									<p><strong> <?php echo get_post_meta($id, 'assessment_author', true)." (".get_post_meta($id, 'assessment_year', true); ?>) </strong></p>
								<?php } // end if there is an author 
								if(get_post_meta($id, 'assessment_publisher_info', true)) { ?>
								            <p> <?php echo get_post_meta($id, 'assessment_publisher_info', true); ?> </p>
								<?php }// end if there is a publisher
								if(get_post_meta($id, 'assessment_url', true)) { ?>
									<p><a class="newsitem" target=_blank href="<?php  echo get_post_meta($id, 'assessment_url', true); ?>" rel="bookmark" title="<?php  echo get_post_meta($id, 'assessment_url', true); ?>" >Get this assessment!</a></p>
								<?php }//end if there is a link to the resource
								if(get_post_meta($id, 'free_resource', true)) { ?>
								           <p style="color:#c2132f;"> Free Resource! </p>
								<?php } //end the if free resource?>
								<p><strong>Description</strong>
								      <?php the_content();  ?>
								<?php echo "<br><br>";
								}//end if this is an assessment review
							elseif ( get_post_type() == 'tip'){ 
								 $average_rating = get_post_meta($id, 'crfp-average-rating', true); 
								 $numRatings = tc_reviews_get_number_reviews_per_post($id);
								 $filesString="";
								 $sContact1info="";
								 $sContact2info="";
								 $sContact3info="";
								 $sWeblink1info="";
								 $sWeblink2info="";
								 ?>
								<?php if (get_post_meta($id, 'tip_related_files')){ 
							        //get the related files into a string
								$related_file_ids = get_multiple_meta_values_by_key($id, 'tip_related_files');
						                if ($related_file_ids <> 0){
								$i=0;
							          foreach ($related_file_ids as $file_id){
								  $itemNum = $i+1;
								  if ($i > 0){
								  $filesString.="<br><span style='padding-left: 40px;'>";
								  }
								  $filesString.= "<a href='". get_the_guid($file_id->meta_value) ."' target='_blank'>View/download support files.</a>(". $itemNum . ")"; 
								  if ($i > 0){
								   $filesString.= "</span>";
								  }
								  $i++;
								  }
								}
								//get the contact information
								if (get_post_meta($id, 'tip_contact_name_1', true)){ 
								$sContact1info=get_post_meta($id, 'tip_contact_name_1', true);
									   if (get_post_meta($id, 'tip_contact_name_1', true)){
									   $sContact1info="<a href='mailto:". get_post_meta($id, 'tip_contact_email_1',true) ."'>". get_post_meta($id, 'tip_contact_name_1', true)."</a>";
									   }
									   if (get_post_meta($id, 'tip_contact_state_1', true)){
									   $sContact1info.= ",  " . get_post_meta($id, 'tip_contact_state_1', true); 
									   }
								}
								if (get_post_meta($id, 'tip_contact_name_2', true)){ 
								$sContact2info=get_post_meta($id, 'tip_contact_name_2', true);
									   if (get_post_meta($id, 'tip_contact_name_2', true)){
									   $sContact2info="<a href='mailto:". get_post_meta($id, 'tip_contact_email_2',true)."'>". get_post_meta($id, 'tip_contact_name_2', true)."</a>";
									   }
									   if (get_post_meta($id, 'tip_contact_state_2', true)){
									   $sContact2info.=",  " . get_post_meta($id, 'tip_contact_state_2', true); 
									   }
								}
								if (get_post_meta($id, 'tip_contact_name_3', true)){ 
								$sContact3info=get_post_meta($id, 'tip_contact_name_3', true);
									   if (get_post_meta($id, 'tip_contact_name_3', true)){
									   $sContact3info="<a href='mailto:". get_post_meta($id, 'tip_contact_email_3',true) ."'>". get_post_meta($id, 'tip_contact_name_3', true)."</a>";
									   }
									   if (get_post_meta($id, 'tip_contact_state_3', true)){
									   $sContact3info.=",  " . get_post_meta($id, 'tip_contact_state_3', true); 
									   }
								}
								//get the weblink information
								if (get_post_meta($id, 'tip_web_link_1', true)){ 
								$sWeblink1info=get_post_meta($id, 'tip_web_link_1', true);
									   if (get_post_meta($id, 'tip_web_link_1_title', true)){
									   $sWeblink1info="<a href='". get_post_meta($id, 'tip_web_link_1', true) ."' target=_blank>".get_post_meta($id, 'tip_web_link_1_title', true)."</a>";
									   }
								}
								if (get_post_meta($id, 'tip_web_link_2', true)){ 
								$sWeblink2info=get_post_meta($id, 'tip_web_link_2', true);
									   if (get_post_meta($id, 'tip_web_link_2_title', true)){
									   $sWeblink2info="<a href='". get_post_meta($id, 'tip_web_link_2', true) ."' target=_blank>".get_post_meta($id, 'tip_web_link_2_title', true)."</a>";
									   }
								}
								 ?>
								<div style='float: left; margin-right: 10px; margin-bottom: 70px; margin-top: 20px; width: 200px;'>
								<img src="/wp-content/uploads/2014/06/tip-icon.gif" alt="tip icon" style='width: 110px; height: auto;'/> <!-- DEFAULT IMAGE FOR POST -->
								</div>
								&nbsp;	
								<!---------------ratings----------->
							         <?php } ?>
															 
								<?php if ($average_rating > 0){  ?>
								<?php get_post_meta($id, 'crfp-average-rating', true);?>
								Average Rating&nbsp;&nbsp;<div  class='crfp-rating crfp-rating-<?php echo $average_rating; ?>'></div><span style="font-size:12px;">(<?php echo "  " .$numRatings; if ($numRatings==1){echo " rating";}else{echo " ratings;";}?>)</span>
								<?php } 
								else{
								echo "<p style='color: #c2132f; font-size: 12px;'>No ratings yet! Be the first to rate this tip!</p>";
								}//end if there are any ratings
								//output websites
								if ( $sWeblink1info<> ""){
								echo "<p>Websites: " . $sWeblink1info ."<br>";
								}
								if ( $sWeblink2info<> ""){
								echo "<span style='padding-left: 67px;'>" . $sWeblink2info ."</span>";
								}
								echo "</p>";
								//output files
								if ($filesString <> ""){
								 echo "<p>Files: ". $filesString ."</p>";
								}
								//output contacts
								if ($sContact1info <> "" ){
								 echo "<p>Contacts : ". $sContact1info;
									if ($sContact2info<>""){
									echo "<br><span style='padding-left: 70px;'>" . $sContact2info ."</span>";
									}
									if ($sContact3info<>""){
									echo "<br><span style='padding-left: 55px;'>&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp" . $sContact3info."</span>";
									}
								echo"</p>";
								}
								
								?> 
						
						        <p><strong>Description</strong>
							      <?php the_content();  ?>
							<?php echo "<br><br>";
							
							}
							else{ //regular post output the content
							 the_content();
							}
							?>
						       <?php //wp_link_pages(array('before'=>'<div class="post-pages">'.__('Pages:','hueman'),'after'=>'</div>')); ?>
						</div>
						<div class="clear"></div>				
					</div><!--/.entry-->
				</div><!--/.post-inner-->	
			</article><!--/.post-->				
		<?php endwhile; ?>
		
		
		<?php //the_tags('<p class="post-tags"><span>'.__('Tags:','hueman').'</span> ','','</p>'); ?>
		
		<?php if ( ( ot_get_option( 'author-bio' ) != 'off' ) && get_the_author_meta( 'description' ) ): ?>
			<div class="author-bio">
				<div class="bio-avatar"><?php echo get_avatar(get_the_author_meta('user_email'),'128'); ?></div>
				<p class="bio-name"><?php the_author_meta('display_name'); ?></p>
				<p class="bio-desc"><?php the_author_meta('description'); ?></p>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
		<?php if ( ot_get_option( 'post-nav' ) == 'content') { get_template_part('inc/post-nav'); } ?>
		<?php if ( ot_get_option( 'related-posts' ) != '1' ) {
	        // $post_type =  get_post_type(get_the_ID());
           //if ( $post_type == 'post' || $post_type  == 'tc_materials' ||  $post_type == 'webinar'){
		   //get_template_part('inc/related-posts');
		//}
		}
		?>
		<?php comments_template('/comments.php',true); ?>
		
	</div><!--/.pad-->
	
</section><!--/.content-->

<?php
if ( get_post_type() != 'comment_topic'){ 
get_sidebar(); 
}
?>

<?php get_footer(); ?>