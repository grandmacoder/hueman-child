<?php
/*
Template Name: reflection and implementation plan
*/
/**
 * shows the reflection and implementation plan for self determination module
 *
 * @package WordPress
 */

/* Displays customize output implementation plan
*/

get_header();
?>
<?php
$courseID = 15;
$current_userID = get_current_user_id();
$user_info = get_userdata($current_userID);
$displayName = $user_info->first_name . " " . $user_info->last_name;
$displayEmail = $user_info->user_email;
$logoPath="<img src='/wp-content/uploads/2014/12/tc_logo.png'>";
$modelCourseIDs=array('2','8192','1','8205','2','8260','2','8216','1','8225','1','8239');
$otherCourseIDs=array('2','8255','1','8365','2','8365');
//header and logo
$heading="<br><br><br><br><p style='text-align: center;'><span style='font-size: 12px;'>Report generated by ". $displayName." (". 

$displayEmail.") on" .date('y-m-d hh:mm:ss')."</span><br></p>
<p style='text-align: center;'><img class='aligncenter size-full wp-image-8389' src='/wp-content/uploads/2014/12/tc_logo.png' alt='tc_logo' width='195' height='85' /></p>
<br><p style='text-align: center;'><span style='font-size: 16px;'><strong>The <em>Essentials of Self-

Determination</em> Training Module</strong></span></p>
<p style='text-align: center;'><br>www.transitioncoalition.org<br><strong>Reflection & Implementation Plan</strong><br><br></p>";


$table="<center><table class=basic_table><tr><td><strong>Key Element from <i>Model of Self-Determination</i> Field & 

Hoffman (2005)</strong> </td><td>Your responses in Session 2 identifying additional content you need to address 

with your students in each element</td></tr>";

for ($i = 0; $i < count($modelCourseIDs); $i+=2){
$order= $modelCourseIDs[$i];
$postTitle =  get_the_title( $modelCourseIDs[$i+1] );
$answer= $wpdb->get_var("Select activity_value from wp_course_activities where user_id=". $current_userID ." and 

page_order=". $order ." and  post_id = ". $modelCourseIDs[$i+1]);
if ($answer ==""){
$answer="<i>NOT ANSWERED YET</i>";
}
$table .="<tr><td><strong>". $postTitle."</strong></td><td>". $answer."</td></tr>";
}
$table .="</table><br><br>";

for ($i = 0; $i < count($otherCourseIDs); $i+=2){
$order=$otherCourseIDs[$i];
$rows= $wpdb->get_results("Select activity_value, post_id, description from wp_course_activities where user_id=". $current_userID ." and page_order =". $order ." and post_id =" .$otherCourseIDs[$i+1], OBJECT); 
	foreach ($rows as $row){
	$summaryAnswer= $row->activity_value;
	$summaryQuestion= "Your response to: " . $row->description;
	$final .="<div style='text-align:left'><p><strong>". $summaryQuestion ."</strong></p><p>". $summaryAnswer ."<br><br></p></div>";
	}
}
$js="<script type='text/javascript'>
window.print();
window.close();
</script>";
$content = "<html><head><link rel='stylesheet' type='text/css' href='/wp-content/themes/hueman-child/style.css'></head><body><div style='width:800px'; >". $heading . $table . $final ."</center></div>". $js."</body></html>";
?>
<section class="content">
<?php get_template_part('inc/page-title'); ?>	
    <div class="pad group">
		<article>
		<div style="padding-left: 30px;">
			<?php the_content(); 

echo $content;
?>
</div><!--/.entry-->
</article>
</div><!--/.pad-->
</section><!--/.content-->				
<?php get_sidebar(); ?>
<?php 	get_footer(); ?> 

