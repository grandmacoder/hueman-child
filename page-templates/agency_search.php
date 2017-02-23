<?php
/*
Template Name: agency_search
*/
/**
 * Confirms that the activation key that is sent in an email after a user signs
 * up for a new blog matches the key for that user and then displays confirmation.
 *
 * @package WordPress
 */

get_header();
//get the term tax id for Missouri Agency
$term_taxonomy_id  = 393;
?>

<section class="content">
<h3><span>Missouri Agency Search</span></h3>
<div style="padding-right: 50px;">
<?php the_content(); ?>
</div>
<form id = "search_agency_form" onkeypress="return event.keyCode != 13;">
<input type="hidden" name="action" value="searchAgencies">
	<fieldset>
            <dl>
        	<dt><label for="agency_disability">Disability:</label></dt>
            <dd>
            	<select   name="agency_disability" id="agency_disability">
					<option value="All">All</option>
					<option value="Autism">Autism</option>
					<option value="Deaf-Blindness">Deaf-Blindness</option>
					<option value="Deafness/Hearing Impairment">Deafness/Hearing Impairment</option>
					<option value="Emotional Disturbance">Emotional Disturbance</option>
					<option value="Intellectual/Developmental Disabilities">Intellectual/Developmental Disabilities</option>
					<option value="Learning Disability">Learning Disability</option>
					<option value="Mental Illness">Mental Illness</option>
					<option value="Orthopedic Impairment">Orthopedic Impairment</option>
					<option value="Other Health Impairment">Other Health Impairment</option>
					<option value="Speech or Language Impairment">Speech or Language Impairment</option>
					<option value="Traumatic Brain Injury">Traumatic Brain Injury</option>
					<option value="Visual Impairment/Blindness">Visual Impairment/Blindness</option>
           </select>
          </dd>
        </dl> 
        <dl >
        	<dt ><label   for="agency_service">Services:</label></dt>
            <dd>
            	<select size="1" name="agency_service" id="agency_service">
				<option value="All">All</option>
				<option value="Advocacy">Advocacy</option>
				<option value="Education and Training">Education and Training</option>
				<option value="Employment">Employment</option>
				<option value="Financial">Financial</option>
				<option value="Foster Care">Foster Care</option>
				<option value="Guardianship/Conservator">Guardianship/Conservator</option>
				<option value="Health&Wellness">Health&Wellness</option>
				<option value="Independent Living">Independent Living</option>
				<option value="Individual &amp; Family Advocacy">Individual & Family Advocacy</option>
				<option value="Information & Referral">Information & Referral</option>
				<option value="Legal">Legal</option>
				<option value="Mental Health">Mental Health</option>
				<option value="Recreation Leisure">Recreation Leisure</option>
				<option value="Transportation">Transportation</option>
            	</select>
            </dd>
        </dl>
		  <dl >
        	<dt ><label   for="agency_county">County:</label></dt>
            <dd>
            	<select size="1" name="agency_county" id="agency_county">
				<option value="All">All</option><option value="Adair County">Adair County</option><option value="Andrew County">Andrew County</option><option value="Atchison County">Atchison County</option><option value="Audrain County">Audrain County</option><option value="Barry County">Barry County</option><option value="Barton County">Barton County</option><option value="Bates County">Bates County</option><option value="Benton County">Benton County</option><option value="Bollinger County">Bollinger County</option><option value="Boone County">Boone County</option><option value="Buchanan County">Buchanan County</option><option value="Butler County">Butler County</option><option value="Caldwell County">Caldwell County</option><option value="Callaway County">Callaway County</option><option value="Camden County">Camden County</option><option value="Cape Girardeau County">Cape Girardeau County</option><option value="Carroll County">Carroll County</option><option value="Carter County">Carter County</option><option value="Cass County">Cass County</option><option value="Cedar County">Cedar County</option><option value="Chariton County">Chariton County</option><option value="Christian County">Christian County</option><option value="Clark County">Clark County</option><option value="Clay County">Clay County</option><option value="Clinton County">Clinton County</option><option value="Cole County">Cole County</option><option value="Cooper County">Cooper County</option><option value="Crawford County">Crawford County</option><option value="Dade County">Dade County</option><option value="Dallas County">Dallas County</option><option value="Daviess County">Daviess County</option><option value="DeKalb County">DeKalb County</option><option value="Dent County">Dent County</option><option value="Douglas County">Douglas County</option><option value="Dunklin County">Dunklin County</option><option value="Franklin County">Franklin County</option><option value="Gasconade County">Gasconade County</option><option value="Gentry County">Gentry County</option><option value="Greene County">Greene County</option><option value="Grundy County">Grundy County</option><option value="Harrison County">Harrison County</option><option value="Henry County">Henry County</option><option value="Hickory County">Hickory County</option><option value="Holt County">Holt County</option><option value="Howard County">Howard County</option><option value="Howell County">Howell County</option><option value="Iron County">Iron County</option><option value="Jackson County">Jackson County</option><option value="Jasper County">Jasper County</option><option value="Jefferson County">Jefferson County</option><option value="Johnson County">Johnson County</option><option value="Knox County">Knox County</option><option value="Laclede County">Laclede County</option><option value="Lafayette County">Lafayette County</option><option value="Lawrence County">Lawrence County</option><option value="Lewis County">Lewis County</option><option value="Lincoln County">Lincoln County</option><option value="Linn County">Linn County</option><option value="Livingston County">Livingston County</option><option value="Macon County">Macon County</option><option value="Madison County">Madison County</option><option value="Maries County">Maries County</option><option value="Marion County">Marion County</option><option value="McDonald County">McDonald County</option><option value="Mercer County">Mercer County</option><option value="Miller County">Miller County</option><option value="Mississippi County">Mississippi County</option><option value="Moniteau County">Moniteau County</option><option value="Monroe County">Monroe County</option><option value="Montgomery County">Montgomery County</option><option value="Morgan County">Morgan County</option><option value="New Madrid County">New Madrid County</option><option value="Newton County">Newton County</option><option value="Nodaway County">Nodaway County</option><option value="Oregon County">Oregon County</option><option value="Osage County">Osage County</option><option value="Ozark County">Ozark County</option><option value="Pemiscot County">Pemiscot County</option><option value="Perry County">Perry County</option><option value="Pettis County">Pettis County</option><option value="Phelps County">Phelps County</option><option value="Pike County">Pike County</option><option value="Platte County">Platte County</option><option value="Polk County">Polk County</option><option value="Pulaski County">Pulaski County</option><option value="Putnam County">Putnam County</option><option value="Ralls County">Ralls County</option><option value="Randolph County">Randolph County</option><option value="Ray County">Ray County</option><option value="Reynolds County">Reynolds County</option><option value="Ripley County">Ripley County</option><option value="Saline County">Saline County</option><option value="Schuyler County">Schuyler County</option><option value="Scotland County">Scotland County</option><option value="Scott County">Scott County</option><option value="Shannon County">Shannon County</option><option value="Shelby County">Shelby County</option><option value="St. Charles County">St. Charles County</option><option value="St. Clair County">St. Clair County</option><option value="St. Francois County">St. Francois County</option><option value="St. Genevieve County">St. Genevieve County</option><option value="St. Louis city">St. Louis city</option><option value="St. Louis County">St. Louis County</option><option value="Statewide">Statewide</option><option value="Stoddard County">Stoddard County</option><option value="Stone County">Stone County</option><option value="Sullivan County">Sullivan County</option><option value="Taney County">Taney County</option><option value="Texas County">Texas County</option><option value="Vernon County">Vernon County</option><option value="Warren County">Warren County</option><option value="Washington County">Washington County</option><option value="Wayne County">Wayne County</option><option value="Webster County">Webster County</option><option value="Worth County">Worth County</option><option value="Wright County">Wright County</option>
            	</select>
            </dd>
        </dl>
        <dl >
        	<dt><label  for="agency_keyword">Keyword:</label></dt>
            <dd ><input type="text" name="agency_keyword" id="tip_keyword" size="32" maxlength="128" /></dd>
        </dl>
		<dl >
     
            <dd ><input type="hidden" name="term_taxonomy_id" id="term_taxonomy_id" value=<?php echo $term_taxonomy_id;?> /></dd>
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
    <br><input type="button" class="submitagencysearch" name="submitagencysearch" id="submitagencysearch" value="Search" />
    </fieldset>
 </form>	
<br>
<div style="clear:both";></div>
<!--/results-->
<div id="agency_results"></div> 
<p>

</section><!--/.content-->

					
<?php get_sidebar(); ?>
<?php get_footer(); ?> 
