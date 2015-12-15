<?php

class Country extends MY_Controller
{
    private $langId = 'en';
    private $appId = 'MST0012';

    public function getAppId()
    {
        return $this->appId;
    }

    public function index()
    {
        $sub_id = $this->getAppId().'01_'.$this->getLangId();

        $_SESSION['clist_page'] = base_url().'mastercfg/country/?'.$_SERVER['QUERY_STRING'];

        $where = $option = [];
        if ($this->input->get('id') != '') {
            $where['country_id LIKE'] = '%'.$this->input->get('id').'%';
        }

        if ($this->input->get('id_3_digit') != '') {
            $where['id_3_digit LIKE'] = '%'.$this->input->get('id_3_digit').'%';
        }

        if ($this->input->get('name') != '') {
            $where['name LIKE'] = '%'.$this->input->get('name').'%';
        }

        if ($this->input->get('status') != '') {
            $where['status'] = $this->input->get('status');
        }

        if ($this->input->get('currency_id') != '') {
            $where['currency_id'] = $this->input->get('currency_id');
        }

        if ($this->input->get('language_id') != '') {
            $where['language_id'] = $this->input->get('language_id');
        }

        if ($this->input->get('fc_id') != '') {
            $where['fc_id'] = $this->input->get('fc_id');
        }

        // if ($this->input->get('rma_fc') != '') {
        //     $where['rma_fc'] = $this->input->get('rma_fc');
        // }

        if ($this->input->get('allow_sell')) {
            $where['allow_sell'] = $this->input->get('allow_sell');
        }

        $sort = $this->input->get('sort');
        $order = $this->input->get('order');

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        if (empty($sort)) {
            $sort = 'status';
        }

        if (empty($order)) {
            $order = 'desc';
        }

        $option['orderby'] = $sort.' '.$order;

        $clist = $this->sc['Country']->getDao('Country')->getList($where, $option);
        $data["total"] = $this->sc['Country']->getDao('Country')->getListWRmaFc($where, ['num_rows' => 1]);

        $config['base_url'] = base_url('mastercfg/country/index');
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data['ar_lang'] = $this->sc['languageModel']->getNameWIdKey();
        $data['ar_currency'] = $this->sc['Currency']->getNameWithIdKey();

        include_once APPPATH.'language/'.$sub_id.'.php';
        $data['lang'] = $lang;
        $data['clist'] = $clist;
        $data['notice'] = notice($lang);
        $data['sortimg'][$sort] = "<img src='".base_url().'images/'.$order.".gif'>";
        $data['xsort'][$sort] = $order == 'asc' ? 'desc' : 'asc';
        $data['searchdisplay'] = '';
        $this->load->view('mastercfg/country/v_index', $data);
    }

    public function view($country = '')
    {
        $sub_id = $this->getAppId().'02_'.$this->getLangId();

        if ($this->input->post('posted')) {
            $cobj = $this->sc['Country']->getDao('Country')->get(['country_id' => $country]);
            $cobj->setId3Digit($this->input->post('id_3_digit'));
            $cobj->setStatus($this->input->post('status'));
            $cobj->setCurrencyId($this->input->post('currency_id'));
            $cobj->setLanguageId($this->input->post('language_id'));
            $cobj->setFcId('');
            $cobj->setAllowSell($this->input->post('allow_sell'));

            if ($this->sc['Country']->getDao('Country')->update($cobj) === false) {
                $_SESSION['NOTICE'] = __LINE__.' : '.$this->db->display_error();
            } else {
                //continue updating country name in different country
                $error = 0;
                foreach ($_POST['langname'] as $key => $name) {
                    $ceobj = $this->sc['Country']->getDao('CountryExt')->get(['lang_id' => $key, 'cid' => $country]);
                    if ($ceobj) {
                        $ceobj->setName($name);
                        $action = 'update';
                    } else {
                        $ceobj = $this->sc['Country']->getDao('CountryExt')->get();
                        $ceobj->setCid($country);
                        $ceobj->setLangId($key);
                        $ceobj->setName($name);
                        $action = 'insert';
                    }

                    if ($this->sc['Country']->getDao('CountryExt')->$action($ceobj) === false) {
                        ++$error;
                    }
                }

                if ($rma_fc_obj = $this->sc['Country']->getDao('RmaFc')->get(['cid' => $country])) {
                    $rma_fc_obj->setRmaFc($this->input->post('rma_fc'));

                    if ($this->sc['Country']->getDao('RmaFc')->update($rma_fc_obj) === false) {
                        ++$error;
                    }
                } else {
                    ++$error;
                }

                if (!$error) {
                    Redirect(base_url().'mastercfg/country/view/'.$country);
                }
            }
        }

        if ($country == '') {
            Redirect(base_url().'mastercfg/country/?'.$_SESSION['cquery_string']);
        }

        $country_vo = $this->sc['Country']->getDao('Country')->get(['country_id' => $country]);
        $lang_list = $this->sc['languageModel']->getList();
        $name = [];
        foreach ($lang_list as $lobj) {
            $tmp = $this->sc['Country']->getDao('CountryExt')->get(['cid' => $country, 'lang_id' => $lobj->getLangId()]);
            $name[$lobj->getLangId()] = $tmp ? $tmp->getName() : '';
        }

        include_once APPPATH.'language/'.$sub_id.'.php';
        $data['lang'] = $lang;
        $data['country_vo'] = $country_vo;
        $data['name'] = $name;
        $data['notice'] = notice($lang);
        $data['ar_lang'] = $this->sc['languageModel']->getNameWIdKey();
        $data['ar_currency'] = $this->sc['Currency']->getNameWithIdKey();
        $data['rmaFcVo'] = $this->sc['Country']->getDao('RmaFc')->get(['cid' => $country]);
        // $data["rmaFcVo"] = $this->sc['Country']->getDao()->get('RmaFc', );
        $this->load->view('mastercfg/country/v_view', $data);
    }
}
