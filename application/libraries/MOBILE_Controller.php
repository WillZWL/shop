<?php

class MOBILE_Controller extends PUB_Controller
{
    function __construct())
    {
        parent::PUB_Controller($params);
        if (isset($params['template']) && ($params['template'] == 'default'))
        {
            $this->load->library('template');
            $this->template->set_template($params['template']);
        }

$this->load->model('template/template_model');
$this->load->helper('string');
}

public
function load_tpl($region, $view, $vars = array(), $overwrite = FALSE, $autoload_meta = FALSE)
{
    $preLoadData = $this->get_preload_data();
    $data = $preLoadData;

//get template language
    $templateLang["lang_text"] = $this->load_template_language();
//prepare header
    $headerData = $this->template_model->load_default("header", $preLoadData);
    $head = array_merge($preLoadData, $headerData, $templateLang);
    $this->template->write_view('header', $this->template->template["template"] . '_header', $head, $overwrite);
//prepare footer
    $footerData = $this->template_model->load_default("footer", $preLoadData);
    $foot = array_merge($preLoadData, $footerData, $templateLang);

    include_once(APPPATH . 'hooks/country_selection.php');
    $foot['full_site_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://") . Country_selection::rewrite_domain_by_country('www.valuebasket.com', PLATFORMCOUNTRYID, TRUE);

    $this->template->write_view('footer', $this->template->template["template"] . '_footer', $foot, $overwrite);

    if ($vars && is_array($vars)) {
        $data = $vars;
    }
//load default laguage file
    if (!isset($vars['lang_text'])) {
        $data['lang_text'] = $this->_get_language_file();
    } else
        $data['lang_text'] = $vars['lang_text'];

    if ($autoload_meta) {
        $meta_title = $data['lang_text']['meta_title'];
        $meta_desc = $data['lang_text']['meta_desc'];
        $meta_keyword = $data['lang_text']['meta_keyword'];
        if (!empty($meta_title)) {
            $this->template->add_title($meta_title);
        }
        if (!empty($meta_desc))
            $this->template->add_meta(array('name' => 'description', 'content' => $meta_desc));
        if (!empty($meta_keyword))
            $this->template->add_meta(array('name' => 'keywords', 'content' => $meta_keyword));
    }
    $tracking_script = $this->auto_load_tracking($vars['tracking_data']);
    $this->template->add_js($tracking_script, "print", false, "body");
    $this->template->write_view($region, $view, $data, $overwrite);
    $this->template->render();
}
}

/* End of file MOBILE_Controller.php */
