<?php
$fc = DATAPATH.COMMENTFILE;
$read = file($fc);
if(!empty($read)) {
	for($i=0;$i<count($read);$i++) {
		if (preg_match("/[0-9]{14}::1::/", $read[$i])) {
		{$aprrc[] = $read[$i];}
		} else {continue;}
	}
	unset($read);
	if(isset($aprrc)) {
		for($a=0;$a<count($aprrc);$a++) {
			if(isset($aprrc[$a])) {
			list($pagelink,$appruved,$rubrika,$name,$postname,$mail,$textcom,$ip,$user_agent,$date) = explode(RAZDELITEL,$aprrc[$a]);
			$postl = $rubrika . $pagelink;
			$l = '<a href="' . siteengine('single',$postl) . '">' . $postname . '</a>';
			{$li[] = $l;}
			}
		}
		unset($aprrc);
		$links = array_count_values($li);
		unset($li);
		while (list($key, $val) = each($links)) {
			$lb = $val . RAZDELITEL . $key;
			{$lib[] = $lb;}
		}
		unset($links);
		rsort($lib);
		for($i=0;$i<COUNTTOP;$i++) {
			if(isset($lib[$i])) {
			list($kl,$linkta) = explode(RAZDELITEL,$lib[$i]);
			if (!empty($linkta)) {echo "<li>" . $linkta . ': ' . $kl . " </li>\r\n";}
			}
		}
		unset($lib);
	} else {echo '<li>'.__('No comments for articles on this site').'</li>';}
}
else {echo '<li>'.__('No comments for articles on this site').'</li>';}
?>
