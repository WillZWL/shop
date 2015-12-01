<?php
class RaProdCatVo extends \BaseVo
{
    private $id;
    private $ss_cat_id;
    private $rcm_ss_cat_id_1 = '0';
    private $rcm_ss_cat_id_2 = '0';
    private $rcm_ss_cat_id_3 = '0';
    private $rcm_ss_cat_id_4 = '0';
    private $rcm_ss_cat_id_5 = '0';
    private $rcm_ss_cat_id_6 = '0';
    private $rcm_ss_cat_id_7 = '0';
    private $rcm_ss_cat_id_8 = '0';
    private $warranty_cat = '0';
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSsCatId($ss_cat_id)
    {
        if ($ss_cat_id !== null) {
            $this->ss_cat_id = $ss_cat_id;
        }
    }

    public function getSsCatId()
    {
        return $this->ss_cat_id;
    }

    public function setRcmSsCatId1($rcm_ss_cat_id_1)
    {
        if ($rcm_ss_cat_id_1 !== null) {
            $this->rcm_ss_cat_id_1 = $rcm_ss_cat_id_1;
        }
    }

    public function getRcmSsCatId1()
    {
        return $this->rcm_ss_cat_id_1;
    }

    public function setRcmSsCatId2($rcm_ss_cat_id_2)
    {
        if ($rcm_ss_cat_id_2 !== null) {
            $this->rcm_ss_cat_id_2 = $rcm_ss_cat_id_2;
        }
    }

    public function getRcmSsCatId2()
    {
        return $this->rcm_ss_cat_id_2;
    }

    public function setRcmSsCatId3($rcm_ss_cat_id_3)
    {
        if ($rcm_ss_cat_id_3 !== null) {
            $this->rcm_ss_cat_id_3 = $rcm_ss_cat_id_3;
        }
    }

    public function getRcmSsCatId3()
    {
        return $this->rcm_ss_cat_id_3;
    }

    public function setRcmSsCatId4($rcm_ss_cat_id_4)
    {
        if ($rcm_ss_cat_id_4 !== null) {
            $this->rcm_ss_cat_id_4 = $rcm_ss_cat_id_4;
        }
    }

    public function getRcmSsCatId4()
    {
        return $this->rcm_ss_cat_id_4;
    }

    public function setRcmSsCatId5($rcm_ss_cat_id_5)
    {
        if ($rcm_ss_cat_id_5 !== null) {
            $this->rcm_ss_cat_id_5 = $rcm_ss_cat_id_5;
        }
    }

    public function getRcmSsCatId5()
    {
        return $this->rcm_ss_cat_id_5;
    }

    public function setRcmSsCatId6($rcm_ss_cat_id_6)
    {
        if ($rcm_ss_cat_id_6 !== null) {
            $this->rcm_ss_cat_id_6 = $rcm_ss_cat_id_6;
        }
    }

    public function getRcmSsCatId6()
    {
        return $this->rcm_ss_cat_id_6;
    }

    public function setRcmSsCatId7($rcm_ss_cat_id_7)
    {
        if ($rcm_ss_cat_id_7 !== null) {
            $this->rcm_ss_cat_id_7 = $rcm_ss_cat_id_7;
        }
    }

    public function getRcmSsCatId7()
    {
        return $this->rcm_ss_cat_id_7;
    }

    public function setRcmSsCatId8($rcm_ss_cat_id_8)
    {
        if ($rcm_ss_cat_id_8 !== null) {
            $this->rcm_ss_cat_id_8 = $rcm_ss_cat_id_8;
        }
    }

    public function getRcmSsCatId8()
    {
        return $this->rcm_ss_cat_id_8;
    }

    public function setWarrantyCat($warranty_cat)
    {
        if ($warranty_cat !== null) {
            $this->warranty_cat = $warranty_cat;
        }
    }

    public function getWarrantyCat()
    {
        return $this->warranty_cat;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
            $this->modify_by = $modify_by;
        }
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
