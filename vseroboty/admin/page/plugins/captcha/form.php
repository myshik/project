<?php
$spath = str_replace("\\","/",dirname(__FILE__));
plugin_textdomain('captcha', 'plugins/captcha');
?>
<div>
<?php _e('Refresh image can be by clicking on it.', 'captcha'); ?>
</div><div>
<div id="captcha" style="height:62px;padding:3px 0;"><img onclick="reload(); return false;" src="<?php echo SITEURL; ?>plugins/captcha/captcha.php" style="border:1px solid #999;" alt="" /></div>
</div>
<div>
<script type="text/javascript">
function reload () {
var rndval = new Date().getTime();
document.getElementById('captcha').innerHTML = '<img onclick="reload(); return false;" src="<?php echo SITEURL; ?>plugins/captcha/captcha.php?rndval=' + rndval + '" style="border:1px solid #999;" alt="" />';
};
</script>
</div>
<div>
<?php _e('Enter only the last 4 digits, first 2 letters DO NOT WRITE.', 'captcha'); ?>
</div><div>
<input type="text" name="random_string" maxlength="4" />
</div>
