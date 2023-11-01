<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AWL_Admin_Options' ) ) :

    /**
     * Class for plugin admin options methods
     */
    class AWL_Admin_Options {

        /*
         * Get default settings values
         * @param string $tab Tab name
		 * @return array
         */
        static public function get_default_settings( $tab = false ) {

            $options = self::options_array( $tab );
            $default_settings = array();

            foreach ( $options as $section_name => $section ) {

                foreach ($section as $values) {

                    if ( isset( $values['type'] ) && ( $values['type'] === 'heading' || $values['type'] === 'hooks_table' ) ) {
                        continue;
                    }

                    if ( isset( $values['type'] ) && ( $values['type'] === 'checkbox' || $values['type'] === 'table' ) ) {
                        foreach ( $values['choices'] as $key => $val ) {
                            $default_settings[$values['id']][$key] = sanitize_text_field( $values['value'][$key] );
                        }
                        continue;
                    }

                    if ( isset( $values['type'] ) && $values['type'] === 'textarea' ) {
                        if ( function_exists('sanitize_textarea_field') ) {
                            $default_settings[$values['id']] = (string) sanitize_textarea_field( $values['value'] );
                        } else {
                            $default_settings[$values['id']] = (string) str_replace( "<\n", "&lt;\n", wp_strip_all_tags( $values['value'] ) );
                        }
                    } else {
                        $default_settings[$values['id']] = (string) sanitize_text_field( $values['value'] );
                    }

                    if (isset( $values['sub_option'])) {
                        $default_settings[$values['sub_option']['id']] = (string) sanitize_text_field( $values['sub_option']['value'] );
                    }

                }
            }

            return $default_settings;

        }

        /*
         * Update plugin settings
         */
        static public function update_settings() {

            $options = self::options_array();
            $update_settings = AWL()->get_settings();
            $current_tab = empty( $_GET['tab'] ) ? 'general' : sanitize_text_field( $_GET['tab'] );

            foreach ( $options[$current_tab] as $values ) {

                if ( $values['type'] === 'heading' ) {
                    continue;
                }

                if ( $values['type'] === 'hooks_table' && isset( $_POST[ $values['id'] ] ) ) {
                    $hooks_array = array();
                    foreach ( $_POST[ $values['id'] ] as $hook_id => $hook_args ) {
                        foreach ( $hook_args as $hook_param => $hook_val ) {
                            $hooks_array[$hook_id][$hook_param] = (string) sanitize_text_field( $hook_val );
                        }
                    }
                    $update_settings[ $values['id'] ] = $hooks_array;
                    continue;
                }

                if ( $values['type'] === 'checkbox' ) {

                    $checkbox_array = array();

                    foreach ( $values['choices'] as $key => $value ) {
                        $new_value = isset( $_POST[ $values['id'] ][$key] ) ? '1' : '0';
                        $checkbox_array[$key] = (string) sanitize_text_field( $new_value );
                    }

                    $update_settings[ $values['id'] ] = $checkbox_array;

                    continue;
                }

                if ( $values['type'] === 'textarea' && isset( $_POST[ $values['id'] ] ) ) {
                    if ( function_exists('sanitize_textarea_field') ) {
                        $update_settings[ $values['id'] ] = (string) sanitize_textarea_field( $_POST[ $values['id'] ] );
                    } else {
                        $update_settings[ $values['id'] ] = (string) str_replace( "<\n", "&lt;\n", wp_strip_all_tags( $_POST[ $values['id'] ] ) );
                    }
                    continue;
                }

                $new_value = isset( $_POST[ $values['id'] ] ) ? (string) sanitize_text_field( $_POST[ $values['id'] ] ) : '';
                $update_settings[ $values['id'] ] = $new_value;

                if ( isset( $values['sub_option'] ) ) {
                    $new_value = isset( $_POST[ $values['sub_option']['id'] ] ) ? (string) sanitize_text_field( $_POST[ $values['sub_option']['id'] ] ) : '';
                    $update_settings[ $values['sub_option']['id'] ] = $new_value;
                }

            }

            update_option( 'awl_settings', $update_settings );

            do_action( 'awl_settings_saved' );

        }

        /*
         * Get plugin settings
         * @return array
         */
        static public function check_settings() {
            $plugin_options = get_option( 'awl_settings' );
            return $plugin_options;
        }

        /*
         * Get options array
         *
         * @param string $tab Tab name
         * @param string $section Section name
         * @return array
         */
        static public function options_array( $tab = false, $section = false ) {

            $options = self::include_options();
            $options_arr = array();

            foreach ( $options as $tab_name => $tab_options ) {

                if ( $tab && $tab !== $tab_name ) {
                    continue;
                }

                foreach ( $tab_options as $option ) {

                    if ( $section ) {

                        if ( ( isset( $option['section'] ) && $option['section'] !== $section ) || ( !isset( $option['section'] ) && $section !== 'none' ) ) {
                            continue;
                        }

                    }

                    if ( isset( $option['value'] ) && isset( $option['value']['callback'] ) ) {
                        $option['value'] = call_user_func_array( $option['value']['callback'], $option['value']['params'] );
                    }

                    if ( isset( $option['choices'] ) && isset( $option['choices']['callback'] ) ) {
                        $option['choices'] = call_user_func_array( $option['choices']['callback'], $option['choices']['params'] );
                    }

                    $options_arr[$tab_name][] = $option;

                }


            }

            return $options_arr;

        }

        /*
         * Include options array
         * @return array
         */
        static public function include_options() {

            $options = array();

            $options['general'][] = array(
                "name" => __( "Main Settings", "advanced-woo-labels" ),
                "type" => "heading"
            );
            
            $options['general'][] = array(
                "name" => __( "Show default 'sale' label", "advanced-woo-labels" ),
                "desc"  => __( "Show or not default WooCommerce 'sale' label.", "advanced-woo-labels" ),
                "id"   => "show_default_sale",
                "value" => 'true',
                "type"  => "select",
                'choices' => array(
                    'true'  => __( 'Show', 'advanced-woo-labels' ),
                    'false' => __( 'Hide', 'advanced-woo-labels' ),
                )
            );

            $options['general'][] = array(
                "name" => __( "Show default 'out-of-stock' label", "advanced-woo-labels" ),
                "desc"  => __( "Show or not default WooCommerce 'out-of-stock' label.", "advanced-woo-labels" ),
                "id"   => "show_default_stock",
                "value" => 'true',
                "type"  => "select",
                'choices' => array(
                    'true'  => __( 'Show', 'advanced-woo-labels' ),
                    'false' => __( 'Hide', 'advanced-woo-labels' ),
                )
            );

            $options['general'][] = array(
                "name" => __( "Show for single product", "advanced-woo-labels" ),
                "desc"  => __( "Show the product labels for product detail pages. This overwrites settings inside single label display rules.", "advanced-woo-labels" ),
                "id"   => "show_single",
                "value" => 'true',
                "type"  => "select",
                'choices' => array(
                    'true'  => __( 'Show', 'advanced-woo-labels' ),
                    'false' => __( 'Hide', 'advanced-woo-labels' ),
                )
            );

            $options['general'][] = array(
                "name" => __( "Label Groups", "advanced-woo-labels" ),
                "type" => "heading"
            );

            $options['general'][] = array(
                "name" => __( "Labels alignment", "advanced-woo-labels" ),
                "desc"  => __( "Alignment of several labels inside one product.", "advanced-woo-labels" ),
                "id"   => "labels_alignment",
                "value" => 'horizontal',
                "type"  => "select",
                'choices' => array(
                    'vertical'  => __( 'Vertical', 'advanced-woo-labels' ),
                    'horizontal' => __( 'Horizontal', 'advanced-woo-labels' ),
                )
            );

            $options['general'][] = array(
                "name" => __( "Labels distance (px)", "advanced-woo-labels" ),
                "desc"  => __( "Distance between several labels inside one product position.", "advanced-woo-labels" ),
                "id"   => "labels_distance",
                "value" => '5',
                "min"   => '0',
                "type"  => "number",
            );

            $options['general'][] = array(
                "name" => __( "Max. number of labels per product", "advanced-woo-labels" ),
                "desc"  => __( "Maximal number of labels over one product.", "advanced-woo-labels" ),
                "id"   => "number_per_product",
                "value" => '10',
                "min"   => '0',
                "type"  => "number",
            );

            $options['general'][] = array(
                "name" => __( "Max. number of labels per position", "advanced-woo-labels" ),
                "desc"  => __( "Maximal number of labels over one product position.", "advanced-woo-labels" ),
                "id"   => "number_per_position",
                "value" => '5',
                "min"   => '0',
                "type"  => "number",
            );

            $options['general'][] = array(
                "name" => __( "Hooks", "advanced-woo-labels" ),
                "type" => "heading"
            );

            $options['general'][] = array(
                "name" => __( "Change display hooks", "advanced-woo-labels" ),
                "desc"  => __( "Change hooks that used to display labels on different product positions.", "advanced-woo-labels" ),
                "id"   => "hooks",
                "type"  => "hooks_table",
            );

            $options['general'][] = array(
                "name" => __( "Hooks relation", "advanced-woo-labels" ),
                "desc"  => __( "Rewrite existing hooks to new hooks, or simply add additional hooks to existing hooks.", "advanced-woo-labels" ),
                "id"   => "hooks_relation",
                "value" => 'true',
                "type"  => "select",
                'choices' => array(
                    'additional' => __( 'Add additional hooks', 'advanced-woo-labels' ),
                    'rewrite'  => __( 'Rewrite hooks', 'advanced-woo-labels' ),
                )
            );

            $options['general'][] = array(
                "name" => __( "Disable all hooks", "advanced-woo-labels" ),
                "desc"  => __( "Turn off all display hooks. Use this option if you want to manually place product labels via shortcodes or php functions.", "advanced-woo-labels" ),
                "id"   => "display_hooks",
                "value" => 'true',
                "type"  => "select",
                'choices' => array(
                    'true' => __( 'Enable hooks', 'advanced-woo-labels' ),
                    'false'  => __( 'Disable hooks', 'advanced-woo-labels' ),
                )
            );

            /**
             * Filter admin page options
             * @since 1.08
             * @param array $options Array of options
             */
            $options = apply_filters( 'awl_admin_page_options', $options );

            return $options;

        }

        /*
         * Rules operators
         * @param $name string Operator name
         * @return array
         */
        static public function get_rule_operators( $name ) {

            $operators = array();

            $operators['equals'] = array(
                array(
                    "name" => __( "equal to", "advanced-woo-labels" ),
                    "id"   => "equal",
                ),
                array(
                    "name" => __( "not equal to", "advanced-woo-labels" ),
                    "id"   => "not_equal",
                ),
            );

            $operators['equals_compare'] = array(
                array(
                    "name" => __( "equal to", "advanced-woo-labels" ),
                    "id"   => "equal",
                ),
                array(
                    "name" => __( "not equal to", "advanced-woo-labels" ),
                    "id"   => "not_equal",
                ),
                array(
                    "name" => __( "greater or equal to", "advanced-woo-labels" ),
                    "id"   => "greater",
                ),
                array(
                    "name" => __( "less or equal to", "advanced-woo-labels" ),
                    "id"   => "less",
                ),
            );

            return $operators[$name];

        }

        /*
         * Include rule array by rule id
         * @return array
         */
        static public function include_rule_by_id( $id ) {

            $rules = AWL_Admin_Options::include_rules();
            $rule = array();

            if ( $rules ) {
                foreach ( $rules as $rule_section => $section_rules ) {
                    foreach ( $section_rules as $section_rule ) {
                        if ( $section_rule['id'] === $id ) {
                            $rule = $section_rule;
                            break;
                        }
                    }
                }
            }

            if ( empty( $rule ) ) {
                $rule = $rules['attributes'][0];
            }

            return $rule;

        }

        /*
         * Include label settings array
         * @return array
         */
        static public function include_label_settings() {

            $options = array();

            $options['general'][] = array(
                "name" => __( "Label text", "advanced-woo-labels" ),
                "id"   => "text",
                "value" => 'SALE!',
                "spoiler" => array(
                    "title" => '* ' . __( "supports variables", "advanced-woo-labels" ),
                    "text"  => AWL_Admin_Helpers::get_default_text_variables()
                ),
                "type"  => "text",
                "class" => "awl-for-text"
            );

            $options['general'][] = array(
                "name" => __( "Template", "advanced-woo-labels" ),
                "id"   => "template",
                "value" => 'standard',
                "type"  => "template",
                'choices' => array(
                    'text'  => array(
                        'standard' => AWL_URL . '/assets/img/label-1.png',
                        'rounded' => AWL_URL . '/assets/img/label-2.png',
                        'round' => AWL_URL . '/assets/img/label-7.png',
                        'triangled' => AWL_URL . '/assets/img/label-3.png',
                        'angle' => AWL_URL . '/assets/img/label-4.png',
                    ),
                )
            );

            $options['general'][] = array(
                "name" => __( "Position type", "advanced-woo-labels" ),
                "id"   => "position_type",
                "value" => 'on_image',
                "type"  => "select",
                'choices' => array(
                    'on_image'  => __( 'On image', 'advanced-woo-labels' ),
                    'before_title' => __( 'Before title', 'advanced-woo-labels' ),
                )
            );

            $options['general'][] = array(
                "name" => __( "Position", "advanced-woo-labels" ),
                "id"   => "position",
                "value" => 'left_top',
                "type"  => "select",
                'choices' => array(
                    'left_top'  => __( 'Left top', 'advanced-woo-labels' ),
                    'center_top' => __( 'Center Top', 'advanced-woo-labels' ),
                    'right_top' => __( 'Right Top', 'advanced-woo-labels' ),
                    'left_center'  => __( 'Left center', 'advanced-woo-labels' ),
                    'center_center' => __( 'Center center', 'advanced-woo-labels' ),
                    'right_center' => __( 'Right center', 'advanced-woo-labels' ),
                    'left_bottom'  => __( 'Left bottom', 'advanced-woo-labels' ),
                    'center_bottom' => __( 'Center bottom', 'advanced-woo-labels' ),
                    'right_bottom' => __( 'Right bottom', 'advanced-woo-labels' ),
                ),
                "class" => "awl-position-xy"
            );

            $options['general'][] = array(
                "name" => __( "Position", "advanced-woo-labels" ),
                "id"   => "position_x",
                "value" => 'left',
                "type"  => "select",
                'choices' => array(
                    'left'  => __( 'Left', 'advanced-woo-labels' ),
                    'center' => __( 'Center', 'advanced-woo-labels' ),
                    'right' => __( 'Right', 'advanced-woo-labels' ),
                ),
                "class" => "awl-position-x"
            );

            $options['general'][] = array(
                "name" => __( "Set custom styles", "advanced-woo-labels" ),
                "id"   => "custom_styles",
                "value" => 'false',
                "type"  => "checkbox2",
            );

            $options['styles'][] = array(
                "name" => __( "Label color", "advanced-woo-labels" ),
                "id"   => "bg_color",
                "value" => '#3986c6',
                "type"  => "color",
                "class" => "awl-for-text",
                "alpha" => true,
            );

            $options['styles'][] = array(
                "name" => __( "Text color", "advanced-woo-labels" ),
                "id"   => "text_color",
                "value" => '#fff',
                "type"  => "color",
                "class" => "awl-for-text"
            );

            $options['styles'][] = array(
                "name" => __( "Font size (px)", "advanced-woo-labels" ),
                "id"   => "font_size",
                "value" => '14',
                "type"  => "number",
                "class" => "awl-for-text"
            );

            $options['styles'][] = array(
                "name" => __( "Font style", "advanced-woo-labels" ),
                "id"   => "font_style",
                "value" => 'normal',
                "type"  => "select",
                'choices' => array(
                    'normal'  => __( 'Normal', 'advanced-woo-labels' ),
                    'italic' => __( 'Italic', 'advanced-woo-labels' ),
                    'oblique' => __( 'Oblique', 'advanced-woo-labels' ),
                ),
                "class" => "awl-for-text"
            );

            $options['styles'][] = array(
                "name" => __( "Font weight", "advanced-woo-labels" ),
                "id"   => "font_weight",
                "value" => '400',
                "type"  => "select",
                'choices' => array(
                    '100'  => __( 'Thin', 'advanced-woo-labels' ),
                    '200'  => __( 'Extra Light', 'advanced-woo-labels' ),
                    '300'  => __( 'Light', 'advanced-woo-labels' ),
                    '400'  => __( 'Normal', 'advanced-woo-labels' ),
                    '500'  => __( 'Medium', 'advanced-woo-labels' ),
                    '600'  => __( 'Semi Bold', 'advanced-woo-labels' ),
                    '700'  => __( 'Bold', 'advanced-woo-labels' ),
                    '800'  => __( 'Extra Bold', 'advanced-woo-labels' ),
                    '900'  => __( 'Black', 'advanced-woo-labels' ),
                    '950'  => __( 'Extra Black', 'advanced-woo-labels' ),
                ),
                "class" => "awl-for-text"
            );

            $options['styles'][] = array(
                "name" => __( "Letter spacing (px)", "advanced-woo-labels" ),
                "id"   => "letter_spacing",
                "value" => '0',
                "type"  => "number",
                "class" => "awl-for-text"
            );

            $options['styles'][] = array(
                "name" => __( "Opacity", "advanced-woo-labels" ),
                "id"   => "opacity",
                "value" => '1',
                "type"  => "number",
                "step" => "0.01",
                "min" => "0",
                "max" => "1",
            );

            $options['styles'][] = array(
                "name" => __( "Padding (em)", "advanced-woo-labels" ),
                "id"   => "padding",
                "value" => '0',
                "min" => "0",
                "step" => "0.01",
                "params" => array( 'top' => '0.30', 'right' => '0.60', 'bottom' => '0.30', 'left' => '0.60' ),
                "tip"   => __( "Top, right, bottom, left.", "advanced-woo-labels" ),
                "type"  => "number",
                "class" => "awl-for-text"
            );

            $options['styles'][] = array(
                "name" => __( "Margin (px)", "advanced-woo-labels" ),
                "id"   => "margin",
                "value" => '0',
                "params" => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),
                "tip"   => __( "Top, right, bottom, left.", "advanced-woo-labels" ),
                "type"  => "number",
            );

            $options['styles'][] = array(
                "name" => __( "Custom css", "advanced-woo-labels" ),
                "id"   => "custom_css",
                "value" => '',
                "tip"   => __( "Set custom styles for your label.", "advanced-woo-labels" ) .
                    ' <a href="https://advanced-woo-labels.com/guide/label-custom-styles/" target="_blank">' . __( "Learn more.", "advanced-woo-labels" ) . '</a>',
                "type"  => "textarea",
            );

            /**
             * Filter label options
             * @since 1.08
             * @param array $options Array of label options
             */
            $options = apply_filters( 'awl_label_admin_options', $options );

            return $options;

        }

        /*
         * Include rules array
         * @return array
         */
        static public function include_rules() {

            $options = array();

            $options['attributes'][] = array(
                "name" => __( "Stock status", "advanced-woo-labels" ),
                "id"   => "stock_status",
                "type" => "callback",
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_stock_statuses',
                    'params'   => array()
                ),
            );

            $options['attributes'][] = array(
                "name" => __( "Visibility", "advanced-woo-labels" ),
                "id"   => "visibility",
                "type" => "callback",
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_visibilities',
                    'params'   => array()
                ),
            );

            $options['attributes'][] = array(
                "name" => __( "Price", "advanced-woo-labels" ),
                "id"   => "price",
                "type" => "number",
                "step" => "0.01",
                "operators" => "equals_compare",
                "suboption" => array(
                    'callback' => 'AWL_Admin_Helpers::get_price',
                    'params'   => array()
                ),
            );

            $options['attributes'][] = array(
                "name" => __( "Sale discount", "advanced-woo-labels" ),
                "id"   => "sale_discount",
                "type" => "number",
                "step" => "0.01",
                "operators" => "equals_compare",
                "suboption" => array(
                    'callback' => 'AWL_Admin_Helpers::get_sale_discount',
                    'params'   => array()
                ),
            );

            $options['attributes'][] = array(
                "name" => __( "Quantity", "advanced-woo-labels" ),
                "id"   => "quantity",
                "type" => "number",
                "operators" => "equals_compare",
            );

            $options['attributes'][] = array(
                "name" => __( "Shipping class", "advanced-woo-labels" ),
                "id"   => "shipping_class",
                "type" => "callback",
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_tax_terms',
                    'params'   => array( 'product_shipping_class' )
                ),
            );

            $options['attributes'][] = array(
                "name" => __( "Rating", "advanced-woo-labels" ),
                "id"   => "rating",
                "type" => "number",
                "step" => "0.01",
                "operators" => "equals_compare",
            );

            $options['attributes'][] = array(
                "name" => __( "Reviews count", "advanced-woo-labels" ),
                "id"   => "reviews_count",
                "type" => "number",
                "operators" => "equals_compare",
                "suboption" => array(
                    'callback' => 'AWL_Admin_Helpers::get_time_periods',
                    'params'   => array()
                ),
            );

            $options['attributes'][] = array(
                "name" => __( "Is on sale", "advanced-woo-labels" ),
                "id"   => "sale_status",
                "type" => "bool",
                "operators" => "equals",
            );

            $options['attributes'][] = array(
                "name" => __( "Is featured", "advanced-woo-labels" ),
                "id"   => "featured",
                "type" => "bool",
                "operators" => "equals",
            );

            $options['attributes'][] = array(
                "name" => __( "Has product image", "advanced-woo-labels" ),
                "id"   => "has_image",
                "type" => "bool",
                "operators" => "equals",
            );

            $options['attributes'][] = array(
                "name" => __( "Has gallery", "advanced-woo-labels" ),
                "id"   => "has_gallery",
                "type" => "bool",
                "operators" => "equals",
            );

            $options['product'][] = array(
                "name" => __( "Product", "advanced-woo-labels" ),
                "id"   => "product",
                "type" => "callback_ajax",
                "ajax" => "awl-searchForProducts",
                "placeholder" => __( "Search for a product...", "advanced-woo-labels" ),
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_product',
                    'params'   => array()
                ),
            );

            $options['product'][] = array(
                "name" => __( "Product category", "advanced-woo-labels" ),
                "id"   => "product_category",
                "type" => "callback",
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_tax_terms',
                    'params'   => array( 'product_cat' )
                ),
            );

            $options['product'][] = array(
                "name" => __( "Product tag", "advanced-woo-labels" ),
                "id"   => "product_tag",
                "type" => "callback",
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_tax_terms',
                    'params'   => array( 'product_tag' )
                ),
            );

            $options['user'][] = array(
                "name" => __( "User", "advanced-woo-labels" ),
                "id"   => "user",
                "type" => "callback",
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_users',
                    'params'   => array()
                ),
            );

            $options['user'][] = array(
                "name" => __( "User role", "advanced-woo-labels" ),
                "id"   => "user_role",
                "type" => "callback",
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_user_roles',
                    'params'   => array()
                ),
            );

            $options['page'][] = array(
                "name" => __( "Page", "advanced-woo-labels" ),
                "id"   => "page",
                "type" => "callback",
                "operators" => "equals",
                "choices" => array(
                    'callback' => 'AWL_Admin_Helpers::get_pages',
                    'params'   => array()
                ),
            );

            if ( AWL_Helpers::is_lang_plugin_active() ) {

                $options['page'][] = array(
                    "name" => __( "Page language", "advanced-woo-labels" ),
                    "id"   => "page_language",
                    "type" => "callback",
                    "operators" => "equals",
                    "choices" => array(
                        'callback' => 'AWL_Admin_Helpers::get_languages',
                        'params'   => array()
                    ),
                );

            }

            /**
             * Filter label rules
             * @since 1.00
             * @param array $options Array of label rules
             */
            $options = apply_filters( 'awl_label_rules', $options );

            return $options;

        }

        /*
         * Get section name
         * @param $name string Section id
         * @return string
         */
        static public function get_rule_section( $name ) {

            $label = $name;

            $sections = array(
                'attributes' => __( "Attributes", "advanced-woo-labels" ),
                'product'    => __( "Product", "advanced-woo-labels" ),
                'user'       => __( "User", "advanced-woo-labels" ),
                'page'       => __( "Page", "advanced-woo-labels" ),
                'date'       => __( "Date", "advanced-woo-labels" ),
            );

            if ( isset( $sections[$name] ) ) {
                $label = $sections[$name];
            }

            return $label;

        }

    }

endif;