<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Rendering_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
    }
}


