<?php
namespace ESG\Panther\Dao;

class ProductNoteDao extends BaseDao
{
    private $table_name = 'product_note';
    private $vo_class_name = 'ProductNoteVo';

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

    public function getNoteWithAuthorName($platform = "", $sku, $type, $classname = "ProductNoteUserDto")
    {
        $sql = "SELECT *
                FROM
                (
                    SELECT n.*, u.username
                    FROM product_note n
                    JOIN user u
                        ON u.id = n.create_by
                    WHERE sku = ?
                    AND type = ?
                ";
        $where = [$sku, $type];

        if ($platform != "") {
            $sql .= "   AND platform_id = ?";
            $where[] = $platform;
        }

        $sql .= "   ORDER BY create_on DESC
                    LIMIT 5
                ) n
                ORDER BY create_on ASC
                ";
        $rs = [];

        if ($query = $this->db->query($sql, $where)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            return FALSE;
        }
    }
}

