<?php
// 自訂的helper function

if ( ! function_exists('str_slug_utf8'))
{
	/**
	 * Generate a URL friendly "slug" from a given string.
	 *
	 * @param  string  $title
	 * @param  string  $separator
	 * @return string
	 */
	function str_slug_utf8($title, $separator = '-')
	{
		// 跟原來的插在這行
		//$title = static::ascii($title); //comment it out to suport farsi

    	// Convert all dashes/underscores into separator
		$flip = $separator == '-' ? '_' : '-';

	    $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

	    // Remove all characters that are not the separator, letters, numbers, or whitespace.
	    $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

	    // Replace all separator characters and whitespace by a single separator
	    $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

	    return trim($title, $separator);
	}
}