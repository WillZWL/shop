<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Search extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($page = 1)
    {
        $keyword = $this->input->get('w');

        switch ($this->get_lang_id()) {
            case "fr":
                $heading = "Désolé, aucuns résultats trouvés";
                $message = "Aucuns résultats trouvés pour la recherche \"" . $keyword . "\", vous avez peut être saisi des mots incorrectement, ou êtes trop spécifique dans votre recherche.<br />
                            Merci d’essayer d’utiliser des termes plus généraux. ";
                break;
            case "es":
                $heading = "Lo sentimos, no se ha encontrado ningún resultado";
                $message = "La búsqueda no ha encontrado ningún resultado para \"" . $keyword . "\", tal vez la búsqueda contenga errores o es muy específica.<br />
                            Por favor inténtalo de nuevo utilizando un criterio menos restrictivo. ";
                break;
            case "ru":
                $heading = "К сожалению, нет ничего не найдено";
                $message = "К сожалению, мы не нашли соответствий \"" . $keyword . "\", Возможно слово пишется иначе, или Вы вводите слишком конректное описание.<br />
                            Пожалуйста, попробуйте ввести альтернативный запрос, используя более широкое название, если возможно. ";
                break;
            case "en":
            default:
                $heading = "Sorry, No Results Were Found";
                $message = "Search was unable to find any results for \"" . $keyword . "\",you may have typed your word incorrectly, or are being too specific.<br />
                            Please try using a broader search phrase. ";
        }

        $sort = $this->input->get('sort');
        $order = $this->input->get("order");

        $limit = $this->input->get('limit') ? $this->input->get('limit') : 12;

        $where['keyword'] = $data['keyword'] = trim($keyword);
        $where['lang_id'] = $this->get_lang_id();
        $where['platform_id'] = $this->getSiteInfo()->getPlatform();

        if ($this->input->get('bid')) {
            $where['brand_id'] = $this->input->get('bid');
        }

        if (empty($sort)) {
            $sort = "name";
        }

        if (empty($order)) {
            $order = "desc";
        }

        $option["orderby"] = $sort . " " . $order;
        $option['split_keyword'] = false;

        $where['limit'] = $limit;
        $where['offset'] = $limit * ($page - 1);
        $search_list = $this->sc['ProductSearch']->getProductSearchList($where, $option);

        if (empty($search_list)) {

            $option['split_keyword'] = true;
            $res = $this->sc['ProductSearch']->getProductSearchList($where, $option);
            $search_list = $res['objlist'];
            $data['skey'] = implode($res['skey']['unformated'], ',');


            $option["num_rows"] = 1;
            $rt = $this->sc['ProductSearch']->getProductSearchList($where, $option);
            $total = $rt['total'];
        } else {
            $option["num_rows"] = 1;
            $total = $this->sc['ProductSearch']->getProductSearchList($where, $option);
        }

        if ($search_list) {

            $obj_list = $this->sc['Price']->getListingInfoList($search_list, $this->getSiteInfo()->getPlatform(), $this->get_lang_id());
        }

        $data['brand_result'] = $this->getBrandFilterGridInfo($search_list);
        $data['productList'] = $obj_list;

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['pagination'] = 3;
        $data['limit'] = $limit;
        $data['keyword'] = $keyword;
        $data['total_result'] = $total;
        $data['curr_page'] = $page;
        $data['heading'] = $heading;
        $data['message'] = $message;
        $data['total_page'] = (int)ceil($total / $limit);

        $this->load->view('search', $data);
    }

    public function getBrandFilterGridInfo($search_list)
    {
        foreach ($search_list as $search_obj) {
            $sku_list[] = $search_obj->getSku();
        }

        if (empty($sku_list)) {
            return false;
        }

        $condition = "p.sku IN ('" . implode("','", $sku_list) . "')";
        $where[$condition] = null;
        $where['p.status'] = 2;
        $option['groupby'] = "p.brand_id";
        $option['orderby'] = "br.brand_name";

        return $this->sc['categoryModel']->getBrandFilterGridInfo($where, $option);
    }



    public function searchBySs()
    {
        $this->sc['Affiliate']->addAfCookie($_GET);

        $data = array();

        $this->load->view('searchspring_result', $data);
        //$this->load_tpl('content', 'tbs_searchspring_result', $data, TRUE);
    }
}
