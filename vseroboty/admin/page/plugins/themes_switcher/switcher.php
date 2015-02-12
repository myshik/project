<?php
$spath = str_replace("\\","/",dirname(__FILE__));
$s = "'/plugins/.*'";
$r = '/';
$path = preg_replace($s,$r,$spath);
require_once($path.'const.php');
plugin_textdomain('themes_switcher', 'plugins/themes_switcher');
?>
<h2><?php echo __('Select', 'themes_switcher').' '.__('design', 'themes_switcher'); ?></h2>
<ul>
	<li>
		<form action="" method="post">
			<p>
<select name="templates" style="width:150px;font-size:80%;">
<?php
if (TPLDIR !== 1) {
if ($handl = opendir(SPATH . "/templates/")) {
    while (false !== ($files = readdir($handl))) 
        if ($files != "." && $files != ".." && $files != "index.php" && $files != "dropdown.js") 
			{$papki[] = $files;}
				sort($papki);
				reset($papki);
				while (list($key, $valu) = each($papki)) {
				echo "<option value=\"" . $valu . "\">" . $valu . "</option>\r\n";		
				}
    closedir($handl);
	}
}
else {
	for ($i = 0, $cnt = count($tpl); $i < $cnt; $i++) {
		echo "<option value=\"" . $tpl[$i] . "\">" . $tpl[$i] . "</option>\r\n";
		unset($tpl[$i]);
		}
}
?>
</select>
			</p><p>
<input type="submit" value="<?php echo __('Select', 'themes_switcher').'!'; ?>" />
			</p><p>
<?php
echo __('Current', 'themes_switcher').' '.__('design', 'themes_switcher'); ?>:<br /><b><?php if (empty($_SESSION['templates'])) {
echo THEMES; 
}
else { echo $_SESSION['templates']; }
?></b>
			</p>
		</form>
	</li>
</ul>
