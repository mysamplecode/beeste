<?php global $product; ?>
<li>
	<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
		<span><?php echo $product->get_title(); ?></span>
		<?php echo $product->get_image(); ?>
		
	</a>
	<?php echo $product->get_price_html(); ?>
	<?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
	
</li>