<?php
require('const.php');
require_once('functions/func.php');
$now = date("D, d M Y H:i:s");
$next = date("YmdHis");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n"; ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:wfw="http://wellformedweb.org/CommentAPI/">
<!-- generator="DataLife Engine" -->
<channel>
<title><?php echo SITENAME; ?></title>
<link><?php echo SITEURL; ?></link>
<description><?php echo SITEDESCRIPT; ?></description>
<generator>DataLife Engine</generator>
<language>ru</language>
<pubDate><?php echo $now; ?> GMT</pubDate>
<lastBuildDate><?php echo $now; ?> GMT</lastBuildDate>
<?php
foreach($cat as $dir => $rubrika) {
if(file_exists(DATAPATH.$dir."/")) {
	if ($handle = opendir(DATAPATH.$dir."/")) {
		while (false !== ($file = readdir($handle))) 
			if (preg_match("/".PAGETYPE."/i",$file)) 
				//writen to the array $files, provided that time of publication of this article no more than current time
				if($file<=$next) {$files[] = str_replace(PAGETYPE,'',$file);}
	closedir($handle);
	}
}
else {continue;}
if(!isset($files)) {continue;}
else {
rsort($files);
reset($files);
for($n=0;$n<PFI;$n++) {
	if(isset($files[$n])) {
		$ppath = $dir."/".$files[$n];
		{$pfiles[]=$ppath;}
	}
}
unset($files);
//echo "<pre>\r\n";print_r($pfiles);echo "</pre>\r\n";
		foreach ($pfiles as $key => $val) {
			$query = '/' . $val;
			$url=DATAPATH . $query . PAGETYPE;
			if(file_exists($url)) {$content = file($url);
			list($win_pagetitle,$keywords,$meta,$descript,$win_kontent) = explode(RAZDELITEL,$content[0]);
			$postlink = siteengine(POST_SCRIPT,$query);
?>
<item>
<title><?php echo $win_pagetitle; ?></title>
<link><?php echo $postlink; ?></link>
<description><?php echo strip_tags(stripslashes($descript)); ?></description>
<pubDate><?php echo pubtime($val); ?> GMT</pubDate>
<category><?php echo $rubrika; ?></category>
<guid><?php echo $postlink; ?></guid>
</item>
<?php
			unset($content);
			}
		}
	}
unset($pfiles);
continue;
}
?>
</channel>
</rss>
<?php exit(); ?>
