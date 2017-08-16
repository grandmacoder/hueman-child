<?php
/*
Template Name: fontpage
*/
/**
The frontpage template inserts slider at top the rest is in the associated page
 *
 * @package WordPress
 */

/* Set up index page
*/
?>
<?php get_header(); ?>
<?php $theContent=get_the_content();?>
<?php //just get slider posts
$args = array('numberposts' =>6,
        'category' => 43, 
		'post_status' =>'publish',
        'suppress_filters' => true);
$postlist = get_posts( $args );
$aSlogans= array('Enter to Learn. Leave to Deliver.',
'Lead the Charge. Be the Change.',
'Explore. Learn. Connect.',
'Seek. Learn. Apply. Results.',
'Increase Skills. Change Practice.',
'Got resources? We do!',
'Never stop learning.');
$sloganNum=rand(0,6);
$slogan=$aSlogans[$sloganNum]; 
?>
<style>
.quotetext {font-size: 13px; font-style:italic; line-height:14px; color: #000; font-weight:normal;}
.addition {font-size: 13px;line-height:15px; color: #000; }

</style>
<!-- add the js for the slider only -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet' type='text/css'>
<link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/google-code-prettify/prettify.css" rel="stylesheet">
      <!-- Le fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/ico/apple-touch-icon-57-precomposed.png">
<section class="content">
<div class="pad group">
	<div id="indexTop">
     <?php echo $slogan;?>
	</div> <!--end top of index -->	
	<div id="indexCarousel">
	<div id="owl-demo" class="owl-carousel">
	         <?php if ( $postlist ) {
                 foreach ( $postlist as $post ){
                  setup_postdata( $post ); 
			   ?>
			
				<div class="item">
				 <a href="<?php the_permalink();?>" onClick="ga('send', 'event', { eventCategory: 'slider link', eventAction: 'slider article', eventLabel: '<?php the_title(); ?>'});"><?php the_post_thumbnail();?></a>
		         <a href="<?php the_permalink();?>" onClick="ga('send', 'event', { eventCategory: 'slider link', eventAction: 'slider article', eventLabel: '<?php the_title(); ?>'});"><?php the_excerpt(); ?></a>
		         </div>
			 <?php
			 }//end for
			 }//end if
?>
         </div>
     <br>
     </div>
<?php echo $theContent; ?>
</div><!--/.pad-->
</section><!--/.content-->	 
			
<?php get_sidebar(); ?>
<?php get_footer(); ?>
<svg class="defs-only">
  <filter id="monochrome" color-interpolation-filters="sRGB"
          x="0" y="0" height="100%" width="100%">
    <feColorMatrix type="matrix"
      values="0.95 0 0 0 0.05 
              0.85 0 0 0 0.15  
              0.50 0 0 0 0.50 
              0    0 0 1 0" />
  </filter>
</svg>
<!-- js -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-1.9.1.min.js"></script> 
<script src="<?php echo get_stylesheet_directory_uri(); ?>/owl-carousel/owl.carousel.js"></script>
<script>
jQuery(document).ready(function($){
    //Sort random function
    function random(owlSelector){
    owlSelector.children().sort(function(){
    return Math.round(Math.random()) - 0.5;
    }).each(function(){
    $(this).appendTo(owlSelector);
    });
    }
     
    $("#owl-demo").owlCarousel({
        items: 3,
        itemsDesktop: [1400, 3],
        itemsDesktopSmall: [1100, 3],
        itemsTablet: [700, 2],
        itemsMobile: [500, 1],
    navigation: true,
    beforeInit : function(elem){
    //Parameter elem pointing to $("#owl-demo")
    random(elem);
    }
       
    });	 
    });
</script>
<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/signup-forms/popup/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script>
<script type="text/javascript">require(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us12.list-manage.com","uuid":"548482e079ad95e36835cc3b1","lid":"61489b109d"}) })
</script>

