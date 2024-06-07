<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://shyamkbhandari.com.np/
 * @since      1.0.0
 *
 * @package    Wc_Integrate_Qr
 * @subpackage Wc_Integrate_Qr/admin
 */




if (!class_exists('WC_Integrate_QR_Admin_Menus')) {
    class WC_Integrate_QR_Admin_Menus
    {

        private $options;

        public function __construct()
        {
            // Hook to add the menu item and submenu item
            add_action('admin_menu', [$this, 'add_plugin_menu']);
            // Hook to initialize plugin settings
            add_action('admin_init', [$this, 'settings_init']);
        }

        public function add_plugin_menu()
        {
            // Add main menu item
            // add_submenu_page(
            //     'edit.php?post_type=restaurant',           // Parent slug
            //     'WC Integrate QR',           // Page title
            //     'WC Integrate QR',           // Menu title
            //     'manage_options',            // Capability
            //     'wc-integrate-qr',           // Menu slug
            //     [$this, 'display_plugin_page'], // Callback function
            // );

            // Add submenu item
            add_submenu_page(
                'edit.php?post_type=restaurant',           // Parent slug
                'Settings',                  // Page title
                'Settings',                  // Menu title
                'manage_options',            // Capability
                'wc-integrate-qr-settings',  // Menu slug
                [$this, 'display_settings_page'] // Callback function
            );

            // Add submenu item
            add_submenu_page(
                'edit.php?post_type=restaurant',           // Parent slug
                'Restaurent Selection',                  // Page title
                'Restaurent Selection',                  // Menu title
                'manage_options',            // Capability
                'restaurent_selection',  // Menu slug
                [$this, 'wc_integrate_setting_callback'] // Callback function
            );
        }

        public function display_plugin_page()
        {
            // Check if user has the necessary permissions
            if (!current_user_can('manage_options')) {
                return;
            }


            // Fetch plugin options
            $this->options = get_option('wc_integrate_qr_options');
?>
            <div class="wrap">
                <h1><?php esc_html_e('WC Integrate QR', 'text-domain'); ?></h1>
                <p><?php esc_html_e('Welcome to the WC Integrate page.', 'text-domain'); ?></p>
                <div>
                    <label for="qr-url">Enter URL:</label>
                    <input type="text" id="qr-url" name="qr-url">
                    <button id="generate-qr-code">Generate QR Code</button>
                </div>
                <div id="qr-code-result"></div>

            </div>
        <?php
        }

        public function display_settings_page()
        {
            // Check if user has the necessary permissions
            if (!current_user_can('manage_options')) {
                return;
            }

            // Fetch plugin options
            $this->options = get_option('wc_integrate_qr_options');
        ?>
            <div class="wrap">
                <h1><?php esc_html_e('WC Integrate QR Settings', 'text-domain'); ?></h1>
                <form method="post" action="options.php">
                    <?php
                    // Output security fields for the registered setting
                    settings_fields('wc_integrate_qr_options_group');
                    // Output setting sections and their fields
                    do_settings_sections('wc-integrate-qr');
                    // Output save settings button
                    submit_button();
                    ?>
                </form>
            </div>
        <?php
        }

        public function settings_init()
        {
            register_setting(
                'wc_integrate_qr_options_group', // Options group
                'wc_integrate_qr_options'        // Option name
            );


            add_settings_section(
                'wc_integrate_qr_section',        // Section ID
                __('WC Integrate QR Settings', 'text-domain'), // Title
                [$this, 'settings_section_callback'], // Callback
                'wc-integrate-qr'                 // Page
            );

            add_settings_field(
                'wc_integrate_qr_field_api_key',  // Field ID
                __('API Key', 'text-domain'),     // Title
                [$this, 'field_api_key_callback'], // Callback
                'wc-integrate-qr',                // Page
                'wc_integrate_qr_section'         // Section
            );
            add_settings_field(
                'wc_integrate_qr_field_email_addresses',  // Field ID
                __('Email Addresses', 'text-domain'),     // Title
                [$this, 'field_email_address_callback'], // Callback
                'wc-integrate-qr',                // Page
                'wc_integrate_qr_section'         // Section
            );


            add_settings_section('wc_restaurent_section', 'Restaurent Selection', [$this, 'wc_multiselect_restaurent_title_callback'], 'wc-mutiselect-restaurent-setting');

            add_settings_field('wc_selected_restaurent', 'Restaurent', [$this, 'wc_multiselect_restaurent_body_callback'], 'wc-mutiselect-restaurent-setting', 'wc_restaurent_section');

            register_setting(
                'wc_select2_restaurent_group', // Options group
                'wc_select2_restaurent'        // Option name
            );
        }

        public function settings_section_callback()
        {
            echo '<p>' . esc_html__('Enter your API key and other settings below:', 'text-domain') . '</p>';
        }

        public function field_api_key_callback()
        {
        ?>

            <input type="text" name="wc_integrate_qr_options[api_key]" value="<?php echo esc_attr($this->options['api_key'] ?? ''); ?>" class="regular-text">
        <?php
        }

        public function field_email_address_callback()
        {
            $this->options = get_option('wc_integrate_qr_options');
        ?>
            <style>
                .added_email {
                    display: flex;
                    align-items: center;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    margin-bottom: 5px;
                    background-color: #f9f9f9;
                }

                .added_email a {
                    text-decoration: none;
                    color: #ff0000;
                    font-size: 20px;
                    margin-right: 10px;
                }

                .added_email a:hover {
                    color: #cc0000;
                }

                .added_email div {
                    font-size: 16px;
                    color: #333;
                }
            </style>
            <div class="multiple-val-input">
                <ul>
            <input type="text" id="email_addresses_hidden" name="wc_integrate_qr_options[email_addresses]" value="<?php echo $this->options['email_addresses'] ?? '' ?>">
                </ul>
            </div>
        <?php
        }


        public function wc_integrate_setting_callback()
        {
            $this->options = get_option('wc_select2_restaurent');
        ?>
            <div class="select-two-restaurent-wrapper">
                <form method="post" action="options.php">
                    <h1>Restaurant Selection</h1>
                    <fieldset>
                        <!-- <legend><span class="number">1</span> Stripe Details</legend> -->
                        <?php
                        // Output security fields for the registered setting
                        settings_fields('wc_select2_restaurent_group');
                        // Output setting sections and their fields
                        do_settings_sections('wc-mutiselect-restaurent-setting');
                        // Output save settings button
                        submit_button();
                        ?>
                    </fieldset>
                </form>
            </div>
        <?php
        }

        public function wc_multiselect_restaurent_title_callback()
        {
            //echo '<p>' . esc_html__('Select your restaurent for pdf:', 'text-domain') . '</p>';
        }

        public function wc_multiselect_restaurent_body_callback()
        {


            // Retrieve stored options
            $stored_options = get_option('wc_select2_restaurent', array());

            // Ensure $stored_options is an array
            $stored_options = maybe_unserialize($stored_options);
            if (!is_array($stored_options)) {
                $stored_options = array();
            }

            // Query for restaurant posts
            $args = array(
                'post_type' => 'restaurant',
                'posts_per_page' => -1, // Get all posts
            );
            $restaurants = get_posts($args);

        ?>
            <div class="restaurent">
                <select id="multiple" class="js-states form-control widefat" name="wc_select2_restaurent[]" multiple>
                    <?php foreach ($restaurants as $restaurant) : ?>
                        <option value="<?php echo esc_attr($restaurant->ID); ?>" <?php echo in_array($restaurant->ID, $stored_options) ? 'selected' : ''; ?>>
                            <?php echo esc_html($restaurant->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
<?php


        }
    }
}

// Initialize the plugin

if (is_admin()) {
    new WC_Integrate_QR_Admin_Menus();
}
