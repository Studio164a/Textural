	<footer id="site_footer">

		<div class="cf">

			<section class="column is_first column_33">
				<?php dynamic_sidebar( 'footer_1' ) ?>
			</section>

			<section class="column column_33">
				<?php dynamic_sidebar( 'footer_2' ) ?>			
			</section>

			<section class="column is_last column_33">
				<?php dynamic_sidebar( 'footer_3' ) ?>				
			</section>

		</div>

		<div id="rockbottom">
			<?php if ( get_theme_mod( 'show_copyright' ) ) : ?>
				<p class="alignleft copyright"><?php echo get_theme_mod( 'copyright_text' ) ?></p>
			<?php endif ?>
			
			<?php osfa_social_links() ?>
		</div>

	</footer>
	
	<?php wp_footer() ?>

</body>
</html>