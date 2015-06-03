<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('simple_log'))
{
	function simple_log($file, $content, $append = TRUE)
	{
		if ($append)
			$open_mode = 'a';
		else
			$open_mode = 'w';

		if (!$handle = @fopen($file, $open_mode))
			return;

		if (fwrite($handle, $content . "\r\n") === FALSE)
			return;

		fclose($handle);
	}
}

/* End of file simple_log_helper.php */
/* Location: ./system/application/helpers/simple_log_helper.php */