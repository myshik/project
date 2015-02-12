<?php
$spath = str_replace("\\","/",dirname(__FILE__));
plugin_textdomain('adminnews', 'plugins/adminnews');

$base = file(DATAPATH.'/adminnews.txt');
for($i=count($base)-1;$i>=count($base)-ANCOUNT;$i--) {
	list($date,$text)  = explode(RAZDELITEL,$base[$i]);
	$times = str_split($date);
$date = $times[6] . $times[7] . '.' . $times[4] . $times[5] . '.' . $times[0] . $times[1] . $times[2] . $times[3];
	if(!empty($base[$i])) {echo '<li><p style="text-align:left;margin-top: 0px;"><small>' . $date . '</small><br />' . $text . '</p></li>';}
}
echo '<li><a href="' . SITEURL . 'plugins/adminnews/news_page.php">'.__('All notices', 'adminnews').'</a></li>';
?>
