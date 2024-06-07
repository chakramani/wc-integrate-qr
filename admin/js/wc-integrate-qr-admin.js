(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  jQuery(document).ready(function ($) {
    $("#generate-qr-code").on("click", function () {
      var url = $("#qr-url").val();
      //console.log(url);

      $.ajax({
        url: qrCodeAjax.ajax_url,
        type: "POST",
        data: {
          action: "generate_qr_code",
          nonce: qrCodeAjax.nonce,
          url: url,
        },
        success: function (response) {
          console.log(response); // Log the response for debugging

          if (response.success) {
            var qrUrl = response.data.result.qr;
            $("#qr-code-result").html(
              '<img src="' + qrUrl + '" alt="QR Code">'
            );
          } else {
            $("#qr-code-result").html("<p>Error: " + response.data + "</p>");
          }
        },
      });
    });

    /* Generate Qr for Resturant post  */
    $("#generate_qr_button").on("click", function () {
      $("#generate_qr_button").text("Generating....");
      var post_link = $("#get_postId").val();
      //console.log(url);

      $.ajax({
        url: qrCodeAjax.ajax_url,
        type: "POST",
        data: {
          action: "generate_resturant_qr_code",
          nonce: qrCodeAjax.nonce,
          url: post_link,
        },
        success: function (response) {
          console.log(response); // Log the response for debugging

          if (response.success) {
            var qrUrl = response.data.result.qr;
            $("#qr_code_display").html(
              '<img src="' + qrUrl + '" alt="QR Code">'
            );
            $("#generate_qr_button").text("Generated !!");
          } else {
            $("#qr_code_display").html("<p>Error: " + response.data + "</p>");
          }
        },
      });
    });

    $("#multiple").select2({
      placeholder: "Select a restaurents for pdf",
      allowClear: true,
    });
  });
})(jQuery);

jQuery(document).ready(function ($) {
  // Uploading files
  var file_frame;

  jQuery.fn.upload_feature_image = function(button) {
    var button_id = button.attr("id");
    var field_id = button_id.replace("_button", "");

    // If the media frame already exists, reopen it.
    if (file_frame) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: jQuery(this).data("uploader_title"),
      button: {
        text: jQuery(this).data("uploader_button_text"),
      },
      multiple: false,
    });

    // When an image is selected, run a callback.
    file_frame.on("select", function() {
      var attachment = file_frame.state().get("selection").first().toJSON();
      jQuery("#" + field_id).val(attachment.id);
      jQuery("#featureimagediv img").attr("src", attachment.url);
      jQuery("#featureimagediv img").show();
      jQuery("#" + button_id).attr("id", "remove_feature_image_button");
      jQuery("#remove_feature_image_button").text("Remove logo");
    });

    // Finally, open the modal
    file_frame.open();
  };

  jQuery("#featureimagediv").on("click", "#upload_feature_image_button",
    function(event) {
      event.preventDefault();
      jQuery.fn.upload_feature_image(jQuery(this));
    }
  );

  jQuery("#featureimagediv").on(
    "click",
    "#remove_feature_image_button",
    function(event) {
      event.preventDefault();
      jQuery("#upload_feature_image").val("");
      jQuery("#featureimagediv img").attr("src", "");
      jQuery("#featureimagediv img").hide();
      jQuery(this).attr("id", "upload_feature_image_button");
      jQuery("#upload_feature_image_button").text("Restaurant Logo");
    }
  );
});
