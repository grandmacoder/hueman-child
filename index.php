<?php get_header(); ?>
<?php //just get slider posts
query_posts($query_string . '&cat=43');
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
<!-- add the js for the slider only -->
 <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet' type='text/css'>
<!-- Owl Carousel Assets   imported into the main style.css
    <link href="../owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="../owl-carousel/owl.theme.css" rel="stylesheet">
    -->
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
	         <?php if ( have_posts() ) : 
                                while ( have_posts()): the_post();
				?>
				<div class="item">
				 <a href="<?php the_permalink();?>" onClick="ga('send', 'event', { eventCategory: 'slider link', eventAction: 'slider article', eventLabel: '<?php the_title(); ?>'});"><?php the_post_thumbnail();?></a>
		               <a href="<?php the_permalink();?>" onClick="ga('send', 'event', { eventCategory: 'slider link', eventAction: 'slider article', eventLabel: '<?php the_title(); ?>'});"><?php the_excerpt(); ?></a>
		                  </div>
			 <?php
				endwhile; 
			 endif; 
		 ?>
         </div>
		 
<div id="index-half-bottom">
	    <div id ="index-feature"> 
	   <br>
       <a class="largeBlue" href="http://transitioncoalition.org/blog/transition-coalition-self-study/">Professional Development through Self-Study</a>
	    <p style= "text-align:center;color:#807f7f; font-weight: 500;padding-left: 20px;padding-top: 10px; font-style:italic; font-size: 15px;">
       "Thanks again for the wonderfully organized and executed and immensely valuable Self-Study experience"<br>
       <span style="font-style:italic; font-size: 14px;">~ Steve, Interrelated Teacher</span></p>
   
	  <p><strong>Enhance your transition program</strong><br>
	    <div id="indexFeaturedListContainer">
	  <ul class="indexFeatureList">
	   <li>Online learning</li>
	   <li>Group discussions</li>
	   <li>Applied learning activities</li>
	   <li>Data driven analysis of your current practices</li>
	   <li>Action planning and implementations</li>
	  </ul>
	  <br></p>
	  </div>
	  <input type="button" onClick="ga('send', 'event', { eventCategory: 'find out more', eventAction: 'button'}); self.location='http://www.transitioncoalition.org/?p=11235/';" value="Find out more!">
      </div>

<div style="clear: both;"></div>
</div>
     <div id ="index_doublewide">    
	<a class="largeBlue"  href="/news">News and Noteworthy</a>
	<br>
	<?php
	$args = array(
	'posts_per_page'   => 6,
	'category'         => 38,
	'orderby'          => 'id',
	'order'            => 'DESC',
	'post_type'        => 'post',
	'post_status'      => 'publish',
	'suppress_filters' => true ); 
	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post ); 
	?>
        <article id="post-<?php the_ID(); ?>">	
<p >
	  <?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
	            echo "<span style='padding-right: 10px; vertical-align:top;float:left;'>";
		    the_post_thumbnail( 'thumb-small');
		    echo "</span>";
		    }
		   ?>
		<a class="newsitem" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>" ><strong><?php the_title(); ?></strong></a><span class="post-date-newslist">Posted on <?php the_time('M j, Y'); ?></span><br>
			<!--/.post-title-->
			
		        <?php the_excerpt(); ?>
		
              <br></p>
	<div style="clear:both;"></div>
         </article><!--/.post-->		
          <?php endforeach;?>
	</div>	
</div>
 
</div><!--/.pad-->
</section><!--/.content-->

<?php get_sidebar(); ?>
<?php get_template_part('sidebar-2'); ?>

<?php get_footer(); ?>
<!-- js -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-1.9.1.min.js"></script> 
<script src="<?php echo get_stylesheet_directory_uri(); ?>/owl-carousel/owl.carousel.js"></script>
<script>
    $(document).ready(function() {
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

