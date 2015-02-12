<?php
// function of localization:
$spath = str_replace("\\","/",dirname(__FILE__));
$s="'/plugins/.*'";
$r='/';
$path=preg_replace($s,$r,$spath);
require_once $path.'const.php';
plugin_textdomain('polls', 'plugins/polls');
$stat=DATAPATH.$pollstat;
$fc=file($stat);
$razd2='@@';
$iss=0;
for($a=0;$a<count($fc);$a++) {
	list($act,$cookie,$pid,$them,$qwe)=explode(RAZDELITEL,$fc[$a]);
	if($act==='1' && $pid==='sidebar') {
		$iss++;
	}
}
unset($fc);
if($iss===1) {
?>
	<li>
	<h2><?php echo __('Poll'); ?></h2>
		<ul>
			<li>
<script type="text/javascript">
// jqpanles examples
$(document).ready(function(){
	// ajaxContent Examples
	$('.ajax').ajaxContent();
		$('.ajax').ajaxContent({
		errorMsg:'<?php echo __('An error occurred in the process of displaying the requested page', 'polls'); ?>!',
		loadingMsg:'<?php echo __('Loading', 'polls'); ?>...',
			target:'#ajaxCont'
		});
});
</script>
<div class="pollbox">
	<div class="pollbut">
		<span class="polla"><a class="ajax" href="<?php echo SITEURL; ?>plugins/polls/vote.php"><?php echo __('Survey', 'polls'); ?></a></span>
		<span class="pollb"><a class="ajax" href="<?php echo SITEURL; ?>plugins/polls/rezult.php"><?php echo __('Results', 'polls'); ?></a></span>
		<div id="ajaxCont" class="pollcont"><?php include (SPATH . 'plugins/polls/vote.php'); ?></div>
	</div>
</div>
			</li>
		</ul>
	</li>
<?php } ?>
