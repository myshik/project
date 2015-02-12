<?php
require('const.php');
require_once('functions/func.php');
$now = date("D, d M Y H:i:s");
$next = date("YmdHis");
//Determine the current directory
foreach($cat as $k => $v)
{ if (preg_match("/".$k."/i", $_SERVER['QUERY_STRING'])) {
    $thiscat = $k;
	$thisrub = $v;
	}
}
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n"; ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:wfw="http://wellformedweb.org/CommentAPI/">
<!-- generator="DataLife Engine" -->
<channel>
<title><?php echo __('Archive category');
echo ' ' . $thisrub . ' - ' . SITENAME; ?></title>
<link><?php echo siteengine(CAT_SCRIPT,$thiscat); ?></link>
<description><?php echo SITEDESCRIPT; ?></description>
<generator>DataLife Engine</generator>
<language>ru</language>
<pubDate><?php echo $now; ?> GMT</pubDate>
<lastBuildDate><?php echo $now; ?> GMT</lastBuildDate>
<?php
$keypop = PFP;
if(file_exists(DATAPATH.'/'.$thiscat."/")) {
	if ($handle = opendir(DATAPATH.'/'.$thiscat."/")) {
		while (false !== ($file = readdir($handle))) 
			if (preg_match("/".PAGETYPE."/i",$file)) {
				//writen to the array $files, provided that time of publication of this article no more than current time
				if($file<=$next) {$files[] = str_replace(PAGETYPE,'',$file);}
		}
	closedir($handle);
	}
}
if(!isset($files)) {echo "<description>".__('Empty here for the time being')."...</description>\r\n";}
else {
	rsort($files);
	reset($files);
//write in the array of files for reading:
for($n=0;$n<PFP;$n++) {
	if(isset($files[$n])) {
		$ppath = $thiscat."/".$files[$n];
		{$pfiles[]=$ppath;}
	}
}
unset($files);
foreach ($pfiles as $key => $val) {
$url=$val.PAGETYPE;			//path to file
if(file_exists(DATAPATH.'/'.$url)) {
	$content=file(DATAPATH.'/'.$url);
	list($win_pagetitle,$keywords,$meta,$descript,$win_kontent) = explode(RAZDELITEL,$content[0]);
		$postlink = siteengine(POST_SCRIPT,$val);
		$pubtime = pubtime(str_replace($thiscat.'/','',$val));
//Displaying information in a browser
?>
<item>
<title><?php echo $win_pagetitle; ?></title>
<description><?php echo strip_tags(stripslashes($descript)); ?></description>
<pubDate><?php echo pubtime($val); ?> GMT</pubDate>
<category><?php echo $thisrub; ?></category>
<guid><?php echo $postlink; ?></guid>
</item>
<?php
		unset($content);
		}
	}
}
?>
</channel>
</rss>
<?php exit(); ?>
