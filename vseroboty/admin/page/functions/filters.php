<?php
function apply_filters($tag, $string) {
	global $f_filter, $merged_filters;
	if ( !isset( $merged_filters[ $tag ] ) )
		merge_filters($tag);
	if ( !isset($f_filter[$tag]) )
		return $string;
	reset( $f_filter[ $tag ] );
	$args = func_get_args();
	do{
		foreach( (array) current($f_filter[$tag]) as $the_ )
			if ( !is_null($the_['function']) ){
				$args[1] = $string;
				$string = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
			}
	} while ( next($f_filter[$tag]) !== false );
	return $string;
}
function merge_filters($tag) {
	global $f_filter, $merged_filters;
	if ( isset($f_filter['all']) && is_array($f_filter['all']) )
		$f_filter[$tag] = array_merge($f_filter['all'], (array) $f_filter[$tag]);
	if ( isset($f_filter[$tag]) ){
		reset($f_filter[$tag]);
		uksort($f_filter[$tag], "strnatcasecmp");
	}
	$merged_filters[ $tag ] = true;
}
?>
