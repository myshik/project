<?php
$spath = str_replace("\\","/",dirname(__FILE__));
$s="'/plugins/.*'";
$r='/';
$path=preg_replace($s,$r,$spath);
require_once $path.'const.php';
// function of localization:
plugin_textdomain('polls', 'plugins/polls');
$stat=DATAPATH.$pollstat;
$fc=file($stat);
$razd2='@@';

// Read database and return data of this poll
function sidebarpoll($fc) {
	for($a=0;$a<count($fc);$a++) {
		list($active,$cookies,$post_id,$polltheme,$que)=explode(RAZDELITEL,$fc[$a]);
		if($active==='1' && $post_id==='sidebar') {
			$thispoll=$fc[$a];
		}
	}
unset($fc);
return $thispoll;
}

// Read data of this poll
list($active,$cookies,$post_id,$polltheme,$que)=explode(RAZDELITEL,sidebarpoll($fc));
$quest=explode($razd2,$que);
//print_r($quest);

// If user have already voted:

if(isset($_COOKIE[$cookies])){
$umess=__('You have already voted in this poll. Thanks', 'polls').'!';
}
// If user did not vote:
else {
$umess=__('You have not expressed an opinion', 'polls').'.';
	if(isset($_POST['answer'])) {		// $_POST['answer'] = number of selected answer
	preg_match("/^(http:\/\/)?([^\/]+)/i", SITEURL, $matches);
	setcookie($cookies, $matches[2], time()+31536000);
	$umess=__('Thank you. Your voice takes into account', 'polls').'.';
	list($active,$cookies,$post_id,$polltheme,$que)=explode(RAZDELITEL,sidebarpoll($fc));
	$quest=explode($razd2,$que);		// array with [x] = answer=>result
		for($d=0;$d<count($quest);$d++) {
			if($d==$_POST['answer']) {
			list($ans1,$rez1)=explode('=>',$quest[$d]);
			//echo "<br />\r\n".$rez1."<br />\r\n";	//Old result
			trim($rez1);
			$rez1 += 1;
			//echo "<br />\r\n".$rez1."<br />\r\n";	//New result
			$astr=$ans1.'=>'.$rez1;
			} else {$astr=$quest[$d];}
			{$aass[]=$astr;}			// array with new result this poll
		}

		$newstr=implode($razd2,$aass);
		//echo "<br />".$newstr."<br />";		//string of answers with new results
		$ncnt='';
		for($f=0;$f<count($fc);$f++) {
		list($active,$cookies,$post_id,$polltheme,$que)=explode(RAZDELITEL,$fc[$f]);
			if($cookies===$_POST['cookies']) {
			$fc[$f]=$active.RAZDELITEL.$cookies.RAZDELITEL.$post_id.RAZDELITEL.$polltheme.RAZDELITEL.$newstr;
			}
			$ncnt=$ncnt.$fc[$f]."\r\n";
			$search="'([\r\n])([\s])+'";
			$replac="\r\n";
			$clc=preg_replace($search,$replac,$ncnt);
		}
	//echo "<pre>".$ncnt."</pre>";
	$rf = fopen($stat,"w");
	fwrite($rf,$clc);
	fclose($rf);
	}
}
unset($fc);

$fc=file($stat);
// Read data of this poll
list($active,$cookies,$post_id,$polltheme,$que)=explode(RAZDELITEL,sidebarpoll($fc));
if(isset($que)) {
	$quest=explode($razd2,$que);
	//print_r($quest);

	for($i=0;$i<count($quest);$i++) {
		list($qu,$val)=explode('=>',$quest[$i]);
		{$answs[]=$qu;}
		{$votes[]=$val;}
	}
	unset($quest);
}
$sum=array_sum($votes);
if($sum !== 0) {
	$str1='<p>'.__('Votes', 'polls').': <b>' . $sum . '</b></p><p><b>'.$umess."</b></p>";
	$str2='';
	for($i=0;$i<=count($votes)-1;$i++) {
		$proc = round($votes[$i]/$sum*1000)/10;
		$strp = '<div class="pollansw"><b>' . $answs[$i] . '</b> (' . $votes[$i] . ' - ' . $proc . '%)<div class="shkala"><div class="gradus" style="width:' . round($votes[$i]*100/$sum) . '%;"></div></div></div>';
		$str2=$str2.$strp;
	}
	$mess=$str1.$str2;
}
else {
	$mess= "<p>".__('No one expressed an opinion on this poll', 'polls').'.<br /><br />'.__('Will be the first', 'polls')."?<br /><br /></p>";
}
echo $mess;
?>
