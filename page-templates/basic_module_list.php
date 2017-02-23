<?php
/*
Template Name: basic module list
*/
/**
 * shows the modules that a user has access to. If not logged in just shows the national modules
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
.moduleLogodiv {
    top : 200px;
    left :0px;
    width : 100%;
	height: auto;
}
.moduleLogodiv img {
    position : relative;
    border : 1px solid #fff;
    float : left;
	width: 15%;

}
.module_desc_div{
    position : relative;
    border : 1px solid #fff;
	width: 70%; 
	padding-left: 10px;
	height: 110px;
	float : left;

}
</style>
<?php
	global $wpdb;
         $userID = get_current_user_id();
		 
	    //get the extra values for the course --- need to automate this later.
	    //course logo image, course start page, 
	    //will get all course data for the user, we weed it out in the foreach
	     //if not logged in get the national modules

		 if ($userID <= 0){
         $courseData = tc_get_national_course_list();
		 }
		 else{
		 //otherwise get the learning modules that the user is enrolled in
        $courseData = WPCW_users_getUserCourseList($userID);
		 }
		
        if ($courseData){
	
         foreach ($courseData as $courseDataItem) {   //if it is the module id passed in and there has been some progress
	     //get the course extra fields
		 $courseExtraRow=tc_portfolio_user_module_list_get_course_extra_fields($courseDataItem->course_id);

	      //if learning module
	     if ($courseExtraRow->course_type =='learning module'){ 
		 //get the last item that the user was working on.
		 $nextToCompleteID = tc_portfolio_user_module_list_get_activity($userID, $courseDataItem->course_id);
		 if ($courseExtraRow->course_logo_path <> ""){
		 $courseLogo="<img src='". $courseExtraRow->course_logo_path."' class='alignleft'>";
	
		 }
		 if ($courseExtraRow->course_intro_page_path  <> ""){
		 $courseIntroPage = "<a style ='font-size:15px;' title='module start page' href='". $courseExtraRow->course_intro_page_path ."'>".  $courseLogo. "</a>";
		 }
		 if ($courseDataItem->course_desc <> ""){
		 $courseDesc = 	 $courseDataItem->course_desc;
		 }
		 $courseList.="<div class='moduleLogodiv'>" .$courseIntroPage  ."<div class='module_desc_div'>" . $courseDesc ."</div></div>";
         }
		//end if is learning module 
	   }
	}//end if we have course data
      
?>
<section class="content">
<div class="template_content">
<h3><span>Online Modules</span></h3>
<br>
<article>
<?php the_content(); 
echo  $courseList;
?>		
</article>
</div>
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 

