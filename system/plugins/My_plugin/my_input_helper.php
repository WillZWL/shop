<?php

include_once "validator/my_plugin_validator_interface.php";
include_once "filter/my_plugin_filter_interface.php";

class My_input_helper
{
/***************************************************************************************
**	$inputFilter: put filter object
**	$validator: put validator object
***************************************************************************************/
	public function is_input_valid($validator, $inputFilter, &$value)
	{
		if ($inputFilter instanceof My_plugin_filter_interface)
		{
			$value = $inputFilter->filter($value);
		}
		if ($validator instanceof My_plugin_validator_interface)
		{
			return $validator->is_valid($value);
		}
		return false;
	}

/***************************************************************************************
**	$validators: put validator name only as an array
**	$filters: put filter name only as an array
***************************************************************************************/
	public function is_valid($validators = array(), $filters = array(), &$value)
	{
		foreach ($filters as $filter)
		{
			include_once "filter/" . strtolower($filter) . "_filter.php";
			$className = $filter . "_validator";
			$filterObj = new $className();
			$value = $inputFilter->filter($value);
		}
		foreach ($validators as $validator => $option)
		{
			include_once "validator/" . strtolower($validator) . "_validator.php";
			$className = $validator . "_validator";
			$validatorObj = new $className($option);
			$result = $validatorObj->is_valid($value);
			if (!$result)
				return $result;
		}
		return true;
	}
}
