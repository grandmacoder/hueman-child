<?php
/*
Unit Template Name: Image Map Hotspot CI3T
image hotspot tooltip code found at http://www.inabrains.com/tooltip/examples/image-hotspot.html
*/
/**
 *
 * @package WordPress
 */
get_header(); 
?>

<link href="<?php bloginfo('stylesheet_directory');?>/prettify.css" rel="stylesheet" />
<link href="<?php bloginfo('stylesheet_directory');?>/litetooltip.min.css" rel="stylesheet" />
<script src="<?php bloginfo('stylesheet_directory');?>/js/hotspottip.min.js"></script>



<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#photspot1").css({
             "border": "solid 1px #000099",
            "background": "#0000cc",
            "border-radius": "20px"
        });
 
        $("#photspot2").css({
            "border": "solid 1px #000099",
            "background": "#0000cc",
            "border-radius": "20px"
        });
 
        $("#photspot3").css({
           "border": "solid 1px #000099",
            "background": "#0000cc",
            "border-radius": "20px"
        });
 
        function blink_hotspot() {
            $('#photspot1').animate({ "opacity": '0.3' }, 'slow').animate({ 'opacity': '0.8' }, 'slow', function () { blink_hotspot(); });
            $('#photspot2').animate({ "opacity": '0.3' }, 'slow').animate({ 'opacity': '0.8' }, 'slow', function () { blink_hotspot(); });
            $('#photspot3').animate({ "opacity": '0.3' }, 'slow').animate({ 'opacity': '0.8' }, 'slow', function () { blink_hotspot(); });
        }
 
        blink_hotspot();

 
    $('#photspot1').LiteTooltip({
        location: 'top',
        trigger: 'hoverable',
        textalign: 'left',
        padding: 5,
		width: 300,
        templatename: "Spindle",
        title:
        '<div class="template">' +
		'<h4><i class="icon-flag"></i> Results</h4>' +
        '<p>The screening tool gives a quick reference to students who may need more intensive inverventions regarding behavior.</p>' +
		'<p>This is useful to determine if a student is in need of Tiers 2 & 3 supports.</p>' +
		'<a target=_blank href="http://www.ci3t.org/screening#srssie">Learn about this screening tool(SRSS-IE).</a>'+
        '</div>'
    });
 
    $('#photspot2').LiteTooltip({
        location: 'bottom',
		trigger: 'hoverable',
        textalign: 'left',
        templatename: 'Spindle',
        width: 300,
        padding: 5,
        title:
        '<div class="template">' +
        '<h4><i class="icon-key"></i> Key Comparison</h4>' +
        '<p>The key corresponds with the risk level and intervention level associated with  <strong>Behavior</strong> from the ABC indicators</p>' +
        '</div>'
    });
 
    $('#photspot3').LiteTooltip({
		    trigger: 'hoverable',
            textalign: 'left',
            width: 300,
            padding: 5,
            templatename: "Spindle",
            title:
		    '<div class="template">' +
			'<h4><i class="icon-star"></i> Scale and Rating</h4>' +
			'<p>Students are individually rated on seven items using a 4-point Likert-type scale: <br>'+
		     'never = 0, occasionally = 1, sometimes = 2, frequently = 3.</p>' +
			 '<a href="http://ci3t.org/xlsx/2015-2016_SRSS-IE_ES_MSHS_posted_2015_09_06_F.xls">Download an example .xlxs file.</a>'+
			'</div>'
		});
});
</script>

<div class="content">
	<?php get_template_part('inc/page-title'); ?>
<div class="pad group">
<h3><?php the_title();?></h3>
<section>
<?php the_content();?>

</section>
</div>
</div><!--content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?> 



