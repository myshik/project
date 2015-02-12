<div class="clear"></div>
<div class="footer-top-left"><div class="footer-top-right"><div class="footer-top-middle"> </div></div></div>
<div id="footer">
 <div class="footer-bg">
  <?php if ($informations) { ?>
  <div class="column">
    <h3 class="info"><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
      <li><a href="<?php echo $blog; ?>"><?php echo $text_blog; ?></a></li>
    </ul>
  </div>
  <?php } ?>
  <div class="column">
    <h3 class="service"><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="javascript:goPage('<?php echo $return; ?>')"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3 class="extra"><?php echo $text_extra; ?></h3>
    <ul>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <li><a href="javascript:goPage('<?php echo $voucher; ?>')"><?php echo $text_voucher; ?></a></li>
      <li><a href="javascript:goPage('<?php echo $affiliate; ?>')"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
    </ul>
  </div>
  <div class="column" style="background:none;">
    <h3 class="account"><?php echo $text_account; ?></h3>
	<ul>
      <li><a href="javascript:goPage('<?php echo $account; ?>')"><?php echo $text_account; ?></a></li>
      <li><a href="javascript:goPage('<?php echo $order; ?>')"><?php echo $text_order; ?></a></li>
      <li><a href="javascript:goPage('<?php echo $wishlist; ?>')"><?php echo $text_wishlist; ?></a></li>
      <li><a href="javascript:goPage('<?php echo $newsletter; ?>')"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
 </div>
</div>
</div>
<div id="footer-bottom">
  <div id="footer-menu">
   <div class="footer-links"><a class="home" href="<?php echo $home; ?>"><?php echo $text_home; ?></a><a href="javascript:goPage('<?php echo $wishlist; ?>')" id="wishlist-total"><?php echo $text_wishlist; ?></a><a href="javascript:goPage('<?php echo $compare; ?>')" id="compare-total-header"><?php echo $text_compare; ?></a><a href="javascript:goPage('<?php echo $account; ?>')"><?php echo $text_account; ?></a><a href="javascript:goPage('<?php echo $shopping_cart; ?>')"><?php echo $text_shopping_cart; ?></a><a href="javascript:goPage('<?php echo $checkout; ?>')"><?php echo $text_checkout; ?></a><a href="javascript:goPage('<?php echo $blog; ?>')"><?php echo $text_blog; ?></a></div>
  </div>
<div id="powered"><?php echo $powered; ?>

    <div id='yandex-metrika'>
        <meta name='yandex-verification' content='53d886a8258fd924' />
<!-- Yandex.Metrika informer -->
<a href="https://metrika.yandex.ru/stat/?id=24580082&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/24580082/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:24580082,lang:'ru'});return false}catch(e){}"/></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter24580082 = new Ya.Metrika({id:24580082,
                    webvisor:true,
                    clickmap:true,
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
<noscript><div><img src="//mc.yandex.ru/watch/24580082" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    </div>
</div>
</div>
</body></html>