<?php
namespace ESG\Panther\Service;

use ESG\Panther\Models\Marketing\CategoryModel;

class ProductSearchService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new CategoryModel;
    }

    public function getProductSearchList($where, $option)
    {
        if ($where['keyword']) {
            $res = [];
            if ($option['split_keyword']) {
                $where['skey'] = $this->formatSearchKey($where['keyword'], ".?");
                $resr_key = $option["num_rows"] ? "total" : "objlist";
                $res[ $resr_key] = $this->getDao('Product')->searchByProductName($where, $option);
                $res['skey'] = $where['skey'];
            } else {
                $res = $this->getDao('Product')->searchByProductName($where, $option);
            }

            return $res;
        }
    }

    public function formatSearchKey($skey = "", $replace = " ")
    {
        /*
         *  1.  Pre-process the search key to remove unnecessary character.
         *  2.  Insert a whitespace as default inbetween number and alphabet or any other characters as specified in the 2nd parameter
         *
         */
        $uf_arr = explode(" ", $skey);

        foreach ($uf_arr as $k => $v) {
            if ($v != "") {
                $v = trim(preg_replace('/[.,`\[\]\(\)\"\';\/\\\\?*\+]/', "$replace", $v));
                $v = trim(preg_replace('/([0-9]{1,})([a-zA-Z]{1,})/', "\\1$replace\\2", $v));
                $v = trim(preg_replace('/([a-zA-Z]{1,})([0-9]{1,})/', "\\1$replace\\2", $v));
                if ((trim(str_replace('.?', '', $v))) != "") {
                    $f_arr[] = $v;
                }
            } else {
                unset($uf_arr[$k]);
            }
        }
        $sk['unformated'] = $uf_arr;
        $sk['formated'] = $f_arr;

        return $sk;
    }

}