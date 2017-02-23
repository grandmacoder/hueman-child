<article id="post-<?php the_ID(); ?>">	
          <p>
		<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h3><!--/.post-title-->
		<span class="post-date"><?php the_time('M j, Y'); ?></span>
		</p>
	  <?php the_excerpt(); ?>
	<div style="clear:both;"></div>
</article><!--/.post-->	