<?php
error_reporting(0);
require('const.php');
require_once('functions/func.php');
if(SFP===1) {		// If display a static home page
	$ipfile=DATAPATH.'index.txt';
	if(file_exists($ipfile)) {
		$content = file($ipfile);
			list($keywords,$win_kontent) = explode(RAZDELITEL,$content[0]);
			$sfptext = "\r\n<div class=\"post\">\r\n" .  $win_kontent . "\r\n</div>\r\n";
		unset($content);		
	} else{$sfptext = 'FILE '.$ipfile.' NOT FOUND';}
}
else {$sfptext = '';}
$next = date("YmdHis");

$kw = "file42117";
$kw_translit = "file42117";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="description" content="<?php echo SITEDESCRIPT; ?>" />
<meta name="robots" content="index,follow" />
<meta name="revisit-after" content="1 days" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>style.css" type="text/css" media="screen" />
<?php if(LIGHTBOX !== 0 || GALLERY !== '') { ?><link rel="stylesheet" href="<?php echo SITEURL; ?>lightbox.css" type="text/css" media="screen" /><?php } ?>
<link rel="stylesheet" href="<?php echo SITEURL . 'templates/' . $themes; ?>/style.css" type="text/css" media="screen" />
<link rel="icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<title><?php echo SITEDESCRIPT . " &raquo; " . SITENAME; ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php echo SITENAME; ?>" href="<?php echo feedsite(); ?>" />
<?php
$ptitle ="index";
include ('templates/' . $themes . '/header.php');
echo $sfptext.'<p></p>';

if(FPTITLE !== 0) {			// provided that the mapping blocks should be:
echo "<ul class=\"rubblock\">";
foreach($cat as $dir => $rubrika) {
$caturl = siteengine(CAT_SCRIPT,$dir);
if(file_exists(DATAPATH.$dir."/")) {
	if ($handle = opendir(DATAPATH.$dir."/")) {
		while (false !== ($file = readdir($handle))) 
			if (preg_match("/".PAGETYPE."/i",$file)) 
				//writen to the array $files, provided that time of publication of this article no more than current time
				if($file<=$next) {$files[] = str_replace(PAGETYPE,'',$file);}
	closedir($handle);
	}
}
else {echo "<li>" . __('Not found directory').': '.$dir."</li>\r\n";}
if(!isset($files)) {continue;}
else {
rsort($files);
reset($files);
echo "\r\n<li><div>\r\n<h3>";
echo __('Last');
echo ' ' . PFI . ' ';
echo __('publications of the');
echo ' ' . count($files) . ' ';
echo __('existing in category');
echo ' <a href="' . $caturl . '" title="';
echo __('Open archive of category');
echo ' ' . $rubrika . '">' . $rubrika . '</a></h3>';
//write in the array of files for reading:
for($n=0;$n<PFI;$n++) {
	if(isset($files[$n])) {
		$ppath = $dir."/".$files[$n];
		{$pfiles[]=$ppath;}
	}
}
unset($files);
			foreach ($pfiles as $key => $val) {
				$url=DATAPATH.$val.PAGETYPE;
				$pn=preg_replace("'(.*)/(\d{14})'","$2",$val);
				if(file_exists($url)) {$content = file($url);
					list($win_pagetitle,$keywords,$meta,$descript,$win_kontent) = explode(RAZDELITEL,$content[0]);
					$postlink = siteengine(POST_SCRIPT,$val);
						if (DSCR === 1) {		// If you displaying the article with the announcement
?>
	<h2><a href="<?php echo $postlink; ?>" title="<?php echo ' ' . $win_pagetitle; ?>"><?php echo $win_pagetitle; ?></a></h2>
		<?php echo $descript; ?>
		<div  class="meta">
		<span><a href="<?php echo $postlink; ?>"><?php echo __('more'); ?></a></span>
		</div>
			<?php } else {	// If must show only title of articles ?>
	<strong><a href="<?php echo $postlink; ?>" title="<?php echo ' ' . $win_pagetitle; ?>"><?php echo $win_pagetitle; ?></a></strong>
	<small><?php echo pubtime($pn); ?></small><br />
<?php
					}
				unset($content[0]);
				}
			}
unset($pfiles);
echo "\r\n</div></li>\r\n";
continue;
		}
	}
echo "</ul>";
}
if(SFP === 0 && FPTITLE === 0) {echo '<p><b>';
echo __('Messages to the webmaster of this site');
echo ':</b></p><p>';
echo __('You have indicated in the configuration');
echo '<br /><br />1. ';
echo __('DO NOT SHOW the static article on the home page');
echo ';<br /><br />2. ';
echo __('blocks of articles on the main page is also DO NOT SHOW');
echo '.<br /><br />';
echo __('Therefore, is no content there');
echo '.<br /><br />';
echo __('Edit this helps commented out the text in the block configuration for the home page at file const.php in the root folder of the script');
echo '.</p>';
}
include ('templates/' . $themes . '/footer.php');
exit();
?>
