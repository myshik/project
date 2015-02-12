<?php
// ------------------------------------------
// Follow Me Version 1.5.3
// For Opencart v1.5.1 / 1.5.2 / 1.5.3 
// Original by KangDJ
// Updated by Lamiaa Ahmed (1.5.0)
// Updated by villagedefrance (1.5.1, 1.5.1 V2)
// Updated by HelderIM (1.5.1 V3) 
// Updated by villagedefrance (1.5.3)
// Подготовлено специально для opencart.ws
// Перевод и адаптация модуля Marianna radiance.com.ua fatalemary@gmail.com 
// ------------------------------------------
?>

<?php if($box) { ?>
	<div class="box">
		<div class="box-heading">
			<?php if($icon) { ?>
				<div style="float: left; margin-right: 8px;"><img src="catalog/view/theme/default/image/connect.png" alt="" /></div>
			<?php } ?>
			<?php if($title) { ?>
				<?php echo $title; ?>
			<?php } ?>
		</div>
			
		<div class="box-content">
			<?php if($face) { ?>
				<a onclick="window.open('http://www.facebook.com/<?php echo $facebook_url; ?>');" title="Follow <?php echo $store; ?> on Facebook"><img src="catalog/view/theme/default/image/logo_facebook.gif" alt="" /></a>
			<?php } ?>
			<?php if($twit) { ?>
				<a onclick="window.open('http://twitter.com/<?php echo $twitter_url; ?>');" title="Follow <?php echo $store; ?> on Twitter"><img src="catalog/view/theme/default/image/logo_twitter.gif" alt="" /></a>
			<?php } ?>
			<?php if($gplus) { ?>
				<a onclick="window.open('https://plus.google.com/<?php echo $google_url; ?>');" title="Follow <?php echo $store; ?> on Google+"><img src="catalog/view/theme/default/image/logo_google.gif" alt=""/></a>
			<?php } ?>
			<?php if($odno) { ?>
				<a onclick="window.open('http://www.odnoklassniki.ru/group/<?php echo $odnoklassniki_url; ?>');" title="Follow <?php echo $store; ?> on odnoklassniki"><img src="catalog/view/theme/default/image/logo_odnoklassniki.png" alt=""/></a>
			<?php } ?>
			<?php if($vk) { ?>
				<a onclick="window.open('http://vk.com/<?php echo $vkontakte_url; ?>');" title="Follow <?php echo $store; ?> on Vkontakte"><img src="catalog/view/theme/default/image/logo_vkontakte.gif" alt=""/></a>
			<?php } ?>
			<?php if($tube) { ?>
				<a onclick="window.open('http://youtube.com/<?php echo $youtube_url; ?>');" title="Follow <?php echo $store; ?> on youtube"><img src="catalog/view/theme/default/image/logo_youtube.png" alt=""/></a>
			<?php } ?>
		</div>
	</div>

<?php } else { ?>

		<div style="padding:0px 10px;margin-bottom:10px;">
			<?php if($face) { ?>
				<a onclick="window.open('http://www.facebook.com/<?php echo $facebook_url; ?>');" title="Follow <?php echo $store; ?> on Facebook"><img src="catalog/view/theme/default/image/logo_facebook.gif" alt="" /></a>
			<?php } ?>
			<?php if($twit) { ?>
				<a onclick="window.open('http://twitter.com/<?php echo $twitter_url; ?>');" title="Follow <?php echo $store; ?> on Twitter"><img src="catalog/view/theme/default/image/logo_twitter.gif" alt="" /></a>
			<?php } ?>
			<?php if($gplus) { ?>
				<a onclick="window.open('https://plus.google.com/<?php echo $google_url; ?>');" title="Follow <?php echo $store; ?> on Google+"><img src="catalog/view/theme/default/image/logo_google.gif" alt=""/></a>
			<?php } ?>
			<?php if($odno) { ?>
				<a onclick="window.open('http://www.odnoklassniki.ru/group/<?php echo $odnoklassniki_url; ?>');" title="Follow <?php echo $store; ?> on odnoklassniki"><img src="catalog/view/theme/default/image/logo_odnoklassniki.png" alt=""/></a>
			<?php } ?>
			<?php if($vk) { ?>
				<a onclick="window.open('http://vk.com/<?php echo $vkontakte_url; ?>');" title="Follow <?php echo $store; ?> on Vkontakte"><img src="catalog/view/theme/default/image/logo_vkontakte.gif" alt=""/></a>
			<?php } ?>
			<?php if($tube) { ?>
				<a onclick="window.open('http://youtube.com/<?php echo $youtube_url; ?>');" title="Follow <?php echo $store; ?> on youtube"><img src="catalog/view/theme/default/image/logo_youtube.png" alt=""/></a>
			<?php } ?>
		</div>
			
<?php } ?>
