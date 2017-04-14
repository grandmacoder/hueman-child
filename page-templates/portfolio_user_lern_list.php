<?php
/*
Template Name: portfolio user lern list
*/
/**
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
.biggerfont{font-size: 15px;}
</style>
<?php
	global $wpdb;
        $referring_page =$_SERVER['HTTP_REFERER'];
        $userID = get_current_user_id();
	//get the extra values for the course --- need to automate this later.
	//course logo image, course start page, 
	//will get all course data for the user, we weed it out in the foreach
	    $courseData = WPCW_users_getUserCourseList($userID);
      if ($courseData){
     foreach ($courseData as $courseDataItem) {   //if it is the module id passed in and there has been some progress
	   //get the course extra fields
	   $courseExtraRow=tc_portfolio_user_module_list_get_course_extra_fields($courseDataItem->course_id);
	   
	  //if learning module
	  if ($courseExtraRow->course_type =='LERN'){ 
		 //get the last item that the user was working on.
		 $nextToCompleteID = tc_portfolio_user_module_list_get_activity($userID, $courseDataItem->course_id);
		 if ($courseExtraRow->course_start_page_path <> ""){
		 $courseLogo="<img src='". $courseExtraRow->course_logo_path."' class=alignleft>";
		 }
		 if ($courseExtraRow->course_start_page_path  <> ""){
		 $courseStartPage = "<a style ='font-size:15px;' title='module start page' href='". $courseExtraRow->course_start_page_path ."'>";
		 }
			if ( $courseDataItem->course_progress > 0){
			$progressBar.="<div id=courseProgress><br><p>". $courseLogo."<br>";
			$progressBar.= "<div style='width: 50%;'>".WPCW_stats_convertPercentageToBar($courseDataItem->course_progress, $courseDataItem->course_title) ."</div>";
			$progressBar.="<a style ='font-size:16px;' href='/my-lerns/?courseid=".$courseDataItem->course_id ."' title='module details'>Review ".$courseDataItem->course_title."!</a>"; 
			$progressBar.="</div>";
			}
		//end if is learning module 
		}
		}
      }
       else{
       $progressBar .="<h3 class=template>You have not started LERN topics yet.</h3>Go to our modules listing to <a href='/lern-series/' title='Go to LERN listing'>start your professional development training</a>.";
      }
	
       ?>
<section class="content">
<div class="template_content">
<h3><span>My LERNs</span></h3>
<br>
<article>
<?php the_content(); 
echo $progressBar;
?>		
</article>
</div>
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 

