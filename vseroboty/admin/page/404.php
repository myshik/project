<?php
require('const.php');
require('functions/func.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="title" content="<?php echo __('Page 404'); ?>" />
<meta name="robots" content="index,follow" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>style.css" type="text/css" media="screen" />
<?php if(LIGHTBOX !== 0 || GALLERY !== '') { ?><link rel="stylesheet" href="<?php echo SITEURL; ?>lightbox.css" type="text/css" media="screen" /><?php } ?>
<link rel="stylesheet" href="<?php echo SITEURL . 'templates/' . $themes; ?>/style.css" type="text/css" media="screen" />
<link rel="icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<title><?php echo __('Page 404'); ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php echo SITENAME; ?>" href="<?php echo feedsite(); ?>" />
<?php
// list RSS-feeds:
foreach($cat as $dir => $rub) {echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"" . $rub . "\" href=\"".feedengine($dir)."\" />\r\n";}
include ('stat.php');
include('./templates/' . $themes . '/header.php');
?>
<h2><?php echo __('Page 404 - Not Found'); ?>.</h2>
<p><?php echo __('You are here because the pages which you want to get on this site do not exist. Perhaps, this page it was before, but at the moment this page or renamed, or moved, or removed entirely ...'); ?></p>
<p><?php echo __('But do not despair, you can use navigate on site or search!'); ?></p>
<?php
include('./templates/' . $themes . '/footer.php');
exit();
?>
