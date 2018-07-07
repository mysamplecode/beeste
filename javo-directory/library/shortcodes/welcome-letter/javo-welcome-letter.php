<?php
class javo_welcome_letter_func
{
	public function __construct()
	{
		add_shortcode( 'javo_welcome_letter', Array( __CLASS__ , 'callback') );
	}
	public function callback( $atts, $content="" )
	{
		extract( shortcode_atts( Array(
			'title'				=> __('Hello World', 'javo_fr')
			, 'descriptions'	=> __('No Description', 'javo_fr')
			, 'letter_contents'	=> ''
			
		), $atts) );
		ob_start();
		?>

		<div class="javo-welcome-letter-wrap">
			<hgroup class="header text-center">
				<h1><?php echo $title;?></h1>
			</hgroup><!-- /,header -->
			<section class="describe text-center">
				<article>
					<span class="describe-content">
						<?php echo $descriptions; ?>
					</span><!-- /.describe-content -->
				</article>
			</section><!-- /.describe -->
			<section class="letter-container text-center">
				<div class="letter-content-wrap">
					<article class="letter-content">
						<div>
							<?php echo $letter_contents;?>
						</div>
					</article>
				</div>
			</section><!-- /.letter-content -->
			<section class="letter-footer text-center">
				<h2 class="letter-footer-header"><?php _e("You Think This is Awesome? Purchase NOW!", 'javo_fr');?></h2>
				<button type="button" class="btn btn-info btn-lg"><?php _e("Purchase", 'javo_fr');?></button>
			</section>



		</div><!-- /.javo-welcome-letter-wrap -->
		<?php
		return ob_get_clean();
	}


}
new javo_welcome_letter_func();