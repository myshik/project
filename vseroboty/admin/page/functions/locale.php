<?php
require(SPATH.'functions/l10n.php');
require(SPATH.'functions/gettext.php');
require(SPATH.'functions/filters.php');
// Load the default text localization domain.
$locale = get_locale();
$locale_file = "$locale.mo";
if ( is_readable($locale_file) ) {
require_once($locale_file);}
default_textdomain('lang/');
?>
