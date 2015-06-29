<?php

class ClickTale extends PUB_Controller
{
    public function ClickTale()
    {
        parent::PUB_Controller();
    }

    public function ClickTaleCache()
    {
        $this->load->view('ClickTale/ClickTaleCache.php');
    }
}


