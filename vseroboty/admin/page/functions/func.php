<?php
 // Formation of the date of publication
function pubtime($vnam) {
$ndm=preg_replace("'.*([0-9]{14})'","$1",$vnam);
$atimes = str_split($ndm);
$pdate = $atimes[6].$atimes[7].'.'.$atimes[4].$atimes[5].'.'.$atimes[0].$atimes[1].$atimes[2].$atimes[3].' '. $atimes[8].$atimes[9].':'.$atimes[10].$atimes[11].":".$atimes[12].$atimes[13];
return $pdate;
unset($atimes);
}
// RewriteEngine functions for posts
function siteengine($pagetype,$query) {
	if(ENGINE === 1) {
	$lss = SITEURL.$pagetype.'/'.$query;
	}
	else {
	$lss = SITEURL.$pagetype.'.php?'.$query;
	}
return $lss;
}
// RewriteEngine functions for gallery
function galengine($pagetype,$query) {
	if(ENGINE === 1) {
	$lgs = GALURL.$pagetype.'/'.$query;
	}
	else {
	$lgs = GALURL.$pagetype.'.php?'.$query;
	}
return $lgs;
}
// RewriteEngine functions for rss of categories
function feedengine($query) {
	if(ENGINE === 1) {
	$lfsc = SITEURL.CAT_SCRIPT.'_rss/'.$query.'/rss.xml';
	}
	else {
	$lfsc = SITEURL.CAT_SCRIPT.'_rss.php?'.$query;
	}
return $lfsc;
}
// RewriteEngine functions for rss
function feedsite() {
	if(ENGINE === 1) {
	$lffs = SITEURL.'rss.xml';
	}
else {
$lffs = SITEURL.'rss.php';
}
return $lffs;
}
 // The function of reading only title
function title($url) {
	$content = file(DATAPATH.$url);
		list($win_pagetitle,$keywords,$meta,$descript,$win_kontent) = explode(RAZDELITEL,$content[0]);
		return $win_pagetitle;
	unset($content);
}
 // Function for the horizontal navigation
function next_prev($url) {
$next = date("YmdHis");		//The current date-time
$dirn=str_replace(PAGETYPE,'',$url);
$dir = preg_replace("'([0-9]{14})'",'',$dirn);
 if ($handle = opendir(DATAPATH.$dir)) {
    while (false !== ($file = readdir($handle))) 
        if ($file != "." && $file != ".." && $file != "index.php")
			//write to the array $files, provided that time of publication of this article no more than current time
			if($file<=$next) {$files[] = str_replace(PAGETYPE,'',$file);}
				rsort($files);
				reset($files);
closedir($handle);
}
while (list($key, $val) = each($files)) {
	if($files[$key] === str_replace(PAGETYPE,'',str_replace($dir,'',$url))) {
	$nxti = $key+1;
	$prvi = $key-1;
	}
}
// Formation variables with links for navigation if exists Article
	if(isset($files[$nxti]) && file_exists(DATAPATH.$dir.$files[$nxti].PAGETYPE)) {
		$nfpath = $dir.$files[$nxti];
		$nxtl = '<p style="text-align:left;"><a href="'.siteengine(POST_SCRIPT,$nfpath).'">&larr; '.title($dir.$files[$nxti].PAGETYPE).'</a></p>';
	}
	else {
		$nxtl = '';
	}
	if(isset($files[$prvi]) && file_exists(DATAPATH.$dir.$files[$prvi].PAGETYPE)) {
		$pfpath = $dir.$files[$prvi];
		$prvl = '<p style="text-align:right;"><a href="'.siteengine(POST_SCRIPT,$pfpath).'">'.title($dir.$files[$prvi].PAGETYPE).' &rarr;</a></p>';
	}
	else {
		$prvl = '';
	}
unset($files);
$hornav = $nxtl.$prvl;
return $hornav;
}
 // End of function for the horizontal navigation
 // full category structure
function menu_cat($cat_tree) {
	for($i=0;$i<count($cat_tree);$i++) {
		if(isset($cat_tree[$i])) {
		list($pname,$purl,$folder,$name) = explode(RAZDELITEL,$cat_tree[$i]);
		$query = $purl.'/'.$folder;
			if ($purl==='') {$categ = "<li>".'<a href="'.siteengine(CAT_SCRIPT,$folder).'">'.$name."</a></li>";}
			elseif (preg_match("'([_A-Za-z0-9-]+)\/([_A-Za-z0-9-]+)'",$purl)) {$categ = "<ul><li><ul><li>".'<a href="'.siteengine(CAT_SCRIPT,$query).'">'.$name."</a></li></ul></li></ul>";}
			elseif (preg_match("'([_A-Za-z0-9-]+)'",$purl)) {$categ = "<ul><li>".'<a href="'.siteengine(CAT_SCRIPT,$query).'">'.$name."</a></li></ul>";}
			{$licat[]=$categ;}
		}
	}
$lscat=implode($licat);
$categ=str_replace('</li></ul><li>','</li></ul></li><li>',str_replace('</li></ul><ul><li>','</li><li>',str_replace('</li></ul><ul><li><ul><li>','<ul><li>',str_replace('</li></ul></li></ul><ul><li><ul><li>','</li><li>',str_replace('</li><ul>','<ul>',$lscat))))).'</li>';
$categs = str_replace('</li></li>','</li>',$categ);
return $categs;
}

// Emulation of functions PHP5 for PHP4:
if (!function_exists('array_combine')) {
	function array_combine($arr1,$arr2) {
	$out = array();
		foreach ($arr1 as $key1 => $value1) {
		$out[$value1] = $arr2[$key1];
		}
	return $out;
	}
}
if (!function_exists('str_split')) {
	function str_split($str, $l=1) {
		$str_array = explode("\r\n", chunk_split($str,$l));
		return $str_array;
	}
}
// End emulation of functions PHP5 for PHP4.
// list category
function list_cat($cat_tree) {
	for($i=0;$i<count($cat_tree);$i++) {
		list($pname,$purl,$folder,$name) = explode(RAZDELITEL,$cat_tree[$i]);
			if($purl !== '') {$cpath = $purl.'/'.$folder;}
			else {$cpath = $folder;}
			{$catpath[] = $cpath;}
			{$catname[] = $name;}
	}
$cats = array_combine($catpath, $catname);
return $cats;
}
$cat=list_cat($cat_tree);	// simple array with categories

// array for horizontal navigation categories 
function nav_cat($cat_tree) {
	for($i=0;$i<count($cat_tree);$i++) {
		list($pname,$purl,$folder,$name) = explode(RAZDELITEL,$cat_tree[$i]);
		$query = $purl.'/'.$folder;
			if($purl !== '') {$catr = '<a href="'.siteengine(CAT_SCRIPT,$purl).'" title="'.__('Parents category').' - '.$pname.'">'.$pname."</a>".' - <a href="'.siteengine(CAT_SCRIPT,$query).'" title="'.__('Category').' - '.$name.'">'.$name."</a><br />\r\n";}
			else {$catr = '<a href="'.siteengine(CAT_SCRIPT,$folder).'" title="'.__('Category').' - '.$name.'">'.$name."</a><br />\r\n";}
			{$cfold[] = $name;}
			{$cattree[] = $catr;}
	}
$ctree = array_combine($cfold, $cattree);
return $ctree;
}
// listing of subcategories in this category
function subcategory($fold,$cat_tree) {
$scat = '';
	for($i=0;$i<count($cat_tree);$i++) {
		list($pname,$purl,$folder,$name) = explode(RAZDELITEL,$cat_tree[$i]);
			if(preg_match("/".$fold."/i",$purl)) {
			$query = $purl.'/'.$folder;
			$subcat = '<li><a href="'.siteengine(CAT_SCRIPT,$query).'" title="'.__('subcategory').' - '.$name.'">'.$name.'</a></li>';
			$scat = $scat.$subcat;
			}
	}
if($scat !== '') {$scat = '<ul>'.$scat.'</ul>';}
else {$scat='';}
return $scat;
}
// function for navigations to any array:
function newoldnavi($lns,$cp,$page,$pagename) {
	$ent=$page*$cp;
	$cmax=((count($lns)-$ent)+$cp)-1;			//$page*$cp 1*3=3 3*3=9
	$cmin=$cmax-$cp;
	//echo '$cmin = '.$cmin.'<br />$cmax = '.$cmax."<br />\r\n";
	if($cmin>count($lns) or !ctype_digit($page)) {echo __('Page 404 - Not Found').'<body><html>';exit();}
	// Construction of navigation through the pages:
	$num=ceil(count($lns)/$cp);
	$p_1 = $page - 5;
	$p_2 = $page - 4;
	$p_3 = $page - 3;
	$p_4 = $page - 2;
	$p_5 = $page - 1;
	$p_6 = $page + 1;
	$p_7 = $page + 2;
	$p_8 = $page + 3;
	$p_9 = $page + 4;
	$p_0 = $page + 5;
	if(isset($num)) {$nums = $num;}
	else {$nums = '';}
	if($p_5 >= 1 || $p_6 <= $num) {
		$t1 = "<div class=\"meta\"><b>".__('Pages');
		if(isset($num)) {$t2 = ": ".$num."</b> ";} else {$t2 = ": 1</b> ";}
		$t3 = "<span><a title=\"".__('At start')."\" href=\"".$pagename."\">".__('At start')."</a></span>\r\n";
		if ($p_1 >= 1) {$t4 = "<span><a title=\"".__('At page').' '.$p_1."\" href=\"".$pagename."?". $p_1."\">".$p_1."</a></span>\r\n";} else {$t4='';}
		if ($p_2 >= 1) {$t5 = "<span><a title=\"".__('At page').' '.$p_2."\" href=\"".$pagename."?". $p_2."\">".$p_2."</a></span>\r\n";} else {$t5='';}
		if ($p_3 >= 1) {$t6 = "<span><a title=\"".__('At page').' '.$p_3."\" href=\"".$pagename."?". $p_3."\">".$p_3."</a></span>\r\n";} else {$t6='';}
		if ($p_4 >= 1) {$t7 = "<span><a title=\"".__('At page').' '.$p_4."\" href=\"".$pagename."?". $p_4."\">".$p_4."</a></span>\r\n";} else {$t7='';}
		if ($p_5 >= 1) {$t8 = "<span><a title=\"".__('At page').' '.$p_5."\" href=\"".$pagename."?". $p_5."\">".$p_5."</a></span>\r\n";} else {$t8='';}
		$t9 = "<span>".$page."</span>";
		if ($p_6 <= $num) {$t10 = "<span><a title=\"".__('At page').' '.$p_6."\" href=\"".$pagename."?". $p_6."\">".$p_6."</a></span>\r\n";} else {$t10='';}
		if ($p_7 <= $num) {$t11 = "<span><a title=\"".__('At page').' '.$p_7."\" href=\"".$pagename."?". $p_7."\">".$p_7."</a></span>\r\n";} else {$t11='';}
		if ($p_8 <= $num) {$t12 = "<span><a title=\"".__('At page').' '.$p_8."\" href=\"".$pagename."?". $p_8."\">".$p_8."</a></span>\r\n";} else {$t12='';}
		if ($p_9 <= $num) {$t13 = "<span><a title=\"".__('At page').' '.$p_9."\" href=\"".$pagename."?". $p_9."\">".$p_9."</a></span>\r\n";} else {$t13='';}
		if ($p_0 <= $num) {$t14 = "<span><a title=\"".__('At page').' '.$p_0."\" href=\"".$pagename."?". $p_0."\">".$p_0."</a></span>\r\n";} else {$t14='';}
		$t15 = "<span><a title=\"".__('At end')."\" href=\"".$pagename."?".$nums."\">".__('At end')."</a></span>\r\n</div>\r\n";
	$pana=$t1.$t2.$t3.$t4.$t5.$t6.$t7.$t8.$t9.$t10.$t11.$t12.$t13.$t14.$t15;
	} else {$pana='';}
return($pana);
}

function metategformation($tmsring) {
$s=array('\'','"',RAZDELITEL);
$r='';
$str=str_replace($s,$r,$tmsring);
return $str;
}

// Time of page generation
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
?>
