<?php
$spath = str_replace("\\","/",dirname(__FILE__));
$s="'/plugins/.*'";
$r='/';
$path=preg_replace($s,$r,$spath);
require_once $path.'const.php';
// function of localization:
plugin_textdomain('polls', 'plugins/polls');
$file=DATAPATH.$pollstat;
$fc=file($file);
//print_r($fc);
$razd2='@@';
?>
<span>
<script type="text/javascript">
function FormClick () {
	var str = $("#pollForm").serialize();
	$.post("<?php echo SITEURL; ?>plugins/polls/rezult.php", str, function(data) {
	$("#poll").html(data);
  });
}
</script>
</span>
<div id="poll">
<?php
//Form
if(!isset($_POST['poll'])) {
echo "<form id=\"pollForm\" action=\"rezult.php\" method=\"post\">\r\n"; //Добавил rezult.php???


for($a=0;$a<count($fc);$a++) {
	list($active,$cookies,$post_id,$polltheme,$que)=explode(RAZDELITEL,$fc[$a]);
	if($active==='1' && $post_id==='sidebar') {
	echo "<p><b>" . $polltheme . "</b><input type=\"hidden\" name=\"cookies\" value=\"".$cookies."\" /></p>\r\n";	
	echo '';
	$quest=explode($razd2,$que);
	//print_r($quest);
		for($i=0;$i<count($quest);$i++) {
			list($qu,$val)=explode('=>',$quest[$i]);
			echo "<p class=\"ask\"><label><input name=\"answer\" type=\"radio\" value=\"" . $i . "\" /> ".$qu."</label></p>\r\n";
		}
	}
}
echo "<p><input onclick=\"FormClick(); return false\" name=\"poll\" type=\"submit\" value=\"".__('Vote', 'polls')."!\" /></p>\r\n" ;
echo "</form>\r\n";
}
?>
</div>
