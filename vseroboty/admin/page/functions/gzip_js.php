<?php
require '../const.php';
// This file defines the necessary set of active Java-scripts in the config for plugins to integrate them into one file and returns through the GZIP
if(GZIP===1) {ob_start('ob_gzhandler');}
header('Content-type: text/javascript; charset: UTF-8');
header('Cache-Control: must-revalidate');
header('Expires: '.gmdate('D, d M Y H:i:s',time()+604800).' GMT');

if(SWFOBJECT===1) {require SPATH.'functions/js/swfobject.js';}			// connect if the plugin swfobject activated.
echo "\r\n";
if(GALLERY !== '') {													// connect if GALLERY activated (the variable GALLERY is not empty)
require SPATH.'gallery/js/jcar/jquery.jcarousel.js';
echo "\r\n";
require SPATH.'gallery/js/lightbox/jquery.lightbox.js';
}
if(LIGHTBOX !== 0) {													// If GALLERY NOT activated, but the lightbox is activated, connect without carousel
if(GALLERY == '') {
require SPATH.'gallery/js/lightbox/jquery.lightbox.js';
}
}
echo "\r\n";
if(POLL===1) {require SPATH.'functions/js/jquery.ajaxcontent.js';}
echo "\r\n";
require SPATH.'functions/js/tooltip.js';
echo "\r\n";
require SPATH.'functions/js/dropdown.js';
?>
