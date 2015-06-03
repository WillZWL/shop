<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('js_cache_header'))
{
	function js_cache_header($last_modify, $manual_modify = NULL)
	{
		header("Content-type: application/javascript; charset: UTF-8");
		header('Cache-Control: private');
		header('Pragma: private');

		if ($last_modify)
		{
			$last_modify_ts = strtotime($last_modify);
		}
		else
		{
			$last_modify_ts = time();
		}

		if (!is_null($manual_modify))
		{
			$manual_modify_ts = strtotime($manual_modify);
			$last_modify_ts = max($last_modify_ts, $manual_modify_ts);
		}

		header('Etag: ' . ($etag = md5($last_modify_ts)));
		header('Last-Modified: ' . ($gm_last_modify = gmdate("D, d M Y H:i:s", $last_modify_ts) . " GMT"));
		header("Expires: " . gmdate("D, d M Y H:i:s", $last_modify_ts + 86400) . " GMT");

		if (strpos($_SERVER['HTTP_IF_MODIFIED_SINCE'], $gm_last_modify) !== FALSE || $_SERVER['HTTP_IF_NONE_MATCH'] == $etag)
		{
			header("HTTP/1.1 304 Not Modified");
			exit;
		}
	}
}

/* End of file js_helper.php */
/* Location: ./system/application/helpers/js_helper.php */