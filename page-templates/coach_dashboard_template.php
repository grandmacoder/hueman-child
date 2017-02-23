<?php
/**
Template Name: Coach Dashboard Template
 * To display the course unit content, be sure to inclue the loop.
 */
?>
<?php get_header(); 
  if(isset($_GET['course_id']) && is_numeric($_GET['course_id'])){
	$courseid = $_GET['course_id']; //from the url
	}else{
		header("Location: /");
		exit();
	}
?>
<?php
global $wpdb;
get_header(); 
?>
<script src="/wp-content/themes/hueman-child/highcharts/js/highcharts.js"></script>
<script src="/wp-content/themes/hueman-child/highcharts/js/modules/drilldown.js"></script>
<script src="/wp-content/themes/hueman-child/highcharts/js/modules/data.js"></script>
<script src="/wp-content/themes/hueman-child/highcharts/js/highcharts-3d.js"></script>
<script src="/wp-content/themes/hueman-child/highcharts/js/themes/tc-chart.js"></script>
<style>
.courseid{display:none;}
#byquestion_bystudent{ display:none;}
#qaprogressbyquestion{ display:none;}
#qaprogressbystudent{ display:none;}
#progresschartcontainer{
height: auto;
}
#addatopiccontainer{ display:none;}
</style>
<?php the_content();?>
<br><br>
<div class="courseid"><?php echo $courseid;?></div>
<div id="topitems" >

<div class="content-column one_fourth">
<a href="#" id="showpercentcompleted" title="See overall progress"><img src="/wp-content/uploads/2016/10/StudentCompletion.png" height="100" width="100"></a>
</div>

<div class="content-column one_fourth">
<a href="#" id="showstudentqamenu" title="See how lerners answered q/a with the rubric"><img src="/wp-content/uploads/2016/10/StudentProgress1.png" height="100" width="100"></a>
</div>

<div class="content-column one_fourth">
<a href="#" id="showdiscussiontopics" title="Add a new discussion topic"><img src="/wp-content/uploads/2016/10/DiscussionTopics.png" height="100" width="100"></a>
</div>
<div class="content-column one_fourth one_fourth_last">
<a href="#" id="gototopic" title="See overall progress"><img src="/wp-content/uploads/2016/10/gototopic.png" height="100" width="100"></a>
</div>

</div>

<div style="clear:both;"><br><br></div>
<div id ="byquestion_bystudent">
<div class="content-column one_half"><input id="showqabyquestion" type="button" value="Show Rubric by Student Self-grade"></div><input  id="showqabystudent" type="button" value="Show Rubric by Student Responses"></div>
<div id="qaprogressbystudent"></div>
<div id="qaprogressbyquestion"></div>
<div id="progresschartcontainer"></div>
<div id="progressbystudentcontainer"></div>
<div id="addatopiccontainer">
<div id="currenttopics">
</div>
<div id="addtopicformcontainer">
<br><br>
<h4> Add a new discussion topic</h4>
<div id ="validatearea">All fields are required.</div>
<form id="addtopicform" method="post" class="ng-pristine ng-valid">
    <fieldset>
    <label for="title">Give the topic a brief title</label><br>
      <input name="topic-title" id="topic-title" value="" size="40" maxlength="40"><br>
       <label for="topic-description">Enter the topic/question that you are posting to your students for discussion.</label><br>
      <textarea name="topic-description" id="topic-description" value="" cols="40"></textarea><br>
         <input type="hidden" name="userid" id="userid" value="<?php echo get_current_user_id();?>"><br>
		 <input type="hidden" name="course_id" id="course_id" value="<?php echo $courseid;?>"><br>
      <input type="submit" id="submitcommenttopic" value="Add topic"><br>
    </fieldset>
  </form>
<div id ="topicslist"></div>
</div>
<div id="current_topics_table"></div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>



