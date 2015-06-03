<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

/*
 * Class for handling the dto without actual coding implementation.
 * This dto is expected to be a bit slow.
 *
 * @author Trunks
 */
class Lazy_dto extends Base_dto
{
	// Mode available
	const READ_ONLY = 1;
	const FIX_SIZE_WRITABLE = 2;
	const FREE_SIZE_WRITABLE = 3;

	private $property = array();
	private $mode;

	/*
	 * Constructor for the class
	 *
	 * @param array $value_array input for initialize the property and value of the dto
	 * @param int $mode to indicate the edit mode of the dto, 1 - read only, 2 - fix size but writable, 3 - free size and writable.
	 * @return null
	 *
	 * Below is inline example:
	 * <code>
	 * <?php
	 * $dto = new Lazy_dto(array('name'=>'Canon S90', 'price'=>10.09, 'currency_id'=>'GBP'), 2);
	 * echo $dto->get_name() . ' - ' . $dto->get_price();
	 * $dto->set_price(123.11);
	 * echo $dto->get_name() . ' - ' . $dto->get_price();
	 * </code>
	 */
	public function __construct($value_array, $mode = 0)
	{
		if (is_array($value_array))
		{
			$this->property = $value_array;
		}

		$this->mode = $mode;
	}

	/*
	 * Magic method for calling the object's methods.
	 *
	 * It first checks the existence of the objects
	 */
	public function __call($name, $arguments)
	{
		if (method_exists($this, $name))
		{
			return $this->$name;
		}

		$property_name = '';

		if ($this->_is_getter_call($name, $property_name))
		{
			if (array_key_exists($property_name, $this->property))
			{
				return $this->property[$property_name];
			}
			else
			{
				trigger_error
				(
					"Undefined property $property_name.",
					E_USER_NOTICE
				);

				return null;
			}
		}
		else if ($this->mode >= self::FIX_SIZE_WRITABLE
			&& $this->_is_setter_call($name, $property_name))
		{
			if ($this->mode >= self::FREE_SIZE_WRITABLE
				|| array_key_exists($property_name, $this->property))
			{
				switch(count($arguments))
				{
					case 0:
						$this->property[$property_name] = '';
						return $this->property[$property_name];
					case 1:
						$this->property[$property_name] = $arguments[0];
						return $this->property[$property_name];
					default:
						$this->property[$property_name] = $arguments;
				}
			}
			else
			{
				trigger_error
				(
					"Undefined property $property_name.",
					E_USER_NOTICE
				);

				return null;
			}
		}
		else
		{
			trigger_error
			(
				"Call to undefined method " . $property_name . "()",
				E_USER_ERROR
			);
		}
	}

	protected function _is_getter_call($method, &$property_name)
	{
		$count = 0;
		$match_pattern = '/^get_/';
		$property_name = preg_replace($match_pattern, '', $method, 1, $count);

		return ($count > 0); //If replace count is zero means it is not a getter call
	}

	protected function _is_setter_call($method, &$property_name)
	{
		$count = 0;
		$match_pattern = '/^set_/';
		$property_name = preg_replace($match_pattern, '', $method, 1, $count);

		return ($count > 0); //If replace count is zero means it is not a setter call
	}
}