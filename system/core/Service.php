<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Service Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Servcie {

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		log_message('info', 'Servcie Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * __get magic
	 *
	 * Allows servcies to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string	$key
	 */
	public function __get($key)
	{
		// Debugging note:
		//	If you're here because you're getting an error message
		//	saying 'Undefined Property: system/core/Servcie.php', it's
		//	most likely a typo in your servcie code.
		return get_instance()->$key;
	}

}