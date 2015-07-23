<?php

class Faqadmin_service extends Base_service
{
    private $dao;

    public function __construct()
    {
        parent::__construct();
        include_once APPPATH . "libraries/dao/Faqadmin_dao.php";
        $this->set_dao(new Faqadmin_dao());
    }

    public function get_list_cnt($where = array(), $option = array())
    {
        return $this->get_dao()->get_list_cnt($where, $option);
    }

    public function get_dao()
    {
        return $this->dao;
    }

    public function set_dao(Base_dao $dao)
    {
        $this->dao = $dao;
    }

    public function update(Base_vo $vo)
    {
        return $this->get_dao()->update($vo);
    }

    public function insert(Base_vo $vo)
    {
        return $this->get_dao()->insert($vo);
    }

    public function get_content($platform_id = "WSGB")
    {
        include_once APPPATH . "libraries/service/Platform_biz_var_service.php";
        $pbv_svc = new Platform_biz_var_service();

        $pbv_obj = $pbv_svc->get_dao()->get(array("selling_platform_id" => $platform_id));
        if (!$pbv_obj) {
            $lang_id = 'en';
        } else {
            $lang_id = $pbv_obj->get_language_id();
        }
        unset($pbv_svc);
        unset($pbv_obj);

        $faq_obj = $this->get(array("lang_id" => $lang_id));
        if (!$faq_obj) {
            $faq_ver = "cveng";
        } else {
            $faq_ver = $faq_obj->get_faq_ver();
        }

        $hash_str = array("cveng" => "a922f5e654fb5bcbdc18cdfb30b9a6ef", "cv-fr" => "7710e562acfc94706c5ed32776a749bb", "cv-de" => "175508585e967bcb4ad502426032b4f2", "cv-es" => "4381adf386419dc4d7f2d836a03f59e1");
        $domain = array("cveng" => "http://cveng.host4kb.com/", "cv-fr" => "http://cv-fr.host4kb.com/", "cv-de" => "http://cv-de.host4kb.com/", "cv-es" => "http://cv-es.host4kb.com/");

        if (get_magic_quotes_gpc()) {
            function stripslashes_deep($value)
            {
                $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
                return $value;
            }

            $_POST = array_map('stripslashes_deep', $_POST);
            $_GET = array_map('stripslashes_deep', $_GET);
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
            $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
        }

        $post_data = array('gatewayAddr' => "http://" . $_SERVER["HTTP_HOST"] . "/faq/");
        $action = (empty($_GET['action']) ? "index" : $_GET['action']);
        $getData = (empty($_GET['data']) ? "" : $_GET['data']);

        $url = $domain[$faq_ver] . "admin/index.php?/gateway_main/$action/3/" . $hash_str[$faq_ver] . "/$getData";
        $cookies = "";
        if (!empty($_COOKIE)) {
            foreach ($_COOKIE as $k => $cook) {
                $cookies .= "$k=$cook;";
            }
        }

        $ch = curl_init($url);
        if (is_resource($ch)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
            curl_setopt($ch, CURLOPT_NOBODY, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => base64_encode(serialize(array_merge((array)$post_data, @$_POST)))));
            curl_setopt($ch, CURLOPT_COOKIE, $cookies);
            $kmp_result = curl_exec($ch);
            curl_close($ch);
        } else {
            curl_close($ch);
        }
        $kmp_result = explode("<!--__KMP_content_separator__-->", $kmp_result);
        if (!empty($kmp_result[0])) {
            $kmp_result[0] = explode("\n", $kmp_result[0]);
            foreach ($kmp_result[0] as $res) {
                if (!empty($res)) {
                    if ((strpos($res, "Set-Cookie:") === 0) || (strpos($res, "Location:") === 0) || (strpos($res, "Content-Type:") === 0)) {
                        header($res);
                        if (strpos($res, "image/jpeg")) {
                            echo $kmp_result[2];
                            exit();
                        }
                    }

                }
            }
        }
        if (!empty($kmp_result[1])) {
            $kmp_arr = explode("\n", $kmp_result[1]);
            $kmp_meta_data = array();
            foreach ($kmp_arr as $kmp_arr_row) {
                $kmp_tmp_key = substr($kmp_arr_row, 0, 9);
                $kmp_tmp_value = substr($kmp_arr_row, 10);
                $kmp_meta_data[$kmp_tmp_key] = $kmp_tmp_value;
            }
            $kmp_title = @$kmp_meta_data['art_title'];
        }

        return array("title" => @$kmp_title, "content" => @$kmp_result[2], "meta_keyword" => @$kmp_meta_data['meta_kwds'], "meta_desc" => @$kmp_meta_data['meta_dscr']);
    }

    public function get($where = array())
    {
        if (empty($where)) {
            return $this->get_dao()->get();
        } else {
            return $this->get_dao()->get($where);
        }
    }
}

?>