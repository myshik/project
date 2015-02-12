<?php
class CachedFileReader extends StringReader {
  function CachedFileReader($filename) {
    if (file_exists($filename)) {
      $length=filesize($filename);
      $fd = fopen($filename,'rb');
      if (!$fd) {
	$this->error = 3; // Cannot read file, probably permissions
	return false;
      }
      $this->_str = fread($fd, $length);
      fclose($fd);
    } else {
      $this->error = 2; // File doesn't exist
      return false;
    }
  }
}

class StringReader {
  var $_pos;
  var $_str;

  function StringReader($str='') {
    $this->_str = $str;
    $this->_pos = 0;
  }

  function read($bytes) {
    $data = substr($this->_str, $this->_pos, $bytes);
    $this->_pos += $bytes;
    if (strlen($this->_str)<$this->_pos)
      $this->_pos = strlen($this->_str);

    return $data;
  }

  function seekto($pos) {
    $this->_pos = $pos;
    if (strlen($this->_str)<$this->_pos)
      $this->_pos = strlen($this->_str);
    return $this->_pos;
  }
}

function get_locale() {
	global $locale;
	if (isset($locale))
		return apply_filters( 'locale', $locale );
	if (defined('LANG'))
		$locale = LANG;
	if (empty($locale))
		$locale = '';
	$locale = apply_filters('locale', $locale);
	return $locale;
}

function translate($text, $domain) {
	global $l10n;
	if (isset($l10n[$domain]))
		return apply_filters('gettext', $l10n[$domain]->translate($text), $text);
	else
		return $text;
}

// Return a translated string.
function __($text, $domain = 'default') {
	return translate($text, $domain);
}

// Echo a translated string.
function _e($text, $domain = 'default') {
	echo translate($text, $domain);
}

function _c($text, $domain = 'default') {
	$whole = translate($text, $domain);
	$last_bar = strrpos($whole, '|');
	if ( false == $last_bar ) {
		return $whole;
	} else {
		return substr($whole, 0, $last_bar);
	}
}

function load_textdomain($domain, $mofile) {
	global $l10n;
	if (isset($l10n[$domain]))
		return;
	if ( is_readable($mofile))
		$input = new CachedFileReader($mofile);
	else
		return;
	$l10n[$domain] = new gettext_reader($input);
}

function default_textdomain() {
	global $l10n;
	$locale = get_locale();
	if ( empty($locale) )
		$locale = 'en';
	$mofile = SPATH."/lang/$locale.mo";
	load_textdomain('default', $mofile);
}
/*
function load_plugin_textdomain($lpl) {
	global $l10n;
	$locale = get_locale();
	if ( empty($locale) )
		$locale = 'en';
	$mofile = $lpl."$locale.mo";
	load_textdomain('default', $mofile);
}*/
function plugin_textdomain($domain, $path = false) {
	$locale = get_locale();
	if ( empty($locale) )
		$locale = 'en';

	if ( false === $path )
		$path = SPATH . "$path/";

	$mofile = SPATH . "$path/lang/$locale.mo";
	load_textdomain($domain, $mofile);
	return $mofile;
}
?>
