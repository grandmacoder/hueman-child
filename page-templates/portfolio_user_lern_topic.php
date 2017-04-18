<?php
/*
Template Name: portfolio lern topics
*/
/**
 * shows the LERN topics that a user is signed up for 
 *
 * @package WordPress
 */

/* Displays customize output of links for a category.
*/
/* Get roster if there are any if not show the create roster form
*/
get_header();
?>
<style>
.biggerfont{font-size: 16px;}
a:link {color:#537c1b; font-weight: 500;}
a:visited {color:#537c1b; font-weight: 500;}
a:hover {color:#3b8dbd; font-weight: 500; text-decoration:underline; }
#activityContentSection{display:none;}
</style>
<?php
        $current_user = wp_get_current_user();
		$userID = $current_user->ID;
		$user_state = get_user_meta( $userID, 'state', true);
	    $user_role = get_user_meta( $userID, 'transition_profile_role', true);
	    $user_email = $current_user->user_email;
		$numCourses = 0;
		$hasLERNmsg="";
		if (isset($_GET['courseid']) && !is_numeric($_GET['courseid'])){
		header("Location: /");
		exit();
		}
		else{
		$courseID = $_GET['courseid'];
		}
		//get the extra values for the course --- need to automate this later.
		//course logo image, course start page, entry id and wid for video
		//will get all course data for the user, we weed it out in the foreach
	    $courseData = WPCW_users_getUserCourseList($userID);
        if ($courseData){
         foreach ($courseData as $courseDataItem) {   //if it is the module id passed in and there has been some progress
		 if ($courseDataItem->course_id == $courseID && $courseDataItem->course_progress > 0){
		   $courseDataItem->course_title;
		   $percentComplete=$courseDataItem->course_progress;
		  //get the course extra fields
		  $courseExtraRow=tc_portfolio_user_module_list_get_course_extra_fields($courseDataItem->course_id);
          $progressBar.="<div>";
          //course activity text area answers
	       $activityAnswers = tc_portfolio_module_get_activity_answers($userID, $courseDataItem->course_id);
		   $num_activities = sizeof($activityAnswers);
           $start_date = $courseExtraRow->start_date;
           $today =date('Y-m-d 00:00:00');
			  $formatdate=date_create($start_date);
			  $startdate =date_format($formatdate,"M. d, Y");
			  $entry_id = $courseExtraRow->entry_id;
			  $wid = $courseExtraRow->wid;
			  $hasLERNmsg="<h6>Progress</h6>";
			  $progressBar .= "<div style='float:left;'><img src='". $courseExtraRow->course_logo_path."' height='100' width='200'></div><div style='clear:both;'></div>";
			//if the start date for the lern has not occurred yet, show the logo and the start date
			if ($start_date > $today){
			$progressBar .= "<p>This course has not started yet. It will be available on ".   $startdate .".</p>";
			//otherwise show the logo/completion/and details
			}
			else{
			    if ( $courseDataItem->course_progress > 0){
				$progressBar.= "<div style='width: 50%;'>".WPCW_stats_convertPercentageToBar($courseDataItem->course_progress, $courseDataItem->course_title) ."</div>";
				}
				if ($courseDataItem->course_progress  < 100){
				$progressBar.="<span class = biggerfont>You are " . $courseDataItem->course_progress ."% finished.</span><a href='". $courseExtraRow->course_start_page_path ."' title='continue working on lern topic'> Continue working on this lern topic.</a>";
				}
				else if ($courseDataItem->course_progress == 100){
				$progressBar.="<h6>Your LERN activities <a class='print_summary_sheet' href='#' data-courseid='".$courseDataItem->course_id."' data-userid='".$userID."' title='View and print summary sheet'><img src='wp-content/uploads/2015/05/summary_sheet_icon.png' height='30' width='20'></a></h6>";
				}
			}
            $progressBar.="</div>";
			$numCourses++;
		  //output the activities and answers if there are any
		   if ($num_activities > 0){
		   foreach ($activityAnswers as $item){
			$activityContent.="<p><strong>" . $item->description ."<br>Your response:</strong> " . $item->activity_value."<br><br></p>";
			}
		   }
		   else{
		   $activityContent.="<p>There were no activities with saved responses for ". $courseDataItem->course_title ."</p>";
		   }//end if activities
		   //get the simple links into a google analytic tracker list
		           $category =get_term_by('name', $courseDataItem->course_title, 'simple_link_category');
		           $posts = get_objects_in_term($category->term_id, 'simple_link_category');
	               for($i = 0; $i < count($posts); $i++){
					$current_post = get_post($posts[$i]);
					       //start div tag for list of posts
						if ($current_post->post_status =='publish'){
						$current_content = "";
						$current_content = $current_post->post_content;
						$current_title = $current_post->post_title;
						$current_post_id = $current_post->ID;
						$simple_link_url = get_post_meta($posts[$i], 'web_address', true);
						 $link_style="";
						 $link_class="";
						 if ($userID > 0){
						 $link_user_data='data-selectedpostid="'.$current_post_id.'" data-role="'. $user_role.'" data-state="'.$user_state .'" data-email="'.$user_email.'" data-currentuserid="'. $userID.'"';
						 }
						 else{
						 $link_user_data=""; 
						 }
					    $endStr=substr($simple_link_url,(strlen($simple_link_url)-5), strlen($simple_link_url));
						//place image for file type extention
						if(strpos($endStr,'doc')){
						$link_style="<i class='fa fa-file-text-o fa- fa-icon-blue'></i>"; 
                        } elseif(strpos($endStr,'pdf')){
	                      $link_style="<i class='fa fa-file-pdf-o fa- fa-icon-blue'></i>"; 
						  $link_class="class='captureLinkClick'";
                          }elseif(strpos($endStr,'pps')){
                            $link_style="<i class='fa fa-file-powerpoint-o fa- fa-icon-blue'></i>"; 
							$link_class="class='captureLinkClick'";
                            }else{
                                $link_style="<i class='fa fa-globe fa- fa-icon-blue'></i>";
								$link_class="";
                                }                                      
						$GA_simple_links_list.=$link_style."<a ". $link_class . " " . $link_user_data ." href='". $simple_link_url . "' target=_blank title='" . $current_title . "' data-selected_post_title='" . $current_title ." data-selectedpostid='".$current_post_id ."' data-role='". $user_role."' data-state='". $user_state ."' data-email='".$user_email."'  data-currentuserid='". $userID ."'>". $current_title."</a><br><br>";   
				}//end output only if published
		      }// end for loop
		  }
		}
	}//end foreach course

//reset the string if there are no LERN courses for the user
 if ($numCourses == 0) {$progressBar ="<span class = biggerfont>It looks like you have not started any LERN topics.</span><br><a href='/lern-series/' title='Go to the LERN series'>Go to our LERN series and catch the next registration date!</a></div>.";}
?>
<section class="content">
<div class="template_content">
<?php the_content(); ?>
<script src='https://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
<link rel='stylesheet' href='https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css'>  
<script src="<?php echo get_stylesheet_directory_uri();  ?>/js/jquery.alerts.js" type="text/javascript"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.cookie.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/jquery.alerts.css?v=<?php echo time(); ?>" media="screen" />
<?php
echo $hasLERNmsg;
echo $progressBar;
?>
<?php if ($percentComplete == 100){	?>
<!-- video stuff and resources -->
<div class="content-column one_half">
<br><br>
<script src="//cdnapisec.kaltura.com/p/243342/sp/24334200/embedIframeJs/uiconf_id/36818192/partner_id/368641">
</script>
<div style="width: 90%;display: inline-block;position: relative;"> 
<div id="dummy" style="margin-top: 56.25%;"></div>
<div id="kaltura_player_558c29f12c1aa" style="width:90%; height: 110%; position:absolute;top:0;left:0;left: 0;right: 0;bottom:-36px;" itemprop="video" itemscope itemtype="http://schema.org/VideoObject" ></div>
</div>	
<script>
	kWidget.embed({
		targetId: "kaltura_player_558c29f12c1aa",
		wid: "<?php echo $wid;?>",
		uiconf_id: "36818192",
		entry_id: "<?php echo $entry_id;?>",
		flashvars: {
            "autoPlay": true,
            "autoMute": true,
			"googleAnalytics": {
				"plugin" : true,
				"position" : "before",
				"urchinCode" : "UA-1678035-1",
				"anonymizeIp" : false,
				"customEvent" : "doPlay",
                "doPlayCategory" : "LERN ",
                "doPlayAction" : "playing",
       			"doPlayLabel" : "<?php echo $course_title;?>",
				"doPlayValue" : "",
				"relativeTo" : "video",
              }
        }
})
</script> 
</div>
<div class="content-column one_half last_column">
<h6>Resources</h6>
<?php echo $GA_simple_links_list;?>
</div>
<?php } ?>
<div class="clear_column"></div>

<?php
echo "<div id=activityContentSection><hr>". $activityContent ."</div>";
echo "<div id='summary_sheet' title='Summary Sheet'><div id='box_msg'><span style='font-family: Georgia; font-size: large;'></span></div></div>";
?>
</div>
</section><!--/.content-->

<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 


