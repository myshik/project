<?php
//echo $_SERVER['QUERY_STRING'];		//$_SERVER['QUERY_STRING'] = $pid
// function of localization:
plugin_textdomain('polls', 'plugins/polls');
$stat=DATAPATH.$pollstat;
$fc=file($stat);
$razd2='@@';
// Read database and return data of this poll
function pagepoll($fc) {
$iss=0;
for($a=0;$a<count($fc);$a++) {
	list($act,$cookie,$pid,$them,$qwe)=explode(RAZDELITEL,$fc[$a]);
	if($act==='1' && $pid===$_SERVER['QUERY_STRING']) {
		$iss++;
	}
}

for($a=0;$a<count($fc);$a++) {
	list($act,$cookie,$pid,$them,$qwe)=explode(RAZDELITEL,$fc[$a]);
	if($iss===1 && $act==='1' && $pid===$_SERVER['QUERY_STRING']) {
		$thispoll=$fc[$a];
	} else {$thispoll='';}
}
unset($fc);
return $thispoll;
}
$pollpage=pagepoll($fc);
if(!empty($pollpage)) {
// Read data of this poll
list($act,$cookie,$pid,$them,$qwe)=explode(RAZDELITEL,$pollpage);

$qwst=explode($razd2,$qwe);
//print_r($qwst);
if($_SERVER['QUERY_STRING']===$pid) {
echo '<table class="postpoll"><tr><td></td><td class="postpolltd">';
echo "\r\n<h3>".__('Conducting poll in this article')."</h3>\r\n";
	// If user have already voted:

	if(isset($_COOKIE[$cookie])){
	$umess=__('You have already voted in this poll. Thanks', 'polls').'!';
	}
	// If user did not vote:
	else {
	$umess=__('You have not expressed an opinion', 'polls').'.';
		if(isset($_POST['answer'])) {		// $_POST['answer'] = number of selected answer
		preg_match("/^(http:\/\/)?([^\/]+)/i", SITEURL, $matches);
		setcookie($cookie, $matches[2], time()+31536000);
		$umess=__('Thank you. Your voice takes into account', 'polls').'.';
		list($act,$cookie,$pid,$them,$qwe)=explode(RAZDELITEL,$pollpage);
		$qwst=explode($razd2,$qwe);		// array with [x] = answer=>result
			for($d=0;$d<count($qwst);$d++) {
				if($d==$_POST['answer']) {
				list($ans1,$rez1)=explode('=>',$qwst[$d]);
				//echo "<br />\r\n".$rez1."<br />\r\n";	//Old result
				trim($rez1);
				$rez1 += 1;
				//echo "<br />\r\n".$rez1."<br />\r\n";	//New result
				$astr=$ans1.'=>'.$rez1;
				} else {$astr=$qwst[$d];}
				{$aass[]=$astr;}			// array with new result this poll
			}

			$newstr=implode($razd2,$aass)."\r\n";
			//echo "<br />".$newstr."<br />";		//string of answers with new results
			$ncnt='';
			for($f=0;$f<count($fc);$f++) {
			list($act,$cookie,$pid,$them,$qwe)=explode(RAZDELITEL,$fc[$f]);
				if($cookie===$_POST['cookies']) {
				$fc[$f]=$act.RAZDELITEL.$cookie.RAZDELITEL.$pid.RAZDELITEL.$them.RAZDELITEL.$newstr;
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

	$fc=file($stat);
	// Read data of this poll
	list($act,$cookie,$pid,$them,$qwe)=explode(RAZDELITEL,$pollpage);
	if(isset($qwe)) {
		$qwst=explode($razd2,$qwe);
		//print_r($qwst);

		for($i=0;$i<count($qwst);$i++) {
			list($qu,$val)=explode('=>',$qwst[$i]);
			{$answs[]=$qu;}
			{$votes[]=$val;}
		}
		unset($qwst);
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
	//Form
	if($_POST['pagepoll'] =='') {
	echo "<div>\r\n<form id=\"pollForm\" action=\"\" method=\"post\">\r\n";
	$tp=$pollpage;

		echo '<input type="hidden" name="cookies" value="'.$cookie.'" />';
		echo "<p><b>" . $them . "</b></p>\r\n";
		$qwst=explode($razd2,$qwe);
		//print_r($qwst);
			for($i=0;$i<count($qwst);$i++) {
				list($qu,$val)=explode('=>',$qwst[$i]);
				echo "<p class=\"ask\"><label><input name=\"answer\" type=\"radio\" value=\"" . $i . "\" /> ".$qu."</label></p>\r\n";
			}

	echo "<p><input name=\"pagepoll\" type=\"submit\" value=\"".__('Vote', 'polls')."!\" /></p>\r\n" ;
	echo "</form>\r\n</div>\r\n";
	}
echo '<h3>'.__('Results of this polls:', 'polls')."</h3>\r\n";
echo $mess;
echo "</td><td></td></tr></table>\r\n";
}
}
?>

