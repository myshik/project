<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div  class="middle" style=" width:630px; float:left">
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>

<h2><?php echo $text_my_account; ?></h2>

  <div class="content">
    <ul>
     <div  style="float: left; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $edit; ?>"><img src="catalog/view/theme/default/image/Account/edit1.png" alt="Account Details" style="float: left; margin-right: 8px;"><?php echo $text_edit_main; ?></a><br>
        <?php echo $text_edit; ?></div>
     <div style="float: right; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $password; ?>"><img src="catalog/view/theme/default/image/Account/password.png" alt="Account Password" style="float: left; margin-right: 8px;"><?php echo $text_password_main; ?></a><br>
        <?php echo $text_password; ?></div>
     <div style="float: left; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $address; ?>"><img src="catalog/view/theme/default/image/Account/delivery.png" alt="Address book" style="float: left; margin-right: 8px;"><?php echo $text_address_main; ?></a><br>
        <?php echo $text_address; ?></div>
     <div  style="float: right; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $wishlist; ?>"><img src="catalog/view/theme/default/image/Account/wishlist.png" alt="Wishlist" style="float: left; margin-right: 8px;"><?php echo $text_wishlist_main; ?></a><br>
        <?php echo $text_wishlist; ?></div>
    </ul>
  </div>

<h2><?php echo $text_my_orders; ?></h2>
  <div class="content">
    <ul>
     <div style="float: left; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $order; ?>"><img src="catalog/view/theme/default/image/Account/orders.png" alt="Order History" style="float: left; margin-right: 8px;"><?php echo $text_order_main; ?></a><br>
        <?php echo $text_order; ?></div>
     <div style="float: right; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $download; ?>"><img src="catalog/view/theme/default/image/Account/download.png" alt="Your Downloads" style="float: left; margin-right: 8px;"><?php echo $text_download_main; ?></a><br>
        <?php echo $text_download; ?></div>
     <div style="float: left; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $reward; ?>"><img src="catalog/view/theme/default/image/Account/reward.png" alt="Reward Points" style="float: left; margin-right: 8px;"><?php echo $text_reward_main; ?></a><br>
        <?php echo $text_reward; ?></div>
     <div style="float: right; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $return; ?>"><img src="catalog/view/theme/default/image/Account/return.png" alt="Your Returns" style="float: left; margin-right: 8px;"><?php echo $text_return_main; ?></a><br>
        <?php echo $text_return; ?></div>
     <div style="float: left; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $transaction; ?>"><img src="catalog/view/theme/default/image/Account/trans.png" alt="transaction" style="float: left; margin-right: 8px;"><?php echo $text_transaction_main; ?></a><br>
        <?php echo $text_transaction; ?></div>
    </ul>
  </div>
        
<h2><?php echo $text_my_newsletter; ?></h2>
  <div class="content">
    <ul>
     <div style="float: left; width: 260px; margin-bottom: 10px; padding: 5px;"><a href="<?php echo $newsletter; ?>"><img src="catalog/view/theme/default/image/Account/newsletter.png" alt="Your Newsletter" style="float: left; margin-right: 8px;"><?php echo $text_newsletter_main; ?></a><br>
        <?php echo $text_newsletter; ?></div>
    </ul>
  </div>

  </div>
  <div class="bottom" style="width:580px; float:left;">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 