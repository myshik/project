<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>


<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } else { ?>
<meta name="keywords" content="робот пылесос, роботы игрушки, роботы газонокосилки, роботы для чистки бассейна, профессиональные роботы, комплектующие к роботам" />
<?php } ?>
<?php if ($icon) { ?>
<link href="favicon.ico" rel="icon" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/news.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/theme/default/corner/peel.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<script type='text/javascript'>$(function(){$.config = {url:'<?php echo HTTP_SERVER; ?>'};});</script>
		<script type="text/javascript" src="catalog/view/javascript/fastorder.js"></script>
		<script type="text/javascript" src="catalog/view/javascript/jquery.liveValidation.js"></script>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/fastorder.css" />
 <link rel="stylesheet"  type="text/css" href="windowstyle.css" />
	<script>
	$(document).ready(function(){
		var pop_window = $('#pop-up-window');
		
		var X = ($(window).width()-pop_window.width())/2;
		var Y = ($(window).height()-pop_window.height())/2;
	
		pop_window.css('left', X+ 'px');
		pop_window.css('top', Y+ 300+'px');
		 
		pop_window.hide();
		var visible_window=false;
		
		$(document).ready(function(){
    var pop_window = $('#pop-up-window');

        pop_window.hide();

    $('#showWindow').click(function(){ 
        pop_window.show(1000);
    });

    $('#btnClose').click(function(){ 
        pop_window.hide(1000);
    });

    });


		$('#btnClose').click(function(){ 
			pop_window.hide(1000);
		});
	
		
	});
	
	  </script>	
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie8.css" />
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php echo $google_analytics; ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="/js/transfers.js"></script></head>
<body>

     <? if (isset($_POST['add'])) { 
		
		 if(is_uploaded_file($_FILES["userfile"]["tmp_name"]))
   {
     // Если файл загружен успешно, перемещаем его
     // из временной директории в конечную
	 $time=time();
	 $filename=$time.$_FILES["userfile"]["name"];
     move_uploaded_file($_FILES["userfile"]["tmp_name"], "image/foto/".$filename);
   } else {
      echo("Ошибка загрузки файла");
   }
  
   $name = $_POST['name'];
   $city = $_POST['city'];
      $text = $_POST['text'];
  $query ="insert into shop_rewies (`name`,`city`,`text`,`foto`) VALUES ('$name','$city','$text','$filename')"  ;
  mysql_query($query) or die(mysql_error()); 
		
		}
		?>

<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
  <?php } ?>
 <div class="grafic">
 Прием звонков: Пн-Пт с 10 до 19,<br> Доставка товара Пн-Пт с 12 до 22
 </div>
  <?php echo $cart; ?>
  <div id="search">
    <div class="button-search"></div>
    <?php if ($filter_name) { ?>
    <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
    <?php } else { ?>
    <input type="text" name="filter_name" value="<?php echo $text_search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#FFFFFF';" />
    <?php } ?>
  </div>
  <div id="welcome">
    <?php if (!$logged) { ?>
    <?php echo $text_welcome; ?>
    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
  </div>
  <div class="links"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a><a href="javascript:goPage('<?php echo $wishlist; ?>')" id="wishlist-total"><?php echo $text_wishlist; ?></a><a href="javascript:goPage('<?php echo $compare; ?>')" id="compare-total-header"><?php echo $text_compare; ?></a><a href="javascript:goPage('<?php echo $account; ?>')"><?php echo $text_account; ?></a><a href="javascript:goPage('<?php echo $shopping_cart; ?>')"><?php echo $text_shopping_cart; ?></a><a href="javascript:goPage('<?php echo $checkout; ?>')"><?php echo $text_checkout; ?></a><a href="http://vseroboty.ru/dostavka.html">Доставка</a><a href="http://vseroboty.ru/oplata.html">Оплата</a></div>
</div>
<?php if ($main_menu == '1') { ?>
<div id="advanced_menu"><?php if ($advanced_categories) {  echo $advanced_categories; } ?></div>
<?php } else { ?>
<?php if ($categories) { ?>
<div id="menu">
  <ul>
    <?php foreach ($categories as $category) { ?>
    <li><?php if ($category['active']) { ?>
	<a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
	<?php } else { ?>
	<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	<?php } ?>
      <?php if ($category['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>
<?php } ?>
<div id="notification"></div>