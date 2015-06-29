<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

interface Actable_service
{
    public function init();

    public function run($dto);
}

/* End of file actable_service.php */
/* Location: ./system/application/libraries/service/Actable_service.php */