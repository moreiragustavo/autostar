<?php 
$banner 		= CODESIGNER_ASSETS . '/img/settings-header-banner.png';
$footer_banner 	= CODESIGNER_ASSETS . '/img/settings-footer-banner.png';
$support 		= CODESIGNER_ASSETS . '/img/customer-support.png';
$documentation 	= CODESIGNER_ASSETS . '/img/documentation.png';
$love 			= CODESIGNER_ASSETS . '/img/love.png';
$logo 			= CODESIGNER_ASSETS . '/img/codesigner-logo.png';
$contribute 	= CODESIGNER_ASSETS . '/img/contribute.png';
$video 			= CODESIGNER_ASSETS . '/img/codesigner_video.png';

$utm			= [ 'utm_source' => 'dashboard', 'utm_medium' => 'settings', 'utm_campaign' => 'pro-tab' ];
$pro_link		= add_query_arg( $utm, 'https://codexpert.io/codesigner/pricing' );
?>
<div class="wl-content-panel">
	<div class="wl-header-banner">
		<a href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab" target="_blank"><img src="<?php echo esc_url( $banner ); ?>" /></a>
	</div>
	<div class="wl-services-panel">
		<div class="wl-services-content">
			<div class="wl-single-service">
				<div class="wl-single-service-logo">
					<img src="<?php echo esc_url( $love ); ?>" alt="">
				</div>
				<div class="wl-single-service-content red">
					<h4><?php _e( 'Show your Love', 'codesigner' ); ?></h4>
					<p><?php _e( 'Your reviews highly motivate us to improve and add exciting features on CoDesigner.', 'woocommerce' ); ?></p>
					<a target="_blank" href="https://wordpress.org/support/plugin/codesigner/reviews/?filter=5"><?php _e( 'Leave a review', 'codesigner' ); ?></a>
				</div>
			</div>
			<div class="wl-single-service">
				<div class="wl-single-service-logo">
					<img src="<?php echo esc_url( $documentation ); ?>" alt="">
				</div>
				<div class="wl-single-service-content pink">
					<h4><?php _e( 'Documentation', 'codesigner' ); ?></h4>
					<p><?php _e( 'Stuck with an issue? Our documentations will guide you through the solution.', 'woocommerce' ); ?></p>
					<a target="_blank" href="https://codexpert.io/codesigner/docs/general/"><?php _e( 'Documentation', 'codesigner' ); ?></a>
				</div>
			</div>
			<div class="wl-single-service">
				<div class="wl-single-service-logo">
					<img src="<?php echo esc_url( $support ); ?>" alt="">
				</div>
				<div class="wl-single-service-content yellow">
					<h4><?php _e( 'Customer Support', 'woocommerce' ); ?></h4>
					<p><?php _e( 'We have a super friendly support team to provide you with technical assistance and answers.', 'codesigner' )	; ?></p>
					<a target="_blank" href="https://help.codexpert.io/tickets/"><?php _e( 'Support', 'codesigner' ); ?></a>
				</div>
			</div>
			<div class="wl-single-service">
				<div class="wl-single-service-logo">
					<img src="<?php echo esc_url( $contribute ); ?>" alt="">
				</div>
				<div class="wl-single-service-content blue">
					<h4><?php _e( 'Blog', 'codesigner' ); ?></h4>
					<p><?php _e( 'Get to know more about CoDesigner from our blog posts. You will also get informative tutorials on customizations and tricks.', 'woocommerce' ); ?></p>
					<a target="_blank" href="https://codexpert.io/blog/"><?php _e( 'Visit', 'codesigner' ); ?></a>
				</div>
			</div>
		</div>
		<div class="wl-services-video">
			<a href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab" target="_blank">
				<img src="<?php echo esc_url( $video ); ?>" alt="">
			</a>
		</div>
	</div>
	<div class="wl-bottom-panel">
		<div class="wl-footer-banner" style="background-image: url(<?php echo esc_url( $footer_banner ); ?>);">
			<div class="wl-footer-banner-left">
				<img src="<?php echo esc_url( $logo ); ?>" alt="">
				<p><?php _e( 'Customize your WooCommerce store with Elementor.', 'codesigner' ); ?></p>
			</div>
			<?php if ( ! wcd_is_pro_activated() ):
				echo "<a target='_blank' class='wl-upgrade-btn' href='{$pro_link}'>". __( 'Upgrade to Pro', 'codesigner' ) ."</a>";
			else:
				echo "<a target='_blank' class='wl-upgrade-btn' href='https://codexpert.io/codesigner/'>". __( 'Visit CoDesigner', 'codesigner' ) ."</a>";
			endif; ?>
		</div>
	</div>
</div>