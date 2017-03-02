<?php
/**
 * Unit Template Name: Self Guided Question
 *
 * Be sure to use the "Unit Template Name:" in the header.
 * To display the course unit content, be sure to inclue the loop.
 */
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
background: rgba(164,179,87,1);
background: -moz-linear-gradient(top, rgba(164,179,87,1) 0%, rgba(117,137,12,0.47) 100%);
background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(164,179,87,1)), color-stop(100%, rgba(117,137,12,0.47)));
background: -webkit-linear-gradient(top, rgba(164,179,87,1) 0%, rgba(117,137,12,0.47) 100%);
background: -o-linear-gradient(top, rgba(164,179,87,1) 0%, rgba(117,137,12,0.47) 100%);
background: -ms-linear-gradient(top, rgba(164,179,87,1) 0%, rgba(117,137,12,0.47) 100%);
background: linear-gradient(to bottom, rgba(164,179,87,1) 0%, rgba(117,137,12,0.47) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a4b357', endColorstr='#75890c', GradientType=0 );	
height: auto;

padding-left: 10px;
padding-top: 10px;
padding-bottom: 10px;
}
.practice{
background: rgba(241,231,103,1);
background: -moz-linear-gradient(top, rgba(241,231,103,1) 0%, rgba(254,182,69,0.52) 100%);
background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(241,231,103,1)), color-stop(100%, rgba(254,182,69,0.52)));
background: -webkit-linear-gradient(top, rgba(241,231,103,1) 0%, rgba(254,182,69,0.52) 100%);
background: -o-linear-gradient(top, rgba(241,231,103,1) 0%, rgba(254,182,69,0.52) 100%);
background: -ms-linear-gradient(top, rgba(241,231,103,1) 0%, rgba(254,182,69,0.52) 100%);
background: linear-gradient(to bottom, rgba(241,231,103,1) 0%, rgba(254,182,69,0.52) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f1e767', endColorstr='#feb645', GradientType=0 );	
height: auto;
padding-left: 10px;
padding-top: 10px;
padding-bottom: 10px;
}
.struggling{
background: rgba(248,80,50,1);
background: -moz-linear-gradient(top, rgba(248,80,50,1) 0%, rgba(241,111,92,0.86) 38%, rgba(240,47,23,0.74) 71%, rgba(246,41,12,0.63) 99%, rgba(231,56,39,0.63) 100%);
background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(248,80,50,1)), color-stop(38%, rgba(241,111,92,0.86)), color-stop(71%, rgba(240,47,23,0.74)), color-stop(99%, rgba(246,41,12,0.63)), color-stop(100%, rgba(231,56,39,0.63)));
background: -webkit-linear-gradient(top, rgba(248,80,50,1) 0%, rgba(241,111,92,0.86) 38%, rgba(240,47,23,0.74) 71%, rgba(246,41,12,0.63) 99%, rgba(231,56,39,0.63) 100%);
background: -o-linear-gradient(top, rgba(248,80,50,1) 0%, rgba(241,111,92,0.86) 38%, rgba(240,47,23,0.74) 71%, rgba(246,41,12,0.63) 99%, rgba(231,56,39,0.63) 100%);
background: -ms-linear-gradient(top, rgba(248,80,50,1) 0%, rgba(241,111,92,0.86) 38%, rgba(240,47,23,0.74) 71%, rgba(246,41,12,0.63) 99%, rgba(231,56,39,0.63) 100%);
background: linear-gradient(to bottom, rgba(248,80,50,1) 0%, rgba(241,111,92,0.86) 38%, rgba(240,47,23,0.74) 71%, rgba(246,41,12,0.63) 99%, rgba(231,56,39,0.63) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f85032', endColorstr='#e73827', GradientType=0 );	
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

<section class="content">
<div class="pad group">
	<?php echo the_content();?>
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
				<label title="Review your answer then determine its quality. " rel="tooltip" >Self Assessment: (Choose the one that best describes the quality of your answer)</label>
			     <br><br>    <table class=basic_table>
					     <tbody>
						 <tr>
						 <th class="struggling"> <input type="radio" name="choice1" ng-model="radioB"  value="1" />Developing<div class="check"></div></th><th class="practice"><input type="radio" name="choice2" ng-model="radioB"  value="2" />Proficient</th><th class="mastered"><input type="radio" name="choice3" ng-model="radioB"  value="3" />Mastered</th>
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
                <p class="struggling"><strong>Your answer is developing: </strong>{{answer}}</p>
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
			</div><!-- end width 80% -->
	</div><!--main controller-->
	</div><!--app-->
	</div><!--/.pad-->
</section>
<div style="clear:both;"></div>	 
<div id="circumvent">
<?php get_sidebar(); ?>

</div>