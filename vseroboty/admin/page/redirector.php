<?php
require('const.php');
$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
if(preg_match('#(http?|ftp)://\S+[^\s.,>)\];\'\"!?]#i',$url)){

?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
 <b><?php echo __('Redirecting'); ?><br />
<?php echo __('or go back'); ?> <a href="<?php echo SITEURL; ?>"><?php echo __('to home'); ?></a></b>
<?php
    sleep(3);
    //header("Location: $url");
	echo "<html><head><meta http-equiv=\"refresh\" content=\"0;url=$url\"></head></html>";
    exit();
}
?> 
