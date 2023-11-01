<?php
namespace Codexpert\CoDesigner\App;

use Codexpert\Plugin\Base;
use Codexpert\CoDesigner\Helper;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Codexpert <hi@codexpert.io>
 */
class Admin extends Base {

	public $plugin;

	public $slug;

	public $name;

	public $server;
	
	public $version;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->server	= $this->plugin['server'];
		$this->version	= $this->plugin['Version'];
	}

	/**
	 * Installer. Runs once when the plugin in activated.
	 *
	 * @since 1.0
	 */
	public function install() {

		if( ! get_option( 'codesigner_version' ) ){
			update_option( 'codesigner_version', $this->version );
		}
		
		if( ! get_option( 'codesigner_install_time' ) ){
			update_option( 'codesigner_install_time', date_i18n( 'U' ) );
		}
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'codesigner', false, CODESIGNER_DIR . '/languages/' );
	}

	public function upgrade() {
		if( $this->version == ( $old_version = get_option( "{$this->slug}_db-version" ) ) ) return;

		update_option( "{$this->slug}_db-version", $this->version, false );
		update_option( "{$this->slug}_update_time", date_i18n( 'U' ) );

		if( get_option( "{$this->slug}_install_time" ) == '' ) {
			update_option( "{$this->slug}_install_time", date_i18n( 'U' ), false );
		}

		do_action( "{$this->slug}_upgraded", $this->version, $old_version );
	}

	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {

		$min = defined( 'CODESIGNER_DEBUG' ) && CODESIGNER_DEBUG ? '' : '.min';
		
		global $current_screen;

		if( strpos( $current_screen->base, $this->slug ) === false ) return;
		
		/**
		 * CSS files
		 */
		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/admin{$min}.css", CODESIGNER ), '', $this->version, 'all' );
		wp_enqueue_style( "{$this->slug}-email-designer", plugins_url( "/assets/css/email-designer{$min}.css", CODESIGNER ), '', $this->version, 'all' );
		wp_enqueue_style( "{$this->slug}-pro-features", plugins_url( "/assets/css/pro-features{$min}.css", CODESIGNER ), '', $this->version, 'all' );
		wp_enqueue_style( "{$this->slug}-widgets-settings", plugins_url( "/assets/css/widgets-settings{$min}.css", CODESIGNER ), '', $this->version, 'all' );
		wp_enqueue_style( "{$this->slug}-library", plugins_url( "/assets/css/library{$min}.css", CODESIGNER ), '', $this->version, 'all' );

		/**
		 * JS files
		 */
		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/admin{$min}.js", CODESIGNER ), [ 'jquery' ], $this->version, true );
		wp_enqueue_script( "{$this->slug}-widgets-settings", plugins_url( "/assets/js/widgets-settings{$min}.js", CODESIGNER ), [ 'jquery' ], $this->version, true );

	    $localized = [
	    	'homeurl'		=> get_bloginfo( 'url' ),
	    	'adminurl'		=> admin_url(),
	    	'asseturl'		=> CODESIGNER_ASSETS,
	    	'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
	    	'_wpnonce'		=> wp_create_nonce(),
	    	'api_base'		=> get_rest_url(),
	    	'rest_nonce'	=> wp_create_nonce( 'wp_rest' ),
	    ];
	    wp_localize_script( $this->slug, 'CODESIGNER', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function add_menus() {
		add_menu_page( __( 'CoDesigner', 'codesigner' ), __( 'CoDesigner', 'codesigner' ), 'manage_options', $this->slug, '', CODESIGNER_ASSETS . '/img/icon.png', 58 );
		add_submenu_page( $this->slug, __( 'Dashboard', 'codesigner' ), __( 'Dashboard', 'codesigner' ), 'manage_options', $this->slug, function() {} );
	}

	public function action_links( $links ) {
		$this->admin_url = admin_url( 'admin.php' );

		$new_links = [
			'settings'	=> sprintf( '<a href="%1$s">' . __( 'Settings', 'codesigner' ) . '</a>', add_query_arg( 'page', $this->slug, $this->admin_url ) )
		];
		
		return array_merge( $new_links, $links );
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		
		if ( $this->plugin['basename'] === $plugin_file ) {
			$plugin_meta['help'] = '<a href="https://help.codexpert.io/" target="_blank" class="cx-help">' . __( 'Help', 'codesigner' ) . '</a>';
		}

		return $plugin_meta;
	}

	public function footer_text( $text ) {
		if( get_current_screen()->parent_base != $this->slug ) return $text;

		return sprintf( __( 'If you like <strong>%1$s</strong>, please <a href="%2$s" target="_blank">leave us a %3$s rating</a> on WordPress.org! It\'d motivate and inspire us to make the plugin even better!', 'codesigner' ), $this->name, "https://wordpress.org/support/plugin/woolementor/reviews/?filter=5#new-post", '⭐⭐⭐⭐⭐' );
	}

	/**
	 * Setup the instance
	 *
	 * @since 1.0
	 */
	public function setup() {
		add_image_size( 'codesigner-thumb', 400, 400, true );
	}

	/**
	 * Redirect
	 *
	 * @since 1.0
	 */
	public function settings_page_redirect() {
    	if( get_option( 'codesigner-activated' ) != 1 ) {
			update_option( 'codesigner-activated', 1 );
    		wp_safe_redirect( admin_url( 'admin.php?page=codesigner' ) );
			exit();
    	}
	}

	public function admin_notices() {

		if( get_option( '_codesigner-widget-usage' ) == '' ) return;

		$activated_time = get_option( 'codesigner_install_time' );

		if( get_option( 'codesigner_usage_survey_voted' ) == 1 || get_option( 'codesigner_usage_survey_disagree' ) == 1 || date_i18n( 'U' ) < $activated_time + 3 * DAY_IN_SECONDS ) return;

		// bail out
		if( isset( $_GET['cd-action'] ) && $_GET['cd-action'] != '' && isset( $_GET['type'] ) ) {

			$type = $this->sanitize( $_GET['type'] );

			if( $type == 'disagree' ) {
				update_option( 'codesigner_usage_survey_disagree', 1 );

				return;
			}
			elseif( $type == 'later' ) {
				update_option( 'codesigner_install_time', date_i18n( 'U' ) );

				return;
			}

			// send to API
			$args = [
				'widgets_enabled'	=> array_keys( get_option( 'codesigner_widgets' ) ),
				'widgets_used'		=> get_option( '_codesigner-widget-usage' ),
				'age'				=> human_time_diff( $activated_time ),
			];

			if( $type == 'full' ) {
				$user = get_userdata( get_current_user_id() );

				$args['user']	= [
					'first_name'	=> $user->first_name,
					'last_name'		=> $user->last_name,
					'user_email'	=> $user->user_email,
				];
			}

			$endpoint_url = 'https://codexpert.io/dashboard/wp-json/codesigner/survey';

			$_args = [
				'body'		=> json_encode( $args ),
				'headers'	=> [
					'Content-Type' => 'application/json',
				],
				'method' => 'POST',
			];

			$response = wp_remote_request( $endpoint_url, $_args );

			update_option( 'codesigner_usage_survey_voted', 1 );			

			return;
		}

		// init

		printf(
			'<div class="notice notice-error is-dismissible">
				<p>%1$s</p>
				<p>
					<a href="%5$s" class="button button-primary">%2$s</a>
					<a href="%6$s" class="button button-secondary">%3$s</a>
					<a href="%7$s" class="button">%4$s</a>
				</p>
				<a href="%8$s"><button class="notice-dismiss"></button></a>
			</div>',
			sprintf( __( 'Wow, it\'s been %s since you started using <strong>CoDesigner</strong>! We\'re thrilled to hear it\'s been helpful. Mind sharing which widgets you love using? Your input will help us improve and prioritize the features you need most.' ), human_time_diff( $activated_time ) ),
			__( 'Sure! I\'m OK with this', 'codesigner' ),
			__( 'Yes, but don\'t include my personal info', 'codesigner' ),
			__( 'Remind me later', 'codesigner' ),
			add_query_arg( [ 'cd-action' => 'usage-survey', 'type' => 'full' ] ),
			add_query_arg( [ 'cd-action' => 'usage-survey', 'type' => 'partial' ] ),
			add_query_arg( [ 'cd-action' => 'usage-survey', 'type' => 'later' ] ),
			add_query_arg( [ 'cd-action' => 'usage-survey', 'type' => 'disagree' ] ),
		);
	}

	public function setting_navs_add_item( $settings ) {
		$utm			= [ 'utm_source' => 'dashboard', 'utm_medium' => 'settings', 'utm_campaign' => 'pro-tab' ];
		$pro_link		= add_query_arg( $utm, 'https://codexpert.io/codesigner/#pricing' );

		if ( ! wcd_is_pro_activated() && $settings->config['id'] == 'codesigner' ) {
			echo '<li><a href="'. esc_url( $pro_link ) .'">Get Pro</a></li>';
		}
	}

	public function modal() {
		echo '
		<div id="codesigner-modal" style="display: none">
			<img id="codesigner-modal-loader" src="' . esc_attr( CODESIGNER_ASSETS . '/img/loader.gif' ) . '" />
		</div>';
	}

}