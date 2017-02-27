<?php
/*
Template Name: portfolio user module list
*/
/**
 * shows the progress on a single module based on the qs courseid parameter
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
	  if ($courseExtraRow->course_type =='learning module'){ 
		 //get the last item that the user was working on.
		 $nextToCompleteID = tc_portfolio_user_module_list_get_activity($userID, $courseDataItem->course_id);
		 if ($courseExtraRow->course_start_page_path <> ""){
		 $courseLogo="<img src='". $courseExtraRow->course_logo_path."' class=alignleft>";
		 }
		 if ($courseExtraRow->course_start_page_path  <> ""){
		 $courseStartPage = "<a style ='font-size:15px;' title='module start page' href='". $courseExtraRow->course_start_page_path ."'>";
		 }
			if ( $courseDataItem->course_progress > 0){
			$progressBar.="<div id=courseProgress><br><p>". $courseLogo."<h5>Your progress on the " . $courseDataItem->course_title." Module:</h5><br></p>";
			$progressBar.= "<div style='width: 300px;'>".WPCW_stats_convertPercentageToBar($courseDataItem->course_progress, $courseDataItem->course_title)."</div>";
			$progressBar.="<a style ='font-size:16px;' href='/user-module-progress/?courseid=".$courseDataItem->course_id ."' title='module details'>See your module details (Test scores and activities)!</a>"; 
			if ($courseDataItem->course_progress  < 100){
			$progressBar.="<br>".$courseStartPage." Continue working on this module.</a>";
			}
			else{
			$progressBar.="<br>". $courseStartPage." Review the completed module</a>";
			}
			$progressBar.="</div>";
			}
		//end if is learning module 
	}
	}
      }
       else{
       $progressBar .="<h3 class=template>You have not started any modules yet.</h3>Go to our modules listing to <a href='/online-training-modules/' title='Go to moduules list'>start your professional development training</a>.";
      }
	  //wp_user_id should equal current user id
	  $user_legacy_course_data = tc_portfolio_user_moduel_list_get_legacy_modules($userID);
	  if($user_legacy_course_data){
		foreach($user_legacy_course_data as $course_data){
  			 $module_name = $course_data->module_name;
			 $courseLogo="<img src='". $course_data->module_logo."' class=alignleft>";
			 $legacy_course.="<div id=courseProgress><br><p>". $courseLogo."<h5>Results for the ".$module_name." Module:</h5><br></p>";
			 $legacy_course.="<a style ='font-size:16px;' href='/legacy-user-module-progress/?courseid=".$course_data->tc_module_id ."' title='module details'>See your module details (test scores and activities)!</a>"; 
		}  
	  }
       ?>
<section class="content">
<div class="template_content">
<h3><span>My Modules</span></h3>
<br>
<article>
<?php the_content(); 
echo $progressBar;
echo $legacy_course;
?>		
</article>
</div>
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 

