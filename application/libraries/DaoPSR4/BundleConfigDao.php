<?php

namespace ESG\Panther\Dao;

class BundleConfigDao extends BaseDao
{
    private $table_name = 'bundle_config';
    private $vo_class_name = 'BundleConfigVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getBundleConfig($where = [], $option = [], $classname = 'BundleConfigDto')
    {
        $this->db->from('bundle_config AS bc');

        if (empty($option['orderby'])) {
            $option['orderby'] = 'bc.country_id ASC';
        }

        if (empty($option['num_rows'])) {
            $this->db->select('bc.*');

            if (isset($option['orderby'])) {
                $this->db->order_by($option['orderby']);
            }

            if (empty($option['limit'])) {
                $option['limit'] = $this->rows_limit;
            } elseif ($option['limit'] == -1) {
                $option['limit'] = '';
            }

            if (!isset($option['offset'])) {
                $option['offset'] = 0;
            }

            $this->db->where($where);
            $this->db->limit($option['limit'], $option['offset']);

            if ($query = $this->db->get()) {
                $classname = ($classname) ?: $this->getVoClassname();
                $rs = [];
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }

                return $rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return false;
    }
}
