<?php
use ESG\Panther\Service\LoadSiteParameterService;
use Pimple\Container;
use ESG\Panther\Models\Marketing\CategoryModel;
use ESG\Panther\Service as S;
use ESG\Panther\Dao as D;

class PUB_Controller extends CI_Controller
{
    private $lang_id = 'en';
    protected $container;
    private static $serviceContainer;

//    private $allow_referer_host = '/^http[s]?:\/\/shop\.skype\.com/';
//    private $require_login = 0;
//    private $load_header = 1;
//    private $get_currency_list = 1;

    function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        if (!self::$serviceContainer) {
            $sc = new \Pimple\Container;
            $daoArr = (array) require APPPATH . 'libraries/ServicePSR4/providers.php';
            array_walk($daoArr, function($class, $i, $sc) {
                class_exists($class) AND $sc->register(new $class);
            }, $sc);

            self::$serviceContainer = true;
            $this->sc = $sc;
        }
        $this->loadModelDependcy();
/*

        if (is_array($params) && count($params) > 0) {
            $this->initialize($params);

        } elseif ($params) {
            $this->require_login = $params;
        }

        $_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];
        $ref = isset($_SERVER['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : '';

        if ($this->is_allow_referer($ref)) {
            $_SESSION['GOBACK_URL'] = $ref;
        }
        if ($this->require_login) {
            $this->check_login();
        }
*/
        $this->loadSiteParameterService = new LoadSiteParameterService();
        $this->loadSiteInfo();
    }

    public function loadModelDependcy()
    {
        $this->sc['categoryModel'] = function ($c) {
            return new CategoryModel;
        };
    }

    protected function loadSiteInfo()
    {
        $stieInfo = $this->loadSiteParameterService->initSite();
        $this->set_lang_id($stieInfo->getLangId());
        $this->setSiteInfo($stieInfo);
    }


/*
    function initialize($params = array())
    {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }
    }

    private function _get_platform()
    {
        $platform_id = $this->_get_platform_by_ip();
        include_once(APPPATH . "libraries/service/Platform_biz_var_service.php");
        $pbv_srv = new Platform_biz_var_service();
        $pbv_obj = $pbv_srv->get(array("selling_platform_id" => $platform_id));
        include_once(APPPATH . "libraries/service/Currency_service.php");
        $currency_srv = new Currency_service();
        DEFINE('PLATFORMID', $platform_id);
        DEFINE('PLATFORMCURR', @call_user_func(array($pbv_obj, "get_platform_currency_id")));
        DEFINE('PLATFORMCOUNTRYID', @call_user_func(array($pbv_obj, "get_platform_country_id")));
        $currency_obj = $currency_srv->get(array("id" => PLATFORMCURR));
        DEFINE('PLATFORMCURRSIGN', @call_user_func(array($currency_obj, "get_sign")));

        $this->check_cart();
        if ($this->get_currency_list) {
            $currsign = $currency_srv->get_sign_w_id_key();
            // $this->add_preload_data(array("currsign"=>$currsign));
        }

        DEFINE('SITE_URL', $_SERVER['HTTP_HOST']);
        DEFINE('SITE_NAME', str_replace("dev.valuebasket", "ValueBasket", str_replace("www.valuebasket", "ValueBasket", $_SERVER['HTTP_HOST'])));
    }

    private function _get_platform_by_ip()
    {
        return $_SESSION["domain_platform"]["platform_id"];
    }

    private function check_cart()
    {
        $str = "";
        $str_header = "<script src='/js/common.js' type='text/javascript'></script>";
        include_once(APPPATH . "helpers/string_helper.php");
        $domain = check_domain();

        if (isset($_COOKIE["chk_cart"]) && $_COOKIE["chk_cart"]) {
            $cart_list = unserialize(base64_decode($_COOKIE["chk_cart"]));
            include_once(APPPATH . "libraries/service/Cart_session_service.php");
            $cart_srv = new Cart_session_service();
            if (!is_array($cart_list)) {
                $cart_list = array();
            }
            $rs = $cart_srv->check_cart($cart_list, PLATFORMID, $this->get_lang_id(), isset($_COOKIE["renew_cart"]) && $_COOKIE["renew_cart"]);

            if (isset($rs["cart"])) {
                $_SESSION["cart"][PLATFORMID] = $rs["cart"];
                if ((isset($_COOKIE["renew_cart"]) && $_COOKIE["renew_cart"])) {
                    setcookie("renew_cart", "", time() - 86400, "/", "." . $domain);
                }
                setcookie("chk_cart", "", time() - 86400, "/", "." . $domain);
            } else {
                if (isset($_COOKIE["back_url"]) && $_COOKIE["back_url"]) {
                    $back_url = urldecode($_COOKIE["back_url"]);
                    $prod_name_str = str_replace("'", "\'", implode(", ", $rs["remove"]));
                    $con_str = "{$prod_name_str} is not available for sale in " . PLATFORMCOUNTRYID . " and will be removed from your cart. \\n Do you wish to continue?";
                    $str .= "if (confirm('{$con_str}')){setcookie('renew_cart', '1', 0, '/', '.{$domain}');{$retain_str};document.location.href='" . base_url() . "redirect?url=' + encodeURI(document.location.href);}else{setcookie('chk_cart', '', -1, '/', '.{$domain}'); document.location.href='{$back_url}';}";
                }
            }
        }

        // if (empty($str) && $retain_str) {
        // 	$str = $retain_str;
        // }

        if ($str) {
            echo $str_header;
            echo "<script>" . $str . "</script>";
            exit;
        }
    }
*/
    public function setSiteInfo($siteInfo)
    {
        $this->siteInfo = $siteInfo;
    }

    public function getSiteInfo()
    {
        return $this->siteInfo;
    }

    public function set_lang_id($langId)
    {
        $this->lang_id = $langId;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }
/*
    protected function is_allow_referer($url)
    {
        return preg_match($this->allow_referer_host, $url);
    }
*/
    public function check_login($back = "")
    {
        $login_url = "login";

        if (!$_SESSION["client"]["logged_in"]) {
            redirect(base_url() . $login_url . "?back=" . urlencode(ltrim($back ? $back : $_SESSION["CURRPAGE"], "/")));
        } else {
            return TRUE;
        }
    }

    public function parameter_checking($result)
    {
        if (!$result) {
            show_404("page");
        }
    }

    // public function load_template($view, $vars = array(), $template = 'tbs', $return = FALSE)
    // {
    // 	if ($template == 'tbs') {
    // 		init_tbs();
    // 	}

    // 	$data = $this->get_preload_data();

    // 	if ($vars && is_array($vars)) {
    // 		$data['data'] = $vars;
    // 	}
    // 	$this->load->view($view, $data, $return);
    // }


    public function getLanguageFile($directory = "", $i_class = "", $method = "")
    {
        return $this->load_template_language($this->load_view_language($directory, $i_class, $method));
    }

    public function get_language_file($directory = "", $i_class = "", $method = "")
    {
        return $this->getLanguageFile($directory, $i_class, $method);
    }

    protected function load_template_language($viewLanguageData = array())
    {
        //load template language file
        $template_name_without_lang = $this->template->template["template"];
        $template_and_lang = explode('_', $template_name_without_lang);

        if ($template_and_lang[sizeof($template_and_lang) - 1] == $_SESSION["lang_id"]) {
            $template_name_without_lang = substr($template_name_without_lang, 0, strlen($template_name_without_lang) - 3);
        }
        $template_path = $_SESSION["lang_id"] . "/" . $template_name_without_lang . ".ini";
        if (file_exists(APPPATH . "/language/" . $template_path)) {
            $tempate_arr = parse_ini_file(APPPATH . "/language/" . $template_path);
            if (is_array($viewLanguageData) && (sizeof($viewLanguageData) > 0)) {
                $tempate_arr = array_merge($viewLanguageData, $tempate_arr);
            }
            return $tempate_arr;
        } else {
            return $viewLanguageData;
        }
    }

    protected function load_view_language($directory = "", $i_class = "", $method = "")
    {
        if (empty($directory)) {
            $input_directory = $this->router->directory;
        } else {
            $input_directory = $directory;
        }
        if (empty($i_class)) {
            $input_class = $this->router->class;
        } else {
            $input_class = $i_class;
        }
        if (empty($method)) {
            $input_method = $this->router->method;
        } else {
            $input_method = $method;
        }

        //load page language file
        $language_path = $_SESSION["lang_id"] . "/" . $input_directory . $input_class . "/" . $input_method . ".ini";
        if (file_exists(APPPATH . "/language/" . $language_path)) {
            $data_arr = parse_ini_file(APPPATH . "/language/" . $language_path);
        }
        //load template language file
        $template_name_without_lang = $this->template->template["template"];
        $template_and_lang = explode('_', $template_name_without_lang);

        if ($template_and_lang[sizeof($template_and_lang) - 1] == $_SESSION["lang_id"]) {
            $template_name_without_lang = substr($template_name_without_lang, 0, strlen($template_name_without_lang) - 3);
        }
        $template_path = $_SESSION["lang_id"] . "/" . $template_name_without_lang . ".ini";
        if (file_exists(APPPATH . "/language/" . $template_path)) {
            $tempate_arr = parse_ini_file(APPPATH . "/language/" . $template_path);
            if (is_array($data_arr)) {
                $data_arr = array_merge($data_arr, $tempate_arr);
            } else {
                $data_arr = $tempate_arr;
            }
        }
        return $data_arr;
    }

    public function load_tpl($region, $view, $vars = array(), $overwrite = FALSE, $autoload_meta = FALSE)
    {
        $data = $this->get_preload_data();

        if ($vars && is_array($vars)) {
            $data['data'] = $vars;
        }
        //load default laguage file
        if (!isset($vars['data']['lang_text'])) {
            $data['data']['lang_text'] = $this->getLanguageFile();
        } else {
            $data['data']['lang_text'] = $vars['data']['lang_text'];
        }
        if ($autoload_meta) {
            $meta_title = $data['data']['lang_text']['meta_title'];
            $meta_desc = $data['data']['lang_text']['meta_desc'];
            $meta_keyword = $data['data']['lang_text']['meta_keyword'];
            if (!empty($meta_title)) {
                $this->template->add_title($meta_title);
            }
            if (!empty($meta_desc)) {
                $this->template->add_meta(array('name' => 'description', 'content' => $meta_desc));
            }
            if (!empty($meta_keyword)) {
                $this->template->add_meta(array('name' => 'keywords', 'content' => $meta_keyword));
            }
        }
        $tracking_script = $this->auto_load_tracking($vars['tracking_data']);
        // $this->template->add_js($tracking_script, "print", false, "body");
        // $this->template->write_view($region, $view, $data, $overwrite);
        // $this->template->render();
    }

    public function get_preload_data()
    {
        // if ($this->load_header) {
        // 	include_once(APPPATH."libraries/service/Cart_session_service.php");
        // 	$cs_srv = new Cart_session_service();
        // 	include_once(APPPATH."libraries/service/Customer_service_info_service.php");
        // 	$csi_srv = new Customer_service_info_service();
        // 	include_once(APPPATH."libraries/service/Platform_biz_var_service.php");
        // 	$pbv_srv = new Platform_biz_var_service();
        // 	include_once(APPPATH."libraries/service/Country_service.php");
        // 	$country_srv = new Country_service();

        // 	$cs_phone = $csi_srv->get_short_text(PLATFORMID);
        // 	if (strpos($cs_phone, ") ") !== FALSE) {
        // 		$cs_phone_arr = explode(") ", $cs_phone);
        // 		$cs_phone = $cs_phone_arr[1];
        // 	}
        // 	// $this->add_preload_data
        // 	// (
        // 	// 	array
        // 	// 	(
        // 	// 		"cs_phone_no"=>$cs_phone,
        // 	// 		"cart_info"=>$cs_srv->get_detail(PLATFORMID),
        // 	// 		"free_delivery_limit"=>$pbv_srv->get_free_delivery_limit(PLATFORMID),
        // 	// 		"platform_list"=>$country_srv->get_all_available_country_w_correct_lang(get_lang_id()),
        // 	// 		"controller_path"=> "/" . $this->router->directory . uri_string_without_lang_country($this->router),
        // 	// 		"base_url"=>base_url(),
        // 	// 		"cdn_url"=>base_cdn_url()
        // 	// 	)
        // 	// );
        // }

        // return parent::get_preload_data();
    }

    public function auto_load_tracking($data)
    {
        $registered_tracking = $this->config->item('registered_tracking');
        $tracking_code = "";
        foreach ($registered_tracking as $tracking) {
            $className = $tracking . "_tracking_script_service";
            include_once(APPPATH . "libraries/service/" . strtolower($className) . ".php");
            $tracking_obj = new $className();
            $page = array("class" => $this->router->class,
                "method" => $this->router->method
            );
            if (count($this->router->uri->rsegments) >= 3) {
                $page = array_merge($page, array("method_parameter1" => $this->router->uri->rsegments[3]));
            }
            if ($tracking_obj->is_registered_page($page)) {
                $tracking_code .= $tracking_obj->get_specific_code($page, $data);
            } elseif ($tracking_obj->need_to_show_generic_tracking_page()) {
                $tracking_code .= $tracking_obj->get_all_page_code($page, $data);
            }
        }
        return $tracking_code;
    }

    public function load_view($view, $vars = array(), $return = FALSE, $preferred_template_lang = '')
    {
        $params = $this->get_preload_data();

        if ($vars && is_array($vars)) {
            $params = array_merge($params, $vars);
        }
        //load default laguage file
        if (!isset($vars['lang_text'])) {
            $params['lang_text'] = $this->getLanguageFile();
        } else {
            $params['lang_text'] = $vars['lang_text'];
        }
        //check if there is a specific language version in the view
        if ($preferred_template_lang == '') {
            $try_to_find_lang = strtolower($_SESSION["lang_id"]);
        } else {
            $try_to_find_lang = strtolower($preferred_template_lang);
        }

        if (strstr($view, '.')) {
            $original_file = explode('.', $view);
            $testFileNew = $original_file[0] . "_" . $try_to_find_lang . "." . $original_file[1];
        } else {
            $testFileNew = $view . "_" . $try_to_find_lang . ".php";
        }

        if (file_exists(VIEWPATH . $testFileNew)) {
            $specific_template = $testFileNew;
        } else {
            $specific_template = $view;
        }

        $this->load->view($specific_template, $params, $return);
    }

}
