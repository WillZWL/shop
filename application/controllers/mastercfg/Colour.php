<?php

class Colour extends MY_Controller
{
    private $appId = 'MST0010';

    public function getAppId()
    {
        return $this->appId;
    }

    public function add()
    {
        if ($this->input->post('translate') !== null) {
            $sourceName = ucfirst(strtolower($this->input->post('colour_name')));
            $data['langList'] = $this->sc['Language']->getDao('Language')->getList(['status' => 1], ['orderby' => 'lang_id ASC']);

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
                $errorMsg = __LINE__.'Could not translate. $sourceName and $toLang cannot be empty.';
            }
        } elseif ($this->input->post('add')) {
            $result = $this->sc['Colour']->save($this->input->post());
            redirect('/mastercfg/colour');
        }
    }

    public function edit()
    {
        if ($this->input->post('action') == 'edit') {
            $obj = $this->sc['Colour']->getDao('Colour')->get(['id' => $this->input->post('id')]);
            $obj->setColourName($this->input->post('name'));
            $obj->setStatus($this->input->post('status'));
            $ret = $this->sc['Colour']->getDao('Colour')->update($obj);
            $name_translate = $this->input->post('name_translate');
            $error_msg = '';

            foreach ($name_translate as $lang_id => $value) {
                $colour_ext_obj = $this->sc['Colour']->getDao('ColourExtend')->get(array('colour_id' => $obj->getColourId(), 'lang_id' => $lang_id));
                $colour_ext_obj->setColourName(ucfirst(strtolower($value)));

                $ret_translate = $this->sc['Colour']->getDao('ColourExtend')->update($colour_ext_obj);
                if ($ret_translate === false) {
                    $error_msg .= "\r\nTranslated name <$value> cannot be updated for language <$lang_id>.
                                    DB error_msg: {$colour_ext_dao->db->_error_message()}";
                }
            }
        }
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }

    public function search()
    {
        $where = array();
        $option = array();

        $_SESSION['MC_QUERY'] = base_url().'mastercfg/colour/?'.$_SERVER['QUERY_STRING'];

        if ($this->input->get('id') != '') {
            $where['id LIKE'] = '%'.$this->input->get('id').'%';
        }

        if ($this->input->get('name') != '') {
            $where['name LIKE'] = '%'.$this->input->get('name').'%';
        }

        if ($this->input->get('status') != '') {
            $where['status'] = $this->input->get('status');
        }

        $sort = $this->input->get('sort');
        $order = $this->input->get('order');

        $limit = '20';

        $pconfig['base_url'] = $_SESSION['LISTPAGE'];
        $option['limit'] = $pconfig['per_page'] = $limit;
        if ($option['limit']) {
            $option['offset'] = $this->input->get('per_page');
        }

        if (empty($sort)) {
            $sort = 'id';
        }

        if (empty($order)) {
            $order = 'asc';
        }

        $option['orderby'] = $sort.' '.$order;
    }

    public function index()
    {
        $subAppId = $this->getAppId().'00';
        include_once APPPATH.'language/'.$subAppId.'_'.$this->getLangId().'.php';
        $data['lang'] = $lang;

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        $data['colourList'] = $this->sc['Colour']->getDao('Colour')->getList([], ['limit' => $option['limit'], 'offset' => $option['offset']]);
        $data['langList'] = $this->sc['Language']->getDao('Language')->getList(['status' => 1], ['orderby' => 'lang_id ASC']);
        $data["total"] = $this->sc['Colour']->getDao('Colour')->getNumRows();

        $editColourId = $this->input->get('edit');
        $data['colourWithLang'] = $this->sc['Colour']->getDao('Colour')->getListWithLang(['c.colour_id' => $editColourId], ['limit' => -1]);

        $config['base_url'] = base_url('mastercfg/colour/index');
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $data['per_page']  =  $this->input->get('per_page') ;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $this->load->view('mastercfg/colour/colour_index_v', $data);
    }

    private function translateColourName($sourceName = '', $fromLang = 'en', $toLang)
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

    public function set_translate_service($serv)
    {
        $this->translate_service = $serv;
    }
}
