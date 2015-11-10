<?php
namespace ESG\Panther\Dao;

class ProductImageDao extends BaseDao
{
    private $table_name = 'product_image';
    private $vo_class_name = 'ProductImageVo';

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

    public function getPendingImages()
    {
        $sql = "select pi.id, pi.sku, pi.priority, pi.image, pi.alt_text, pi.image_saved, pi.vb_image,
                    (select min(pi2.priority) from product_image pi2 where pi2.sku = pi.sku ) as min_priority
                from product_image pi
                where pi.image_saved = 0 and pi.vb_image != ''
                  ";


        if ($query = $this->db->query($sql)) {
            foreach ($query->result() as $row) {
                $res[] = $row;
            }
            return $res;
        }
        return FALSE;
    }
}
