<?php
		
	$country_code = strtolower(PLATFORMCOUNTRYID);

	$this->load->helper('tbswrapper');
	$this->tbswrapper = new Tbswrapper();
	$this->tbswrapper->tbsLoadTemplate("resources/template/conditions_of_use_{$country_code}.html", '', '', $data['lang_text']);

	{ # SBF#3114		
		$sweden_country = "";
		$sweden_address = "";

		// SBF#3152, undo #3114...........................
		// if ($data["actual_country_id"] == "SE")
		// SBF#3155, make it appear everywhere
		{
			$sweden_country = "Gibraltar";
			$sweden_address = "Suite 31 Don House, 30 - 38 Main Street";
		}

		$this->tbswrapper->tbsMergeField("sbf3114a", $sweden_country);
		$this->tbswrapper->tbsMergeField("sbf3114b", $sweden_address);
	}

	$this->tbswrapper->tbsMergeField('base_url', base_url());
	$this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());

	echo $this->tbswrapper->tbsRender();
