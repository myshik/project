<?php
$f = DATAPATH.COMMENTFILE;
$read = file($f);
if(!empty($read)) {
	for($i=0;$i<count($read);$i++) {
		if (preg_match("/[0-9]{14}::1::/", $read[$i])) {
		{$aprrc[] = $read[$i];}
		} else {continue;}
	}
	unset($read);
	if(isset($aprrc)) {
		for($i=(count($aprrc)-1);$i>=count($aprrc)-COUNTLC;$i--) {
			if(isset($aprrc[$i])) {
		{$blc[] = $aprrc[$i];}
			}
		}
		unset($aprrc);
		for($a=0;$a<count($blc);$a++) {
			if(isset($blc[$a])) {
			list($pagelink,$appruved,$categ,$name,$postname,$mail,$textcom,$ip,$user_agent,$date) = explode(RAZDELITEL,$blc[$a]);
			$postl = $categ . $pagelink;
			if (!empty($pagelink)) {echo '<li><span style="font-weight:bold">'.$name.'</span> - '.'<a href="'.siteengine('single',$postl).'">'.$postname.'</a>'."</li>\r\n";}
			}
		}
	} else {echo '<li>'.__('No comments for articles on this site').'</li>';}
}
else {echo '<li>'.__('No comments for articles on this site').'</li>';}
?>
