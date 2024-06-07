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

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Integrate_Qr
 * @subpackage Wc_Integrate_Qr/admin
 * @author     CNS <shyam.kumarc3@gmail.com>
 */
class Wc_Integrate_Qr_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('wp_ajax_generate_qr_code', [$this, 'generate_qr_code']);

		add_action('wp_ajax_generate_resturant_qr_code', [$this, 'generate_resturant_qr_code']);
		add_action('wp_ajax_nopriv_generate_resturant_qr_code', [$this, 'generate_resturant_qr_code']);
		add_action('init', [$this, 'wc_integrate_qr_restaurent_register_post_type']);
		add_action('add_meta_boxes', [$this, 'restaurant_register_meta_boxes']);
		add_action('save_post', [$this, 'restaurant_save_meta_box_data']);
		add_shortcode('download_pdf', [$this, 'wc_integrate_download_pdf']);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Integrate_Qr_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Integrate_Qr_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Enqueue style
		if (isset($_GET['page']) && ($_GET['page'] === 'restaurent_selection' || $_GET['page'] === 'wc-integrate-qr-settings')) {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wc-integrate-qr-admin.css', array(), $this->version, 'all');
		}
		wp_enqueue_media();
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Integrate_Qr_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Integrate_Qr_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('wc-integrate-qr-script', plugin_dir_url(__FILE__) . 'js/wc-integrate-qr-admin.js', array('jquery'), $this->version, false);
		wp_localize_script('wc-integrate-qr-script', 'qrCodeAjax', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('qr_code_nonce')
		));
	}


	/* --------------register posttype restaurants------------ */

	public function wc_integrate_qr_restaurent_register_post_type()
	{
		$labels = array(
			'name'                  => _x('Restaurants', 'Post type general name', 'wc-integrate-qr'),
			'singular_name'         => _x('Restaurant', 'Post type singular name', 'wc-integrate-qr'),
			'menu_name'             => _x('Restaurants', 'Admin Menu text', 'wc-integrate-qr'),
			'name_admin_bar'        => _x('Restaurant', 'Add New on Toolbar', 'wc-integrate-qr'),
			'add_new'               => __('Add New Restaurant', 'wc-integrate-qr'),
			'add_new_item'          => __('Add New restaurant', 'wc-integrate-qr'),
			'new_item'              => __('New restaurant', 'wc-integrate-qr'),
			'edit_item'             => __('Edit restaurant', 'wc-integrate-qr'),
			'view_item'             => __('View restaurant', 'wc-integrate-qr'),
			'all_items'             => __('All restaurants', 'wc-integrate-qr'),
			'search_items'          => __('Search restaurants', 'wc-integrate-qr'),
			'parent_item_colon'     => __('Parent restaurants:', 'wc-integrate-qr'),
			'not_found'             => __('No restaurants found.', 'wc-integrate-qr'),
			'not_found_in_trash'    => __('No restaurants found in Trash.', 'wc-integrate-qr'),
			'featured_image'        => _x('Restaurant Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'wc-integrate-qr'),
			'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'wc-integrate-qr'),
			'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'wc-integrate-qr'),
			'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'wc-integrate-qr'),
			'archives'              => _x('Restaurant archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'wc-integrate-qr'),
			'insert_into_item'      => _x('Insert into restaurant', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'wc-integrate-qr'),
			'uploaded_to_this_item' => _x('Uploaded to this restaurant', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'wc-integrate-qr'),
			'filter_items_list'     => _x('Filter restaurants list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wc-integrate-qr'),
			'items_list_navigation' => _x('Restaurants list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wc-integrate-qr'),
			'items_list'            => _x('Restaurants list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wc-integrate-qr'),
		);
		$args = array(
			'labels'             => $labels,
			'description'        => 'Restaurant custom post type.',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => 'restaurant'),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-editor-table',
			'supports'           => array('title', 'editor', 'author', 'thumbnail'),
			'show_in_rest'       => true
		);

		register_post_type('Restaurant', $args);
	}

	/* --------------------------test qr function ------------ */

	public function generate_qr_code()
	{
		check_ajax_referer('qr_code_nonce', 'nonce');

		if (!isset($_POST['url']) || empty($_POST['url'])) {
			wp_send_json_error('URL is required.');
		}

		$url = sanitize_text_field($_POST['url']);
		$set_vanity_url  = sanitize_text_field($_POST['nonce']);
		wc_integrate_qr_api_call($url);
	}


	public function generate_resturant_qr_code()
	{

		$get_option = get_option('wc_integrate_qr_options');
		check_ajax_referer('qr_code_nonce', 'nonce');

		$post_id = sanitize_text_field($_POST['post_id']);
		$url = get_post_meta($post_id, '_restaurant_url', true);

		//var_dump($get_option['api_key']);

		$secret_key = $get_option['api_key'];
		//$secret_key = '61f2027768be9ae6adb52da232944e38';
		// https://travel2budapest2.qrplanet.com/api/short?secretkey=6aa504bacada73cb1bc6307583f5a98f&url=http://Qrplanet.com
		$api_url = 'https://travel2budapest2.qrplanet.com/api/short?secretkey=' . urlencode($secret_key) . '&url=' . urlencode($url) . '&static=1';

		$response = wp_remote_get($api_url);

		if (is_wp_error($response)) {
			wp_send_json_error('Failed to connect to QR API.');
		}

		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, true);

		// var_dump($data['result']['qr']);
		update_post_meta($post_id, '_restaurant_qr_code', $data['result']['qr']);

		if (isset($data)) {
			wp_send_json_success($data);
		} else {
			wp_send_json_error('Failed to generate QR code.');
		}
	}


	/* =====================Resgister meta boxes==================== */

	public function restaurant_register_meta_boxes()
	{
		add_meta_box(
			'restaurant_meta_box',       // Unique ID
			'Restaurant Details',        // Box title
			[$this, 'restaurant_display_meta_box'], // Content callback, must be of type callable
			'restaurant',                 // Post type
			'side',                       // Context
			'default'                     // Priority
		);
		add_meta_box('featureimagediv', __('Restaurant Logo', 'text-domain'), [$this,'restaurant_logo_metabox_callback'], 'restaurant', 'side', 'low');
	}


	public function restaurant_display_meta_box($post)
	{
		// Retrieve current value of meta fields
		$discount = get_post_meta($post->ID, '_restaurant_discount', true);
		$restaurant_type = get_post_meta($post->ID, '_restaurant_sub_title', true);
		$text = get_post_meta($post->ID, '_restaurant_text', true);
		$restaurant_second_text = get_post_meta($post->ID, '_restaurant_second_text', true);
		$restaurant_map = get_post_meta($post->ID, '_restaurant_map', true);
		$restaurant_facebook = get_post_meta($post->ID, '_restaurant_facebook', true);
		$restaurant_instagram = get_post_meta($post->ID, '_restaurant_instagram', true);
		$restaurant_color = get_post_meta($post->ID, '_restaurant_color', true);
		$restaurant_rating = get_post_meta($post->ID, '_restaurant_rating', true);
		// Use nonce for verification
		wp_nonce_field('restaurant_meta_box_nonce', 'meta_box_nonce');
		// Display fields


?>
		<script>
			jQuery(document).ready(function($) {
				$('.color_field').each(function() {
					$(this).wpColorPicker();
				});
			});
		</script>

		<p>
			<label for="restaurant_sub_title">Sub Title</label>
			<input type="text" name="restaurant_sub_title" id="restaurant_sub_title" class="widefat" value="<?php echo esc_attr($restaurant_type); ?>" />
		</p>
		<p>
			<label for="restaurant_discount">Discount</label>
			<input type="text" name="restaurant_discount" id="restaurant_discount" class="widefat" value="<?php echo esc_attr($discount); ?>" />
		</p>
		<p>
			<label for="restaurant_text">Short Text</label>
			<textarea name="restaurant_text" id="restaurant_text" class="widefat"><?php echo esc_textarea($text); ?></textarea>
		</p>
		<p>
			<label for="restaurant_second_text">Resturant Address</label>

			<textarea name="restaurant_second_text" id="restaurant_second_text" class="widefat"><?php echo esc_textarea($restaurant_second_text); ?></textarea>
		</p>

		<p>
			<label for="restaurant_map">Map</label>
			<input type="text" name="restaurant_map" id="restaurant_map" class="widefat" value="<?php echo esc_attr($restaurant_map); ?>" />
		</p>
		<p>
			<label for="restaurant_facebook">Facebook</label>
			<input type="text" name="restaurant_facebook" id="restaurant_facebook" class="widefat" value="<?php echo esc_attr($restaurant_facebook); ?>" />
		</p>
		<p>
			<label for="restaurant_instagram">Instagram</label>
			<input type="text" name="restaurant_instagram" id="restaurant_instagram" class="widefat" value="<?php echo esc_attr($restaurant_instagram); ?>" />
		</p>
		<p>
			<label for="restaurant_second_text">Select Color</label>
			<input type="text" name="restaurant_color" id="restaurant_color" class="widefat color_field" value="<?php echo esc_attr($restaurant_color); ?>" />
		</p>
		<p>
			<label for="restaurant_rating">Rating</label>
			<input type="text" name="restaurant_rating" id="restaurant_rating" class="widefat rating_field" value="<?php echo esc_attr($restaurant_rating); ?>" />
		</p>
	<?php
	}



	public function restaurant_save_meta_box_data($post_id)
	{
		// Check if nonce is set
		if (!isset($_POST['meta_box_nonce'])) {
			return;
		}

		// Verify nonce
		if (!wp_verify_nonce($_POST['meta_box_nonce'], 'restaurant_meta_box_nonce')) {
			return;
		}

		// Check if this is an autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// Check user permissions
		if (isset($_POST['post_type']) && 'restaurant' == $_POST['post_type']) {
			if (!current_user_can('edit_post', $post_id)) {
				return;
			}
		}

		// Sanitize and save fields
		if (isset($_POST['restaurant_discount'])) {
			$discount = sanitize_text_field($_POST['restaurant_discount']);
			update_post_meta($post_id, '_restaurant_discount', $discount);
		}
		if (isset($_POST['restaurant_sub_title'])) {
			$restaurant_sub_title = sanitize_text_field($_POST['restaurant_sub_title']);
			update_post_meta($post_id, '_restaurant_sub_title', $restaurant_sub_title);
		}

		if (isset($_POST['restaurant_text'])) {
			$text = sanitize_textarea_field($_POST['restaurant_text']);
			update_post_meta($post_id, '_restaurant_text', $text);
		}

		if (isset($_POST['restaurant_second_text'])) {
			$second_text = sanitize_textarea_field($_POST['restaurant_second_text']);
			update_post_meta($post_id, '_restaurant_second_text', $second_text);
		}

		if (isset($_POST['restaurant_map'])) {
			$restaurant_map = sanitize_text_field($_POST['restaurant_map']);
			update_post_meta($post_id, '_restaurant_map', $restaurant_map);
		}
		if (isset($_POST['restaurant_facebook'])) {
			$restaurant_facebook = sanitize_text_field($_POST['restaurant_facebook']);
			update_post_meta($post_id, '_restaurant_facebook', $restaurant_facebook);
		}
		if (isset($_POST['restaurant_instagram'])) {
			$restaurant_instagram = sanitize_text_field($_POST['restaurant_instagram']);
			update_post_meta($post_id, '_restaurant_instagram', $restaurant_instagram);
		}
		if (isset($_POST['restaurant_color'])) {
			$restaurant_color = sanitize_text_field($_POST['restaurant_color']);
			update_post_meta($post_id, '_restaurant_color', $restaurant_color);
		}
		if (isset($_POST['restaurant_rating'])) {
			$restaurant_rating = sanitize_text_field($_POST['restaurant_rating']);
			update_post_meta($post_id, '_restaurant_rating', $restaurant_rating);
		}
		if (isset($_POST['restaurant_logo'])) {
			$image_id = (int) $_POST['restaurant_logo'];
			update_post_meta($post_id, '_restaurant_logo', $image_id);
		}
	}

	public function restaurant_logo_metabox_callback($post)
	{
		global $content_width, $_wp_additional_image_sizes;

		$image_id = get_post_meta($post->ID, '_restaurant_logo', true);

		$old_content_width = $content_width;
		$content_width = 254;

		if ($image_id && get_post($image_id)) {

			if (!isset($_wp_additional_image_sizes['post-thumbnail'])) {
				$thumbnail_html = wp_get_attachment_image($image_id, array($content_width, $content_width));
			} else {
				$thumbnail_html = wp_get_attachment_image($image_id, 'post-thumbnail');
			}

			if (!empty($thumbnail_html)) {
				$content = $thumbnail_html;
				$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_feature_image_button" >' . esc_html__('Remove Restaurant Logo', 'text-domain') . '</a></p>';
				$content .= '<input type="hidden" id="upload_feature_image" name="restaurant_logo" value="' . esc_attr($image_id) . '" />';
			}

			$content_width = $old_content_width;
		} else {

			$content = '<img src="" style="width:' . esc_attr($content_width) . 'px;height:auto;border:0;display:none;" />';
			$content .= '<p class="hide-if-no-js"><a title="' . esc_attr__('Set Restaurant Logo', 'text-domain') . '" href="javascript:;" id="upload_feature_image_button" id="set-feature-image" data-uploader_title="' . esc_attr__('Choose an logo', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Set Restaurant Logo', 'text-domain') . '">' . esc_html__('Set Restaurant Logo', 'text-domain') . '</a></p>';
			$content .= '<input type="hidden" id="upload_feature_image" name="restaurant_logo" value="" />';
		}

		echo $content;
	}


	/* shortcode to create download pdf button after order done */
	public function wc_integrate_download_pdf()
	{
		$order_id = intval($_GET['order_id']);
		?>
		<a class="elementor-button elementor-button-link elementor-size-sm" href="<?php echo get_home_url(); ?>/wp-content/plugins/wc-integrate-qr/public/wc-integrate-pdf/generated_pdf<?php echo $order_id; ?>.pdf">
			<span class="elementor-button-content-wrapper">
				<span class="elementor-button-text">Download your pass</span>
			</span>
		</a>
<?php
	}
}
