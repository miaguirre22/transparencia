jQuery(document).ready(function(){jQuery(".helix-popup-logo-link .unlisted").click(function(i){var e=jQuery(".helix-popup-logo-link").data("id");e>0&&jQuery.ajax({url:idf_ajaxurl,type:"POST",data:{action:"idhelix_join_waitlist_ajax",USERID:e},success:function(i){if(i>0){jQuery(".helix-popup-logo-link a").removeClass("unlisted"),jQuery("span.waitlist-length").text(i);var e=jQuery(".helix-popup-logo-link a img").attr("src").replace(".png","");jQuery(".helix-popup-logo-link a img").attr("src",e+"-saved.png")}}})})});