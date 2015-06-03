<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(version_compare(PHP_VERSION, '5')>=0)
{
	require_once(BASEPATH.'plugins/tbs_class_php5.php');
}
else
{
	require_once(BASEPATH.'plugins/tbs_class_php.php');
}

function init_tbs()
{
	$obj =& get_instance();
	$obj->tbswrapper = new Tbswrapper();
	$obj->ci_is_loaded[] = 'tbswrapper';
}

class Tbswrapper
{
	private static $TBS = null;

	public function __construct()
	{
		if(self::$TBS == null) $this->TBS = new clsTinyButStrong();
	}

	public function tbsLoadTemplate($File, $HtmlCharSet='', $preferred_template_lang = '', $lang_text = '')
	{
		if ($preferred_template_lang == '')
			$try_to_find_lang = strtolower($_SESSION["lang_id"]);
		else
			$try_to_find_lang = strtolower($preferred_template_lang);

		if (strstr($File, '.'))
		{
			$original_file = explode('.', $File);
			$testFileNew = $original_file[0] . "_" . $try_to_find_lang . "." . $original_file[1];
		}
		else
		{
			$testFileNew = $File . "_" . $try_to_find_lang;
		}

		if (file_exists(PUBLIC_HTML_PATH . $testFileNew))
		{
			$specific_template = $testFileNew;
		}
		else
			$specific_template = $File;
		$result = $this->TBS->LoadTemplate($specific_template, $HtmlCharSet);
		if ($result)
		{
			if ($lang_text != '')
			{
				$this->tbsMergeField('lang_text', $lang_text);
			}
			return TRUE;
		}
		else
			return FALSE;
	}

	public function tbsMergeBlock($BlockName, $Source)
	{
		return $this->TBS->MergeBlock($BlockName, $Source);
	}

	public function tbsMergeField($BaseName, $X)
	{
		return $this->TBS->MergeField($BaseName, $X);
	}

	public function tbsRender()
	{
		$this->TBS->Show(TBS_NOTHING);
		return $this->TBS->Source;
	}
}
