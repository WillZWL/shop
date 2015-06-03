<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Check whether a similar key exists in the
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('array_similar_key'))
{
	function array_similar_key($input = array(), $search_value = NULL)
	{
		if (!is_array($input) || count($input) <= 0)
		{
			return FALSE;
		}

		// Return first key of the array
		if (!is_string($search_value))
		{
			return FALSE;
		}

		foreach ($input as $key=>$value)
		{
			if (strpos($key, $search_value) !== FALSE)
			{
				return $key;
			}
		}

		return FALSE;
	}
}

/**
 * Check whether a similar key exists in the
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('array_similar_key_exists'))
{
	function array_similar_key_exists($input = array(), $search_value = NULL)
	{
		$key_found = array_similar_key($input, $search_value);

		if ($key_found !== FALSE)
		{
			return TRUE;
		}

		return FALSE;
	}
}
