<?php
class Colour extends MY_Controller
{
    private $appId = "MST0010";

    public function __construct()
    {
        parent::__construct();
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function set_translate_service($serv)
    {
        $this->translate_service = $serv;
    }

    public function add()
    {
        if ($this->input->post('translate') !== null) {
            $sourceName = ucfirst(strtolower($this->input->post('colour_name')));
            $data['langList'] = $this->container['languageService']->getList(['status' => 1], ['orderby' => 'lang_id ASC']);

            $translated = $this->translateColourName($sourceName, 'en', $data['langList']);
            if ($translated) {
                foreach ($translated as $langId => $value) {
                    $data['translate'][$langId] = $value['text'];
                    $errorMsg .= $value['error'];
                }

                if ($errorMsg) {
                    echo "<script type=\"text/javascript\">alert('$errorMsg');</script>";
                }
            } else {
                $errorMsg = __LINE__ . 'Could not translate. $sourceName and $toLang cannot be empty.';
            }
        } elseif ($this->input->post('add')) {
            $result = $this->container['colourModel']->save($this->input->post());
            redirect('/mastercfg/colour');
        }
    }

    public function edit()
    {

        if ($this->input->post('posted')) {


            if ($this->input->post('action') == "edit") {
                $obj = $this->colour_model->get($this->input->post('id'));
                $obj->set_name($this->input->post("name"));
                $obj->set_status($this->input->post("status"));
                $ret = $this->colour_model->update($obj);

                $name_translate = $this->input->post("name_translate");
                $error_msg = "";

                foreach ($name_translate as $lang_id => $value) {
                    $colour_ext_obj = $colour_ext_dao->get(array("colour_id" => $this->input->post('id'), "lang_id" => $lang_id));
                    $colour_ext_obj->set_name(ucfirst(strtolower($value)));

                    $ret_translate = $colour_ext_dao->update($colour_ext_obj);
                    if ($ret_translate === FALSE) {
                        $error_msg .= "\r\nTranslated name <$value> cannot be updated for language <$lang_id>.
                                        DB error_msg: {$colour_ext_dao->db->_error_message()}";
                    }
                }
            }

        }
    }

    public function search()
    {
        $where = array();
        $option = array();

        $_SESSION["MC_QUERY"] = base_url() . "mastercfg/colour/?" . $_SERVER["QUERY_STRING"];

        if ($this->input->get("id") != "") {
            $where["id LIKE"] = '%' . $this->input->get("id") . '%';
        }

        if ($this->input->get("name") != "") {
            $where["name LIKE"] = '%' . $this->input->get('name') . '%';
        }

        if ($this->input->get("status") != "") {
            $where["status"] = $this->input->get("status");
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;
    }

    public function index($offset = 0)
    {
        $subAppId = $this->getAppId() . '00';
        include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $limit = 20;

        $data['colourList'] = $this->container['colourModel']->getList([], ['limit' => $limit, 'offset' => $offset]);
        $data['langList'] = $this->container['languageService']->getList(['status' => 1], ['orderby' => 'lang_id ASC']);
        $total = $this->container['colourModel']->getNumRows();

        $editColourId = $this->input->get('edit');
        $data['colourWithLang'] = $this->container['colourModel']->getListWithLang(['c.colour_id' => $editColourId], ['limit' => -1]);

        $config['base_url'] = base_url('mastercfg/colour/index');
        $config['total_rows'] = $total;
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $this->load->view('mastercfg/colour/colour_index_v', $data);
    }

    private function translateColourName($sourceName = "", $fromLang = "en", $toLang)
    {
        $translated = false;
        $this->translateService = new TranslateService();

        foreach ($toLang as $langObj) {
            $langId = $langObj->getLangId();

            try {
                $this->translateService->translate(nl2br($sourceName), $newLangText, $fromLang, $langId);
            } catch (Exception $e) {
                $newLangText = '';
                $errorMsg = "Translation error on colour name <$sourceName> for language <{$langObj->getLangName()}>";
            }
            $translated[$langId]['text'] = $newLangText;
            $translated[$langId]['error'] = $errorMsg;
        }

        return $translated;
    }
}
