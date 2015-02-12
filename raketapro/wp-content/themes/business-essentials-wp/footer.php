<!-- Start of bottom wrapper -->
<div id="bottom_wrapper">

<!-- Start of content wrapper -->
<div class="content_wrapper">

<!-- Start of one fourth first -->
<div class="one_half_first">
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_one')) : ?>
<?php endif; ?>

</div><!-- End of one fourth first -->

<!-- Start of one fourth -->
<div class="one_half">
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_two')) : ?>
<?php endif; ?>

</div><!-- End of one fourth -->

<!-- Start of one fourth
<div class="one_third">
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_three')) : ?>
<?php endif; ?>

</div>  End of one fourth -->

<!-- Start of one fourth
<div class="one_fourth">
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_four')) : ?>
<?php endif; ?>

</div> End of one fourth -->

</div><!-- End of content wrapper -->

<!-- Clear Fix --><div class="clear"></div>

</div><!-- End of bottom wrapper -->

<!-- Start of copyright wrapper -->
<div id="copyright_wrapper">

<!-- Start of content wrapper -->
<div class="content_wrapper">

<!-- Start of copyright message -->
<div class="copyright_message">
<?php 
if ( function_exists( 'get_option_tree' ) ) {
$copyright = get_option_tree( 'vn_copyright' );
} ?>     

<?php if ($copyright != ('')){ ?> 
 
&copy;<?php echo stripslashes($copyright); ?>

<?php } else { } ?>

</div><!-- End of copyright message -->

<!-- Start of social icons -->
<div class="social_icons">

<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('social')) : ?>
<?php endif; ?>

</div><!-- End of social icons -->

</div><!-- End of content wrapper -->

<!-- Clear Fix --><div class="clear"></div>

</div><!-- End of copyright wrapper -->

<?php 
if ( function_exists( 'get_option_tree' ) ) {
$analytics = get_option_tree( 'vn_tracking' );
} ?>     

<?php echo stripslashes($analytics); ?>

<script type="text/javascript">
// DOM ready
jQuery(document).ready(function(){
	
jQuery('.slider').flexslider();
 
// Create the dropdown base
jQuery("<select />").appendTo(".topmenu");

// Create default option "Go to..."
jQuery("<option />", {
 "selected": "selected",
 "value"   : "",
 "text"    : "Меню"
}).appendTo(".topmenu select");

// Populate dropdown with menu items
jQuery(".topmenu a").each(function() {
var el = jQuery(this);
jQuery("<option />", {
   "value"   : el.attr("href"),
   "text"    : el.text()
}).appendTo(".topmenu select");
});

// To make dropdown actually work
// To make more unobtrusive: http://css-tricks.com/4064-unobtrusive-page-changer/
jQuery(".topmenu select").change(function() {
window.location = jQuery(this).find("option:selected").val();
});

});

</script> 

<?php wp_footer(); ?>

<script type="text/javascript"> _shcp = []; _shcp.push({widget_id : 512162, widget : "Chat"}); (function() { var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true; hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://widget.siteheart.com/apps/js/sh.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); })();</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter16992454 = new Ya.Metrika({id:16992454,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<script type="text/javascript"> 
    var ua = navigator.userAgent;
    var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '551px',
          width: '100%',
          videoId: '7hoqO36CVRM',
          playerVars: {
              rel: '0',
              theme:'light',
              showinfo: '0',
              color:'white'
          },
          events: {
            'onStateChange': onPlayerStateChange
          }
        });
      }
      function onPlayerStateChange() {
    if(player.getPlayerState()==0)
        {
            $('.demovideo').css({visibility : 'visible'});
            if(ua.search(/Chrome/)>0){$('#player').css({display : 'none'})}
            else{$('#player').css({visibility : 'hidden'});}
        }
      }
	$(document).ready(function() {
             
            if(ua.search(/Chrome/)>0){$('#player').css({marginTop: '-551px', display: 'none'})}
            else{$('.demovideo').css({marginBottom: '-27px'});
                $('#player').css({marginTop: '-551px', marginBottom: '-7px', visibility: 'hidden'})}
      $('.demovideo').click(function(){
          $('.demovideo').css({visibility : 'hidden'});
          if(ua.search(/Chrome/)>0){$('#player').css({display : 'block'})}
          else{$('#player').css({visibility : 'visible'})}
      player.playVideo();
})
             initialize();
             initialize2();
	});	
</script>
<noscript><div><img src="//mc.yandex.ru/watch/16992454" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>