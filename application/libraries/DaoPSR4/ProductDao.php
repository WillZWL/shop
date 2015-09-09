<?php
namespace AtomV2\Dao;

class ProductDao extends BaseDao
{
    private $table_name = 'product';
    private $vo_class_name = 'ProductVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getHomeProduct($where = [], $option = [], $class_name = 'SimpleProductDto')
    {
        $where['pd.status'] = 2;
        $where['pr.listing_status'] = 'L';
        $where['pd.website_status <>'] = 'O';

        $this->db->from('landpage_listing ll');
        $this->db->join('product pd', 'pd.sku = ll.selection', 'inner');
        $this->db->join('price pr', 'pr.platform_id = ll.platform_id', 'inner');
        $this->db->where($where);
        $this->db->where(['pr.sku = ll.selection' => null]);
        $this->db->group_by('ll.selection');
        $this->db->order_by("ll.mode = 'M' DESC, ll.rank");

        return $this->commonGetList($class_name, [], $option, 'pd.sku');
    }

    public function getProductOverview($where = [], $option = [], $class_name = "ProductOverviewDto")
    {
        $option = ['limit' => 1];
        $this->db->from('v_prod_items AS vpi');
        $this->db->join('product AS p', 'vpi.prod_sku = p.sku', 'INNER');
        $this->db->join('v_prod_overview_wo_cost AS vpo', 'vpi.item_sku = vpo.sku', 'INNER');

        return $this->commonGetList($class_name, $where, $option, 'vpo.*, p.expected_delivery_date, p.warranty_in_month');
    }

    public function getListedProductList($platform_id = 'WEBGB', $classname = 'WebsiteProdInfoDto')
    {
        $sql = "SELECT * FROM v_prod_overview_wo_shiptype vpo
                WHERE vpo.platform_id = ?
                    AND vpo.listing_status = 'L'";

        $result = $this->db->query($sql, array('platform_id' => $platform_id));

        $result_arr = [];

        if ($result) {
            foreach ($result->result($classname) as $obj) {
                $result_arr[] = $obj;
            }
        }

        return $result_arr;
    }

    public function getProductWPriceInfo($platform_id = 'WEBGB', $sku = "", $classname = 'WebsiteProdInfoDto')
    {

        $sql = "SELECT * FROM v_prod_overview_wo_shiptype vpo WHERE vpo.platform_id = ? AND sku = ?";
        $result = $this->db->query($sql, array($platform_id, $sku));

        $result_arr = [];

        if ($result->num_rows() > 0) {
            foreach ($result->result($classname) as $obj) {
                $result_arr[$obj->get_sku()] = $obj;
            }
        }

        return $result_arr;
    }


    public function getProductWMarginReqUpdate($where = [], $classname = 'WebsiteProdInfoDto')
    {
        $table_alias = [
                'v_prod_overview_w_update_time' => 'vpo',
                'supplier_cost_history' => 'sch', 'price_margin' => 'pm'
            ];
        include_once APPPATH . "helpers/string_helper.php";
        $new_where = replace_db_alias($where, $table_alias);

        if (!isset($new_where['vpo.platform_id'])) {
            $new_where['vpo.platform_id'] = 'WSGB';
        }

        $new_key_list = array_keys($new_where);

        if ($new_key_list && count($new_key_list) > 0) {
            $found = false;

            foreach ($new_key_list as $new_key) {
                if (strstr($new_key, 'vpo.prod_status')) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $new_where['vpo.prod_status >'] = 0;
            }
        }

        $value_list = [];

        if ($new_where && count($new_where) > 0) {
            $where_clause = '';

            foreach ($new_where as $key => $value) {
                $where_clause .= ' AND ';

                if ($this->db->_has_operator($key)) {
                    $where_clause .= "$key ?";
                } else {
                    $where_clause .= "$key = ?";
                }
                array_push($value_list, $value);
                $counter++;
            }
        }


        $sql = "SELECT DISTINCT vpo.*
                FROM v_prod_overview_w_update_time vpo
                INNER JOIN supplier_cost_history sch
                    ON (sch.prod_sku = vpo.sku)
                LEFT JOIN price_margin pm
                    ON (pm.sku = vpo.sku AND pm.platform_id = vpo.platform_id)
                WHERE
                    (vpo.price_modify_on > pm.modify_on OR sch.create_on > pm.modify_on)
                    $where_clause";

        $result = $this->db->query($sql, $value_list);

        $result_arr = [];

        foreach ($result->result($classname) as $obj) {
            $result_arr[] = $obj;
        }

        return $result_arr;
    }

}
