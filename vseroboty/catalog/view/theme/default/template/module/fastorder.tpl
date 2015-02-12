<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<?php
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest') {
    $body = "<b>Product:</b> <a href='".$_POST['url']."'>".$_POST['product']."</a><br/><p><b>Name: </b>".$_POST['name']."</p><p><b>Phone: </b>".$_POST['phone']."</p><p><b>E-mail: </b>".$_POST['email']."</p><p>Код купона: ".$_POST['code']."</p>";
    $from_email = (!empty($_POST['email']))?$_POST['email']:'notreply@'.$_SERVER['SERVER_NAME'];
	$headers = array('From'=>$from_email,'FromName'=>'Быстрый заказ с сайта');
	/* Для отправки HTML-почты вы можете установить шапку Content-type. */
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=windows-1251\r\n";
	$headers .= "From: ".$_POST['name']."  <".$from_email.">\r\n";
	$subject = 'Быстрый заказ продукта - '.$_POST['product'].' - '.$_POST['name'];
	$headers  = iconv('UTF-8', 'windows-1251', $headers);
	$subject = iconv('UTF-8', 'windows-1251', $subject);
	$body1 = $body."<br /> Спасибо за Ваш заказ.<br /> Мы свяжемся с Вами в ближайшее время.";
	$body = iconv('UTF-8', 'windows-1251', $body);
	mail($email, $subject, $body, $headers);
	
	/*Отправка покупателю */
	if ($_POST['email']) {
		$subject1 = 'Быстрый заказ продукта - '.$_POST['product'].' принят в обработку';
		$subject1 = iconv('UTF-8', 'windows-1251', $subject1);
		$body1 = iconv('UTF-8', 'windows-1251', $body1);
		$headers1 = array('From'=>'notreply@'.$_SERVER['SERVER_NAME'],'FromName'=>'Заказ товара '.$_POST['product'].' принят');
		$headers1 .= "MIME-Version: 1.0\r\n";
		$headers1 .= "Content-type: text/html; charset=windows-1251\r\n";
		$headers1 .= "From: Заказ товара  <notreply@".$_SERVER['SERVER_NAME'].">\r\n";
		$headers1  = iconv('UTF-8', 'windows-1251', $headers1);
		mail($_POST['email'], $subject1, $body1, $headers1);
    }
	}
  ?>
<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>