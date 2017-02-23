<?php
/*
Template Name: PDhub roster 
*/
/**
 * Confirms that the activation key that is sent in an email after a user signs
 * up for a new blog matches the key for that user and then displays confirmation.
 *
 * @package WordPress
 */

/* Get roster if there are any if not show the create roster form
*/
get_header(); 
//add the autocomplete jquery classes
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<?php wp_nav_menu(array(
    'theme_location' => 'template_submenu',
	'container'       => 'div',
	'container_id'    => 'submenucontainer',
	'menu_id' => 'submenuid',
));
?>
<section class="content">
<div class="template_content">
      <article>
        <?php the_content();?>
	<h3 name="rosterBanner1" id="rosterBanner1"></h3>
		        <div id="create_roster">
			<br>
			<p>A roster is a group of registered users on the Transition Coalition website who can come together around training modules offered on this website.</span></p>
			<p>PD Hub instructors can use this tool to view student progress on training modules. Students do not have access to PD Hub features.</p>
			<br>
			<div id="messagearearoster"></div>
			<div><strong>To create a roster, enter the following information. After the roster is created you can invite participants/students to join the roster.</strong></div>
			<div id="createroster">
			<form id="createrosterform" ></BR>
			<p>Enter a name for your roster/group. <span style="color: #c2132f;"><strong>(*required) </strong></span></p>
			<input class="groupname" name="groupname" size="40/" type="text" value="" />
			</BR>
			</BR>
			<p>Enter a short description for this roster.<span style="color: #c2132f;"><strong>(*required) </strong></span></p>
			<input class="groupdescription" name="groupdescription" size="40/" type="text" value="" />
			</BR>
			</BR>
			<p>Enter a the name of your school or institution (if applicable).</p>
			<input class="groupschool" name="groupschool" size="40/" type="text" value="" />
			</BR>
			</BR>
			<p>Enter the semester for this roster.<span style="color:#c2132f;"><strong>(*required) </strong></span></p>
			<?php 

			$year = date("Y");
			$yearA = array(($year-1), ($year), ($year+1), ($year+2));
			$semesterA = array("Fall", "Spring", "Summer");
			
			foreach($yearA as $yearV){
				$option_year .= "<option value='".$yearV."'>".$yearV."</option>";	
			}
			foreach($semesterA as $semesterV){
				$option_semester .= "<option value='".$semesterV."'>".$semesterV."</option>";	
			}
			$semesterSelect = "<select id='roster_semester' name='roster_semester'><option id='selectOption1' name='selectOption1' value='Select'></option>".$option_semester."
						</select>";
						
			$yearSelect = "<select id='roster_year' name='roster_year'><option id='selectOption2' name='selectOption2' option value='Select'></option>".$option_year."
						</select>";
			
			echo $semesterSelect."&nbsp&nbsp&nbsp&nbsp".$yearSelect;		
			?>
			</BR>
			</BR>
			<input class="groupid" name="groupid" size="40" type="hidden" value="" />
		        <input class="editroster" name="editroster" size="40" type="hidden" value="" />
			<input class="groupsemester" id="groupsemester" name="groupsemester" size="40" type="hidden" value="" />
			<input id="btnsubmitroster" class="btnsubmitroster" name="btnsubmitroster" type="button" value="Save my roster!" />
		    </form></div>
			</div>
			<div id = "create-group-summary"></div>
			<BR><BR>
		<div id="invite_faculty">
		
	<h3 name="rosterBanner2" id="rosterBanner2" ></h3>
	<div style="width: 300px; height: auto; display: block; float: right; font-size: 14px; line-height: 15px; padding: 15px; text-align: left; background-image: linear-gradient(to left top, #ffffff 0%, #efaa6e 200%);">
        <span style="color: #b76e00; align: left;"><strong>
	<img class="  wp-image-10239 alignleft" src="/wp-content/uploads/2015/05/FAQ.png" alt="Question" width="66" height="85" />DO YOU TEACH COURSES ONLINE?</strong></span>
        Share the link in the text area below with your online class when you assign work from this website.
        </div>
        <p>Follow the progress of participants/students as they complete work on the TC website.<br> 
       <span style="font-size: 16px;"><strong>Complete these easy steps! </strong></span></p>
        <div style="clear:both;"></div>
         <img class="alignnone wp-image-9101 size-full" style="vertical-align: middle;" title="Copy content by clicking in box" src="/wp-content/uploads/2015/01/copyInvite.jpg" alt="copyInvite" width="30" height="30" /> 
	<strong>Step 1.</strong> Click in the box below to select text . Copy it.<br>
	<strong>When the recipients receive the email from you and follow the instructions,they will be added to your roster.</strong><br>
        <br>
	<?php $joinMember = "<div id='joinLink'></div>"; ?>
	<textarea id="joinMember" rows="8" onClick="this.select();" cols="90"></textarea>
	<br><br>
	<img class="alignnone wp-image-9100 size-full" style="vertical-align: middle;" title="Paste text into an email message." src="/wp-content/uploads/2015/01/pasteInvite.jpg" alt="pasteInvite" width="24" height="30" /> 
	<strong>Step 2.</strong> Paste the text into an email  message to invite  members. If you would like you can add your own text to the email as well as the pasted text.<br class="none" /><br>
	<img class="alignnone wp-image-9099 size-full" style="vertical-align: middle;" title="Edit your message." src="/wp-content/uploads/2015/01/editeInvite.jpg" alt="editeInvite" width="30" height="30" /> 
	<strong>Step 3. </strong>Address email message to members you would like to invite to your roster. <br><br>
	<img class="alignnone wp-image-9102 size-full" style="vertical-align: middle;" title="Send the email." src="/wp-content/uploads/2015/01/sendInvite.jpg" alt="sendInvite" width="30" height="28" /> 
	<strong>Step 4.  </strong>Send the email invitation.<br class="none" />
	
         <p><br><strong>As participants/students join your roster and begin working on modules, you can track their progress by going to My Rosters</strong></p>
	
	</article>
	<?php// }else{ ?>
	
	
		
<?php	//} ?>
</div>
</section><!--/.content-->
						
<?php get_sidebar(); ?>
<?php get_footer(); ?> 


