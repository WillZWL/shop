<?php
namespace ESG\Panther\Dao;

class UserDao extends BaseDao
{
    private $tableName = "user";
    private $voClassName = "UserVo";

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

    public function getMenuByUserId($user_id, $app_group_id, $classname = "RoleAppDto")
    {
        $sql = "
                    select
                        distinct a.*

                    from application a
                    inner join rights r         on a.id=r.app_id and r.rights='' and a.status=1 and r.status=1
                    inner join role_rights rr   on rr.rights_id=r.id
                    inner join user_role ur     on ur.role_id=rr.role_id and ur.user_id='" . $user_id . "'
                    where
                    a.app_group_id = '" . $app_group_id . "' and a.status=1 and display_row=1 order by a.display_order;";
        $rs = array();
        if ($query = $this->db->query($sql, $user_id)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return empty($rs) ? $rs : (object)$rs;
        } else {
            return FALSE;
        }
    }

    public function isAllowedToCancelOrderByRole($user_id, $classname = "RoleAppDto")
    {
        $sql = "
                    SELECT role_id FROM user_role WHERE user_id =? AND (role_id = 'admin' OR role_id = 'com_lead' OR role_id = 'com_man' OR role_id = 'com_staff')
                ";
        if ($query = $this->db->query($sql, $user_id)) {
            foreach ($query->result($classname) as $obj) {
                if ($obj->getRoleId()) {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    public function getMenuItem($user_id = "", $classname = "")
    {
        $sql = "
                SELECT DISTINCT a.id AS app_id, a.app_name, a.parent_app_id, a.description, a.display_order
                FROM user_role ur
                RIGHT JOIN role_rights rr
                    ON (ur.role_id = rr.role_id)
                INNER JOIN (rights r, role ro)
                    ON (rr.rights_id = r.id AND rr.role_id = ro.id)
                LEFT JOIN application a
                    ON r.app_id = a.id
                LEFT JOIN user u
                    ON u.id = ur.user_id
                WHERE u.id = ?
                AND r.status = 1
                AND a.status = 1
                AND ro.status = 1
                ORDER BY display_order, app_id
                ";

        $rs = array();
        if ($query = $this->db->query($sql, $user_id)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return empty($rs) ? $rs : (object)$rs;
        } else {
            return FALSE;
        }
    }

    public function getAppRights($user_id = "", $app_id = "", $classname = "")
    {
        $sql = "
                SELECT DISTINCT r.app_id, r.id AS rights_id, r.rights
                FROM user_role ur
                RIGHT JOIN role_rights rr
                    ON (ur.role_id = rr.role_id)
                INNER JOIN (rights r, role ro)
                    ON (rr.rights_id = r.id AND rr.role_id = ro.id)
                LEFT JOIN application a
                    ON r.app_id = a.id
                LEFT JOIN application a2
                    ON a.parent_app_id = a2.id
                LEFT JOIN user u
                    ON u.id = ur.user_id
                WHERE u.id = ?
                AND (a.id = ? OR a2.id = ?)
                AND r.status = 1
                AND a.status = 1
                AND ro.status = 1
                ORDER BY rights_id
                ";

        $rs = array();
        if ($query = $this->db->query($sql, array($user_id, $app_id, $app_id))) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return empty($rs) ? $rs : (object)$rs;
        } else {
            return FALSE;
        }
    }

    public function checkAccess($user_id = "", $app_id = "", $rights = "")
    {
        $sql = "
                SELECT
                    IF(COUNT(*)>0, 1, 0) AS access
                FROM
                    user_role ur
                RIGHT JOIN
                    role_rights rr
                ON
                    (ur.role_id = rr.role_id)
                INNER JOIN
                    (rights r, role ro)
                ON
                    (rr.rights_id = r.id AND rr.role_id = ro.id)
                LEFT JOIN
                    application a
                ON
                    r.app_id = a.id
                LEFT JOIN
                    user u
                ON
                    u.id = ur.user_id
                WHERE
                    u.id = ?
                AND
                    a.id = ?";
        if ($rights == "") {
            $sql .= " AND r.rights = ''";
            $binding = array($user_id, $app_id);
        } else {
            $sql .= " AND r.rights = ?";
            $binding = array($user_id, $app_id, $rights);
        }
        $sql .= " AND r.status = 1
                AND a.status = 1
                AND ro.status = 1
                ORDER BY rights_id
                ";

        if ($query = $this->db->query($sql, $binding)) {
            return $query->row()->access;
        } else {
            return FALSE;
        }
    }

    public function getListWRoles($where = array(), $option = array(), $classname = "")
    {

        $this->db->from('user AS u');
        $this->db->join('(
                    SELECT us.id, GROUP_CONCAT(r.role_name ORDER BY role_name SEPARATOR ", ") AS roles
                    FROM user_role ur
                    JOIN (user us, role r)
                        ON (r.id = ur.role_id AND us.id = ur.user_id)
                    GROUP BY us.id
                ) AS rn', 'u.id = rn.id', 'LEFT');

        if (empty($option["orderby"])) {
            $option["orderby"] = "u.id ASC";
        }

        if (empty($option["num_rows"])) {

            $this->db->select('u.id, u.username, u.email, u.status, rn.roles, u.create_on, u.create_at, u.create_by, u.modify_on, u.modify_at, u.modify_by');

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($query = $this->db->get_where('', $where, $option["limit"], $option["offset"])) {
                $classname = ($classname) ? : $this->getVoClassname();
                $rs = [];
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }

                return $rs;
            }
        } else {
            if ($where !== NULL)
            {
                $this->db->where($where);
            }
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

}


