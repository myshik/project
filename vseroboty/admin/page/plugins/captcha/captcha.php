<?php
if (!isset($_SESSION)) session_start();
$letter = array('A','B','C','D','E','F','H','J','G','Q','W','V','R','T','Y','U','P','K','L','Z','X','N');
$rkeys = array_rand($letter, 2);
$random_string = mt_rand(1000,9999);
$_SESSION['random_string'] = $random_string;

// Create the image
$width  = 220;
$height = 60;
$img = imagecreatetruecolor($width, $height);

// Create some colors
$white = imagecolorallocate($img, 255, 255, 255);
$grey = imagecolorallocate($img, 128, 128, 128);

imagefilledrectangle($img, 0, 0, $width, $height, $white);

// The text to draw
$text1 = $letter[$rkeys[0]] . $letter[$rkeys[1]];

$text2 = $random_string;
// Replace path by your own font path
$fo = array('SimplerClg.ttf','JasperCapsSh.ttf','MonumentoTitulSh.ttf','PlakatCmplSh.ttf','PlakatTitul3D.ttf','RewinderDemiSh.ttf','RewinderOtl.ttf');
$ff = $fo[array_rand($fo)];
$font = 'fonts/' . $ff;

// Add the text
$text=$text1.$text2;
imagettftext($img, 36, -3, 6, 42, $grey, $font, $text);

$text_color = imagecolorallocate($img, mt_rand(102,153), mt_rand(102,153), mt_rand(102,153));

$bx = 8;
$by = mt_rand(10,40);
$lp_x = mt_rand(($width*.1),($width*.3))+50;
$lp_y = mt_rand(10,40);
$bx1 = mt_rand(($width*.3),($width*.5));
$by1 = mt_rand(10,40);
$lp_x1 = $bx1 + mt_rand(25,54);
$lp_y1 = mt_rand(10,40);
$bx2 = mt_rand(($width*.6),($width*.8));
$by2 = mt_rand(10,40);
$lp_x2 = $bx2 + mt_rand(25,54);
$lp_y2 = mt_rand(10,40);

imageline($img,$bx,$by,$lp_x,$lp_y,$grey);
imageline($img,$bx1,$by1,$lp_x1,$lp_y1,$grey);
imageline($img,$bx2,$by2,$lp_x2,$lp_y2,$grey);
imageellipse($img, 110, mt_rand(25,35), 300, mt_rand(6,18), $white);
imageellipse($img, 110, mt_rand(28,32), 300, mt_rand(20,30), $white);

$img2 = imagecreate ($width, $height) or die ("Cannot Initialize new GD image stream");
$x=0;
$i=0;

while ($x<$width) {
$xx = mt_rand(3,4);
$yy = mt_rand(5,7);
$i=$i+($xx/36);
$y = ceil(sin($i)*$yy);
@imagecopy ($img2, $img, $x, $y, $x, 0, 5, $height);
$x++;
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header ("Content-type: image/png");
imagepng ($img2);
@imagedestroy($img2);
@imagedestroy($img);
?>
