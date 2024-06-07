<?php


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://shyamkbhandari.com.np/
 * @since      1.0.0
 *
 * @package    Wc_Integrate_Qr
 * @subpackage Wc_Integrate_Qr/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Integrate_Qr
 * @subpackage Wc_Integrate_Qr/includes
 * @author     CNS <shyam.kumarc3@gmail.com>
 * 
 * */

function wc_integrate_qr_api_call($url)
{

    $get_option = get_option('wc_integrate_qr_options');
    //var_dump($get_option['api_key']);

    $secret_key = $get_option['api_key'];
    //$secret_key = '61f2027768be9ae6adb52da232944e38';
    $api_url = 'https://travel2budapest2.qrplanet.com/api/short?secretkey=' . urlencode($secret_key) . '&url=' . urlencode($url) . '&static=1';

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        wp_send_json_error('Failed to connect to QR API.');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return ($data['result']['qr']);

    // if (isset($data)) {
    //     wp_send_json_success($data);
    // } else {
    //     wp_send_json_error('Failed to generate QR code.');
    // }
}


/* ----------------Dynamic Coupon Vouchar QR code----------- */

function dynamic_coupon_voucher_generate_resturant_qr_code()
{

    $get_option = get_option('wc_integrate_qr_options');
    $secret_key = $get_option['api_key'];

    $api_url = 'https://travel2budapest2.qrplanet.com/api/coupon/issue?secretkey=' . urlencode($secret_key) . '&shorturl=' . 'w7gopq';


    $response = wp_remote_get($api_url);
    // var_dump($response);

    if (is_wp_error($response)) {
        wp_send_json_error('Failed to connect to QR API.');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    $get_qr_img = ($data['result']['qrcode']);
    return $get_qr_img;

    // var_dump($data['result']['qr']);
    //update_post_meta($post_id, '_restaurant_qr_code', $data['result']['qr']);
}
