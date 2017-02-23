				</div><!--/.main-inner-->
			</div><!--/.main-->
		</div><!--/.container-inner-->

	</div><!--/.container-->

	<footer id="footer">
	<?php
	$numactivitiesonscreen=get_post_meta( get_the_ID(), 'number_of_activities', true );
	?>
 <div id="numActivitiesOnScreen"><div style="display:none;"><?php echo $numactivitiesonscreen;?></div></div>	
<!-- modal messages for errors -->
<div id="dialog-nodiscussion-message" title="Discussion error" style="display:none;"><p>Oops, we haven't heard from you. You are required to participate in all the discussions before marking this item as complete. Spend a little time networking with others in the group by sharing your insights. Thanks!</p></div>
<div id="dialog-notextarea-message" title="Activity questions error" style="display:none;"><p>Oops, be sure to provide answers for all text boxes on this screen. You must complete them all before marking this as complete. Thanks.</p> </div>
		
		<?php // footer widgets
			$total = 4;
			if ( ot_get_option( 'footer-widgets' ) != '' ) {
				
				$total = ot_get_option( 'footer-widgets' );
				if( $total == 1) $class = 'one-full';
				if( $total == 2) $class = 'one-half';
				if( $total == 3) $class = 'one-third';
				if( $total == 4) $class = 'one-fourth';
				}

				if ( ( is_active_sidebar( 'footer-1' ) ||
					   is_active_sidebar( 'footer-2' ) ||
					   is_active_sidebar( 'footer-3' ) ||
					   is_active_sidebar( 'footer-4' ) ) && $total > 0 ) 
		{ ?>		
		<section class="container" id="footer-widgets">
			<div class="container-inner">
		                <div class="pad group">
					<?php $i = 0; while ( $i < $total ) { $i++; ?>
						<?php if ( is_active_sidebar( 'footer-' . $i ) ) { ?>
					
					<div class="footer-widget-<?php echo $i; ?> grid <?php echo $class; ?> <?php if ( $i == $total ) { echo 'last'; } ?>">
					        <?php if ($i == 4){
						echo "<div id='copyright'>";?>
							<?php if ( ot_get_option( 'copyright' ) ): ?>
								<p><?php echo ot_get_option( 'copyright' ); ?></p>
							<?php else: ?>
								<p><?php bloginfo(); ?> &copy; <?php echo date( 'Y' ); ?>. <?php _e( 'All Rights Reserved.', 'hueman' ); ?></p>
						<br>
						<?php endif; ?>
						<?php 
						echo "</div>";
						}
						?>
						<?php dynamic_sidebar( 'footer-' . $i ); ?>
					</div>
					        <?php } ?>
					<?php } ?>
				</div><!--/.pad-->
				
			</div><!--/.container-inner-->
		</section><!--/.container-->	
		<?php } ?>
		
		<?php if ( has_nav_menu( 'footer' ) ): ?>
			<nav class="nav-container group" id="nav-footer">
				<div class="nav-toggle"><i class="fa fa-bars"></i></div>
				<div class="nav-text"><!-- put your mobile menu text here --></div>
				<div class="nav-wrap"><?php wp_nav_menu( array('theme_location'=>'footer','menu_class'=>'nav container group','container'=>'','menu_id'=>'','fallback_cb'=>false) ); ?></div>
				
			</nav><!--/#nav-footer-->
		<?php endif; ?>
		<div class="disclaimer" id="disclaimer">
		The University of Kansas <a href="http://policy.ku.edu/IOA/nondiscrimination" title="non descrimination policy" target="_blank">prohibits discrimination </a>on the basis of race, color, ethnicity, religion, sex, national origin, age, ancestry, disability, status as a veteran, sexual orientation, marital status, parental status, gender identity, gender expression and genetic information in the University's programs and activities. The following person has been designated to handle inquiries regarding the non-discrimination policies: Director of the Office of Institutional Opportunity and Access, IOA@ku.edu. <br>
		1200 Sunnyside Ave.  | Room 3109, Lawrence, KS, 66045 | 785-864-0686.

		</div>
		<section class="container" id="footer-bottom">
			<div class="container-inner">
				
				<a id="back-to-top" href="#">^</a>
				<div class="pad group">
					
					<div class="grid one-half">
						
						<?php if ( ot_get_option('footer-logo') ): ?>
							<img id="footer-logo" src="<?php echo ot_get_option('footer-logo'); ?>" alt="<?php get_bloginfo('name'); ?>">
						<?php endif; ?>
					</div><!--/.pad-->
					
					<div class="grid one-half last">	
						<?php alx_social_links() ; ?>
					</div>
				
				</div>
				
			</div><!--/.container-inner-->
		</section><!--/.container-->
		
	</footer><!--/#footer-->

</div><!--/#wrapper-->

<?php wp_footer(); ?>
</body>
</html>