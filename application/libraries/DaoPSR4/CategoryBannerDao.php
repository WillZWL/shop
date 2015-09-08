<?php
namespace AtomV2\Dao;

class CategoryBannerDao extends BaseDao
{
    private $tableName = "category_banner";
    private $voClassName = "CategoryBannerVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getCatBanList($lang_id = "", $classname = "Cat_ban_list_dto")
    {
        $this->include_dto($classname);
        $sql =
            "
            SELECT cb.cat_id AS id, cb.lang_id, cb.country_id, cb.image, cb.flash, cb.priority, c.name FROM category_banner cb
            LEFT JOIN category c
                ON (c.id = cb.cat_id)
            WHERE cb.lang_id = ?
            GROUP BY cb.priority
            ORDER BY cb.priority
            ";

        if ($query = $this->db->query($sql, array($lang_id))) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            if ($rs) {
                return (object)$rs;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
}

