<?php
error_reporting(E_ERROR);

/*
Template Name: assessment reviews search template
*/
/**By: Amy Carlson

This template is built to display assessment reviews for TC. 

 */

/* Get assessment review posts and loop through to show thumbnail image, title, 
link to content, average rating, author, publisher info, link to resource, and 
whether the assessment review is a free resource.
*/
get_header();
//tag array and category (assessment reviews)  for search links and initial load, could be set with a plugin
$tags=array(396,397,398,377,373);
$catid=48;
$extrapostID=21007;  //content is pulled for the TC suggests from this post, otherwise we would have needed a shortcode for the search display, might be worth doing in the future if this type of categorisation is implemented in more areas of the site
for ($i=0; $i < count($tags); $i++){
$tagcontent=get_tag($tags[$i]);
$tag_title=$tagcontent->name;
$sTagLinks.="<a href='#' id='tagchoice'  class='assessment_tag_selection' data-tag_id=".$tags[$i]."><i class='fa fa-star fa-lg'></i>".$tag_title."</a> | ";	
}
?> 
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet' type='text/css'>
<link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/google-code-prettify/prettify.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/owl_assessments_css.css" rel="stylesheet">
<style> 
input[type=text] {
    width: 600px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    font-size: 18px;
	color:#000;
	font-weight:200;
    background-color: #e0e4fd;
    background-position: 10px 10px; 
    background-repeat: no-repeat;
    padding: 7px 20px 7px 40px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
	font-family: 'FontAwesome';
}
input[type=text]:focus {
    width: 80%;
	background-color: white;
}
</style>
<div style="padding-left: 5%; padding-top: 2%;">
<?php
echo "<h3>". get_the_title() ."</h3><BR><BR>";
the_content();
?>
</div>
<div style="padding-left: 21%;">
<form id="assessment_search_form">
<input id="assessment_search" type="text" name="search_assessments" placeholder="&#xf002; Find an assessment...type your search words and hit enter">
<input type="hidden" id="saved_cat_ID" type="text" name="saved_cat_ID" value='<?php echo $catid;?>'>
</form>
<div id = "assessment_results_header" class="assessment_results_header"></div>
<div id="assessmentCarousel">
	<div id="owl-demo" class="owl-carousel"></div>
	<div style="clear:both;"></div>
</div>
<p class="assessment_results_header">Find assessments by category</p>
<div id="search_category_links"> 
<?php echo $sTagLinks;?>
</div>
</div>
<div style="padding-left: 5%; padding-top: 2%;">
<hr />
<h4>TC suggests:</h4>
<p><strong>Right now Transition Coalition is featuring Transition Assessment </strong> through our Self-study program and other great ressources.<br></p>
<p>Do you want to learn more?<br><br></p>
<?php
$extrapost = get_post($extrapostID);
echo $extrapost->post_content;
?>
</div>

<?php get_sidebar(); ?>
<style> div.container-inner{background-color:#e1e4f3 !important;}</style>
<?php 
get_footer(); 
?> 

<!-- js -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-1.9.1.min.js"></script> 
<script src="<?php echo get_stylesheet_directory_uri(); ?>/owl-carousel/owl.carousel.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/assessment_review.js"></script> 
