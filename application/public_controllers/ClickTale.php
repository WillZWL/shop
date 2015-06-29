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

/* End of file checkout.php */
/* Location: ./app/public_controllers/checkout.php */