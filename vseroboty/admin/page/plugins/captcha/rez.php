<?php
$spath = str_replace("\\","/",dirname(__FILE__));
plugin_textdomain('captcha', 'plugins/captcha');
$captcha_check = FALSE;
if (!isset($_SESSION)) session_start();
if (isset($_POST['random_string']))
if (isset($_SESSION['random_string']))
if ($_POST['random_string'])
if ($_POST['random_string']==$_SESSION['random_string']) {
$captcha_check = TRUE;
unset($_SESSION['random_string']);
}
if (isset($_POST['random_string']) && $captcha_check) { 
$rez = 1;
}
elseif(isset($_POST['random_string'])) {
$rez = 0;
}
if($rez==0) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="title" content="<?php echo __('Failed to add a comment'); ?>" />
<?php
echo '<p>';
echo __('Incorrect entered security code CAPTCHA. CAPTCHA consists of two letters and four digits. The first two letters DO NOT NEED TO ENTER. Need to ENTER ONLY THE FINAL FOUR DIGITS.', 'captcha');
echo '</p>';
echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">';
echo __('Back', 'captcha');
echo '</a>';
echo "</body></html>";
exit();
}
?>
