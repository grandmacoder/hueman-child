<?php
/**
 * Unit Template Name: Self Guided Question
 *
 * Be sure to use the "Unit Template Name:" in the header.
 * To display the course unit content, be sure to inclue the loop.
 */
?>

<?php 
$ID = get_the_ID();
$postmeta= get_post_meta($ID);
$embedscript = $postmeta['embed_video_course_unit'][0];
?>
<?php get_header(); ?>
<style>
#circumvent{
top:10px;
position:absolute;
}
    .wpcw_fe_progress_box_wrap.wpcw_fe_navigation_box{
        width: 50% !important;
        float:right !important;
    }

.mastered{
background: #405f15; /* For browsers that do not support gradients */
background: -webkit-linear-gradient(#405f15, #d2e4b8); /* For Safari 5.1 to 6.0 */
background: -o-linear-gradient(#405f15, #d2e4b8); /* For Opera 11.1 to 12.0 */
background: -moz-linear-gradient(#405f15, #d2e4b8); /* For Firefox 3.6 to 15 */
background: linear-gradient(#405f15, #d2e4b8); /* Standard syntax (must be last) */
height: auto;
padding-left: 10px;
padding-top: 10px;
padding-bottom: 10px;
}
.practice{
background: #dc8800; /* For browsers that do not support gradients */
background: -webkit-linear-gradient( #dc8800, #f3dbb4); /* For Safari 5.1 to 6.0 */
background: -o-linear-gradient( #dc8800, #f3dbb4); /* For Opera 11.1 to 12.0 */
background: -moz-linear-gradient( #dc8800, #f3dbb4); /* For Firefox 3.6 to 15 */
background: linear-gradient( #dc8800, #f3dbb4); /* Standard syntax (must be last) */	
height: auto;
padding-left: 10px;
padding-top: 10px;
padding-bottom: 10px;

}
.struggling{
background: #b50000; /* For browsers that do not support gradients */
background: -webkit-linear-gradient( #b50000, #d3c9cb); /* For Safari 5.1 to 6.0 */
background: -o-linear-gradient( #b50000, #d3c9cb); /* For Opera 11.1 to 12.0 */
background: -moz-linear-gradient( #b50000, #d3c9cb); /* For Firefox 3.6 to 15 */
background: linear-gradient( #b50000, #d3c9cb); /* Standard syntax (must be last) */	
height: auto;
padding-left: 10px;
padding-top: 10px;
padding-bottom: 10px;
}

.ratingsdata{
width: 33%;	
}
.ratingsdata label{
color:#000;	
font-weight: normal;
	
}
input[type=radio]:checked ~ .check {
  border: 5px solid #3a528f;
}

input[type=radio]:checked ~ .check::before{
  background: #3a528f;
}

input[type=radio]:checked ~ label{
  color: #3a528f;
}
.wpcw_fe_progress_box_wrap{
display:none;	
}

.wpcw_fe_progress_box_wrap_completed{
     margin: 10px 0;
	border: 1px solid #DDD;
	padding: 15px 20px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	line-height: 2.3em;
	font-size: 11pt;
	display:none;
}
a:link {color:#537c1b; font-weight: 500;}
a:visited {color:#537c1b; font-weight: 500;}
a:hover{color:#3b8dbd; font-weight: 500; text-decoration:underline; }
.fancybox-iframe a:hover{color:#3b8dbd; font-weight: 500; text-decoration:underline; }
</style>

<div style="margin-top: 100px;">
<div class="container">
<section class="content">
</section>
<?php 
echo the_content();
?>
<div ng-app="myApp">
<div ng-controller="EngageController">
			<div style="width: 80%;">
            <div ng-show="question">
			
               <label><p ng-bind-html="questions[questioncount]"></p></label><br>
			   	<textarea style="height:100px;width:500px;" ng-model="answertext" ng-click="answertext=''"></textarea>
				 <br>
				 <button id="answerbutton" ng-click="saveAnswer()">Save Answer</button>
			    </div>
				
				
				<div ng-show="answer">
				<label><p ng-bind-html="prevQuest"></p></label><br>
				<p>
				 Your answer: {{answertext}}
				 </p>
				<br>
				<label title="Review your answer then determine its quality. " rel="tooltip" >Self Assessment: Choose the option that best describes the quality of your answer.</label>
			     <br><br>    <table class=basic_table>
					     <tbody>
						 <tr>
						 <th class="struggling"> <input type="radio" name="choice1" ng-model="radioB"  value="1" />Emerging<div class="check"></div></th><th class="practice"><input type="radio" name="choice2" ng-model="radioB"  value="2" />Proficient</th><th class="mastered"><input type="radio" name="choice3" ng-model="radioB"  value="3" />Mastered</th>
						 </th>
                         <tr>
					     <td ng-repeat="suggAnswer in suggestAns[questioncount-1]" class="ratingsdata">
						 <p ng-bind-html="suggAnswer"</label>
						 </td>
					     </tr>
					    </tbody>
					    </table>
				   <br>	   
				<button id="nextbutton" ng-click="showQuestion()">Next Question</button>
	            </div>
				
				
				<div ng-show="results">
				<label>Your self-graded responses:</label><br>
				<ul>
				<li style="list-style: none;" ng-repeat="answer in answerA">
				<p ng-bind-html="questions[$index]"></p>

				<div ng-if="selfgradeA[$index] == 3" >
                <p class="mastered"><strong>Your answer is mastered: </strong>{{answer}}</p>
                </div>
				<div ng-if="selfgradeA[$index] == 2" >
                <p class="practice"><strong>Your answer is proficient: </strong>{{answer}}</p>
                </div>
				<div ng-if="selfgradeA[$index] == 1" >
                <p class="struggling"><strong>Your answer is emerging: </strong>{{answer}}</p>
			    </div>
				<br>
				</li>
				</ul>
			   </div>
			   <div ng-show="course_navigation"> 
<?php 
$postID= get_the_ID();
$parentData = WPCW_units_getAssociatedParentData($postID);
$userProgress = new UserProgress($parentData->course_id, 1);
$progressDetails = WPCW_units_getUserUnitDetails(1,$postID);
$completed = ($progressDetails && $progressDetails->unit_completed_status == 'complete');    
$html2 = false;
if ($completed){
// Work out if course completed. so either show the item is complete or the course is complete
 $html2.="<div class='wpcw_fe_progress_box_wrap2'><div class='wpcw_fe_progress_box wpcw_fe_progress_box_complete'>You have completed this item.</div></div><div class='wpcw_fe_progress_box_wrap_completed'><div class='wpcw_fe_progress_box2 wpcw_fe_progress_box_complete'>You have completed this unit.</div></div>";
  }
else{ //show the complete button and toggle the completed div
$html2.="<div class='wpcw_fe_progress_box_wrap2' id='wpcw_fe_unit_complete_".$postID."'><div class='wpcw_fe_progress_box wpcw_fe_progress_box_pending wpcw_fe_progress_box_updating'><div class='wpcw_fe_progress_box_mark'><a href='#' class='fe_btn fe_btn_completion btn_completion' id='unit_complete_".$postID."'>Mark as complete</a></div>Have you finished this item? Mark it as completed before going on. Thanks!</div></div>";		
//set completed this to display none at first
$html2.="<div class='wpcw_fe_progress_box_wrap_completed'><div class='wpcw_fe_progress_box2 wpcw_fe_progress_box_complete'>You have completed this unit.</div></div>";
}
//get the next prev nav
$nextAndPrev = $userProgress->getNextAndPreviousUnit($postID);
$navhtml = false;	
	if ($nextAndPrev['prev'] > 0) 
	{
	$navhtml.= "<a href='/?p=".$nextAndPrev['prev']."'><img src='/wp-content/uploads/2015/05/previous.png' title='Previous page' ></a>&nbsp;&nbsp;&nbsp;&nbsp;";
    }
	if ($nextAndPrev['next'] > 0) 
	{
	$navhtml.="<a href='/?p=".$nextAndPrev['next']."'><img src='/wp-content/uploads/2015/05/next.png' title='Next page' alt='Next'></a><br><span class='prev_next_text'> Previous&nbsp;&nbsp;Next</span>";
	}
echo $html2;
if ($navhtml){
echo "<div style='text-align:center'><br>".$navhtml ."</div>";
}
?>
			   
				</div>

 
		
		</div>
		</div>

       
</div><!-- end engage controller -->
</div><!-- end myapp -->


</div>

<div id="circumvent">
<div style="clear:both;"></div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
</div>


