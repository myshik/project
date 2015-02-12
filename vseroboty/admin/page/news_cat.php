<?php
error_reporting(0);
require('const.php');
require_once('functions/func.php');
$thiscat = preg_replace("'(/)?(\d+)?'", '', $_SERVER['QUERY_STRING']);
//Determine the current directory
foreach($cat as $k => $v) {
	if (preg_match("/".$k."/i",$_SERVER['QUERY_STRING'])) {
		$thiscat = $k;
		$thisrub = $v;
	}
}
if(!empty($_SERVER['QUERY_STRING'])) {
$page_num=str_replace('/','',str_replace($thiscat,'',$_SERVER['QUERY_STRING']));	//number of thise page
$keypop = $page_num * PFP - PFP;
} else {$keypop = 0; $page_num = 1;}
if ($keypop<0) {$keypop = 0; $page_num = 1;}
if($page_num != 1) {$pnm=' &raquo; '.__('Page').' '.$page_num;}
else {$pnm='';}
$navcat = nav_cat($cat_tree);
foreach($navcat as $ck => $cv) {
if($ck === $thisrub) {
$catn = '<a href="'.SITEURL.'" title="'.__('Home').'">'.__('Home').'</a> - '.$cv;
	}
}
$next = date("YmdHis");
$sc = subcategory($thiscat,$cat_tree);
$catl = siteengine(CAT_SCRIPT,$thiscat);


function extractKeyword($url) 
{  
    $searchEngines = array(  
        'google.' => 'q',
        'yahoo.' => 'p',  
        'live.' => 'q',  
        'msn.' => 'q',  
        'aol.' => 'query',
        'ask.' => 'q',  
        'altavista.' => 'q',  
        'yandex.' => 'text', 
        'mail.ru' => 'q',  
        'rambler.ru' => 'words'
    );  
    $host = parse_url($url, PHP_URL_HOST);  
    $query = parse_url($url, PHP_URL_QUERY);  
    $queryItems = array();  
    parse_str($query, $queryItems);  
    foreach ($searchEngines as $needle=>$param) {  
        if (strpos($host, $needle)!==false && !empty($queryItems[$param])) {  
            return urldecode($queryItems[$param]);  
        }  
    }  
    return false;  
}  
$ptitle = "file1203";
$url = $_SERVER["HTTP_REFERER"];
$keywords = extractKeyword($url);
if ($keywords != "") {$ptitle = $keywords;}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="title" content="Категория: <?php echo $thisrub.$pnm; ?>" />
<meta name="description" content="Archive category <?php echo $thisrub.$pnm; ?>" />
<meta name="robots" content="index,follow" />
<meta name="revisit-after" content="1 days" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>style.css" type="text/css" media="screen" />
<?php if(LIGHTBOX !== 0 || GALLERY !== '') { ?><link rel="stylesheet" href="<?php echo SITEURL; ?>lightbox.css" type="text/css" media="screen" /><?php } ?>
<link rel="stylesheet" href="<?php echo SITEURL . 'templates/' . $themes; ?>/style.css" type="text/css" media="screen" />
<link rel="icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<title><?php echo $thisrub.$pnm; ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php echo SITENAME; ?>" href="<?php echo feedsite(); ?>" />
<?php
include('templates/'.$themes.'/header.php');
echo '<div class="hornav"><p>'.$catn.'</p></div>';
echo __('Archive category');
echo " \"$thisrub\"";

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
else {echo "\r\n<br /><br />\r\n".__('Not found directory').': '.$thiscat."<br /><br />\r\n";}
if(!isset($files) && $sc !== '') {echo '<p>'.__('In this category have subcategories').':</p>'.$sc;}
elseif(!isset($files) && $sc == '') {echo '<p>'.__('Empty here for the time being').'...</p>';}
else {
	rsort($files);
	reset($files);
	$num=ceil(count($files)/PFP);
//write in the array of files for reading:
for($n=$keypop;$n<$keypop+PFP;$n++) {
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
	<h2><a title="<?php echo $win_pagetitle.' - '.__('Permalink to post'); ?>" href="<?php echo $postlink; ?>"><?php echo $win_pagetitle; ?></a></h2>
	<?php echo $descript; ?>
		<div  class="meta">
		<span><a href="<?php echo $postlink; ?>"><?php echo __('Read more'); ?>...</a></span> &nbsp;&bull;&nbsp;
		<span><?php echo __('Published'); ?>: <?php echo $pubtime; ?> <?php echo __('in the category'); ?> <a href="<?php echo $catl; ?>" title="<?php echo __('View all entries in category'); ?> <?php echo $thisrub; ?>">"<?php echo $thisrub; ?>"</a></span>
		</div>
<?php
	unset($content);
	}
}
// Construction of navigation through the pages:
$p_1 = $page_num - 5;
$p_2 = $page_num - 4;
$p_3 = $page_num - 3;
$p_4 = $page_num - 2;
$p_5 = $page_num - 1;
$p_6 = $page_num + 1;
$p_7 = $page_num + 2;
$p_8 = $page_num + 3;
$p_9 = $page_num + 4;
$p_0 = $page_num + 5;
if(isset($num)) {$nums = '/'.$num;}
else {$nums = '';}
	if($p_5 >= 1 || $p_6 <= $num) {
		echo "<div class=\"pagenav\"><b>";
		echo __('Pages');
		if(isset($num)) {echo ": ".$num."</b> ";}
		else {echo ": 1</b> ";}
		echo "<span><a title=\"".__('At start')."\" href=\"".$catl."\">".__('At start')."</a></span>";
		if ($p_1 >= 1) {echo "<span><a title=\"".__('At page').' '.$p_1."\" href=\"".$catl."/". $p_1."\">".$p_1."</a></span>";}
		if ($p_2 >= 1) {echo "<span><a title=\"".__('At page').' '.$p_2."\" href=\"".$catl."/". $p_2."\">".$p_2."</a></span>";}
		if ($p_3 >= 1) {echo "<span><a title=\"".__('At page').' '.$p_3."\" href=\"".$catl."/". $p_3."\">".$p_3."</a></span>";}
		if ($p_4 >= 1) {echo "<span><a title=\"".__('At page').' '.$p_4."\" href=\"".$catl."/". $p_4."\">".$p_4."</a></span>";}
		if ($p_5 >= 1) {echo "<span><a title=\"".__('At page').' '.$p_5."\" href=\"".$catl."/". $p_5."\">".$p_5."</a></span>";}
		echo "<span>".$page_num."</span>";
		if ($p_6 <= $num) {echo "<span><a title=\"".__('At page').' '.$p_6."\" href=\"".$catl."/". $p_6."\">".$p_6."</a></span>";}
		if ($p_7 <= $num) {echo "<span><a title=\"".__('At page').' '.$p_7."\" href=\"".$catl."/". $p_7."\">".$p_7."</a></span>";}
		if ($p_8 <= $num) {echo "<span><a title=\"".__('At page').' '.$p_8."\" href=\"".$catl."/". $p_8."\">".$p_8."</a></span>";}
		if ($p_9 <= $num) {echo "<span><a title=\"".__('At page').' '.$p_9."\" href=\"".$catl."/". $p_9."\">".$p_9."</a></span>";}
		if ($p_0 <= $num) {echo "<span><a title=\"".__('At page').' '.$p_0."\" href=\"".$catl."/". $p_0."\">".$p_0."</a></span>";}
		echo "<span><a title=\"".__('At end')."\" href=\"".$catl. $nums."\">".__('At end')."</a></span></div>";
	}
}
include('templates/'.$themes.'/footer.php');
exit();
?>
