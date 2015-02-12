<?php
die();
error_reporting(0);
require('const.php');
require_once('functions/func.php');
//Determine the current directory
foreach($cat as $k => $v) {
	if (preg_match("/".$k."/i",$_SERVER['QUERY_STRING'])) {
		$thiscat = $k;
		$thisrub = $v;
		$rlink = '<a href="'.siteengine(CAT_SCRIPT,$thiscat).'" title="'. $thisrub.'">'. $thisrub.'</a>';
	}
}


$navcat = nav_cat($cat_tree);
foreach($navcat as $ck => $cv) {
	if($ck === $thisrub) {
	$catn = '<a href="'.SITEURL.'">'.__('Home').'</a> - '.$cv;
	}
}
$number = str_replace('/','',str_replace($thiscat."/",'',$_SERVER['QUERY_STRING']));
$url=$thiscat."/".$number.PAGETYPE;											//path to file
if(!file_exists(DATAPATH.$url)) {echo "0";}		// If the file does not exist, give 404 error
else{																		// If the file is found
$f	= file(DATAPATH.$url);
list($ptitle,$keywords,$meta,$descript,$win_kontent) = explode(RAZDELITEL,$f[0]);
unset($f);

function replace($s, $r, $exmp)
{
	$p = strpos($exmp, $s);
	if (false !== $p)
	{
	$exmp[$p] = $r;
	return $exmp;
	}
	else
	{
		return $exmp;
	}
}

function translitIt($str) 
{
    $tr = array(
        "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
        "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
        "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
        "О"=>"o","П"=>"p","Р"=>"r","С"=>"S","Т"=>"t",
        "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
        "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
        "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"_"
    );
    return strtr($str,$tr);
}

function _strtolower($string)
{
    $small = array('а','б','в','г','д','е','ё','ж','з','и','й',
                   'к','л','м','н','о','п','р','с','т','у','ф',
                   'х','ч','ц','ш','щ','э','ю','я','ы','ъ','ь',
                   'э', 'ю', 'я');
    $large = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й',
                   'К','Л','М','Н','О','П','Р','С','Т','У','Ф',
                   'Х','Ч','Ц','Ш','Щ','Э','Ю','Я','Ы','Ъ','Ь',
                   'Э', 'Ю', 'Я');
    return str_replace($large, $small, $string); 
}
 
function _strtoupper($string)
{
    $small = array('а','б','в','г','д','е','ё','ж','з','и','й',
                   'к','л','м','н','о','п','р','с','т','у','ф',
                   'х','ч','ц','ш','щ','э','ю','я','ы','ъ','ь',
                   'э', 'ю', 'я');
    $large = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й',
                   'К','Л','М','Н','О','П','Р','С','Т','У','Ф',
                   'Х','Ч','Ц','Ш','Щ','Э','Ю','Я','Ы','Ъ','Ь',
                   'Э', 'Ю', 'Я');
    return str_replace($small, $large, $string); 
}

function _ucfirst($string)
{
	$string = _strtoupper(substr($string,0,2)).substr($string,2);
	return $string;
}

$vowels = array("Скочать","Скачать","Бесплатно","скачать","бесплатно","!","@","#","$","%","^","&","*","(",")","`","\"","№",";",":","?");
$kw = trim(str_replace($vowels, "", $ptitle));
$kw = str_replace("программу", "программа", $kw);
$kw = str_replace("програму", "программа", $kw);
$kw = str_replace("прграмму", "программа", $kw);
$kw = str_replace("прогу", "программа", $kw);
$kw = _ucfirst($kw);

$for_name_file = _strtolower($kw);
$for_name_file = str_replace("для", "for_", $for_name_file);
$for_name_file = str_replace("кряк", "crack", $for_name_file);
$for_name_file = str_replace("крэк", "crack", $for_name_file);
$for_name_file = str_replace("кейген", "кeygen", $for_name_file);
$for_name_file = str_replace("взлом", "crack", $for_name_file);
$for_name_file = str_replace("патч", "patch", $for_name_file);
$for_name_file = str_replace("пач", "patch", $for_name_file);
$for_name_file = str_replace("руссификатор", "rus", $for_name_file);
$for_name_file = str_replace("русификатор", "rus", $for_name_file);
$for_name_file = str_replace("Кряк", "crack", $for_name_file);
$for_name_file = str_replace("Крэк", "crack", $for_name_file);
$for_name_file = str_replace("Кейген", "кeygen", $for_name_file);
$for_name_file = str_replace("Взлом", "crack", $for_name_file);
$for_name_file = str_replace("Патч", "patch", $for_name_file);
$for_name_file = str_replace("Пач", "patch", $for_name_file);
$for_name_file = str_replace("Руссификатор", "rus", $for_name_file);
$for_name_file = str_replace("Русификатор", "rus", $for_name_file);
$for_name_file = str_replace("игры", "game", $for_name_file);
$for_name_file = str_replace("игру", "game", $for_name_file);
$for_name_file = str_replace("игра", "game", $for_name_file);
$for_name_file = str_replace("проги", "prog", $for_name_file);
$for_name_file = str_replace("онлайн", "online", $for_name_file);
$for_name_file = str_replace("паролей", "pass", $for_name_file);

$kw_translit = trim(translitIt($for_name_file),"_");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="title" content="<?php echo $ptitle; ?>" />
<meta name="description" content="<?php echo $meta; ?>" />
<meta name="robots" content="index,follow" />
<meta name="revisit-after" content="1 days" />
<link rel="stylesheet" href="<?php echo SITEURL . 'templates/' . $themes; ?>/style.css" type="text/css" media="screen" />
<link rel="icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo SITEURL; ?>favicon.ico" type="image/x-icon" />
<title><?php echo $ptitle; ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php echo SITENAME; ?>" href="<?php echo feedsite(); ?>" />
<?php
include('templates/'.$themes.'/header.php');
echo '<div class="hornav"><p>'.$catn.'</p>';
echo next_prev($url).'</div>';
echo "\r\n<h1>".$ptitle."</h1>\r\n";
echo "\r\n".$win_kontent."\r\n";
echo "<div  class=\"meta\"><span>";
echo __('Published');
echo ': '. pubtime($number).' ';
echo __('in the category');
echo ' '.$rlink."</span>\r\n</div>\r\n";

echo '<div class="hornav">'.next_prev($url).'</div>';
include('templates/'.$themes.'/footer.php');
}
exit();
?>
