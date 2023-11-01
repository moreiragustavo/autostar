<?php
/**
 * All settings related functions
 */
namespace Codexpert\CoDesigner\App;
use Codexpert\CoDesigner\Helper;
use Codexpert\Plugin\Base;
use Codexpert\Plugin\Settings as Settings_API;

/**
 * @package Plugin
 * @subpackage Settings
 * @author Codexpert <hi@codexpert.io>
 */
class Settings extends Base {

	public $plugin;
	
	public $slug;

	public $name;

	public $version;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}
	
	public function init_menu() {
		global $codesigner, $codesigner_pro;

		$site_config = [
			'PHP Version'				=> PHP_VERSION,
			'WordPress Version' 		=> get_bloginfo( 'version' ),
			'WooCommerce Version'		=> is_plugin_active( 'woocommerce/woocommerce.php' ) ? get_option( 'woocommerce_version' ) : 'Not Active',
			'Elementor Version'			=> is_plugin_active( 'elementor/elementor.php' ) ? get_option( 'elementor_version' ) : 'Not Active',
			'Elementor Pro Version'		=> is_plugin_active( 'elementor-pro/elementor-pro.php' ) ? get_option( 'elementor_pro_version' ) : 'Not Active',
			'CoDesigner Version'		=> $this->version,
			'CoDesigner Pro Version'	=> defined( 'CODESIGNER_PRO' ) ? $codesigner_pro['Version'] : 'Not Active',
			'CoDesigner Pro License'	=> defined( 'CODESIGNER_PRO' ) && wcd_is_pro_activated() ? 'Activated' : 'Not Activated',
			'Memory Limit'				=> defined( 'WP_MEMORY_LIMIT' ) && WP_MEMORY_LIMIT ? WP_MEMORY_LIMIT : 'Not Defined',
			'Debug Mode'				=> defined( 'WP_DEBUG' ) && WP_DEBUG ? 'Enabled' : 'Disabled',
			'Active Plugins'			=> get_option( 'active_plugins' ),
			'Checkout Page ID'			=> get_option( 'woocommerce_checkout_page_id' ),
			'Enable Debug' 				=> get_option( 'wl_enable_debug' ),
			'Enabled Widgets'			=> wcd_active_widgets(),
			'Checkout Fields'			=> defined( 'CODESIGNER_PRO' ) ? get_option( '_wcd_checkout_fields' ) : [],
		];

		$settings = [
			'id'            => $this->slug,
			'label'         => $this->name,
			'title'         => $this->name,
			'header'        => $this->name,
			'icon'			=> CODESIGNER_ASSETS . '/img/icon.png',
			'position'		=> 58,
			'topnav'		=> 'top',
			'sections'      => [
				'codesigner_general'	=> [
					'id'        => 'codesigner_general',
					'label'     => __( 'General', 'codesigner' ),
					'icon'      => 'dashicons dashicons-admin-generic',
					'color'		=> '#42bea9',
					'hide_form'	=> true,
					'template'  => CODESIGNER_DIR . '/views/settings/general.php',
				],
				'codesigner_widgets'	=> [
					'id'        => 'codesigner_widgets',
					'label'     => __( 'Widgets', 'codesigner' ),
					'icon'      => 'dashicons-screenoptions',
					'color'		=> '#42bea9',
					'sticky'	=> false,
					'template'  => CODESIGNER_DIR . '/views/settings/widgets.php',
				],
				'wcd_email_designer'	=> [
					'id'        => 'wcd_email_designer',
					'label'     => __( 'Email Designer', 'codesigner' ),
					'icon'      => 'dashicons-email-alt',
					'color'		=> '#7D3CF7',
					'hide_form'	=> true,
					'template'  => CODESIGNER_DIR . '/views/settings/email-designer.php',
				],
				'wcd_templates'	=> [
					'id'        => 'wcd_templates',
					'label'     => __( 'Template Library', 'codesigner' ),
					'icon'      => 'dashicons-download',
					'color'		=> '#266fb8',
					'hide_form'	=> true,
					'template'  => CODESIGNER_DIR . '/views/settings/templates.php',
				],
				'codesigner_tools'	=> [
					'id'        => 'codesigner_tools',
					'label'     => __( 'Tools', 'codesigner' ),
					'icon'      => 'dashicons-admin-tools',
					'color'		=> '#f29a20',
					'page_load'	=> true,
					'sticky'	=> false,
					'fields'    => [
						'enable_debug' => [
							'id'      	=> 'enable_debug',
							'label'     => __( 'Enable Debug', 'codesigner' ),
							'type'      => 'switch',
							'desc'      => __( 'Enable this if you face any CSS or JS related issues.', 'codesigner' ),
							'disabled'  => false,
						],
						'quantity_input' => [
							'id'      	=> 'quantity_input',
							'label'     => __( 'Fix Quantity Button', 'codesigner' ),
							'type'      => 'switch',
							'desc'      => sprintf( __( 'Check this if you see the %s and %s buttons twice in the cart.', 'codesigner' ), '<button type="button">+</button>', '<button type="button">-</button>' ),
							'disabled'  => false,
						],
						'add-to-cart-text' => [
							'id'      	=> 'add-to-cart-text',
							'label'     => __( 'Add to Cart Text', 'codesigner' ),
							'type'      => 'text',
							'default'   => __( 'Add to Cart', 'codesigner' ),
							'desc'   	=> __( 'Enable this if you want to change the default text of the \'Add to Cart\' button.', 'codesigner' ),
						],
						'redirect_to_checkout' => [
							'id'      	=> 'redirect_to_checkout',
							'label'     => __( 'Skip Cart Page', 'codesigner' ),
							'type'      => 'switch',
							'desc'		=> __( 'Enable this if you want to skip the cart page and take customers directly to the checkout page after they add products to the cart.', 'codesigner' ),
						],
						'cross_domain_copy_paste' => [
							'id'      	=> 'cross_domain_copy_paste',
							'label'     => __( 'Cross-domain Copy Paste', 'codesigner' ),
							'type'      => 'switch',
							'desc'		=> __( 'Enable this if you want to enable cross-domain copy &amp; paste feature.', 'codesigner' ),
						],
						'reset' => [
							'id'      	=> 'reset',
							'label'     => __( 'Reset Settings', 'codesigner' ),
							'type'      => 'switch',
							'desc'      => __( 'This will reset every changes you\'ve made. Don\'t do this unless you know what you\'re doing.', 'codesigner' ),
							'disabled'  => false,
						],						
						// ],
						// 'report' => [
						// 	'id'      => 'report',
						// 	'label'     => __( 'Report', 'codesigner' ),
						// 	'type'      => 'textarea',
						// 	'desc'     	=> '<button id="wl-report-copy" class="button button-primary"><span class="dashicons dashicons-admin-page"></span></button>',
						// 	'columns'   => 24,
						// 	'rows'      => 10,
						// 	'default'   => json_encode( $site_config, JSON_PRETTY_PRINT ),
						// 	'readonly'  => true,
						// ],
					]
				],
				'wcd_help' => [
					'id'        => 'wcd_help',
					'label'     => __( 'Help &amp; Support', 'codesigner' ),
					'icon'      => 'dashicons-sos',
					'color'		=> '#008742',
					'template'  => CODESIGNER_DIR . '/views/settings/help.php',
					'hide_form'	=> true,
				],
				'wcd_upgrade'	=> [
					'id'        => 'wcd_upgrade',
					'label'     => __( 'Premium Features', 'codesigner' ),
					'icon'      => 'dashicons-buddicons-groups',
					'color'		=> '#ec5ca7',
					'hide_form'	=> true,
					'template'  => CODESIGNER_DIR . '/views/settings/pro-features.php',
				],
			],
		];

		new Settings_API( apply_filters( 'codesigner-settings_args', $settings ));
	}

	public function reset( $option_name, $posted ) {

		if ( isset( $posted['reset'] ) && $posted['reset'] == 'on' ) {
			delete_option( $option_name );
			delete_option( 'codesigner_widgets' );
			delete_option( 'codesigner_tools' );
			delete_option( 'wcd_email_designer' );
		}
	}
}