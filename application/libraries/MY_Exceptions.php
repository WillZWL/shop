<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions
{
    protected $error_controller = 'error_page';
    protected $error_method_404 = 'error_404';

    function __construct()
    {
        parent::CI_Exceptions();
    }

    function show_error($heading, $message, $template = 'error_general')
    {
        if (file_exists(CTRLPATH . $this->error_controller . EXT)) {
            if (!function_exists('base_url')) {
                function base_url()
                {
                    global $CFG;
                    return $CFG->slash_item('base_url');
                }
            }

            require_once BASEPATH . 'helpers/url_helper.php';
            redirect(base_url() . $this->error_controller . '/' . $this->error_method_404);
        } else {
            return parent::show_error($heading, $message, $template);
        }
    }
}


