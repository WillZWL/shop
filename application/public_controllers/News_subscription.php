<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class News_subscription extends PUB_Controller
{

    public function News_subscription()
    {
        parent::PUB_Controller();
        $this->load->helper('url');
        $this->load->model('website/news_subscription_model');
    }

    public function add($email = '')
    {
        if (empty($email) || $email == '')
        {
            $email = $this->input->post('email');

            if (empty($email))
            {
                if($this->input->post('redirect_url'))
                {
                    redirect($this->input->post('redirect_url'));
                }
                else
                {
                    redirect('');
                }
            }
        }

        $this->news_subscription_model->add_subscriber($email);

        //var_dump($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
        if($this->input->post('redirect_url'))
        {
            redirect($this->input->post('redirect_url'));
        }
        else
        {
            redirect('');
        }
    }

}

/* End of file news_subscription.php */
/* Location: ./app/public_controller/news_subscription.php */