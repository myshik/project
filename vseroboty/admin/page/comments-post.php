<?php
if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit();
}
require('const.php');
if(CAPTCHA===1) {require(SPATH.'plugins/captcha/rez.php');}
list($nic, $maildomen) = explode("@", MAIL);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="title" content="<?php echo __('Results of adding the comment'); ?>" />
<meta name="robots" content="index,follow" />
<link rel="icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<title><?php echo __('Results of adding the comment'); ?></title>
<link rel="alternate" type="application/rss+xml" title="DataLife Engine" href="/rss.php" />
<?php
if ('' == $_POST['name']){ echo __("Field Name is empty"); }
elseif (strcasecmp($_POST['name'], 'admin') == 0 || strcasecmp($_POST['name'], 'administrator') == 0 || strcasecmp($_POST['name'], $nic) == 0){ echo __("Sorry, you not can use this name on this site. Please enter other login."); }
elseif ('' == $_POST['mail']){ echo __("Field Email is empty"); }
elseif (!preg_match("^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$", $_POST['mail'])) { echo __("Invalid Email"); }
elseif ('' == $_POST['comment']){ echo __("Field Text commentary is empty"); }
elseif (isset($_COOKIE['almazcmscomments'])) { echo __("Too often the comments. On this site is permitted to add comments with an interval of 2 minutes. Please wait ... Then you can simply upgrade options."); }
else {
// If sending message from the domain
if (preg_match("/".SITEURL."/i", $_SERVER['HTTP_REFERER'])) {
$tc = preg_replace("'\[\S+\].\S+.\[\/\w+\]'",'',strip_tags(stripslashes($_POST['comment'])));
// Replace RAZDELITEL in data:
$re=':';
$cname=str_replace(RAZDELITEL,$re,$_POST['name']);
$cmail=str_replace(RAZDELITEL,$re,$_POST['mail']);
$ctext=str_replace(RAZDELITEL,$re,$tc);

$commpost = strip_tags(stripslashes($_POST['pagelink'] . RAZDELITEL . '0' . RAZDELITEL . $_POST['rubrica'] . RAZDELITEL . $cname . RAZDELITEL . $_POST['title'] . RAZDELITEL . $cmail . RAZDELITEL . $ctext . RAZDELITEL . $_SERVER['REMOTE_ADDR'] . RAZDELITEL . $_SERVER['HTTP_USER_AGENT'] . RAZDELITEL . date("d.m.Y H:i")));
// replace the transfer lines to the HTML-formatting and removing BB-code:
$search = array("'\[[-_A-Za-z0-9\=\/\:\s\.]+[^\]]\]'","'\<'","'\>'","'([\r\n])+'");
$replace = array('','','',"<br />");
$commcont = preg_replace($search, $replace, $commpost) . "\r\n";
/*preg_match("/^(http:\/\/)?([^\/]+)/i", SITEURL, $matches);
$domain =  $matches[2];*/
// put cookie in two minutes:
setcookie("almazcmscomments", $servname, time()+120);
echo "</head>\r\n<body>\r\n" . __('Comment from') . " <b>" . $cname . "</b>:<br /><p style=\"border:1px solid #999;padding:15px;margin:10px;\">" . $ctext . "</p><br />" . __('Added to the database and') . " <br /><b>" . __('will be published on the site after moderation') . ".<br />" . __('Please do not send it again') . ".</b><br />" . __('This page can be closed') . ".\r\n";
$filename = DATAPATH.COMMENTFILE;
$fp = fopen ($filename, "a");
fwrite($fp,$commcont);
fclose($fp);
// Emailing to admin:
	$subject = SITENAME.' - new comment on site';
	$body = "\r\n".__('New comment on site').' '.SITENAME." - ".SITEURL.":\r\n\r\n".__('For page').' '.$_SERVER['HTTP_REFERER']."\r\n\r\n".__('from user:').' '.$cname."\r\n\r\n".__('Record at database').":"."\r\n ~~\r\n".$ctext."\r\n ~~\r\n".__('Email of autor this comment').": ".$cmail."\r\n".__('IP of autor this comment').":  ".$_SERVER['REMOTE_ADDR'];
	$headers = "From: almaz_cms@".$servname."\n";
	$headers .= "X-Sender: <almaz_cms@".$servname.">\n";
	$headers .= "Content-Type: text/plain; charset=UTF-8";
	mail(MAIL, $subject, $body, $headers);
}
	else {
    echo "It is spam...<br />C IP: " . $_SERVER['REMOTE_ADDR'] . "<br />" . $_SERVER['HTTP_USER_AGENT'];
	}
}

echo "\r\n</body>\r\n</html>";
exit();
?>
