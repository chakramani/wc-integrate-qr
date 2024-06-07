<?php


add_action('woocommerce_thankyou', 'wc_thankyou_mail_template_action');

/**
 * Function for `woocommerce_thankyou` action-hook.
 * 
 * @param  $order_id 
 *
 * @return void
 */

use Dompdf\Dompdf;
use Dompdf\Options;


function wc_thankyou_mail_template_action($order_id)
{

	require_once plugin_dir_path(__FILE__) .  '../includes/cnsdompdf/vendor/autoload.php';
	$order = wc_get_order($order_id);
	$billing_email = $order->get_billing_email();

	$pdf_output_folder = plugin_dir_path(__FILE__) . 'wc-integrate-pdf';
	if (!is_dir($pdf_output_folder)) {
		wp_mkdir_p($pdf_output_folder);
		chmod($pdf_output_folder, 0777);
	}

	$options = new Options();
	$options->set('defaultFont', 'Neometric');

	// Initialize Dompdf
	$dompdf = new Dompdf();
	$dompdf->set_option('isHtml5ParserEnabled', true);
	$dompdf->set_option('isRemoteEnabled', true);
	$admin_email_form_custom_plugin = get_option('wc_integrate_qr_options');
	$admin_email = $admin_email_form_custom_plugin['email_addresses'] ? $admin_email_form_custom_plugin['email_addresses'] : get_option('admin_email');



	$post_ids = get_option('wc_select2_restaurent'); // Make sure the option name is correct
	$args = array(
		'post_type' => 'restaurant',
		'post__in'  => $post_ids,
		'orderby'   => 'post__in',
		'posts_per_page' => -1,
	);
	$query = new WP_Query($args);

	if ($query->have_posts()) {
		$html = '<!DOCTYPE html>
		<html lang="en">
		<head>
			<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
		<style>
		* {
			padding: 0;
			margin: 0;
			
			}
			body {
			font-family: "Montserrat", sans-serif;
			background-color: #fff;
			margin: 0;
			padding: 0;
			}
			.p-0 {
			padding: 0;
			}
			.pt-0 {
			padding-top: 0;
			}
			.py-0 {
			padding-top: 0;
			padding-bottom: 0;
			}
			.m-o {
			margin: 0;
			}
			.my-5 {
			margin-top: 5px;
			margin-bottom: 5px;
			}
			.my-10 {
			margin-top: 10px;
			margin-bottom: 10px;
			}
			.ml-10 {
			margin-left: 10px;
			}
			.mr-20 {
			margin-right: 20px;
			}
			.container {
			margin: 20px auto;
			background-color: #fff;
			overflow: hidden;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
			}
			table {
			width: 100%;
			border-collapse: collapse;
			}
		
			td {
			padding: 5px;
			vertical-align: top;
			}
			.offer-header {
			text-align: right;
			}
			.offer-title h2{
			font-family: "Montserrat", sans-serif;
			font-size: 50px;
			line-height: 50px;
			font-weight: 700;
			word-spacing: 15px;
			letter-spacing: 10px;
			}
			.offer-title h3 {
			font-family: "Montserrat", sans-serif;
			font-size: 28px;
			line-height: 28px;
			letter-spacing: 1px;
			font-weight: 600;
			word-spacing: normal;
			margin: 0 !important;
			padding-top: 0;
			}
			.offer-special {
			font-family: "Montserrat", sans-serif;
			background-color: #fa7b09;
			border: 2px solid #fa7b09;
			color: #fff;
			padding: 16px 32px;
			border-radius: 4px;
			font-weight: bold;
			display: inline-block;
			margin:0;
			font-size: 36px;
			font-weight: 800;
			letter-spacing: 10px;
			border-radius: 0;
			width: 38%;
			float: left;
			text-align: center;
			margin-top: 0;
			}
			.offer-discount {
			font-family: "Montserrat", sans-serif;
			display: inline-block;
			background-color: #eee;
			padding: 8px 16px;
			border-radius: 4px;
			font-weight: 800;
			background: #fff;
			border: 2px solid #eee;
			padding: 16px 32px;
			font-size: 36px;
			float: left;
			width: 38%;
			text-align: center;
			margin-top: 0;
			}
			.offer-footer-text {
			width: auto;
			float: none;
			}
			.offer-footer-text p {
			min-height: 30px;
			}
			.offer-wrapper {
			width: 100%; clear:both;
			}
			.offer-footer-text h4 {
			font-family: "Montserrat", sans-serif; margin-top: 0; 
			}
			.offer-footer p {  
			font-size: 22px; color: #333333; ;
			}
			hr {
			margin: 50px 100px;
			}
			.social-icons {
			float: right;
			}
			.social-icons ul {
			margin-top: -30px;padding: 0;
			}
			.social-icons ul li {
			list-style: none;
			display: inline-block;
			margin-right: 5px;
			}
			.social-icons ul li a {
			text-decoration: none;
			}
			.spacer {background: #F8F8F8; width: auto; padding: 25px 80px; margin: 20px 0;}
			.spacer .border {color: #AFAFAF; border-top: 1px dashed #AFAFAF; height: 1px;}
			.custom-circle {
				position: relative;
				display: inline-block;
				width: 30px; /* Set the width */
				height: 30px; /* Set the height */
				padding: 10px 10px; 
				border-radius: 50%; /* Make the element a circle */
				background-color: #3498db; /* Background color */
				color: white; /* Icon color */
				text-align: center; /* Horizontally center the icon */
				font-size: 24px; /* Font size of the icon */
				}
		
		</style>
		</head>
		<body>';
		$i=0;
		// Iterate over posts and add images to PDF
		while ($query->have_posts()) {
			$query->the_post();
			$post_id = get_the_ID();
			$post_title = get_the_title();
			$restaurant_color = get_post_meta($post_id, '_restaurant_color', true);
			$_restaurant_discount = get_post_meta($post_id, '_restaurant_discount', true);
			$subtitle = get_post_meta($post_id, '_restaurant_sub_title', true);
			$short_text = get_post_meta($post_id, '_restaurant_text', true);
			$restaurant_location_text = get_post_meta($post_id, '_restaurant_second_text', true);
			$restaurant_map = get_post_meta($post_id, '_restaurant_map', true);
			$restaurant_facebook = get_post_meta($post_id, '_restaurant_facebook', true);
			$restaurant_instagram = get_post_meta($post_id, '_restaurant_instagram', true);
			// $_restaurant_qr_code = dynamic_coupon_voucher_generate_resturant_qr_code();
			$_restaurant_qr_code = "";
		
			// Get the featured image URL
			$image_url = get_the_post_thumbnail_url($post_id, 'full') ? get_the_post_thumbnail_url($post_id, 'full') : plugin_dir_url(__FILE__) . 'img/restdefault.jpeg';
		
			$html .= '<div class="container">';
			if ($i % 2 == 0) {
				$html .= '
				<table border="0" class="parent-table">
					<tr>
						<td class="p-0" width="25%">
							<img src="' . esc_url($image_url) . '" alt="' . esc_attr($post_title) . '" width="250">
						</td>
						<td class="offer-title" width="75%">
							<table>
								<tr>
									<td class="p-100">
										<h2>' . esc_html($post_title) . '</h2>
										<h3 class="p-100">' . esc_html($subtitle) . '</h3>
									</td>
									<td style="text-align: right"><img class="offer-qr" src="' . esc_url($_restaurant_qr_code) . '" alt="QR Code" width="120"></td>
								</tr>
								<tr>
									<td colspan="2" class="offer-header">
										<table border="0">
											<tr>
												<td style="background:' . $restaurant_color . '; padding: 10px 32px; font-size: 36px; color: #fff; letter-spacing: 10px; border-radius: 0; font-family: Montserrat, sans-serif; margin-top: 0; line-height: 36px; text-align: center;"><h4 style="font-weight: 800;">SPECIAL</h4></td>
												<td style="border: 1px solid #EDEDED; padding: 10px 32px; text-align: center;"><h4 style="font-family: Montserrat, sans-serif; margin-top: 0; font-size: 36px; font-weight: 800; line-height: 36px;">' . esc_html($_restaurant_discount) . '% OFF</h4></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="offer-footer">
										<div class="offer-footer-text">
											<p class="short-text">' . $short_text . '</p>
											<p class="location-text">' . esc_html($restaurant_location_text) . '</p>
										</div>
										<div class="social-icons">
											<ul>
												<li><a href="' . $restaurant_map . '"><span class="fa fa-map-marker custom-circle" style="background:' . $restaurant_color . '; font-size: 30px;"></span></a></li>
												<li><a href="' . $restaurant_facebook . '"><span class="fa fa-facebook custom-circle" style="background:' . $restaurant_color . '; font-size: 30px;"></span></a></li>
												<li><a href="' . $restaurant_instagram . '"><span class="fa fa-instagram custom-circle" style="background:' . $restaurant_color . '; font-size: 30px;"></a></li>
											</ul>
										</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
			} else {
				$html .= '
				<table border="0" class="parent-table">
					<tr>
						<td class="offer-title" width="75%">
							<table>
								<tr>
									<td class="p-100">
										<h2>' . esc_html($post_title) . '</h2>
										<h3 class="p-100">' . esc_html($subtitle) . '</h3>
									</td>
									<td style="text-align: right"><img class="offer-qr" src="' . esc_url($_restaurant_qr_code) . '" alt="QR Code" width="120"></td>
								</tr>
								<tr>
									<td colspan="2" class="offer-header">
										<table border="0">
											<tr>
												<td style="background:' . $restaurant_color . '; padding: 10px 32px; font-size: 36px; color: #fff; letter-spacing: 10px; border-radius: 0; font-family: Montserrat, sans-serif; margin-top: 0; line-height: 36px; text-align: center;"><h4 style="font-weight: 800;">SPECIAL</h4></td>
												<td style="border: 1px solid #EDEDED; padding: 10px 32px; text-align: center;"><h4 style="font-family: Montserrat, sans-serif; margin-top: 0; font-size: 36px; font-weight: 800; line-height: 36px;">' . esc_html($_restaurant_discount) . '% OFF</h4></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="offer-footer">
										<div class="offer-footer-text">
											<p class="short-text">' . $short_text . '</p>
											<p class="location-text">' . esc_html($restaurant_location_text) . '</p>
										</div>
										<div class="social-icons">
											<ul>
												<li><a href="' . $restaurant_map . '"><span class="fa fa-map-marker custom-circle" style="background:' . $restaurant_color . '; font-size: 30px;"></span></a></li>
												<li><a href="' . $restaurant_facebook . '"><span class="fa fa-facebook custom-circle" style="background:' . $restaurant_color . '; font-size: 30px;"></span></a></li>
												<li><a href="' . $restaurant_instagram . '"><span class="fa fa-instagram custom-circle" style="background:' . $restaurant_color . '; font-size: 30px;"></a></li>
											</ul>
										</div>
									</td>
								</tr>
							</table>
						</td>
						<td class="p-0" width="25%">
							<img src="' . esc_url($image_url) . '" alt="' . esc_attr($post_title) . '" width="250">
						</td>
					</tr>
				</table>';
			}
		
			if ($i != $query->post_count - 1) {
				$html .= '
				<div class="spacer">
					<div class="border"></div>
				</div>';
			}
		
			$html .= '</div>';
			$i++;
		}
		

		// Close the HTML tags
		$html .= '</body></html>';

		// Reset post data
		wp_reset_postdata();

		// Load HTML into Dompdf
		$dompdf->loadHtml($html);

		// Set paper size and orientation
		$dompdf->setPaper('A3', 'portrait');

		// Render the PDF
		$dompdf->render();



		$pdf_output = $dompdf->output();
		$pdf_file_path = $pdf_output_folder . '/generated_pdf'.$order_id.'.pdf';

		file_put_contents($pdf_file_path, $pdf_output);
		// Get the URL for the PDF
		$pdf_url = plugin_dir_url(__FILE__) . 'wc-integrate-pdf/generated_pdf'.$order_id.'.pdf';

		echo '<p>PDF generated successfully. <a class="btn btn-default" href="' . esc_url($pdf_url) . '">Download your pass</a></p>';
	}else{
		echo 'No restaurant posts found.';
	}



	$email_message = '
    <head>
  <title>Check Your PDF Pass</title>
 <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap");
    </style>
</head>
<body>
<div id="wrapper" dir="ltr" style="margin: 0 auto; padding: 70px 0; width: 100%; max-width: 600px; -webkit-text-size-adjust: none;" width="100%">
						<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
							<tbody><tr>
								<td align="center" valign="top">
									<div id="template_header_image">
																			</div>
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container" style="background-color: #fff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0,0,0,.1); border-radius: 3px;" bgcolor="#fff">
										<tbody><tr>
											<td align="center" valign="top">
												<!-- Header -->
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color: #2daa21; color: #fff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; border-radius: 3px 3px 0 0;" bgcolor="#2daa21">
													<tbody><tr>
														<td id="header_wrapper" style="padding: 36px 48px; display: block;">
															<h1 style= font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #57bb4d; color: #fff; background-color: inherit;" bgcolor="inherit">PDF FOR QR PASS!</h1>
														</td>
													</tr>
												</tbody></table>
												<!-- End Header -->
											</td>
										</tr>
										<tr>
											<td align="center" valign="top">
												<!-- Body -->
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
													<tbody><tr>
														<td valign="top" id="body_content" style="background-color: #fff;" bgcolor="#fff">
															<!-- Content -->
															<table border="0" cellpadding="20" cellspacing="0" width="100%">
																<tbody><tr>
																	<td valign="top" style="padding: 48px 48px 32px;">
																		<div id="body_content_inner" style="color: #636363; font-size: 14px; line-height: 150%; text-align: left;" align="left">

<p style="margin: 0 0 16px;">You’ve received pdf for the order #<a href="' . $order_id . '">' . $order_id . '</a>.</p>
                                    <a href="' . esc_url($pdf_url) . '" class="pdf_file_link"> Click Here </a> to download.</div>
																	</td>
																</tr>
															</tbody></table>
															<!-- End Content -->
														</td>
													</tr>
												</tbody></table>
												<!-- End Body -->
											</td>
										</tr>
									</tbody></table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Footer -->
									<table border="0" cellpadding="10" cellspacing="0" width="100%" id="template_footer">
										<tbody><tr>
											<td valign="top" style="padding: 0; border-radius: 6px;">
												<table border="0" cellpadding="10" cellspacing="0" width="100%">
													<tbody><tr>
														<td colspan="2" valign="middle" id="credit" style="border-radius: 6px; border: 0; color: #8a8a8a; font-size: 12px; line-height: 150%; text-align: center; padding: 24px 0;" align="center">
															<p style="margin: 0 0 16px;">Gastro Pass® by @Travel2Budapest</p>
														</td>
													</tr>
												</tbody></table>
											</td>
										</tr>
									</tbody></table>
									<!-- End Footer -->
								</td>
							</tr>
						</tbody></table>
					</div>
</body>
</html>';
	$headers[] = 'Content-Type: text/html; charset=UTF-8';

	wp_mail([$admin_email, $billing_email], 'PDF Pass', $email_message, $headers);
}
