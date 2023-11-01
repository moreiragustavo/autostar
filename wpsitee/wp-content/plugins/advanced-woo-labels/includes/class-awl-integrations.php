<?php
/**
 * AWL plugin integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWL_Integrations' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWL_Integrations {
        
        /**
         * @var AWL_Integrations The single instance of the class
         */
        protected static $_instance = null;

        /**
         * @var AWL_Integrations Current theme name
         */
        public $current_theme = '';

        /**
         * @var AWL_Integrations Init theme name
         */
        public $child_theme = '';

        /**
         * Main AWL_Integrations Instance
         *
         * @static
         * @return AWL_Integrations - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            $theme = function_exists( 'wp_get_theme' ) ? wp_get_theme() : false;

            if ( $theme ) {
                $this->current_theme = $theme->get( 'Name' );
                $this->child_theme = $theme->get( 'Name' );
                if ( $theme->parent() ) {
                    $this->current_theme = $theme->parent()->get( 'Name' );
                }
            }

            $this->includes();

            add_filter( 'awl_labels_hooks', array( $this, 'awl_labels_hooks' ), 1 );

            add_filter( 'awl_label_container_styles', array( $this, 'awl_label_container_styles' ), 1, 3 );

            add_action( 'wp_head', array( $this, 'wp_head_styles' ) );

            add_action( 'awl_hide_default_sale_flash', array( $this, 'hide_default_sale_flash' ), 1 );

            add_action( 'awl_hide_default_stock_flash', array( $this, 'hide_default_stock_flash' ), 1 );

        }

        /**
         * Include files
         */
        public function includes() {

            if ( defined( 'WP_CLI' ) && WP_CLI ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-wp-cli.php' );
            }

            if ( defined( 'WOOLENTOR_VERSION' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-woolentor.php' );
            }

            if ( defined( 'WOOLEMENTOR' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-woolementor.php' );
            }

            if ( class_exists( 'SP_WooCommerce_Product_Slider' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-sp-slider.php' );
            }

            if ( defined( 'WDR_VERSION' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-wdr.php' );
            }

            if ( function_exists( 'AWS' ) || function_exists( 'AWS_PRO' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-aws.php' );
            }

            if ( defined( 'ELEMENTOR_VERSION' ) || defined( 'ELEMENTOR_PRO_VERSION' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-elementor.php' );
            }

            if ( class_exists( 'Jet_Woo_Builder' ) || class_exists( 'Jet_Engine' ) || class_exists( 'Jet_Smart_Filters' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-jet-plugins.php' );
            }

            if ( defined( 'YITH_YWDPD_VERSION' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-yith-discounts.php' );
            }

            if ( class_exists( 'FLBuilder' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-bb.php' );
            }

            if ( defined( 'ET_BUILDER_PLUGIN_DIR' ) || function_exists( 'et_setup_theme' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-divi.php' );
            }

            if ( class_exists( 'WC_Product_Table_Plugin' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-barn-tables.php' );
            }

            if ( defined( "UNLIMITED_ELEMENTS_VERSION" ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-unlimites-elements.php' );
            }

            if ( class_exists( 'XforWC_Product_Filters' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-prdctfltr.php' );
            }

            if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-wpml.php' );
            }

            if ( class_exists( 'ShopEngine' ) ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-shopengine.php' );
            }

            if ( defined('KADENCE_BLOCKS_PATH') ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-kadence.php' );
            }

            if ( defined('UAGB_FILE') ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-spectra.php' );
            }

            if ( 'Avada' === $this->current_theme ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-avada.php' );
            }

            if ( 'Flatsome' === $this->current_theme ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-flatsome.php' );
            }

            if ( 'Bricks' === $this->current_theme ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-bricks.php' );
            }

            if ( 'Astra' === $this->current_theme && defined('ASTRA_EXT_DIR') ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-astra.php' );
            }

            if ( 'Martfury' === $this->current_theme ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-martfury.php' );
            }

            if ( 'Virtue' === $this->current_theme ) {
                include_once( AWL_DIR . '/includes/modules/class-awl-virtue.php' );
            }

        }

        /*
         * Change display hooks
         */
        public function awl_labels_hooks( $hooks ) {

            $hooks = array(
                'on_image' => array(
                    'archive' => array(
                        'woocommerce_before_shop_loop_item_title' => array( 'priority' => 10 ),
                        'woocommerce_product_get_image' => array( 'priority' => 10, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::woocommerce_product_get_image', 'args' => 3 ),
                        'woocommerce_blocks_product_grid_item_html' => array( 'priority' => 10, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::woocommerce_blocks_product_grid_item_html_on_image', 'args' => 3 )
                    ),
                    'single' => array(
                        'woocommerce_product_thumbnails' => array( 'priority' => 10 )
                    ),
                ),
                'before_title' => array(
                    'archive' => array(
                        'woocommerce_shop_loop_item_title' => array( 'priority' => 9 ),
                        'woocommerce_blocks_product_grid_item_html' => array( 'priority' => 11, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::woocommerce_blocks_product_grid_item_html_before_title', 'args' => 3 )
                    ),
                    'single' => array(
                        'woocommerce_single_product_summary' => array( 'priority' => 4 )
                    ),
                ),
            );

            if ( is_singular( 'product' ) ) {
                if ( get_post_meta( get_queried_object_id(), '_product_image_gallery', true ) ) {
                    $hooks['on_image']['single'] = array( 'woocommerce_product_thumbnails' => array( 'priority' => 10, 'js' =>  array( '.woocommerce-product-gallery .flex-viewport, .woocommerce-product-gallery__wrapper', 'append' ) ) );
                }
            }

            switch ( $this->current_theme ) {

                case 'Aurum':
                    $hooks['on_image']['archive'] = array( 'get_template_part_tpls/woocommerce-item-thumbnail' => array( 'priority' => 10 ) );
                    $hooks['before_title']['archive'] = array( 'aurum_before_shop_loop_item_title' => array( 'priority' => 10 ) );
                    $hooks['on_image']['single'] = array( 'woocommerce_before_single_product_summary' => array( 'priority' => 25, 'js' =>  array( '.product-images-container .product-images--main', 'append' ) ) );
                    break;

                case 'Betheme':
                    $hooks['on_image']['archive'] = array(
                        'post_thumbnail_html' => array( 'priority' => 10, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::post_thumbnail_html', 'args' => 4 ),
                        'woocommerce_placeholder_img' => array( 'priority' => 10, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::betheme_woocommerce_placeholder_img', 'args' => 3 )
                    );
                    $hooks['before_title']['archive'] = array( 'woocommerce_after_shop_loop_item_title' => array( 'priority' => 10 ) );
                    break;

                case 'Porto':
                    $hooks['on_image']['single'] = array( 'woocommerce_single_product_image_html' => array( 'priority' => 10, 'type' => 'filter'  ) );
                    break;

                case 'Devita':
                    $hooks['on_image']['archive'] = array( 'woocommerce_before_shop_loop_item' => array( 'priority' => 10 ) );
                    break;

                case 'Electro':
                    $hooks['on_image']['archive'] = array( 'electro_template_loop_product_thumbnail' => array( 'priority' => 10, 'type' => 'filter' ) );
                    break;

                case 'firezy':
                    $hooks['before_title']['archive'] = array( 'woocommerce_after_shop_loop_item_title' => array( 'priority' => 10 ) );
                    break;

                case 'GreenMart':
                    $hooks['before_title']['archive'] = array( 'woocommerce_before_shop_loop_item_title' => array( 'priority' => 20 ) );
                    break;

                case 'HandMade':
                    $hooks['before_title']['archive'] = array( 'woocommerce_after_shop_loop_item_title' => array( 'priority' => 1 ) );
                    $hooks['on_image']['single'] = array( 'woocommerce_single_product_image_html' => array( 'priority' => 10, 'type' => 'filter' ) );
                    break;

                case 'Jupiter':
                    $hooks['on_image']['archive'] = array( 'woocommerce_after_shop_loop_item' => array( 'priority' => 10 ) );
                    $hooks['before_title']['archive'] = array( 'woocommerce_before_shop_loop_item' => array( 'priority' => 10 ) );
                    break;

                case 'MetroStore':
                    $hooks['on_image']['archive'] = array( 'post_thumbnail_html' => array( 'priority' => 10, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::post_thumbnail_html', 'args' => 4 ) );
                    break;

                case 'Kallyas':
                    $hooks['on_image']['archive'] = array( 'woocommerce_before_shop_loop_item' => array( 'priority' => 10, 'js' => array( '.kw-prodimage', 'append' ) ) );
                    break;

                case 'OceanWP';
                    $hooks['on_image']['archive'] = array( 'ocean_before_archive_product_image' => array( 'priority' => 10 ) );
                    $hooks['before_title']['archive'] = array( 'ocean_before_archive_product_categories' => array( 'priority' => 1 ), 'ocean_before_archive_product_title' => array( 'priority' => 1 ) );
                    $hooks['on_image']['single']['ocean_woo_quick_view_product_image'] = array( 'priority' => 10 );
                    $hooks['before_title']['single']['ocean_before_single_product_title'] = array( 'priority' => 10 );
                    break;

                case 'Shopkeeper';
                    $hooks['on_image']['archive'] = array( 'woocommerce_shop_loop_item_thumbnail' => array( 'priority' => 1 ) );
                    $hooks['before_title']['archive'] = array( 'woocommerce_shop_loop_item_thumbnail' => array( 'priority' => 10 ) );
                    $hooks['before_title']['single'] = array( 'woocommerce_single_product_summary_single_title' => array( 'priority' => 1 ) );
                    break;

                case 'Orchid Store':
                    $hooks['on_image']['archive'] = array( 'orchid_store_product_thumbnail' => array( 'priority' => 1 ) );
                    $hooks['before_title']['archive'] = array( 'orchid_store_shop_loop_item_title' => array( 'priority' => 5 ) );
                    break;

                case 'TheGem':
                    $hooks['before_title']['archive'] = array( 'woocommerce_before_shop_loop_item_title' => array( 'priority' => 10 ) );
                    $hooks['before_title']['single'] = array( 'thegem_woocommerce_single_product_right' => array( 'priority' => 1 ) );
                    $hooks['on_image']['single'] = array( 'thegem_woocommerce_single_product_left' => array( 'priority' => 1 ) );
                    break;

                case 'Oxygen':
                    $hooks['before_title']['archive'] = array( 'oxygen_woocommerce_after_loop_item_title' => array( 'priority' => 10, 'js' => array( '.woocommerce-loop-product__title', 'before' ) ) );
                    $hooks['on_image']['single'] = array( 'oxygen_woocommerce_single_product_before_images' => array( 'priority' => 10 ) );
                    break;

                case 'Konado':
                    $hooks['on_image']['archive'] = array( 'woocommerce_before_shop_loop_item' => array( 'priority' => 10 ) );
                    $hooks['before_title']['archive'] = array( 'woocommerce_after_shop_loop_item' => array( 'priority' => 10, 'js' => array( '.product-name', 'prepend' ) ) );
                    break;

                case 'Woodmart':
                    $hooks['on_image']['single'] = array( 'woocommerce_single_product_summary' => array( 'priority' => 10, 'js' =>  array( '.woocommerce-product-gallery figure', 'append' ) ) );
                    break;

                case 'Stockie':
                    $hooks['on_image']['archive'] = array( 'woocommerce_sale_flash' => array( 'priority' => 10, 'type' => 'filter' ) );
                    $hooks['before_title']['archive'] = array( 'woocommerce_sale_flash' => array( 'priority' => 15, 'type' => 'filter', 'js' => array( '.font-titles', 'before' ) ) );
                    break;

                case 'Martfury':
                    $hooks['on_image']['archive'] = array( 'martfury_after_product_loop_thumbnail' => array( 'priority' => 10 ) );
                    break;

                case 'BoxShop':
                    $hooks['on_image']['single'] = array( 'boxshop_before_product_image' => array( 'priority' => 10 ) );
                    $hooks['before_title']['archive'] = array( 'woocommerce_after_shop_loop_item' => array( 'priority' => 10 ) );
                    break;

                case 'Rehub theme':
                    $hooks['on_image']['archive']['rh_woo_thumbnail_loop'] = array( 'priority' => 10 );
                    $hooks['before_title']['archive']['rh_woo_thumbnail_loop'] = array( 'priority' => 10 );
                    $hooks['before_title']['single']['rh_woo_single_product_title'] = array( 'priority' => 10 );
                    break;

                case 'XStore':
                    $hooks['on_image']['single']['woocommerce_single_product_image_thumbnail_html'] = array( 'priority' => 10, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::xstore_single_image', 'args' => 1 );
                    $hooks['before_title']['archive']['woocommerce_before_shop_loop_item_title'] = array( 'priority' => 20, 'callback' => 'AWL_Integrations_Callbacks::echo_title_centered' );
                    if ( get_option( 'etheme_single_product_builder', false ) ) {
                        $hooks['before_title']['single']['woocommerce_single_product_image_thumbnail_html'] = array( 'priority' => 11, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::xstore_single_image_title', 'args' => 1, 'js' => array( '.product_title.entry-title', 'before' ) );
                    }
                    break;

                case 'Royal':
                    $hooks['on_image']['single']['woocommerce_single_product_image_html'] = array( 'priority' => 10, 'type' => 'filter'  );
                    break;

                case 'Uncode':
                    $hooks['on_image']['archive'] = array( 'uncode_entry_visual_after_image' => array( 'priority' => 10 ) );
                    break;

                case 'Total':
                    $hooks['on_image']['archive'] = array( 'wpex_woocommerce_loop_thumbnail_before' => array( 'priority' => 10 ) );
                    break;

                case 'Blocksy':
                    $hooks['on_image']['archive'] = array(
                        'blocksy:woocommerce:product-card:thumbnail:start' => array( 'priority' => 10 ),
                        'woocommerce_product_get_image' => array( 'priority' => 10, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::wrap_thumb_container_filter', 'args' => 1 ),
                    );
                    break;

                case 'Basel':
                    $hooks['on_image']['single'] = array( 'woocommerce_before_single_product_summary' => array( 'priority' => 10, 'js' => array( '.woocommerce-product-gallery figure', 'append' ) ) );
                    break;

                case 'Kapee':
                    $hooks['on_image']['single'] = array( 'kapee_product_gallery_top' => array( 'priority' => 10 ) );
                    break;

                case 'TeeSpace':
                    $hooks['on_image']['single']['woocommerce_before_single_product_summary'] = array( 'priority' => 10 );
                    break;

            }

            // Oxygen builder
            if ( class_exists( 'OxyWooCommerce' ) ) {
                $hooks['on_image']['archive']['woocommerce_product_get_image'] = array( 'priority' => 10, 'type' => 'filter', 'callback' => 'AWL_Integrations_Callbacks::woocommerce_product_get_image', 'args' => 3 );
                $hooks['before_title']['archive']['woocommerce_before_shop_loop_item_title'] = array( 'priority' => 10 );
                $hooks['on_image']['single']['woocommerce_product_thumbnails'] = array( 'priority' => 10 );
            }

            if ( class_exists( 'Iconic_WooThumbs' ) ) {
                $hooks['on_image']['single']['iconic_woothumbs_before_images_wrap'] = array( 'priority' => 10 );
            }

            // Product Gallery Slider for Woocommerce ( Formerly Twist )
            if ( class_exists( 'Twist' ) ) {
                $hooks['on_image']['single']['wpgs_after_image_gallery'] = array( 'priority' => 10, 'js' => array( '.wpgs-image', 'prepend' ) );
            }

            // Additional Variation Images Gallery for WooCommerce plugin
            if ( class_exists( 'Woo_Variation_Gallery' ) ) {
                $hooks['on_image']['single']['woo_variation_product_gallery_start'] = array( 'priority' => 10, 'js' => array( '.woo-variation-gallery-slider-wrapper', 'append' ) );
            }

            // Premium Addons for Elementor
            if ( defined('PREMIUM_ADDONS_VERSION') ) {
                $hooks['before_title']['archive']['pa_woo_product_before_title'] = array( 'priority' => 10 );
                $hooks['on_image']['archive']['pa_woo_product_before_details_wrap_start'] = array( 'priority' => 10, 'js' => array( '.premium-woo-product-thumbnail', 'append' ) );
                $hooks['on_image']['single']['premium_woo_qv_image'] = array( 'priority' => 10 );
                $hooks['before_title']['single']['premium_woo_quick_view_product'] = array( 'priority' => 10 );
            }

            // WooPack plugin
            if ( class_exists('WooPack') ) {
                $hooks['on_image']['archive']['woopack_loop_before_product_image'] = array( 'priority' => 10 );
                $hooks['before_title']['archive']['woopack_loop_before_product_title'] = array( 'priority' => 10 );
            }
            
            return $hooks;

        }

        /*
         * Change labels container styles
         */
        public function awl_label_container_styles( $styles, $position_type, $labels ) {

            global $wp_filter, $wp_current_filter;
            $current_filter = array_slice( $wp_current_filter, -2, 1 );

            $current_filter = isset( $current_filter[0] ) ? $current_filter[0] : false;

            if ( $current_filter ) {

                $filter_obj = $wp_filter[$current_filter];
                $priority = method_exists( $filter_obj, 'current_priority' ) ? $filter_obj->current_priority() : 10;

                $hooks = AWL_Helpers::get_hooks();
                if ( is_array( $hooks ) && ! empty( $hooks ) ) {
                    foreach( $hooks as $position => $hooks_list_type ) {
                        foreach ( $hooks_list_type as $hooks_display => $hooks_list ) {
                            foreach ( $hooks_list as $hook_name => $hook_vars ) {
                                $hook_priority = isset( $hook_vars['priority'] ) ? $hook_vars['priority'] : 10;
                                if ( $hook_name === $current_filter && isset( $hook_vars['js'] ) && $hook_priority === $priority ) {
                                    $styles['display'] = 'none';
                                    break 3;
                                }
                            }
                        }
                    }
                }

            }

            if ( 'Avada' === $this->current_theme ) {
                $styles['z-index'] = '99';
            }

            if ( 'Twenty Twenty' === $this->current_theme && in_array( 'woocommerce_shop_loop_item_title', $wp_current_filter ) ) {
                $styles['margin-top'] = '10px';
            }

            if ( 'TheGem' === $this->current_theme && in_array( 'thegem_woocommerce_single_product_right', $wp_current_filter ) ) {
                $styles['margin-bottom'] = '10px';
            }

            if ( 'TheGem' === $this->current_theme && in_array( 'thegem_woocommerce_single_product_left', $wp_current_filter ) ) {
                $styles['margin-left'] = '21px';
                $styles['margin-right'] = '21px';
            }

            if ( 'Oxygen' === $this->current_theme ) {
                $styles['z-index'] = '1001';
            }

            if ( 'Oxygen' === $this->current_theme && in_array( 'oxygen_woocommerce_single_product_before_images', $wp_current_filter ) ) {
                $styles['margin-left'] = '15px';
                $styles['margin-right'] = '15px';
            }

            if ( 'Konado' === $this->current_theme && in_array( 'woocommerce_after_shop_loop_item', $wp_current_filter ) ) {
                $styles['justify-content'] = 'center';
                $styles['margin-bottom'] = '6px';
            }

            if ( 'Stockie' === $this->current_theme ) {
                $styles['display'] = 'flex';
            }

            if ( 'BoxShop' === $this->current_theme ) {
                $styles['z-index'] = '999';
            }

            if ( 'BoxShop' === $this->current_theme && in_array( 'woocommerce_after_shop_loop_item', $wp_current_filter ) ) {
                $styles['justify-content'] = 'center';
                $styles['margin-top'] = '8px';
                $styles['margin-bottom'] = '5px';
            }

            if ( 'Rehub theme' === $this->current_theme && in_array( 'rh_woo_single_product_title', $wp_current_filter ) ) {
                $styles['margin-bottom'] = '15px';
            }

            if ( 'Rehub theme' === $this->current_theme && in_array( 'rh_woo_thumbnail_loop', $wp_current_filter ) && $position_type === 'before_title' ) {
                $styles['justify-content'] = 'center';
                $styles['margin-top'] = '14px';
            }

            return $styles;

        }

        /*
         * Add custom styles
         */
        public function wp_head_styles() {

            $output = '';

            echo $output;

        }

        /*
         * Hide default sale flash if this option is enables
         */
        public function hide_default_sale_flash() {

            if ( 'BoxShop' === $this->current_theme ) {
                remove_action( 'boxshop_before_product_image', 'boxshop_template_loop_product_label', 10 );
                remove_action( 'woocommerce_after_shop_loop_item_title', 'boxshop_template_loop_product_label', 1 );
            }

            if ( 'XStore' === $this->current_theme ) {
                remove_filter( 'woocommerce_sale_flash', 'etheme_woocommerce_sale_flash', 20, 3 );
            }

            add_filter( 'woocommerce_blocks_product_grid_item_html', 'AWL_Integrations_Callbacks::woocommerce_blocks_product_grid_item_html_hide_bagge', 10, 3 );

        }

        /*
         * Hide default out-of-stock flash if this option is enables
         */
        public function hide_default_stock_flash() {

            if ( 'XStore' === $this->current_theme ) {
                add_filter( 'woocommerce_stock_html', 'AWL_Integrations_Callbacks::return_empty_string', 999 );
            }

        }

    }

endif;