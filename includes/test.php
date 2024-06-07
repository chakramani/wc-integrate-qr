<?php

use Dompdf\Dompdf;

function generate_pdf_on_template_redirect()
{
    if (is_admin()) {
        return; // Avoid running on admin pages
    }

    if (isset($_GET['generate_pdf']) && $_GET['generate_pdf'] == 'true') {
        // Start output buffering
        ob_start();

        // Include Dompdf library
        require_once plugin_dir_path(__FILE__) .  'cnsdompdf/vendor/autoload.php';



        // Initialize Dompdf
        $dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        // Load HTML content

        $html = '<!DOCTYPE html>
        <html lang="en">
        <head>
        <style>
        * {
            padding: 0;
            margin: 0;
            
          }
          body {
            font-family: Arial, sans-serif;
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
          h3.p-100 {
            width: 100%;
            font-siz3: 16px;
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
            margin-bottom: 20px;
          }
          td {
            padding: 16px;
            vertical-align: top;
          }
          img {
            width: 100%;
            max-width: 350px;
          }
          .offer-header {
            text-align: right;
          }
          .offer-title h2{
            font-size: 36px;
            font-weight: bold;
            word-spacing: 15px;
            letter-spacing: 6px;
          }
          .offer-title h3 {
            font-size: 28px;
            letter-spacing: 1px;
            font-weight: 500;
            word-spacing: normal;
            margin: 20px 0;
          }
          .offer-qr {
            width: 80px;
            height: 80px;
          }
          .offer-special {
            background-color: #fa7b09;
            border: 2px solid #fa7b09;
            color: #fff;
            padding: 16px 32px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin:0;
            font-size: 36px;
            letter-spacing: 10px;
            border-radius: 0;
            width: 38%;
            float: left;
            text-align: center;
          }
          .offer-discount {
                display: inline-block;
              background-color: #eee;
              padding: 8px 16px;
              border-radius: 4px;
              font-weight: 900;
              background: #fff;
              border: 2px solid #eee;
              padding: 16px 32px;
              font-size: 36px;
              float: left;
              width: 38%;
              text-align: center;
          }
          
          .offer-footer-text {
            width: auto;
            float: none;
            margin-top: 25px;
          }
          .offer-footer p {  
            
            font-size: 17px;
            color: #333333;
          }
          hr {
            margin: 50px 100px;
          }
          
          .social-icons {
            float: right;
            
          }
          .social-icons ul {
            margin-top: -50px
            margin: 0; padding: 0;
          }
          .social-icons ul li {
            list-style: none;
            display: inline-block;
            width: 30px;
            margin-right: 5px;
          }
          .social-icons ul li img {
            max-width: 30px;
          }
        </style>
        </head>
            <body>
                <div class="container">
                <table border="0">
                    <tr>
                        <td class="p-0" width="40%">
                            <img src="https://img001.prntscr.com/file/img001/WqRwlSvUS3i_yfVwIiu7SA.png" alt="A La Gringa">
                        </td>
                        <td class="offer-title"  width="60%">
                        <table>
                        <tr>
                        <td class="p-100">
                           <h2> A La Gringa</h2>
                            <h3 class="p-100">Brazilian Restaurant</h3>
                            </td>
                            <td><img class="offer-qr" src="https://img001.prntscr.com/file/img001/WmGyXbf-QeeWY0OU8EOODA.png" alt="QR Code"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="offer-header">
                            <div class="offer-special">SPECIAL</div>
                            <div class="offer-discount">16% OFF
                            </td>
                        </tr>
                        
                        <tr>
                        
                        <td colspan="2" class="offer-footer">
                            <div class="offer-footer-text">
                            <h5>Vitkovics Mihály Street</h5>
                                <p>Vitkovics Mihály Street 3-5, Budapest</p>
                            </div>
                            <div class="social-icons">
                            <ul>
                                <li><img src="https://staging.travel2budapest.com/wp-content/uploads/2024/05/gmap.png" alt="location" /></li>
                                <li><img src="https://staging.travel2budapest.com/wp-content/uploads/2024/05/fb.png" alt="Facebook" /></li>
                                <li><img src="https://staging.travel2budapest.com/wp-content/uploads/2024/05/insta.png" alt="Instagram" /> </li>
                            </ul> 
                            </div>
                        </td>
                        </tr>
                        </table>
                        
                        </td>
                    </tr>
                    
                </table>
                <hr style="border: 1px dashed black;" />
                <table border="0">
                    <tr>
                        
                        <td class="offer-title"  width="60%">
                        <table>
                        <tr>
                        <td class="p-100">
                           <h2> A La Gringa</h2>
                            <h3 class="p-100">Brazilian Restaurant</h3>
                            </td>
                            <td><img class="offer-qr" src="https://img001.prntscr.com/file/img001/WmGyXbf-QeeWY0OU8EOODA.png" alt="QR Code"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="offer-header">
                            <div class="offer-special">SPECIAL</div>
                            <div class="offer-discount">16% OFF
                            </td>
                        </tr>
                        <tr>
                        <td colspan="2" class="offer-footer">
                            <div class="offer-footer-text">
                                <p>Vitkovics Mihály Street 3-5, Budapest</p>
                            </div>
                            <div class="social-icons">
                            <ul>
                                <li><img src="https://staging.travel2budapest.com/wp-content/uploads/2024/05/gmap.png" alt="location" /></li>
                                <li><img src="https://staging.travel2budapest.com/wp-content/uploads/2024/05/fb.png" alt="Facebook" /></li>
                                <li><img src="https://staging.travel2budapest.com/wp-content/uploads/2024/05/insta.png" alt="Instagram" /> </li>
                            </ul> 
                            </div>
                        </td>
                        </tr>
                        </table>
                        
                        </td>
                        <td class="p-0" width="40%">
                            <img src="https://img001.prntscr.com/file/img001/WqRwlSvUS3i_yfVwIiu7SA.png" alt="A La Gringa">
                        </td>
                    </tr>
                    
                </table>
                <hr style="border: 1px dashed black;" />
            </div>
        </body>
        </html>
        
        ';

        // Load HTML into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A3', 'portrait');

        // Render the PDF
        $dompdf->render();
        file_put_contents($outputFile, $dompdf->output());
        // Clear the output buffer and turn off output buffering
        ob_end_clean();

        // Stream the PDF to the browser
        // $dompdf->stream("sample.pdf", array("Attachment" => 0));
        exit; // Ensure no further output is sent
    }
}

// Hook into template_redirect
add_action('template_redirect', 'generate_pdf_on_template_redirect');
