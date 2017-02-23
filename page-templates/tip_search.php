<?php
/*
Template Name: tip_search
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
?>

<section class="content">
<h3><span>Transition Tips</span></h3>
<div style="padding-right: 50px;">
<?php the_content(); ?>
</div>
<form id = "search_tip_form" onkeypress="return event.keyCode != 13;">
<input type="hidden" name="action" value="searchTips">
	<fieldset>
            <dl>
        	<dt><label for="tip_category">Category:</label></dt>
            <dd>
            	<select   name="tip_category" id="tip_category">
            	    <option value="" selected>All</option>
                    <option value="135">Transition Planning</option>
                    <option value="136">Student Involvement</option>
                    <option value="137">Transition Assessment</option>
                    <option value="140">Families</option>
                    <option value="142">Interagency Collaboration</option>
                    <option value="139">Disability Specific</option>
                    <option value="141">Curriculum and Instruction</option>
                    <option value="138">Assistive Tech and Universal Design</option>
           </select>
          </dd>
        </dl> 
        <dl >
        	<dt ><label   for="tip_state">State:</label></dt>
            <dd>
            	<select size="1" name="tip_state" id="tip_state">
            		<option value="" selected>All</option>
                    <option value="AL">Alabama</option>
					<option value="AK">Alaska</option>
					<option value="AZ">Arizona</option>
					<option value="AR">Arkansas</option>
					<option value="CA">California</option>
					<option value="CO">Colorado</option>
					<option value="CT">Connecticut</option>
					<option value="DE">Delaware</option>
					<option value="DC">District Of Columbia</option>
					<option value="FL">Florida</option>
					<option value="GA">Georgia</option>
					<option value="HI">Hawaii</option>
					<option value="ID">Idaho</option>
					<option value="IL">Illinois</option>
					<option value="IN">Indiana</option>
					<option value="IA">Iowa</option>
					<option value="KS">Kansas</option>
					<option value="KY">Kentucky</option>
					<option value="LA">Louisiana</option>
					<option value="ME">Maine</option>
					<option value="MD">Maryland</option>
					<option value="MA">Massachusetts</option>
					<option value="MI">Michigan</option>
					<option value="MN">Minnesota</option>
					<option value="MS">Mississippi</option>
					<option value="MO">Missouri</option>
					<option value="MT">Montana</option>
					<option value="NE">Nebraska</option>
					<option value="NV">Nevada</option>
					<option value="NH">New Hampshire</option>
					<option value="NJ">New Jersey</option>
					<option value="NM">New Mexico</option>
					<option value="NY">New York</option>
					<option value="NC">North Carolina</option>
					<option value="ND">North Dakota</option>
					<option value="OH">Ohio</option>
					<option value="OK">Oklahoma</option>
					<option value="OR">Oregon</option>
					<option value="PA">Pennsylvania</option>
					<option value="RI">Rhode Island</option>
					<option value="SC">South Carolina</option>
					<option value="SD">South Dakota</option>
					<option value="TN">Tennessee</option>
					<option value="TX">Texas</option>
					<option value="UT">Utah</option>
					<option value="VT">Vermont</option>
					<option value="VA">Virginia</option>
					<option value="WA">Washington</option>
					<option value="WV">West Virginia</option>
					<option value="WI">Wisconsin</option>
					<option value="WY">Wyoming</option>
            	</select>
            </dd>
        </dl>
        <dl >
        	<dt><label  for="tip_keyword">Keyword:</label></dt>
            <dd ><input type="text" name="tip_keyword" id="tip_keyword" size="32" maxlength="128" /></dd>
        </dl>
        <dl >
        	<dt><label  for="results">Summary:</label></dt>
            <dd ><p class=total_tips></p></dd>
        </dl>
        <dl >
        	<dt></dt>
            <dd ><p class=resultSummary></p></dd>
        </dl>
    </fieldset>
   <fieldset class="action"> 
    <br><input type="button" class="submittipsearch" name="submittipsearch" id="submittipsearch" value="Search" />
    </fieldset>
 </form>	
<br>
<div style="clear:both";></div>
<!--/results-->
<div id="tips_results"></div> 
<p>
<br><br>
<a href="http://www.dcdt.org/" target="_blank"><img src="/wp-content/uploads/2014/06/dcdt_logo2.jpg" alt="DCDT logo" /></a> 
<a href="http://dese.mo.gov/" target="_blank"><img src="/wp-content/uploads/2014/06/dese2.jpg" alt="dese logo" /></a>
The development of this database was supported by CEC Division of Career Development &amp; Transition 
(<a href="http://www.dcdt.org/" target="_blank">DCDT</a>)<br> and the Missouri Dept. of Elementary and Secondary Education (<a href="http://dese.mo.gov/" target="_blank">DESE</a>).</p>
</section><!--/.content-->

					
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
