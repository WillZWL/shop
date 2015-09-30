<?php
class RaProdCatVo extends \BaseVo
{
    private $id;
    private $ss_cat_id;
    private $rcm_ss_cat_id_1;
    private $rcm_ss_cat_id_2;
    private $rcm_ss_cat_id_3;
    private $rcm_ss_cat_id_4;
    private $rcm_ss_cat_id_5;
    private $rcm_ss_cat_id_6;
    private $rcm_ss_cat_id_7;
    private $rcm_ss_cat_id_8;
    private $warranty_cat;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSsCatId($ss_cat_id)
    {
        $this->ss_cat_id = $ss_cat_id;
    }

    public function getSsCatId()
    {
        return $this->ss_cat_id;
    }

    public function setRcmSsCatId1($rcm_ss_cat_id_1)
    {
        $this->rcm_ss_cat_id_1 = $rcm_ss_cat_id_1;
    }

    public function getRcmSsCatId1()
    {
        return $this->rcm_ss_cat_id_1;
    }

    public function setRcmSsCatId2($rcm_ss_cat_id_2)
    {
        $this->rcm_ss_cat_id_2 = $rcm_ss_cat_id_2;
    }

    public function getRcmSsCatId2()
    {
        return $this->rcm_ss_cat_id_2;
    }

    public function setRcmSsCatId3($rcm_ss_cat_id_3)
    {
        $this->rcm_ss_cat_id_3 = $rcm_ss_cat_id_3;
    }

    public function getRcmSsCatId3()
    {
        return $this->rcm_ss_cat_id_3;
    }

    public function setRcmSsCatId4($rcm_ss_cat_id_4)
    {
        $this->rcm_ss_cat_id_4 = $rcm_ss_cat_id_4;
    }

    public function getRcmSsCatId4()
    {
        return $this->rcm_ss_cat_id_4;
    }

    public function setRcmSsCatId5($rcm_ss_cat_id_5)
    {
        $this->rcm_ss_cat_id_5 = $rcm_ss_cat_id_5;
    }

    public function getRcmSsCatId5()
    {
        return $this->rcm_ss_cat_id_5;
    }

    public function setRcmSsCatId6($rcm_ss_cat_id_6)
    {
        $this->rcm_ss_cat_id_6 = $rcm_ss_cat_id_6;
    }

    public function getRcmSsCatId6()
    {
        return $this->rcm_ss_cat_id_6;
    }

    public function setRcmSsCatId7($rcm_ss_cat_id_7)
    {
        $this->rcm_ss_cat_id_7 = $rcm_ss_cat_id_7;
    }

    public function getRcmSsCatId7()
    {
        return $this->rcm_ss_cat_id_7;
    }

    public function setRcmSsCatId8($rcm_ss_cat_id_8)
    {
        $this->rcm_ss_cat_id_8 = $rcm_ss_cat_id_8;
    }

    public function getRcmSsCatId8()
    {
        return $this->rcm_ss_cat_id_8;
    }

    public function setWarrantyCat($warranty_cat)
    {
        $this->warranty_cat = $warranty_cat;
    }

    public function getWarrantyCat()
    {
        return $this->warranty_cat;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
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
