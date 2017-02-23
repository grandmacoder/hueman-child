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
</style>
<?php
        $referring_page =$_SERVER['HTTP_REFERER'];
        $userID = get_current_user_id();
		$numCourses = 0;
		 $hasLERNmsg="";
	//get the extra values for the course --- need to automate this later.
	//course logo image, course start page, 
	//will get all course data for the user, we weed it out in the foreach
	    $courseData = WPCW_users_getUserCourseList($userID);
        if ($courseData){
         foreach ($courseData as $courseDataItem) {   //if it is the module id passed in and there has been some progress
	   //get the course extra fields
	   $courseExtraRow=tc_portfolio_user_module_list_get_course_extra_fields($courseDataItem->course_id);
	   $reviewpagesIDs=tc_portfolio_user_lern_get_review_ids($courseDataItem->course_id);
	   $progressBar.="<div>";
	  //if learning module
	  if ($courseExtraRow->course_type =='LERN'){
           $start_date = $courseExtraRow->start_date;
           $today =date('Y-m-d 00:00:00');
          $formatdate=date_create($start_date);
           $startdate =date_format($formatdate,"M. d, Y");		   
	      $hasLERNmsg="<p>Below you will find all the LERN topics you that you are working on or have completed.<br></p>";
		  $progressBar .= "<img src='". $courseExtraRow->course_logo_path."'><div style='clear:both;'></div>";
			//if the start date for the lern has not occurred yet, show the logo and the start date
			if ($start_date > $today){
			$progressBar .= "<p>This course has not started yet. It will be available on ".   $startdate .".</p>";
			//otherwise show the logo/completion/and details
			}
			else{
			    if ( $courseDataItem->course_progress > 0){
				$progressBar.= WPCW_stats_convertPercentageToBar($courseDataItem->course_progress, $courseDataItem->course_title);
				}
				if ($courseDataItem->course_progress  < 100){
				$progressBar.="<span class = biggerfont>You are " . $courseDataItem->course_progress ."% finished.</span><a href='". $courseExtraRow->course_start_page_path ."' title='continue working on lern topic'> Continue working on this lern topic.</a>";
				}
				else if ($courseDataItem->course_progress == 100){
				$progressBar .= "<a href='/?p=".$reviewpagesIDs[0]. "' title='Go to Q/A'>Review your Q/A</a><br><a href='/?p=".$reviewpagesIDs[1]. "' title='Go to Next Steps'>Review your Next Steps</a><br><a href='/?p=".$reviewpagesIDs[2]. "' title='Go to Resources'>Review resources</a>";
				}
			}
				$progressBar.="</div>";
				 $numCourses++;
				}
	  
		  //end if is lern
		}//end foreach course
      }
     //reset the string if there are no LERN courses for the user
     if ($numCourses == 0)  {$progressBar ="<span class = biggerfont>It looks like you have not started any LERN topics.</span><br><a href='/lern-series/' title='Go to the LERN series'>Go to our LERN series and catch the next registration date!</a>.";}

       ?>
<section class="content">
<div class="template_content">
<h3><span>My LERNs</span></h3>
<br>
<article>
<?php the_content(); 
echo $hasLERNmsg;
echo $progressBar;
?>		
</article>
</div>
</section><!--/.content-->
					
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 

