<?php 
/**
 * A simple template part for sticky posts
 */

if ( is_sticky() && is_home() ) : ?>

	<div class="sticky_wrap">
		<p><i class="icon-star"></i> Sticky</p>
	</div>

<?php endif ?>