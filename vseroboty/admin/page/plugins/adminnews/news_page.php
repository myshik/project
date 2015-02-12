<?php
session_start();
$spath = str_replace("\\","/",dirname(__FILE__));
$s = "'/plugins/.*'";
$r = '/';
$path = preg_replace($s,$r,$spath);
require('../../const.php');
require_once(SPATH . 'functions/func.php');
require_once(SPATH . 'functions/gzip.php');
$base = file(DATAPATH.'/adminnews.txt');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="title" content="<?php echo $ant; ?>" />
<meta name="robots" content="index,follow" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo SITEURL . 'templates/' . $themes; ?>/style.css" type="text/css" media="screen" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<title><?php echo $ant; ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php echo SITENAME; ?>" href="<?php echo feedsite(); ?>" />
<?php
foreach($cat as $dir => $rub) {echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"" . $rub . "\" href=\"".feedengine($dir)."\" />\r\n";}
include (SPATH.'stat.php');
include(SPATH.'templates/' . $themes . '/header.php');
echo '<h1>' . $ant . '</h1>';
echo "\r\n<ul>\r\n";
for($i=count($base)-1;$i>=0;$i--) {
	list($date,$text)  = explode(RAZDELITEL,$base[$i]);
	$times = str_split($date);
$date = $times[6] . $times[7] . '.' . $times[4] . $times[5] . '.' . $times[0] . $times[1] . $times[2] . $times[3];
	echo '<li><p><small>' . $date . '</small><br />' . $text . '</p><p>&nbsp;</p></li>';
}
echo "\r\n</ul>\r\n";
include(SPATH.'templates/' . $themes . '/footer.php');
exit();
?>
