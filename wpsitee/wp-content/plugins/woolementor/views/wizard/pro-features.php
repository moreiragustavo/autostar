<?php
$checkout 			= CODESIGNER_ASSETS . '/img/customizable-checkout.png';
$emails 			= CODESIGNER_ASSETS . '/img/customizable-transactional-emails.png';
$header_footer 		= CODESIGNER_ASSETS . '/img/header-footer-archive.png';
$restriction 		= CODESIGNER_ASSETS . '/img/content-restriction-setup.png';
$features_bg 		= CODESIGNER_ASSETS . '/img/features-bg.png';
?>
<div class="setup-wizard-pro-features-panel">
	<h2><?php _e( '8+ premium features and 54+ widgets.', 'codesigner' ); ?></h2>
	<div class="setup-wizard-pro-features">
		<div class="setup-wizard-pro-feature">
			<div class="setup-wizard-pro-features-img">
				<img src="<?php echo esc_url( $checkout ); ?>" alt="">
			</div>
			<div class="setup-wizard-pro-features-content">
				<h4><?php _e( 'Customizable Checkout', 'codesigner' ); ?> 🔥</h4>
				<p><?php _e( 'Helps you customize your checkout page. Adding new billing
				or shipping fields, changing field attributes, 
				styling your own.. You name it.', 'codesigner' ); ?></p>
				<a target="_blank" href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab#pricing"><button type="button"><?php _e( 'Learn More..', 'codesigner' ); ?></button></a>
			</div>
		</div>
		<div class="setup-wizard-pro-feature">
			<div class="setup-wizard-pro-features-content">
				<h4><?php _e( 'Customizable Transactional Emails', 'codesigner' ); ?> 🔥</h4>
				<p><?php _e( 'Now you can customize WooCommerce emails with Elementor. The system-generated emails were not customizable with Elementor until CoDesigner made it possible.', 'codesigner' ); ?></p>
				<a target="_blank" href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab#pricing"><button type="button"><?php _e( 'Learn More..', 'codesigner' ); ?></button></a>
			</div>
			<div class="setup-wizard-pro-features-img">
				<img src="<?php echo esc_url( $emails ); ?>" alt="">
			</div>
		</div>
		<div class="setup-wizard-pro-feature">
			<div class="setup-wizard-pro-features-img">
				<img src="<?php echo esc_url( $header_footer ); ?>" alt="">
			</div>
			<div class="setup-wizard-pro-features-content">
				<h4><?php _e( 'Header, Footer, Archive & Much More', 'codesigner' ); ?> 🔥</h4>
				<p><?php _e( 'CoDesigner made it possible to create Elementor templates like Header, Footer, Single Product & Shop pages. You can decide where to show your templates based on product categories, product ID etc.', 'codesigner' ); ?></p>
				<a target="_blank" href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab#pricing"><button type="button"><?php _e( 'Learn More..', 'codesigner' ); ?></button></a>
			</div>
		</div>
		<div class="setup-wizard-pro-feature">
			<div class="setup-wizard-pro-features-content">
				<h4><?php _e( 'Content Restriction', 'codesigner' ); ?> 🔥</h4>
				<p><?php _e( 'Want to hide some of your contents from non-logged in users? Or perhaps a message needs to be shown during the night while your support is offline? We\'ve got your covered.', 'codesigner' ); ?></p>
				<a target="_blank" href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab#pricing"><button type="button"><?php _e( 'Learn More..', 'codesigner' ); ?></button></a>
			</div>
			<div class="setup-wizard-pro-features-img">
				<img src="<?php echo esc_url( $restriction ); ?>" alt="">
			</div>
		</div>
	</div>
	<div class="setup-wizard-more-features-panel">
		<h2><?php _e( 'More Features', 'codesigner' ); ?></h2>
		<div class="setup-wizard-more-features">
			<div class="setup-wizard-more-feature">
				<h4><a href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab" target="_blank"><?php _e( 'More Shop Widgets', 'codesigner' ); ?></a></h4>
			</div>
			<div class="setup-wizard-more-feature">
				<h4><a href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab" target="_blank"><?php _e( 'Ready-Made Templates', 'codesigner' ); ?> 🔥</a></h4>
			</div>
			<div class="setup-wizard-more-feature">
				<h4><a href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab" target="_blank"><?php _e( 'Amazing Pricing Tables', 'codesigner' ); ?></a></h4>
			</div>
			<div class="setup-wizard-more-feature">
				<h4><a href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab" target="_blank"><?php _e( 'Smart Wishlist Management', 'codesigner' ); ?>🔥</a></h4>
			</div>
			<div class="setup-wizard-more-feature">
				<h4><a href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab" target="_blank"><?php _e( 'Display Recent Sales', 'codesigner' ); ?></a></h4>
			</div>
			<div class="setup-wizard-more-feature">
				<h4><a href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab" target="_blank"><?php _e( 'And much more...', 'codesigner' ); ?></a></h4>
			</div>
		</div>
	</div>
	<div class="setup-wizard-enable-features-panel">
		<div class="cx-field-wrap ">
			<label class="cx-toggle">
				<input type="checkbox" name="enable_features" id="codesigner_tools-enable_features" class="cx-toggle-checkbox cx-field cx-field-switch" value="on">
				<div class="cx-toggle-switch"></div>
				<span><?php _e( 'Enable these features', 'codesigner' ); ?></span>
			</label>
		</div>
		<div class="setup-wizard-enable-features-content" style="background-image: url(<?php echo esc_url( $features_bg ); ?>); display: none;">
			<h4><?php _e( 'Get these Premium Features', 'codesigner' ); ?></h4>
			<p><?php _e( 'These features are only available in <u>CoDesigner Pro</u>!', 'codesigner' ); ?></p>
			<p><?php _e( 'Make a smart choice today; a <u>small investment</u> can lead to a <u>big boost</u> in your sales. Your decision can make a significant difference.', 'codesigner' ); ?></p>
			<a target="_blank" href="https://codexpert.io/codesigner/?utm_source=dashboard&utm_medium=settings&utm_campaign=pro-tab#pricing"><button type="button"><?php _e( 'Upgrade to Pro', 'codesigner' ); ?></button></a>

			<div class="cx-field-wrap ">
				<label class="cx-toggle">
					<input type="checkbox" name="enable_remind" id="codesigner_tools-enable_remind" class="cx-toggle-checkbox cx-field cx-field-switch" value="on">
					<div class="cx-toggle-switch" style="display: none;"></div>
					<p><?php _e( 'Remind me later', 'codesigner' ); ?></p>
				</label>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(function($){
		$(document).on( 'click', '#codesigner_tools-enable_features', function (e) {
			if ( $(this).is(':checked') ) {
				$('.setup-wizard-enable-features-content').slideDown();
				$('.setup-wizard-pro-features-panel').addClass('enable_features');
			}
			else {
				$('.setup-wizard-enable-features-content').slideUp();
				$('.setup-wizard-pro-features-panel').removeClass('enable_features');
			}
		} );

		$(document).on( 'click', '#codesigner_tools-enable_remind', function (e) {
			if ( $(this).is(':checked') ) {
				$('#cx-pro-features-form').submit();
			}
		} );
	});
</script>