<?php 
$user 		= wp_get_current_user();
$user_name 	= $user->display_name;

?>

<div class="setup-wizard-welcome-panel">
	<h1 class="cx-welcome"><?php _e( 'Welcome to CoDesigner!', 'codesigner' ); ?></h1>
	<p class="cx-wizard-sub">
		<?php printf( __( 'Thanks for installing CoDesigner, %s. We\'re so happy to have you with us.', 'codesigner' ), $user_name ); ?>
	</p>

	<p class="cx-wizard-sub">
		<?php _e( 'This wizard will help you configure the basic things needed to get started. It won\'t take more than a minute!', 'codesigner' ); ?>
	</p>

	<div class="setup-wizard-subs">

		<h3 id="setup-wizard-subtitle"><?php _e( 'Join Our Community', 'codesigner' ); ?></h3>

		<p class="cx-wizard-sub"><?php _e( 'Join our thriving community of 70,000+ users today. By becoming a part of our community, you not only get <strong>special coupons</strong> but also gain access to valuable insights and updates.', 'codesigner' ); ?></p>
		<p class="cx-wizard-sub"><?php _e( 'Don\'t miss out on this <strong>limited-time</strong> offer!', 'codesigner' ); ?></p>
		
		<input id="setup-wizard-email" type="email" name="email" value="<?php echo $user->user_email; ?>" placeholder="<?php _e( 'Enter your email address to receive the coupon', 'codesigner' ); ?>" />
		<p class="cx-wizard-desc"><?php _e( 'It\'s optional and you simply can leave this field blank. But, we\'d recommend you subscribe. We never spam. Promise.' ); ?></p>
	</div>
</div>

<?php
update_option( 'codesigner_setup_done', 1 );